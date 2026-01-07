@extends('layouts.app')

@section('content')
    <users-component :translations='@json($translations)'></users-component>
@endsection