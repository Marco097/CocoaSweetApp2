@extends('adminlte::page')

@section('title', 'Productos')

@section('content_header')
    <h1>Productos</h1>
@stop

@section('content')
    <div id = "app">
        <pedido-component></pedido-component>
        <!--<form-component></form-component>-->
    </div>
@stop

@section('css')
    <!--<link rel="stylesheet" href="/css/admin_custom.css">-->
    @vite ('resources/css/app.css')
@stop

@section('js')
   <!-- <script> console.log('Hi!'); </script>-->
   @vite ('resources/js/app.js')
@stop