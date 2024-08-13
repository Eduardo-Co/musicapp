@extends('adminlte::page')

@section('title', 'Playlist Details')

@section('css')
    <script src="https://cdn.tailwindcss.com"></script>
@stop

@section('content')
    <livewire:playlist-details :playlistId="$playlistId" />
    @livewireScripts
@stop
