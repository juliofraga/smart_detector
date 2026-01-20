@extends('layouts.app')

@section('content')
    <home-component :translations='@json($translations)' locale="{{ app()->getLocale() }}"></home-component>
@endsection
