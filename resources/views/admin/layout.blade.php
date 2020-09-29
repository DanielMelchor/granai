<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Grupo | Amad </title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('assets/adminlte/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/adminlte/css/adminlte.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> -->
  <link rel="stylesheet" href="{{ asset('assets/bootstrap-4.5.2-dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/alertifyjs/css/alertify.min.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}">
  <style type="text/css">
    .btn-reporte{
      background-color: #FF8D33 !important;
    }
    .btn-excel{
      background-color: #A5C890 !important;
    }
    .btn-config{
      background-color: #C8BA90 !important;
    }
    .btn-refactura{
      background-color: #C890A4 !important;
    }
    .btn-renumera{
      background-color: #8B9BC1 !important;
    }
    .btn-anular{
      background-color: #226D7C !important;
      color: white !important;
    }
  </style>
  @yield('css')
</head>
<body class="hold-transition sidebar-collapse layout-top-nav">
  <!-- wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
      <img src="{{ asset('logos/grupo_amad_02.png') }}" style="height: 50px;">
      <div class="container">
        <a href="#" class="navbar-brand">
          <img src="{{ asset('assets/logos/granai_mini.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
          <!-- <span class="brand-text font-weight-light">AdminLTE 3</span> -->
        </a>
        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
          <!-- Left navbar links -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link"><i class="fas fa-home"></i>Inicio</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('nueva_agenda') }}" class="nav-link"><i class="fas fa-calendar-alt"></i> Agenda</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('pacientes') }}" class="nav-link"><i class="fas fa-user-injured"></i> Pacientes</a>
            </li>
            <!-- administracion -->
            <li class="nav-item dropdown">
              <a id="administracionSubMenu" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-user-secret"></i> Administración</a>
              <ul aria-labelledby="administracionSubMenu" class="dropdown-menu border-0 shadow">
                <li><a href="{{ route('empresas') }}" class="dropdown-item">Empresas</a></li>
                <li><a href="#" class="dropdown-item">Roles </a></li>
                <li><a href="{{ route('usuarios') }}" class="dropdown-item">Usuarios</a></li>
              </ul>
            </li>
            <!-- /administracion -->
            <!-- catalogos -->
            <li class="nav-item dropdown">
              <a id="catalogosSubMenu" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-cog"></i> Catálogos</a>
              <ul aria-labelledby="catalogosSubMenu" class="dropdown-menu border-0 shadow">
                <li><a href="{{ route('aseguradoras') }}" class="dropdown-item">Aseguradoras</a></li>
                <li><a href="{{ route('bancos') }}" class="dropdown-item">Bancos</a></li>
                <li><a href="{{ route('cajas') }}" class="dropdown-item">Cajas</a></li>
                <li><a href="{{ route('correlativos') }}" class="dropdown-item">Correlativos</a></li>
                <li><a href="{{ route('dosis') }}" class="dropdown-item">Dosis</a></li>
                <li><a href="{{ route('especialidades') }}" class="dropdown-item">Especialidades medicas</a>
                <li><a href="{{ route('hospitales') }}" class="dropdown-item">Hospitales</a></li>
                <li><a href="{{ route('medicamentos') }}" class="dropdown-item">Medicamentos</a></li>
                <li><a href="{{ route('medicos') }}" class="dropdown-item">Médicos</a></li>
                <li><a href="{{ route('motivosAnulacion') }}" class="dropdown-item">Motivos de Anulación de Facturas</a></li>
                <li><a href="{{ route('motivoRechazos') }}" class="dropdown-item">Motivos de Rechazo</a></li>
                <li><a href="{{ route('observaciones') }}" class="dropdown-item">Observaciones Admisión</a></li>
                <li><a href="{{ route('productos') }}" class="dropdown-item">Productos</a></li>
                <li><a href="{{ route('tipodocumentos') }}" class="dropdown-item">Tipos de Documento</a></li>
                <li><a href="{{ route('unidadmedidas') }}" class="dropdown-item">Unidades de medída</a></li>
              </ul>
            </li>
            <!-- /catalogos -->
            <!-- Procesos -->
            <li class="nav-item dropdown">
              <a id="catalogosSubMenu" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-qrcode"></i> Procesos</a>
              <ul aria-labelledby="catalogosSubMenu" class="dropdown-menu border-0 shadow">
                <li><a href="{{ route('admisiones') }}" class="dropdown-item">Admisiones</a></li>
                <li><a href="{{ route('documentos_listado') }}" class="dropdown-item">Facturación</a></li>
                <li><a href="{{ route('nc_listado') }}" class="dropdown-item">Notas de Crédito</a></li>
                <li><a href="{{ route('nd_listado') }}" class="dropdown-item">Notas de Débito</a></li>
                <li><a href="{{ route('recibos_listado') }}" class="dropdown-item">Recibos</a></li>
                <hr>
                <li><a href="{{ route('listado_cortes') }}" class="dropdown-item">Corte de Caja</a></li>
              </ul>
            </li>
            <!-- procesos -->
            <!-- Reportes -->
            <li class="nav-item dropdown">
              <a id="reportesSubMenu" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-file-alt"></i> Reportes</a>
              <ul aria-labelledby="reportesSubMenu" class="dropdown-menu border-0 shadow">
                <li><a href="{{ route('rpt_admisiones_activas',[date('Y-m-d'),date('Y-m-d'), 'T']) }}" class="dropdown-item">Admisiones Activas</a></li>
                <li><a href="{{ route('rpt_admisiones_con_saldo') }}" class="dropdown-item">Admisiones con Saldo</a></li>
                <li><a href="{{ route('rpt_admisiones_por_fecha',['T', date('Y-m-d'), date('Y-m-d')]) }}" class="dropdown-item">Admisiones por Fecha</a></li>
                <li><a href="{{ route('rpt_admision_consultas',[date('Y-m-d'),date('Y-m-d')]) }}" class="dropdown-item">Consultas</a></li>
                <li><a href="{{ route('rpt_admision_hospitalizacion',[date('Y-m-d'),date('Y-m-d')]) }}" class="dropdown-item">Hospitalizaciones</a></li>
                <li><a href="{{ route('rpt_admision_procedimientos',[date('Y-m-d'),date('Y-m-d')]) }}" class="dropdown-item">Procedimientos</a></li>
                <hr>
                <li><a href="{{ route('rpt_arqueo_factura',[0, date('Y-m-d'),date('Y-m-d')]) }}" class="dropdown-item">Arqueo de Facturas</a></li>
                <li><a href="{{ route('rpt_arqueo_recibo',[0, date('Y-m-d'),date('Y-m-d')]) }}" class="dropdown-item">Arqueo de Recibos</a></li>
                <li><a href="{{ route('rpt_arqueo_cheques',[0, date('Y-m-d'),date('Y-m-d')]) }}" class="dropdown-item">Arqueo de Cheques</a></li>
                <li><a href="{{ route('rpt_arqueo_tarjetas', [0, date('Y-m-d'),date('Y-m-d')]) }}" class="dropdown-item">Arqueo de Tarjetas</a></li>
                <li><a href="{{ route('rpt_antiguedad_saldos') }}" class="dropdown-item">Antiguedad de Saldos</a></li>
                <li><a href="{{ route('rpt_cargos_sin_factura_idx') }}" class="dropdown-item">Cargos no Facturados</a></li>
                <li><a href="{{ route('rpt_anulaciones',[date('Y-m-d'),date('Y-m-d')]) }}" class="dropdown-item">Anulaciones</a></li>
              </ul>
            </li>
            <!-- Reportes -->

          </ul>
        </div>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('cambio_clave') }}">Cambio de contraseña</a>
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
          </li>
        </ul>
      </div>

      </div>
    </nav>
  </div>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- /.card -->
        <div class="row">
          <div class="col-md-10 offset-md-1">
            <br>
            <h5>@yield('titulo') / @yield('subtitulo')</h5>
            <br>
            @yield('contenido')
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.0.4
    </div>
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
    reserved.
  </footer>
  <!-- jQuery -->
  <script src="{{ asset('assets/jquery-3.5.1.min.js') }}"></script>
  <script src="{{ asset('assets/popper.min.js') }}"></script>
  <script src="{{asset('assets/adminlte/plugins/jquery/jquery.min.js')}}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{asset('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('assets/adminlte/js/adminlte.min.js')}}"></script>
  <link  href="{{ asset('assets/fancybox/dist/jquery.fancybox.min.css') }}" rel="stylesheet">
  <script src="{{ asset('assets/fancybox/dist/jquery.fancybox.min.js') }}"></script>
  <script src="{{ asset('assets/bootstrap-4.5.2-dist/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/bootstrap-4.5.2-dist/js/bootstrap.min.js') }}"></script>
  <!--<script src="{{ asset('assets/alertifyjs/alertify.min.js') }}"></script>-->
  <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>

  <script>
     var asset = '{{ asset('') }}'
  </script>
  @yield('js')

</body>
</html>