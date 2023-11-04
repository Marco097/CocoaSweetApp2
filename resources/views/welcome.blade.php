@extends('layouts.app')
      @section('content')
          <!-- ESTE APARTADO ES DONDE EL CLIENTE VERA LOS PRODUCTOS -->
          <!--darle color al menu -->
          <nav class="navbar navbar-expand-md navbar-light" style="background-color: #D97AF7; margin-top: 40px; position: fixed; width: 100%; z-index: 999; "> 
        
            <div class="container-fluid">
                <!--boton para response -->       
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>   
              <!--apartado del navbar (menu)-->
              <div class="collapse navbar-collapse position-relative" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item text-center">
                        <a class="navbar-brand" style="color:#FFFFFF" href="{{url('/productos')}}">Inicio</a>
                    </li>
                    <li class="nav-item text-center">
                      <a class="navbar-brand" style="color: #FFFFFF; font-size: 19px;" href="{{ url('/productos') }}">productos</a>
                  </li>
                  
      
                    <li class="nav-item text-center">
                        <a class="navbar-brand" style="color: #FFFFFF; font-size: 19px;" href="#acerca-de" >Acerca de</a>
                    </li>
                    <li class="nav-item text-center">
                      <a class="navbar-brand" style="color: #FFFFFF; font-size: 19px;" href="#acerca-de" >Contacto</a>
                  </li>
                    <li class="nav-item dropdown  nav-item text-center">
                      <a class="nav-link dropdown-toggle" style="color:#FFFFFF"  href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          promociones
                      </a>
                      <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item" href="#">Paletas</a>
                          <a class="dropdown-item" href="#">Ice roll</a>
                      </div>
                  </li>
                </ul>
                <form class="d-flex ml-auto" role="search">
                  <input class="form-control me-2"  list="datalistOptions" id="exampleDataList" placeholder="Buscar un producto" aria-label="Search">&nbsp;
                  <button class="btn btn-outline-primary custom-search-button" style="background-color: #FF27D2; border-color: #FF27D2;">Buscar</button>
                  <datalist id="datalistOptions">
                      <option value="Ice roll">
                      <option value="Paleta">
                      <option value="Frozen">
                      <option value="Los Angeles">
                      <option value="Chicago">
                  </datalist>
              </form>
                            
                <a class="navbar-toggler d-md-none position-absolute top-0 end-0" data-bs-toggle="collapse" href="#navbarSupportedContent" role="button" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            
            </div>
          </nav>
          <br>
          <br>
          <br>
          <br>
                  <!--carusel-->
                  <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                      <div class="carousel-item active">
                        <img class="d-block w-100" src="{{ asset('imagenes/ice.jpeg') }}"  style="height: 600px;" class="d-block w-100"  alt="First slide">
                      </div>
                      <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('imagenes/paleta.jpeg') }}"  style="height: 600px;" class="d-block w-100"  alt="Second slide">
                      </div>
                      <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('imagenes/login.jpeg') }}"  style="height: 600px;" class="d-block w-100"  alt="Third slide">
                      </div>
                      <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('imagenes/login.jpeg') }}"  style="height: 600px;" class="d-block w-100"  alt="Third slide">
                      </div>
                    </div>
                  </div>
                  <!--apartado de "acerca de nuestra pagina"-->
                  <br>
                  <h3 id="acerca-de" class="card" style="border-radius: 0px;  background: #cb98d4; text-align: center;">Acerca de</h3>
                  <section class="banner" style="position: relative;">
                    <img src="{{ asset('imagenes/local.jpeg') }}" style="height: 600px;" class="d-block w-100" alt="" >
                    <div  style="position: absolute; top: 7%; left: 50%; transform: translate(-40%, -40%); color: rgb(0, 0, 0); font-size: 40px; font-weight: bold; height: 0px;">HELADOS CHALATECOS GOURMET
                    </div>
                    <br>
                    <h3 style=" text-align:center; color:#FF27D2; font-weight: bold; ">Nuestro local</h3>
                    <div style="position: absolute; top: 100%; left: 3%; text-align:center;">Sumérgete en un ambiente lleno de dulzura y diversión, diseñado cuidadosamente para ofrecerte una experiencia única mientras disfrutas de nuestras deliciosas paletas artesanales,
                        Cada detalle, desde la música alegre de fondo hasta la cálida iluminación que resalta los vibrantes colores de nuestras paletas, está pensado para cautivar
                       <br> tus sentidos y transportarte a un mundo de sabores exquisitos. En nuestro local, no solo encontrarás una amplia variedad de sabores y combinaciones, <br> sino también un equipo amable que estará encantado de ayudarte a elegir la paleta perfecta para satisfacer tus antojos. 
                       <br> Visítanos y descubre por qué nuestro local de paletas es el lugar ideal para deleitar tu paladar y crear momentos dulces y memorables. <br> ¡Te esperamos con los brazos abiertos en nuestro encantador rincón rosa!.</div>
                  </section>
                  <br>
                  <br>
                  <br>
                  <section class="banner" style="position: relative; margin-top: 100px;">
                    <img src="{{ asset('imagenes/nosotros.jpeg') }}" style="height: 600px;" class="d-block w-100" alt="" >
                   <!-- <div  style="position: absolute; top: 50%; left: 50%; transform: translate(-40%, -40%); color: rgb(0, 0, 0); font-size: 40px; font-weight: bold; height: 0px;">HELADOS CHALATECOS GOURMET
                    </div> --> 
                    <br>
                    <h3 style=" text-align:center; color:#FF27D2; font-weight: bold; ">Nuestros productos</h3>
                    <div style="position: absolute; top: 100%; left: 10%; text-align: center;">  
                      En nuestro local de venta de paletas artesanales y Ice Roll hechos a mano, te ofrecemos una amplia variedad de sabores y combinaciones deliciosas.  <br> Nuestras paletas son auténticas obras maestras de sabor, desde los clásicos hasta opciones innovadoras que sorprenderán tu paladar. Además, podrás presenciar 
                      <br> la preparación de los famosos Ice Rolls en el momento, donde personalizamos cada detalle según tus preferencias. También te brindamos la oportunidad <br> de agregar toppings y coberturas para darle un toque extra de diversión y sabor a tus creaciones. En nuestro local, no solo encontrarás productos deliciosos,
                      <br> sino también una experiencia única y especial, donde nuestro amable personal te guiará durante todo el proceso. <br> Te invitamos a visitarnos y descubrir la frescura, la creatividad y el sabor que encontrarás en cada bocado de nuestras paletas y Ice Rolls hechos con amor.</div>
                  </section>
                  <br>
                  <br>
                  <br>
                  <br>
                  <!--apartado del pie de la pagina -->
                  <footer style="background-color:#cb98d4; padding: 20px; margin-top: 100px;">
                    <div class="container" >
                      <div class="row" style=" font-family: Arial, sans-serif;">
                        <div class="col-md-4">
                          <h4 style=" font-family:  'Times New Roman', Times, serif">Nuestra Heladería</h4>
                          <p>¡Disfruta de nuestros deliciosos helados artesanales!</p>  
                          <p>La vida puede ser dulce en Cocoa Sweet <br> Prepáralos como quieras!</p>
                         <!-- <p>Dirección: Calle Principal,</p> -->
                        </div>
                        <div class="col-md-4">
                          <h4 style="font-family:  'Times New Roman', Times, serif">Redes Sociales</h4>
                          <ul class="social-icons" style="list-style: none; padding: 2%; margin: 1%;">
                            <li class="d-inline-block" style="margin-right: 20px; font-size: 20px;"><a href="https://www.facebook.com/profile.php?id=100063447984644&mibextid=ZbWKwL" target="_blank" style="color: black;" class="fab fa-facebook-f"></a></li>
                            <li class="d-inline-block" style="margin-right: 20px; font-size: 20px;"><a href="https://www.instagram.com/cocoa_sweet_sv/" target="_blank" style="color: black;" class="fab fa-instagram"></a></li>
                            <li class="d-inline-block" style="margin-right: 20px; font-size: 20px;"><a href="https://www.google.com/maps/place/Cocoa+Sweet/@14.0398707,-88.939954,17.24z/data=!4m14!1m7!3m6!1s0x8f6365c3a8060df1:0x15d78f9637f4c0dd!2sCocoa+Sweet!8m2!3d14.0398454!4d-88.9377959!16s%2Fg%2F11j51pfyg9!3m5!1s0x8f6365c3a8060df1:0x15d78f9637f4c0dd!8m2!3d14.0398454!4d-88.9377959!16s%2Fg%2F11j51pfyg9?entry=ttu" target="_blank" style="color: black;"  class="fas fa-map-marker-alt"></a></li>
                          </ul>
                          <p style=" font-family:  'Times New Roman', Times, serif">VENGA A CONOCERNOS!!</p>
                          <p>Calle principal del barrio el chile. 
                            <br> sexta calle poniente y avenidad libertad, <br> en chalatenango. <br> A pocas cuadras del Dollarcity <br> junto al MOCAR
                        </div>
                        <div class="col-md-4">
                          <h4 style="font-family:  'Times New Roman', Times, serif ">Horario de Atención!</h4>
                          <p>Martes a Domingos: 10:00 am - 6:00 pm <br> (sin cerrar al medioDia)</p>
                          <p>Lunes: CERRADO </p>
                        </div>

                      </div>
                    </div>                 
                  </footer> 
                   <br>
                    <br>
                    <br>
                    <br>     
@endsection