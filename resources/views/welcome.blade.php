<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/index.css') }}">
    <title>Bienvenido</title>
</head>
<body data-spy="scroll" data-target="#navbar" data-offset="57">
    <nav id="header" class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
              <img src="{{ asset('assets/logos/grupo_amad.png') }}" alt="Grupo Amad logo">
              (502)- 58701923
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
              <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                  <a class="nav-link" href="#main">Soporte</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#speakers">Contactenos</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#place-time">Socios</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('login')}}">Iniciar Sesion</a>
                </li>
              </ul>
            </div>
        </div>
    </nav>
    <main id="main">
        <div id="carousel" class="carousel slide carousel-fade" data-ride="carousel" data-pause="false">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="{{ asset('assets/imagenes/Radiologia-Tablets.jpg') }}" alt="Hawaii 1">
                </div>
                <div class="overlay">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-6 offset-md-6 text-center">
                                <h1>Versión del juramento hipocrático de la Convención de Ginebra</h1>
                                <p class="d-none d-md-block">
                                    En el momento de ser admitido entre los miembros de la profesión médica, me comprometo solemnemente a consagrar mi vida al servicio de la humanidad.
                                    Conservaré a mis maestros el respeto y el reconocimiento del que son acreedores.

                                    Desempeñaré mi arte con conciencia y dignidad. La salud y la vida del enfermo serán las primeras de mis preocupaciones.

                                    Respetaré el secreto de quien haya confiado en mí.

                                    Mantendré, en todas las medidas de mi medio, el honor y las nobles tradiciones de la profesión médica. Mis colegas serán mis hermanos.

                                    No permitiré que entre mi deber y mi enfermo vengan a interponerse consideraciones de religión, de nacionalidad, de raza, partido o clase.

                                    Tendré absoluto respeto por la vida humana.

                                    Aun bajo amenazas, no admitiré utilizar mis conocimientos médicos contra las leyes de la humanidad.

                                    Hago estas promesas solemnemente, libremente, por mi honor.
                                </p>
                                <!--<a href="#" class="btn btn-outline-light" data-toggle="modal">Quiero ser orador</a>
                                <button type="button" class="btn btn-platzi" data-toggle="modal" data-target="#modalCompra">Comprar tickets</button>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--speakers-->
      <!--<section id="speakers" class="mt-4 mb-4">
        <div class="container">
          <div class="row">
            <div class="col text-center text-uppercase">
              <small>Conoce a los</small>
              <h2>speakers</h2>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-6 col-lg-4 mb-4">
              <div class="card">
                <img src="images/speaker1.png"  class="card-img-top" alt="speaker1">
                <div class="card-body">
                  <div class="badges"> 
                    <span class="badge badge-warning"> Javascript</span>
                    <span class="badge badge-info"> React</span>
                  </div>
                  <h5 class="card-title mb-0">Speaker seleccionado</h5>
                  <p class="card-text">Cras luctus diam quis justo bibendum blandit. Nunc quam sem, maximus varius porta nec, elementum id purus. </p>
                  <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4 mb-4">
              <div class="card">
                <img src="images/speaker2.jpg" class="card-img-top" alt="speaker2" >
                <div class="card-body">
                  <div class="badges"> 
                    <span class="badge badge-warning"> Javascript</span>
                    <span class="badge badge-info"> React</span>
                  </div>
                  <h5 class="card-title mb-0">Speaker seleccionado</h5>
                  <p class="card-text">Cras luctus diam quis justo bibendum blandit. Nunc quam sem, maximus varius porta nec, elementum id purus. </p>
                  <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4 mb-4">
              <div class="card">
                <img src="images/speaker3.png" class="card-img-top" alt="speaker3">
                <div class="card-body">
                  <div class="badges"> 
                    <span class="badge badge-warning"> Javascript</span>
                    <span class="badge badge-info"> React</span>
                  </div>
                  <h5 class="card-title mb-0">Speaker seleccionado</h5>
                  <p class="card-text">Cras luctus diam quis justo bibendum blandit. Nunc quam sem, maximus varius porta nec, elementum id purus. </p>
                  <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4 mb-4">
              <div class="card">
                <img src="images/speaker4.png" class="card-img-top" alt="speaker4">
                <div class="card-body">
                  <div class="badges"> 
                    <span class="badge badge-warning"> Javascript</span>
                    <span class="badge badge-react"> React</span>
                  </div>
                  <h5 class="card-title mb-0">Speaker seleccionado</h5>
                  <p class="card-text">Cras luctus diam quis justo bibendum blandit. Nunc quam sem, maximus varius porta nec, elementum id purus. </p>
                  <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="index.js"></script>
</body>
</html>