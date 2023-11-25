@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 80px">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/shop">Tienda</a></li>
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
        @foreach($errors->all() as $error)
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
            <img src="{{ asset('images/productos/' . $item->attributes->img) }}" class="img-thumbnail" width="200" height="200">
        </div>
        <div class="col-lg-5">
            <p>
                <b><a href="/shop/{{ $item->attributes->slug }}">{{ $item->name }}</a></b><br>
                <b>Precio: ${{ $item->price }}</b><br>
                <b>Cantidad: {{ $item->quantity }}</b><br>
               {{--   @if ($item->attributes->cobertura)
                    @php
                        $cobertura = $coberturas->firstWhere('id', $item->attributes->cobertura);
                        $precioCobertura = $cobertura ? $cobertura->precio : 0;
                        $totalProducto = ($item->price + $precioCobertura) * $item->quantity;
                    @endphp
                    <b>Precio de Cobertura: ${{ $precioCobertura }}</b><br>
                    <b>Total del Producto: ${{ $totalProducto }}</b><br>
                @else
                    <b>Total del Producto: ${{ $item->price * $item->quantity }}</b><br>
                @endif--}}
            </p>
        </div>
        <div class="col-lg-4">
            <div class="row">
                <form action="{{ route('cart.update') }}" method="POST" >
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $item->id }}" id="id" name="id">
                    <label for="quantity">Cantidad:</label>
                    <input type="number" name="quantity" id="quantity" value="{{ $item->quantity }}">
            
                    @if ($item->producto && $item->producto->catalogo->nombre == 'Paletas')

                    @endif
                    <button type="submit" class="btn btn-primary"  style="margin-right: 10px;">Actualizar</button>
                </form>
                <form action="{{ route('cart.delete', ['id' => $item->id]) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-dark btn-sm"><i class="fa fa-trash"></i></button>
                </form>
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
                    <a href="/checkout" class="btn btn-success">Forma de Pago</a>
                              <!-- Button trigger modal -->
        
                              <form action="{{ route('pedido.finalizar') }}" method="POST" id="pedidoForm">
                                @csrf
                                <div class="form-group">
                                    <label for="direccion_envio">Dirección de envío:</label>
                                    <input type="text" id="direccion_envio" name="direccion_envio" class="form-control" value="{{ auth()->user()->direccion }}">
                                </div>
                                <div class="form-group">
                                    <label for="telefono">Teléfono:</label>
                                    <input type="text" id="telefono" name="telefono" class="form-control" value="{{ auth()->user()->telefono }}">
                                </div>
                                <div class="form-group">
                                    <label for="fecha_entrega">Fecha de entrega:</label>
                                    <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="fecha_entrega">Hora de entrega:</label>
                                    <input type="time" id="hora" name="hora" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-success" >Realizar Pedido</button>
                            </form>

                            <!-- Agrega SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("pedidoForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Evitar el envío del formulario
            Swal.fire({
                title: '¿Confirmar pedido?',
                text: 'Una vez confirmado, no podrás modificar tu pedido.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, confirmar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviar el formulario
                    document.getElementById("pedidoForm").submit();
                }
            });
        });
    });
</script>
                </div>
            @endif        
@endsection
