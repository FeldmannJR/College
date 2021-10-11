<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8/dist/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: white;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            margin: 0;
        }

        .v-select__slot {
            cursor: pointer;
        }

        .v-btn__content {
            font-weight: 1000;
            font-family: 'Nunito', sans-serif;
        }

        a {
            color: unset !important;
            outline: none;
            text-decoration: none;
            cursor: pointer;
        }

        .heButton {
            background-color: #636b6f !important;
            color: white !important;
            transition: .25s;

        }

        .heButton:hover {
            background-color: #344669 !important;
        }

        .centered-input input {
            text-align: center
        }
    </style>

    @stack('scripts')

    @yield('head')

</head>
<body>
<div id="app" data-app="true">
    <v-app>
        @yield('body')
    </v-app>
</div>
</body>
</html>
