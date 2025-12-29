@extends('layouts.app')

@section('content')
    <classification-component :translations='@json($translations)'></classification-component>
@endsection