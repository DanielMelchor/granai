<!DOCTYPE html>
<html>
<head>
  <title>Receta</title>
  <link rel="stylesheet" href="{{ asset('assets/bootstrap-4.5.2-dist/css/bootstrap.min.css') }}">
  <style type="text/css">
    @page {
        margin: 0cm 0cm;
        font-size: 1em;
    }
    body {
        margin: 3cm 2cm 2cm;
    }

    header {
        position: fixed;
        top: 1cm;
        left: 1cm;
        right: 1cm;
        height: 2cm;
        /*background-color: #F93855;*/
        /*color: white;*/
        text-align: center;
        line-height: 15px;
    }

    footer {
        position: fixed;
        bottom: 0cm;
        left: 0cm;
        right: 0cm;
        height: 1cm;
        /*background-color: #F93855;*/
        /*color: white;*/
        text-align: center;
        line-height: 15px;
    }

    .dia {
      position: absolute;
      margin-left: {{ $pRecetaC->dia_x }};
      margin-top: {{ $pRecetaC->dia_y }};
    }

    .mes{
      position: absolute;
      margin-left: {{ $pRecetaC->mes_x }};
      margin-top: {{ $pRecetaC->mes_y }};
    }

    .anio{
      position: absolute;
      margin-left: {{ $pRecetaC->anio_x }};
      margin-top: {{ $pRecetaC->anio_y }};
    }

    #proxima_cita{
      position: relative;
      margin-left: {{ $pRecetaC->proxima_cita_x }};
      margin-top: {{ $pRecetaC->proxima_cita_y }};
    }

    .paciente{
      position: absolute;
      margin-left: {{ $pRecetaC->paciente_x }};
      margin-top: {{ $pRecetaC->paciente_y }};
    }

    .tratamiento{
      position: relative !important;
      width: 600px;
      margin-left : {{ $pRecetaC->tratamiento_x }};
      margin-top: {{ $pRecetaC->tratamiento_y }};
      text-align: justify;
    }
    .page-number:before {
      content: "Pagina " counter(page);
    }
  </style>
</head>
<body>
  <header>
    <table class="table table-sm table-borderless">
      <tbody>
        <tr>
          <td colspan="1">
            <img src="{{ asset($pEmpresa->ruta_logo) }}" style="height: 50px;">
          </td>
          <td colspan="10">
            <div class="row">
              {{ $pEmpresa->nombre_comercial }}
            </div>
            <div class="row">
              {{ $pEmpresa->direccion }}
            </div>
            <div class="row">
              {{ $pEmpresa->telefonos }}
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    </header>
    <body>
      <div class="dia">{{ $dia }}</div>
      <div class="mes">{{ $nombre_mes }}</div>
      <div class="anio">{{ $anio }}</div>
      <div class="paciente">{{ $pConsulta->paciente_nombre }}</div>
      <div class="tratamiento" class="saltopagina">{!! $pConsulta->tratamiento !!}</div>
    </body>

    <footer>
        <div class="page-number"></div>
    </footer>
</body>
</html>