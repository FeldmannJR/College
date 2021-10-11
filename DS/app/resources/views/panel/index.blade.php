@extends('layouts/basic')

@section('body')
  <Panel :fixed="{{json_encode($fixed)}}" :indicators='@json($indicators)' :presentation="{{json_encode($presentation)}}">
@endsection