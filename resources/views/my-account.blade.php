@extends('layouts.app')

@section('content')
    <account-component :user='@json(Auth::user())'></account-component>
@endsection