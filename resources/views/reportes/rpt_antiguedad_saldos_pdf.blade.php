<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <!--<link rel="stylesheet" type="text/css" href="{{ asset('assets/adminlte/plugins/bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">-->
  <link rel="stylesheet" href="{{ asset('assets/bootstrap-4.5.2-dist/css/bootstrap.min.css') }}">
  <title>Antiguedad de Saldos</title>
  <style>
    @page {
        margin: 0px 0px;
        font-family: Arial;
    }

    body {
        margin: 3cm 2cm 2cm;
    }

    header {
        position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 2cm;
        background-color: white;
        color: white;
        text-align: center;
        line-height: 30px;
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
        line-height: 25px;
    }
    h3{text-align: center; text-transform: uppercase; font-size:20px !important;
        }
    h5{text-align: center; text-transform: uppercase; font-size:12px !important;
        }
    th{ font-size: 11px !important; }
    td{ font-size: 10px !important; }
    #footer .page:after { content: counter(page); }
</style>
</head>
<body>
<header>
    <table class="table table-borderless">
      <tr>
        <td width="10%">
          <img src="{{ asset('')}}{{$empresa->ruta_logo}}" alt="logo" height="80%">
        </td>
        <td width="90%">
          <h3>{{ $empresa->nombre_comercial }}</h3>
          <h5>Antiguedad de Saldos</h5>
        </td>
      </tr>
    </table>
</header>
<main>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-sm table-striped text-center">
          <thead>
            <tr>
              <th># Admisión</th><th>Cliente</th><th>Documento</th>
              <th>Fecha</th><th>Total</th><th>Saldo</th><th>30 Dias</th><th>60 Dias</th><th>90 Dias</th><th>120 Dias</th><th>Mas de 120 Dias</th>
            </tr>
          </thead>
          <tbody>
            @foreach($facturas as $f)
              <tr>
                <td>{{ $f->admision }}</td>
                <td>{{ $f->cliente_nombre }}</td>
                <td>{{ $f->serie }}-{{ $f->correlativo }}</td>
                <td>{{ \Carbon\Carbon::parse($f->fecha_emision)->format('d/m/Y') }}</td>
                <td>{{ $f->total_documento }}</td>
                <td>{{ $f->saldo }}</td>
                <td>{{ $f->dias_30 }}</td>
                <td>{{ $f->dias_mayor_30 }}</td>
                <td>{{ $f->dias_mayor_60 }}</td>
                <td>{{ $f->dias_mayor_90 }}</td>
                <td>{{ $f->dias_mayor_120 }}</td>
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