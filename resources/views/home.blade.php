@extends('layouts.app')

@section('content')
    <home-component :translations='@json($translations)'></home-component>
@endsection
