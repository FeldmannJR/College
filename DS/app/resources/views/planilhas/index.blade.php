@extends('layouts.app')

@push('scripts')

    @isset($token)
        <script type="text/javascript">

            // The Browser API key obtained from the Google API Console.
            // Replace with your own Browser API key, or your own key.
            var developerKey = '{{$browser_developer_key}}';

            // The Client ID obtained from the Google API Console. Replace with your own Client ID.
            var clientId = "{{$client_id}}"

            // Scope to use to access user's Drive items.
            var scope = ['{{$scope}}'];

            var pickerApiLoaded = false;
            var oauthToken = "{{$token}}";
            var appId = '{{$app_id}}';

            // Use the API Loader script to load google.picker and gapi.auth.
            function onApiLoad() {
                gapi.load('auth2', onAuthApiLoad);
                gapi.load('picker', onPickerApiLoad);
            }

            function onAuthApiLoad() {
                var authBtn = document.getElementById('auth');
                if (authBtn !== null) {
                    authBtn.disabled = false;
                    authBtn.addEventListener('click', function () {
                        gapi.auth2.init({client_id: clientId}).then(function (googleAuth) {
                            googleAuth.signIn({scope: scope}).then(function (result) {
                                handleAuthResult(result.getAuthResponse());
                            })
                        })
                    });
                }
            }

            function onPickerApiLoad() {
                pickerApiLoaded = true;
            }

            function handleAuthResult(authResult) {
                if (authResult && !authResult.error) {
                    oauthToken = authResult.access_token;
                    createPicker();
                }
            }

            // Create and render a Picker object for picking user Photos.
            function createPicker() {
                if (pickerApiLoaded && oauthToken) {
                    var view = new google.picker.DocsView(google.picker.ViewId.SPREADSHEETS);
                    var picker = new google.picker.PickerBuilder()
                        .addView(view)
                        .setOAuthToken(oauthToken)
                        .setDeveloperKey(developerKey)
                        .setCallback(pickerCallback)
                        .setAppId(appId)
                        .build();
                    picker.setVisible(true);
                }
            }

            // A simple callback implementation.
            function pickerCallback(data) {
                if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
                    var doc = data[google.picker.Response.DOCUMENTS][0];
                    var fileId = doc[google.picker.Document.ID];
                    document.getElementById('choose_file_id').value = fileId;
                    document.getElementById('choose_file').submit();
                    return;
                }
            }
        </script>
    @endisset
@endpush
@section('content')
    <div class="container">

        <div class="bd-callout bd-callout-info">
            <h4>Como funciona</h4>
            <p>O administrador do sistema vai precisar logar com uma conta do google drive e selecionaro arquivo, após
                selecionar vai ficar guardado no banco de dados
                qual usuário escolheu o arquivo junto com o token do google dele, caso o usuário deslogar do google,o
                arquivo selecionado é removido e não será mais possível baixar as tabelas, somente se outro usuario
                logar e selecionar outro!
            </p>
            <p>
                Para baixar a tabela do google drive para o servidor basta clicar no botão, isso vai ocorrer
                automaticamente quando precisar calcular os indicadores.
            </p>
        </div>

        <!-- Se foi setado o arquivo -->
        @isset($token)
            <button type='button' onclick="createPicker();" class="btn btn-primary btn-lg">Selecionar Planilha</button>

            <script type="application/javascript" src="https://apis.google.com/js/api.js?onload=onApiLoad"></script>
            <form id="choose_file" action="{{ action('SpreadsheetController@pickFile') }}" method="POST"
                  style="display: none;">
                @csrf
                <input id="choose_file_id" name="fileId" type="hidden" value=""/>
            </form>
            <a type='button' href="{{action('SpreadsheetController@googleLogout')}}" class="btn btn-dark btn-lg">Deslogar
                Do
                Google</a>
            <a type='button' href="{{action('SpreadsheetController@downloadFromDriveWithRedirects')}}"
               class="btn btn-info btn-lg">Baixar para o servidor</a>

        @else
            <a type='button' href="{{action('SpreadsheetController@googleLogin')}}" class="btn btn-secondary btn-lg">Logar
                no Google</a>
        @endisset


        <div class="container" style="margin-bottom: 32px; margin-top:32px">
            @if($metadata!==null)
                <span style="font-size: 24px">Arquivo <strong>{{$metadata->name}}</strong> selecionado do drive de {{$file_google->email}}</span>
            @else
                <h2>Nenhum arquivo selecionado</h2>
            @endif
        </div>


        @isset($last)
            <div class="container">
                    <span style="font-size:18px">
                        Ultimo Arquivo Baixado <strong>{{$last->original_file_name}}</strong> como <strong>{{$last->file_name}}</strong> em {{$last->created_at}} com tamanho de {{round($last->file_size/1024,3)}}kb
                    </span>
                <a type="button" href="{{action('SpreadsheetController@downloadLast')}}"
                   class="btn btn-outline-success btn-sm">Download</a>
            </div>
        @endisset
    </div>

@endsection