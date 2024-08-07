@extends('adminlte::page')

@section('title', 'Artistas')


@section('css')
<script src="https://cdn.tailwindcss.com"></script>
@stop

@section('content')
   <livewire:album/>
    @livewireScripts
@stop

