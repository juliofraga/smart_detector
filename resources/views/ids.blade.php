@extends('layouts.app')

@section('content')
    <ids-component :translations='@json($translations)'></ids-component>
@endsection
