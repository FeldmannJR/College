<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\GoogleAccount;
use App\Http\Requests\SpreadsheetRequest;
use App\Indicators\Spreadsheets\SpreadsheetDriveService;
use App\Setting;
use App\SpreadsheetFile;
use App\User;
use Google_Client;
use Google_Service_Docs;
use Google_Service_Drive;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SpreadsheetController extends Controller
{


    private $driveService;

    /**
     * SpreadsheetController constructor.
     * @param SpreadsheetDriveService $spreadsheets
     */
    public function __construct(SpreadsheetDriveService $spreadsheets)
    {
        $this->driveService = $spreadsheets;
        $this->middleware('role:' . UserRole::Admin);
    }


    /**
     * Pagina inicial
     * @param Request $req
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function index(Request $req)
    {

        $user = $req->user();
        $user_google = $user->googleAccount;
        // Checa se a conta do google do cara ta linkada ainda
        $this->driveService->checkUserGoogleAccount($user);

        $metadata = $this->driveService->getSelectedFileMetadata();
        $file_google = $this->driveService->getSelectedGoogleAccount();


        $last = SpreadsheetFile::getLast();

        if ($user->hasGoogleAccount()) {
            $client = $this->driveService->getGoogleClientWithAccount($user_google);
            $client_id = $client->getClientId();

            $browser_developer_key = env('GOOGLE_API_KEY');
            $app_id = env('GOOGLE_APP_ID');

            $token = $client->getAccessToken()['access_token'];

            $scope = 'https://www.googleapis.com/auth/drive.file';
            $redirect_url = action('SpreadsheetController@pickFile');
            return view('planilhas.index', compact('scope', 'client_id', 'browser_developer_key', 'token', 'redirect_url', 'app_id', 'metadata', 'file_google', 'user_google', 'last'));
        } else {
            return view('planilhas.index', compact('metadata', 'last', 'file_google'));

        }
    }

    /**
     * Chamado quando o usuario escolhe o arquivo no picker
     */
    public function pickFile(Request $req)
    {
        $user = $req->user();
        if (!$this->driveService->checkUserGoogleAccount($user)) {
            return \redirect('planilhas')->with('error', 'Conta do google não está mais linkada!');
        }
        $validator = Validator::make($req->all(), [
            'fileId' => 'required|min:10|max:100'
        ]);
        $validator->after(function ($v) use ($user) {
            if (!$this->driveService->fileExists($user->googleAccount, $v->validated()['fileId'])) {
                $v->errors()->add('fileId', 'Arquivo não encontrado!');
            }
        });
        if ($validator->fails()) {
            return \redirect('planilhas')
                ->withErrors($validator)
                ->withInput();
        }
        $input = $validator->validated();
        $user->googleAccount->file_id = $input['fileId'];
        $user->googleAccount->save();
        Setting::set('google_account', $user->googleAccount->id);

        return \redirect('planilhas')->with('success', 'Selecionado arquivo com sucesso!');

    }


    /**
     * Redireciona o usuario para a pagina de login do google caso não tenha conta linkada
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function googleLogin(Request $request)
    {
        $user = $request->user();
        if ($user->hasGoogleAccount()) {
            return \redirect(action('SpreadsheetController@index'));
        }
        $client = $this->driveService->getGoogleClient();
        $url = $client->createAuthUrl();
        return \redirect()->away($url);

    }

    /**
     * Deslinka a conta do usuario com a do google revocando o token, e redireciona o usuario para o index
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function googleLogout(Request $request)
    {
        $user = $request->user();
        if (!$user->hasGoogleAccount()) {
            return \redirect(action('SpreadsheetController@index'));
        }

        $user->googleAccount->delete();
        $user->google_account = null;
        $user->save();
        return \redirect(action('SpreadsheetController@index'));
    }


    /*
     * Esta pagina é chamada pelo google após a autenticação
     * com a input 'code', sendo o código para pedir o token
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string|void
     */
    public function googleCallback(Request $request)
    {
        if (!$request->has('code')) {
            echo "Invalid";
            return;
        }

        $client = $this->driveService->getGoogleClient();
        $code = $request->input('code');
        $client->fetchAccessTokenWithAuthCode($code);
        // Conseguiu pegar o token
        $token = $client->getAccessToken();
        $refresh_token = $client->getRefreshToken();
        $user = $request->user();

        if ($token) {
            $oauth2 = new \Google_Service_Oauth2($client);
            $info = $oauth2->userinfo->get();
            $id = $info->getId();

            $count = GoogleAccount::where('google_id', $id)->count();
            $isCurrent = false;
            if ($count > 0) {
                if ($user->hasGoogleAccount()) {
                    $google = $request->user()->googleAccount;
                    if ($google->google_id === $id) {
                        $isCurrent = true;
                    }
                }
                if (!$isCurrent) {
                    return redirect(action('SpreadsheetController@index'))->with('error', 'Está conta já está linkada com outro usuario!');
                }
            }
            if ($user->hasGoogleAccount()) {
                if ($google->google_id !== $id) {
                    return redirect(action('SpreadsheetController@index'))->with('error', 'Você já tem uma conta linkada e não é selecionada!');
                }
            }

            $google = new GoogleAccount(['refresh_token' => $refresh_token, 'token' => $token, 'google_id' => $id, 'picture' => $info->getPicture(), 'email' => $info->getEmail()]);
            if ($isCurrent) {
                /**
                 * @var $account GoogleAccount
                 */
                $account = $user->googleAccount;
                $account->refresh_token = $google->refresh_token;
                $account->token = $google->token;
                $account->email = $google->email;
                $account->picture = $google->picture;
                $account->save();
            } else {

                $google->save();
                $user->googleAccount()->associate($google);
                $user->save();
            }
            return \redirect(action('SpreadsheetController@index'));

        } else {
            return "Error";
        }
    }

    /**
     * Alias para baixar o arquivo do google drive para o storage e redirecionar o usuario
     * @return bool|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function downloadFromDriveWithRedirects()
    {
        return $this->driveService->downloadFromDrive(true);
    }


    /**
     * Baixa no navegador a ultima tabela que foi baixada pelo servidor
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    function downloadLast()
    {
        $fileName = SpreadsheetFile::getLastPath();
        if ($fileName === null) {
            return \redirect(action('SpreadsheetController@index'))->with('error', 'Nenhuma planilha localizada no servidor!');
        }
        return response()->download($fileName, 'ultimaTabela.' . pathinfo($fileName, PATHINFO_EXTENSION));
    }


}
