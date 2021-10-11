<?php


namespace App\Indicators\Spreadsheets;


use App\GoogleAccount;
use App\Setting;
use App\SpreadsheetFile;
use App\User;
use Google_Client;
use Google_Service_Drive;
use Illuminate\Support\Facades\Storage;

class SpreadsheetDriveService
{

    /**
     * Retorna o google client basico sem autenticação
     * @return Google_Client
     * @throws \Google_Exception
     */
    public function getGoogleClient()
    {
        $client = new \Google_Client();
        $client->setAuthConfig(base_path(env('GOOGLE_CLIENT_CREDENTIALS')));
        $client->addScope([Google_Service_Drive::DRIVE_FILE, \Google_Service_Oauth2::USERINFO_EMAIL]);
        $client->setPrompt('select_account consent');
        $client->setRedirectUri(action('SpreadsheetController@googleCallback'));
        $client->setIncludeGrantedScopes(true);
        $client->setAccessType('offline');

        return $client;
    }

    /**
     * Se foi revogado o token da conta do google do $user, irá remover a conta do google
     * @param User $user user to check token
     * @return bool if user token still valid
     */
    public function checkUserGoogleAccount($user)
    {
        if ($user->hasGoogleAccount()) {
            if (!$this->isGoogleAccountValid($user->googleAccount)) {
                $user->googleAccount->delete();
                $user->google_account = null;
                $user->save();
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Verifica se o arquivo selecionado existe na conta do usuario
     * @param GoogleAccount $googleAccount
     * @param $fileId string drive file id
     * @return bool if file exists
     */
    public function fileExists($googleAccount, $fileId)
    {
        if ($this->isGoogleAccountValid($googleAccount)) {
            $client = $this->getGoogleClientWithAccount($googleAccount);
            $drive = new Google_Service_Drive($client);
            try {
                $f = $drive->files->get($fileId);
                if ($f) {
                    return true;
                }
            } catch (\Google_Service_Exception $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * Verifica se não foi revogado o token
     * @param GoogleAccount $googleAccount
     * @return bool
     */
    public function isGoogleAccountValid($googleAccount)
    {
        if ($googleAccount === null) return false;
        $client = $this->getGoogleClientWithAccount($googleAccount);
        if ($client === null) {
            return false;
        }
        return true;
    }

    /**
     * Se o arquivo selecionado é valido
     * @return bool
     */
    public function isSelectedValid()
    {
        return $this->isGoogleAccountValid($this->getSelectedGoogleAccount());
    }

    /**
     * Get the current selected file meta
     * @return \Google_Service_Drive_DriveFile
     */
    public function getSelectedFileMetadata()
    {
        if ($this->isSelectedValid()) {
            $acc = $this->getSelectedGoogleAccount();
            $drive = $this->getSelectedDrive();
            return $drive->files->get($acc->file_id);
        }
        return null;
    }

    /**
     * Get selected google account
     * @return GoogleAccount|null
     */
    public function getSelectedGoogleAccount()
    {
        $id = Setting::get('google_account');
        if ($id === null) return null;
        return GoogleAccount::find($id);
    }

    /**
     * Get the selected drive
     * @return Google_Service_Drive
     */
    public function getSelectedDrive()
    {
        $acc = $this->getSelectedGoogleAccount();
        $client = $this->getGoogleClientWithAccount($acc);
        return new Google_Service_Drive($client);
    }

    /**
     * Retorna uma instacia do google client já autenticado com a $googleAccount
     * @param GoogleAccount $googleAccount
     * @return Google_Client|null
     */
    public function getGoogleClientWithAccount($googleAccount)
    {
        // Instancia um novo cliente
        $client = $this->getGoogleClient();
        if ($googleAccount !== null) {
            // Seta o token que tava no banco
            $client->setAccessToken($googleAccount->token);
            // Se o token expirou ele puxa outro com o refresh token
            if ($client->isAccessTokenExpired()) {
                $token = $client->refreshToken($googleAccount->refresh_token);
                if ($token) {
                    $googleAccount->token = $token;
                    $googleAccount->save();
                } else {
                    return null;
                }
            }
        }
        return $client;
    }

    /**
     * Função baixa o arquivo doo google drive e guarda no storage e banco de dados
     * @param bool $redirect se vai redirecionar o pedido para outra pagina ou retornar booleano
     * @return bool|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function downloadFromDrive($redirect = false)
    {
        if ($this->isSelectedValid()) {
            $drive = $this->getSelectedDrive();
            $fileId = $this->getSelectedGoogleAccount()->file_id;
            $ext = null;
            try {
                $meta = $drive->files->get($fileId);
                if ($meta->getMimeType() == "application/vnd.google-apps.spreadsheet") {
                    $media = $drive->files->export($fileId, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', ['alt' => 'media']);
                    $ext = 'xlsx';
                } else {
                    $media = $drive->files->get($fileId, ['alt' => 'media']);
                }
            } catch (\Google_Service_Exception $e) {
                if ($redirect) {
                    return \redirect(action('SpreadsheetController@index'))->with('error', "Error Google: " . $e->getMessage());
                } else {
                    return 'Error Google ' . $e->getMessage();
                }
            }
            $name = $this->saveSpreadsheet($media->getBody()->getContents(), $meta, $ext);

            if ($name) {
                if ($redirect) {
                    return \redirect(action('SpreadsheetController@index'))->with('success', "Arquivo baixado para o disco com o nome $name");
                } else {
                    return true;
                }
            }
        }
        if ($redirect) {
            return \redirect(action('SpreadsheetController@index'))->with('error', "Não consegui baixar o arquivo!");
        } else {
            return false;
        }
    }

    /**
     * Salva a tabela no disco e inseri no banco de dados
     * @param $stream
     * @param \Google_Service_Drive_DriveFile $metadata
     * @param string|null $ext
     * @return string|null
     */
    public function saveSpreadsheet($stream, \Google_Service_Drive_DriveFile $metadata, string $ext = null)
    {
        if ($ext === null)
            $ext = pathinfo($metadata->getName(), PATHINFO_EXTENSION);
        # Gera um nome com a hora atual + id unico
        $name = date('d.m.Y-H:i:s-') . uniqid() . '.' . $ext;
        $path = "spreadsheets/$name";
        # Salva no storage
        if (Storage::disk()->put($path, $stream)) {
            // Salva no banco
            SpreadsheetFile::create([
                'file_name' => $name,
                'full_path' => $path,
                'original_file_name' => $metadata->getName(),
                'file_size' => Storage::disk()->size($path)
            ]);
            return $name;
        }
        return null;
    }
}