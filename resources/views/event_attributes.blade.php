@extends('layouts.app')

@section('content')
    <event-attribute-component :translations='@json($translations)'></event-attribute-component>
@endsection