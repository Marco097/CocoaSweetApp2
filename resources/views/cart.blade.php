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

                         @php
                    
                        $totalCartPrice = 0;
                        $totalCoberturaPrice = 0;


                    foreach($cartCollection as $item) {
                        if ($item->attributes->cobertura_precio) {
                            $totalCoberturaPrice += $item->attributes->cobertura_precio * $item->quantity;
                        }
                    }

                    $totalCartPrice = Cart::getTotal() + $totalCoberturaPrice;
                    @endphp

                    <b>Sub Total: ${{ $item->price * $item->quantity }}</b><br>

                        <b><a href="/shop/{{ $item->attributes->slug }}">{{ $item->name }}</a></b><br>
                        <b>Precio: ${{ $item->price }}</b><br>
                        @if ($item->attributes->cobertura_precio)
                    <b>Precio de Cobertura: ${{ $item->attributes->cobertura_precio }}</b><br>
                        @endif
                               
                            <li class="list-group-item"><b>Total: ${{ $totalCartPrice }}</b></li>
                                
                                {{-- <b>With Discount: </b>${{ \Cart::get($item->id)->getPriceSumWithConditions() }}--}}
                               
                            </p>
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                    {{-- Agrega un formulario para elegir cobertura y personalización --}}
                    <form action="{{ route('cart.update') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="form-group row">
                            <input type="number" class="form-control form-control-sm" value="{{ $item->quantity }}" name="quantity" min="1">
                            <input type="hidden" name="cobertura_precio" value="{{ $item->attributes->cobertura_precio }}">
                            <!-- Resto de los campos ocultos necesarios para identificar el producto -->
                    
                            <label for="cobertura">Agrega una Cobertura por un precio adicional:</label>
                            <select name="cobertura" id="cobertura">
                                <option value="">Sin Cobertura</option>
                                @foreach($coberturas as $cobertura)
                                    <option value="{{ $cobertura->id }}" {{ $cobertura->id == $item->attributes->cobertura ? 'selected' : '' }}>
                                        {{ $cobertura->nombre }}
                                    </option>
                                @endforeach
                            </select>
                    
                            <label for="personalizacion">Personalización:</label>
                            <input type="text" name="personalizacion" id="personalizacion">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                                <form action="{{ route('cart.remove') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ $item->id }}" id="id" name="id">
                                    <button class="btn btn-dark btn-sm" style="margin-right: 10px;"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <hr>
                @endforeach
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
          <h5 class="modal-title" id="datoModalLabel">llene los siguientes datos para la entrega</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
         <h1>
            
         </h1>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
        </div>
        </div>
        <br><br>

        
@endsection
