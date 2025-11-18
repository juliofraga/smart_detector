@extends('layouts.app')

@section('content')
@php
    $event_id = request()->segment(2);
@endphp
    <event-detailed-component :event_id='@json($event_id)' :all-events='@json(config("system_settings.all_events"))'></event-detailed-component>
@endsection