@extends('layouts/basic')

@section('head')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <style>
        body {
            padding-top: 3vh;
        }
        </style>
@endsection
@section('body')

    <he-navbar :user='@json(Auth::user())' :user-roles='@json(\App\Enums\UserRole::getInstances())'></he-navbar>

    <main class="py-4">
        @yield('content')
    </main>
@endsection
