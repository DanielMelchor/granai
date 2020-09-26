<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <!--<link rel="stylesheet" type="text/css" href="{{ asset('assets/adminlte/plugins/bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
  <link rel="stylesheet" href="{{ asset('assets/bootstrap-4.5.2-dist/css/bootstrap.min.css') }}">
  <title>Detalle de Cargos</title>
  <style>
    @page { margin: 25px 50px 25px 50px  }
    body { background: white; font-size: 11px !important;}
    .bold { font-weight: bold; }
    h1{
      text-align: center;
      text-transform: uppercase;
      font-size: 30px !important;
      }
    th, td { padding:0 !important; margin:0 !important;  }
    .row, .col-xs-* { padding: 0 }
    #mamografia td { border: 1px solid black; }
    p {
      margin: 0 !important;
    }
    h3{text-align: center; text-transform: uppercase; font-size:20px !important;
        }
    h5{text-align: center; text-transform: uppercase; font-size:12px !important;
        }
    h6{text-align: center; text-transform: uppercase; font-size:10px !important;
        }
    upper {
      text-transform: uppercase;
    }
    #header { position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 2cm;
        background-color: white;
        color: white;
        text-align: center;
        line-height: 30px;
    }
    #footer { position: fixed;
        bottom: 0cm;
        left: 0cm;
        right: 0cm;
        height: 1.5cm;
        background-color: white;
        color: black;
        text-align: center;
        line-height: 25px; 
    }
    #footer .page:after { content: counter(page, upper-roman); }
    #linea { height: 6px; }

  </style>
</head>
<body>
<header>
    <table class="table table-borderless">
      <tr>
        <td width="10%">
          <img src="{{ asset('')}}{{$empresa->ruta_logo}}" alt="logo" height="80px;">
        </td>
        <td width="90%">
          <h3>{{ $empresa->nombre_comercial }}</h3>
          <h5>Detalle de Cargos</h5>
          <h6>No Incluye cuenta ajena</h6>
        </td>
      </tr>
    </table>
    <hr>
</header>
<body>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-sm table-borderless" style="width: 100%;">
          <thead>
            <tr>
              <th colspan="1">Admisión</th><td colspan="3" style="text-align: left;">{{ $encabezado->admision }}</td>
              <th colspan="1">Expediente</th><td colspan="3">{{ $encabezado->admision }}</td>
              <th colspan="1">Entrada</th><td colspan="3">{{ $encabezado->fecha_inicio }}</td>
            </tr>
            <tr>
              <th colspan="1">Paciente</th><td colspan="3">{{ $encabezado->paciente_nombre }}</td>
              <td colspan="4"></td>
              <th colspan="1">Alta</th><td colspan="3">{{ $encabezado->fecha_fin }}</td>
            </tr>
            <tr>
              <th colspan="1">Médico</th><td colspan="3">{{ $encabezado->medico_nombre }}</td>
              <td colspan="4"></td>
              <th colspan="1">Teléfonos</th><td colspan="3">{{ $encabezado->telefonos }} / {{ $encabezado->celular }}</td>
            </tr>
          </thead>
        </table>
        <table class="table table-sm table-bordered">
          <thead>
            <tr class="text-center">
              <th colspan="1">Fecha</th>
              <th colspan="1">Cantidad</th>
              <th colspan="5">Descripción</th>
              <th colspan="1">Unidad</th>
              <th colspan="1">Valor</th>
            </tr>
          </thead>
          <tbody>
            @foreach($detalle as $d)
              <tr>
                <td colspan="1" class="text-center">{{ \Carbon\Carbon::parse($d->created_at)->format('d/m/Y') }}</td>
                <td colspan="1" class="text-center">{{ $d->cantidad }}</td>
                <td colspan="5" class="text-center">{{ $d->descripcion }}</td>
                <td colspan="1" class="text-center"></td>
                <td colspan="1" style="text-align: right;">{{ $d->precio_total }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan="7"></td>
              <td colspan="1" class="text-center">Total</td>
              <td colspan="1" style="text-align: right;"><strong>{{ $total }}</strong></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
</body>
<footer id="footer">
    <p class="page">Pagína # </p>
</footer>
</body>
</html>