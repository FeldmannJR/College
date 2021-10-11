@extends('layouts/app')

@section('content')
  <Settings :fixed="{{json_encode($fixed)}}" :presentation="{{json_encode($presentation)}}" :indicators="{{json_encode($indicators)}}" />
@endsection