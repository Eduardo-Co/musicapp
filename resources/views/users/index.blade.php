@extends('adminlte::page')

@section('title', 'Users')


@section('css')
<script src="https://cdn.tailwindcss.com"></script>
@stop

@section('content')
   <livewire:user />
     @livewireScripts
@stop

