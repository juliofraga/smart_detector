@extends('layouts.app')

@section('content')
@php
    $event_id = request()->segment(2);
@endphp
    <event-detailed-component :event_id='@json($event_id)'></event-detailed-component>
@endsection