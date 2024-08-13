@extends('adminlte::page')

@section('title', ' Playlists')


@section('css')
<script src="https://cdn.tailwindcss.com"></script>
@stop

@section('content')
   <livewire:playlists/>
    @livewireScripts
@stop

