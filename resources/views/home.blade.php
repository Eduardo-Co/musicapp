@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<div class="flex justify-center items-center h-screen">
    <div class="text-center">
        <h1 class="text-4xl font-bold mb-4">Welcome to the Music App!</h1>
        <p class="text-lg">Start exploring and enjoying your favorite tunes.</p>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<link href="{{ mix('css/app.css') }}" rel="stylesheet">
@stop

@section('js')
<script>
    console.log('Hi!');
</script>
@stop
