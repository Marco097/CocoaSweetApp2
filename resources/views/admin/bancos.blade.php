@extends('adminlte::page')

@section('title', 'Banco')

@section('content_header')
    <h1>Banco</h1>
@stop

@section('content')
    <div id = "app">
      <banco-component></banco-component>
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