<nav class="navbar navbar-expand-md navbar-light fixed-top" style="background-color: #F0B1FA; ">
    <div class="container" style="" >
        <div style="white-space: nowrap;">  el white-space evita que el texto se mueva cuando la página se visualiza en dispositivos móviles
        <img src="{{ asset('imagenes/icono.jpeg') }}" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
        <span style="color: #BF209E; font-size: 25px;"> Cocoa </span>
        <span style="color:#FF27D2; font-size: 23px;">Sweet</span>
    </div>
    
    <div class="container">

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            COCOASweet
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                </li>
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle"
                       href="#" role="button" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false"
                    >
                        <span class="badge badge-pill badge-dark">
                            </span>
                    </a>
      
                </li>
                 <!-- Elemento del carrito con icono -->
    <li class="nav-item">
        <a class="nav-link" href="/ruta-a-tu-pagina-de-carrito">
            <i class="fas fa-shopping-cart"></i>
        </a>
    </li>
            </ul>
        </div>

    </div>
</nav>
