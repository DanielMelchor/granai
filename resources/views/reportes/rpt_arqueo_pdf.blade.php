<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-4.5.2-dist/css/bootstrap.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-4.5.2-dist/css/bootstrap-grid.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/jquery')}}">
  <script type="text/javascript" src="{{ asset('assets/jquery-3.5.1.min.js')}}"></script>
  <title>Arqueo Detallado</title>
  <style>
    @page {
        margin: 0px 0px;
        font-family: monospace;
    }

    body {
        margin: 3cm 2cm 2cm;
    }

    header {
        position: fixed;
        top: 1cm;
        left: 1cm;
        right: 0cm;
        height: 2cm;
        padding-top: 20px;
        /*background-color: red;
        color: white;*/
        text-align: left;
        line-height: 5px !important;
    }

    footer {
        position: fixed;
        bottom: 0cm;
        left: 0cm;
        right: 0cm;
        height: 1.5cm;
        background-color: white;
        color: black;
        text-align: center;
        line-height: 5px !important;
    }
    main {
      font-size: 11px !important;
    }
    th{ font-size: 11px !important; }
    td{ font-size: 10px !important; }
    p{ font-size: 10px !important;}
    #footer .page:after { content: counter(page); }
    .row {
      line-height: 5px;
    }
    img {
      text-align: left;
    }

    @font-face {
      font-family: Piazzolla-VariableFont_opsz,wght;
      src: url('{{ asset('fonts/Piazzolla-VariableFont_opsz,wght.tff') }}');
    }

</style>
</head>
<body>
<header>
  <div class="row">
    <div class="col-md-10">
      <br>
      <div class="row">
        <div class="col-md-10">
          <h5>{{ $empresa->nombre_comercial }}</h5>
        </div>
      </div>
      <div class="row">
        <div class="col-md-10">
          <p>Caja {{ $corte->nombre_maquina }}</p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-10">
          <p>Corte de caja No. {{ $corte->corte }} de fecha {{ \Carbon\Carbon::parse($corte->fecha)->format('d/m/Y') }}</p>
        </div>
      </div>
    </div>
    <div class="col-md-2 offset-md-10">
      <img src="{{ asset('')}}{{$empresa->ruta_logo}}" alt="logo" height="100%">
    </div>
  </div>
</header>
<main>
    <h6>Recibos</h6>
    <br>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-sm table-striped text-center">
          <thead>
            <tr>
              <th>Recibo</th><th>Tipo</th><th>No. Admisión</th><th>Paciente</th><th>Total</th><th>Efectivo</th><th>Cheque</th><th>Transferencia</th><th>Tarjeta</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pagos as $p)
              <tr>
                <td>{{ $p->serie }}-{{ $p->correlativo }}</td>
                <td>{{ $p->tipo_admision}}</td>
                <td>{{ $p->admision }}</td>
                <td>{{ $p->nombre_completo }}</td>
                <td>{{ $p->total_recibo }}</td>
                <td>{{ $p->Efectivo }}</td>
                <td>{{ $p->Cheque }}</td>
                <td>{{ $p->Transferencia }}</td>
                <td>{{ $p->Tarjeta }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <br>
    <h6>Documentos</h6>
    <br>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-sm table-striped text-center">
          <thead>
            <tr>
              <th>Tipo</th>
              <th>Documento</th>
              <th>Fecha</th>
              <th>N.I.T.</th>
              <th>Nombre</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($documentos as $d)
              <tr>
                <td>{{ $d->tipodocumento_descripcion }}</td>
                <td>{{ $d->serie }}-{{ $d->correlativo }}</td>
                <td>{{ \Carbon\Carbon::parse($d->fecha_emision)->format('d/m/Y') }}</td>
                <td>{{ $d->nit }}</td>
                <td>{{ $d->nombre }}</td>
                <td>{{ $d->total_documento }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
</main>
<footer id="footer">
    <p class="page">Pagína # </p>
</footer>
</body>
</html>