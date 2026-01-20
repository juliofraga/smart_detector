@extends('layouts.app')

@section('content')
    <events-component locale="{{ app()->getLocale() }}"></events-component>
@endsection