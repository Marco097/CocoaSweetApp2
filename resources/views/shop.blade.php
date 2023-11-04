@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 80px">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tienda</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-7">
                        <h4>Productos</h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    @foreach($products as $pro)
                        <div class="col-md">
                            <div class="card" style="margin-bottom: 20px; height: auto;">
                                <img src="/images/productos/{{ $pro->imagen }}"
                                     class="card-img-top mx-auto"
                                     style="height: 150px; width: 150px;display: block;"
                                     alt="{{ $pro->image_path }}"
                                >
                                <div class="card-body" >
                                    <a href=""><h6 class="card-title">{{ $pro->nombre }}</h6></a>
                                    <p>${{ $pro->precio }}</p>
                                    <form action="{{ route('cart.store') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" value="{{ $pro->id }}" id="id" name="id">
                                        <input type="hidden" value="{{ $pro->nombre }}" id="name" name="name">
                                        <input type="hidden" value="{{ $pro->precio }}" id="price" name="price">
                                        <input type="hidden" value="{{ $pro->image }}" id="img" name="img">
                                        <input type="hidden" value="{{ $pro->slug }}" id="slug" name="slug">
                                        <div class="form-group">
                                            <label for="quantity">Cantidad:</label>
                                            <input type="number" value="0" id="quantity" name="quantity" min="1">
                                        </div>
                                        <div class="card-footer" style="background-color: white;">
                                              <div class="row">
                                                <button class="btn  btn-sm" class="tooltip-test" title="Agregar al Carrito" style="background: rgba(240, 193, 216, 0.929)">
                                                    <i class="fa fa-shopping-cart"></i> agregar al carrito
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
