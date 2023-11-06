@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 80px">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Tienda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cart</li>
            </ol>
        </nav>
        @if(session()->has('success_msg'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session()->get('success_msg') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        @endif
        @if(session()->has('alert_msg'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session()->get('alert_msg') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        @endif
        @if(count($errors) > 0)
            @foreach($errors0>all() as $error)
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $error }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            @endforeach
        @endif
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <br>
                @if(\Cart::getTotalQuantity() > 0)
                    <h4>{{ \Cart::getTotalQuantity()}} Producto(s) en el carrito</h4><br>
                @else
                    <h4>No Hay Producto(s) En Su Carrito</h4><br>
                    <a href="/" class="btn btn-dark">Continue en la tienda</a>
                @endif
                @foreach($cartCollection as $item)
    <div class="row">
        <div class="col-lg-3">
            <img src="/images/productos/{{ $item->attributes->img }}" class="img-thumbnail" width="200" height="200">
        </div>
        <div class="col-lg-5">
            <p>
                <b><a href="/shop/{{ $item->attributes->slug }}">{{ $item->name }}</a></b><br>
                <b>Precio: ${{ $item->price }}</b><br>
                <b>Cantidad: {{ $item->quantity }}</b><br>
                @if ($item->attributes->cobertura)
                    @php
                        $cobertura = $coberturas->firstWhere('id', $item->attributes->cobertura);
                        $precioCobertura = $cobertura ? $cobertura->precio : 0;
                        $totalProducto = ($item->price + $precioCobertura) * $item->quantity;
                    @endphp
                    <b>Precio de Cobertura: ${{ $precioCobertura }}</b><br>
                    <b>Total del Producto: ${{ $totalProducto }}</b><br>
                @else
                    <b>Total del Producto: ${{ $item->price * $item->quantity }}</b><br>
                @endif
            </p>
        </div>
        <div class="col-lg-4">
            <div class="row">
                <form action="{{ route('cart.update') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $item->id }}" id="id" name="id">
                    <label for="quantity">Cantidad:</label>
                    <input type="number" name="quantity" id="quantity" value="{{ $item->quantity }}">
                            
                    @if ($item->producto && $item->producto->catalogo->nombre == 'Paletas')
    <label for="cobertura">Cobertura:</label>
    <select name="cobertura" id="cobertura">
        @foreach($coberturas as $cobertura)
            <option value="{{ $cobertura->id }}" {{ $item->attributes->cobertura == $cobertura->id ? 'selected' : '' }}>{{ $cobertura->nombre }}</option>
        @endforeach
    </select>
@endif

                    <label for="personalizacion">Personalización:</label>
                    <input type="text" name="personalizacion" id="personalizacion">
                    <button type="submit" class="btn btn-primary"  style="margin-right: 10px;">Actualizar</button>
                    <form action="{{ route('cart.remove') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" value="{{ $item->id }}" id="id" name="id">
                        <button class="btn btn-dark btn-sm" style="margin-right: 10px;"><i class="fa fa-trash"></i></button>
                    </form>
                </form>
                <!-- Aquí va tu código para eliminar el producto del carrito -->
                
            </div>
        </div>
    </div>
    <hr>
@endforeach
<div class="card">
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><b>Total del Carrito: ${{ Cart::getTotal() }}</b></li>
    </ul>
</div>
                                

                @if(count($cartCollection)>0)
                    <form action="{{ route('cart.clear') }}" method="POST">
                        {{ csrf_field() }}
                        <button class="btn btn-secondary btn-md">Borrar Carrito</button> 
                    </form>
                @endif
            </div>
            @if(count($cartCollection)>0)
                <div class="col-lg-5">
                    <div class="card">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>Total: ${{ Cart::getTotal() }}</b></li>

                        </ul>
                    </div>
                    <br><a href="/shop" class="btn btn-dark">Continue en la tienda</a>
                    <a href="/checkout" class="btn btn-success">Proceder a metodo de pago</a>
                              <!-- Button trigger modal -->
        
            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#datoModal">
                Direccion
            </button>
       
                </div>
            @endif

  <!-- Modal -->
  <div class="modal fade" id="datoModal" tabindex="-1" aria-labelledby="datoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="datoModalLabel">Llene los siguientes datos para la entrega</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="datosEntregaForm">
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion">
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Telefono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarDatosEntrega()">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>

        <br><br>

        
@endsection
