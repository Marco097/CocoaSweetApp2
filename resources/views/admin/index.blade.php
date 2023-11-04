@extends('adminlte::page')

@section('title', 'Administracion')

@section('content_header')
    <h1>Cocoa Sweet</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
@stop

@section('css')
    <!--<link rel="stylesheet" href="/css/admin_custom.css">-->
    @vite('resources/css/app.css')
@stop

@section('js')
   <!-- <script> console.log('Hi!'); </script> -->
    @vite('resources/js/app.js')
@stop