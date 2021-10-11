@extends('layouts.basic')

@section('body')

    <login-form :errors='@json($errors->all())'></login-form>
@endsection
