@extends('layouts.app')

@section('content')
    <update-password-component :email="'{{ request('email') }}'"></update-password-component>
@endsection
