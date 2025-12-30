@extends('layouts.app')

@section('content')
    <type-component :translations='@json($translations)'></type-component>
@endsection