@extends('layouts/app')

@section('content')
  <Report :indicators='@json($indicators)'></Report>
@endsection