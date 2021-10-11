@extends('layouts/app')

@section('content')
  <Maintenance :users="{{json_encode($users)}}"/>
@endsection