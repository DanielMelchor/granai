<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  	<title>Impresión de Factura</title>
	<link rel="stylesheet" href="{{ asset('assets/bootstrap-4.5.2-dist/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/receta.css')}}">
	<style>
		@page { margin: 0px 20px 0px 20px  }
		.section{
			width:770px;
			height:302px;
		    /*border:solid;*/
		    margin-top: 30px;
		    margin-bottom: 5px;
		    border-radius: 1em;
		    position: static;
		}
		.section_detalle{
			width:740px;
			height:150px;
		    background:white;
		    /*border:solid;*/
		    margin-top: 30px;
		    margin-left: 10px;
		    margin-bottom: 10px;
		    border-radius: 1em;
		    position: relative;
		}
		.section_total{
			width:740px;
			height:20px;
		    /*border:solid;*/
		    margin-top: 0px !important;
		    margin-left: 10px !important;
		    margin-bottom: 0px !important;
		    border-radius: 1em !important;
		    position: relative;
		}
		.fecha {
		    width:15%;
		    height:15px;
		    font-size: 11px !important;
		    position: relative;
		    top:5;
		    left:300;
		}
		.nombre{
			position: relative;
		    top:20;
		    left:40;
			font-size: 11px !important;
			margin: 0 !important;
		}
		.direccion{
			position: relative;
		    top:20;
		    left:40;
			font-size: 11px !important;
			margin: 0 !important;
		}
		.nit{
			position: relative;
		    top:20;
		    left:40;
			font-size: 11px !important;
			margin: 0 !important;
		}
		.factura{
			float: right;
		    top:150;
		    left:350;
		    padding-right: 150px;
			font-size: 11px !important;
			margin: 0 !important;
		}
		.admision{
			position: relative;
		    top:0;
		    left:250;
			font-size: 11px !important;
			margin: 0 !important;
		}
		#detalle{
			/*border:solid;*/
			position: relative;
		    top:5;
		    left:10;
		    line-height: 5px;
			font-size: 11px !important;
			margin: 5 !important;
			width:720px;
			height:165px;
		    /*border:solid;*/
		    margin-bottom: 5px;
		    border-radius: 1em;
		}
		p {
			font-size: 11px !important;
		}
	</style>
</head>
<body>
	<div class="section">
		<div class="fecha">{{ \Carbon\Carbon::parse($encabezado->fecha_emision)->format('d/m/Y') }}</div>
		<div class="admision">{{ $encabezado->admision }}</div>
		<div class="nombre">{{ $encabezado->nombre }} </div>
		<div class="factura">{{ $encabezado->serie }}-{{ $encabezado->correlativo }}</div>
		<div class="direccion">{{ $encabezado->direccion }}</div>
		<div class="nit">{{ $encabezado->nit }}</div>
		<div class="section_detalle">
			<table id="detalle" class="table table-sm">
				<tbody>
					@foreach($detalle as $d)
					<tr>
						<td>{{ $d->descripcion }}</td>
						<td colspan="1" style="text-align: right;">{{ $d->precio_bruto }}</td>
					</tr>
					@endforeach
					<tr>
						<td>Paciente: {{ $encabezado->paciente_nombre }}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="section_total">
			<table class="table table-sm">
				<tbody>
					<tr>
						<td><p>{{ $letras }}</p></td>
						<td style="text-align: right;"><p>{{ $total_neto->precio_neto }}</p></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="section">
		<div class="fecha">{{ \Carbon\Carbon::parse($encabezado->fecha_emision)->format('d/m/Y') }}</div>
		<div class="admision">{{ $encabezado->admision }}</div>
		<div class="nombre">{{ $encabezado->nombre }} </div>
		<div class="factura">{{ $encabezado->serie }}-{{ $encabezado->correlativo }}</div>
		<div class="direccion">{{ $encabezado->direccion }}</div>
		<div class="nit">{{ $encabezado->nit }}</div>
		<div class="section_detalle">
			<table id="detalle" class="table table-sm">
				<tbody>
					@foreach($detalle as $d)
					<tr>
						<td>{{ $d->descripcion }}</td>
						<td colspan="1" style="text-align: right;">{{ $d->precio_bruto }}</td>
					</tr>
					@endforeach
					<tr>
						<td>Paciente: {{ $encabezado->paciente_nombre }}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="section_total">
			<table class="table table-sm">
				<tbody>
					<tr>
						<td><p>{{ $letras }}</p></td>
						<td style="text-align: right;"><p>{{ $total_neto->precio_neto }}</p></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="section">
		<div class="fecha">{{ \Carbon\Carbon::parse($encabezado->fecha_emision)->format('d/m/Y') }}</div>
		<div class="admision">{{ $encabezado->admision }}</div>
		<div class="nombre">{{ $encabezado->nombre }} </div>
		<div class="factura">{{ $encabezado->serie }}-{{ $encabezado->correlativo }}</div>
		<div class="direccion">{{ $encabezado->direccion }}</div>
		<div class="nit">{{ $encabezado->nit }}</div>
		<div class="section_detalle">
			<table id="detalle" class="table table-sm">
				<tbody>
					@foreach($detalle as $d)
					<tr>
						<td>{{ $d->descripcion }}</td>
						<td colspan="1" style="text-align: right;">{{ $d->precio_bruto }}</td>
					</tr>
					@endforeach
					<tr>
						<td>Paciente: {{ $encabezado->paciente_nombre }}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="section_total">
			<table class="table table-sm">
				<tbody>
					<tr>
						<td><p>{{ $letras }}</p></td>
						<td style="text-align: right;"><p>{{ $total_neto->precio_neto }}</p></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</body>
</html>