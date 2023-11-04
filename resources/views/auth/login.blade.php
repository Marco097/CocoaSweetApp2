]@extends('layouts.app')

<head>
  <!--  <style>
        body {
            background-image: url('/imagenes/login.jpeg');
            /* Ajusta los estilos de la imagen de fondo según tus necesidades */
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center top;
            background-position: center top 12%;
        
            
        }
    </style> -->
</head>
@section('content')
<!-- 
<style>
    body {
        background-image: url('{{ asset('imagenes/acerca.jpeg') }}'); /* Ruta de la imagen de fondo */
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }
</style> -->
<div class="container" style="margin-top: 90px;">
    <div class="row justify-content-center">
        <div class="col-md-6" >
            <br>
            <div class="card" style="border-radius: 6px;  background: #e2cce6;">
                <div class="card-body">
                    <div style="display: flex; align-items: center; justify-content: center; margin-top: -10%">
                        <img src="{{ asset('imagenes/icono.jpeg') }}" style="width: 60px; height: 60px; border-radius: 50% ;">
                    </div>
                    
                    <!--cuadro de login-->
                    <div style= "text-align: center; border-radius: 10px; width: 150px; height: 30px;  margin: 0 auto;" >{{ __('Iniciar sesión') }}</div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-4">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Correo electronico') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Contraseña') }}</label>
                            
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Recordar contraseña') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn " style="background-color:  #ed9fef ;">
                                    {{ __('Iniciar Sesion') }}
                                </button>
                            </div>
                        </div>
                            
                                <div class="row mb-0"> 
                               <div class="col-md-8 offset-md-4" >
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Olvidaste tu contraseña?') }}
                                    </a>
                                </div>
                            </div>
                                @endif
                           
                    
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
