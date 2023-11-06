@extends('adminlte::page')

@section('title', 'Reporte Reservas')

@section('content_header')
    <h1>Reportes</h1>
@stop

@section('content')
    <div id = "app">
        <reservas-report></reservas-report>
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