@extends('layouts.app')

@section('content')
    <account-component :user='@json(Auth::user())' :translations='@json($translations)'></account-component>
@endsection