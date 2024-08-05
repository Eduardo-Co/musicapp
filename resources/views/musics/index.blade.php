@extends('adminlte::page')

@section('title', 'Musics')


@section('css')
<script src="https://cdn.tailwindcss.com"></script>
@stop

@section('content')

<livewire:musica/>
@livewireScripts
@stop

