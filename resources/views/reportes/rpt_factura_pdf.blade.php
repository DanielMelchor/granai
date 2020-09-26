<!DOCTYPE html>
	<head>
  		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  		<title>Factura</title>
		<link rel="stylesheet" href="{{ asset('bootstrap-4.4.1-dist/css/bootstrap.min.css') }}">
  		<link rel="stylesheet" type="text/css" href="{{ asset('css/receta.css')}}">
  		<style>
	  		@page { margin: 20px 20px 20px 20px  }

		  	.section{
				width:780px;
				height:306px;
			    background:white;
			    /*border:solid;*/
			    margin-top: 5px;
			    margin-bottom: 5px;
			    border-radius: 1em;
			}
			.fecha {
			    width:15%;
			    height:15px;
			    font-size: 10px !important;
			    position: relative;
			    top:5;
			    left:300;
			}
			.nombre{
				position: relative;
			    top:15;
			    left:50;
				font-size: 10px !important;
				margin: 0 !important;
			}
			.direccion{
				position: relative;
			    top:15;
			    left:50;
				font-size: 10px !important;
				margin: 0 !important;
			}
			.nit{
				position: relative;
			    top:15;
			    left:50;
				font-size: 10px !important;
				margin: 0 !important;
			}
			.documento{
				position: relative;
			    top:15;
			    left:300;
				font-size: 10px !important;
				margin: 0 !important;
			}
			#detalle{
				position: relative;
			    top:15;
			    left:20;
				font-size: 11px !important;
				margin: 0 !important;
				width:730px;
				height:185px;
			    /*border:solid;*/
			    padding-right: 5px;
			    padding-left: 5px;
			    margin-bottom: 5px;
			    border-radius: 1em;
			}
			.cantidad{
				width: 50px;
				height: 10px;
				left: 60;
			}
			.descripcion{
				width: 300px;
				height: 10px;
				left: 150;
			}
			.precio{
				position: relative;
			    top:15;
				font-size: 10px !important;
				margin: 0 !important;
			}
	</style>
	</head>
<html>
<body>
	<table width="100%" border="0" class="table table-sm table-borderless" >
		<tr>
			<td>
				<section id="section_cliente" class="section">
					<table>
						<tr style="height: 1px;">
							<td>
								<div class="fecha">{{ \Carbon\Carbon::parse($encabezado->fecha_emision)->format('d/m/Y') }}</div>
							</td>
						</tr>
						<tr style="height: 1px;">
							<td>
								<div class="nombre">{{ $encabezado->nombre }}</div>
							</td>
							<td>
								<div class="documento">{{ $encabezado->serie}}{{'-'}}{{ $encabezado->correlativo }}</div>
							</td>
						</tr>
						<tr style="height: 1px;">
							<td>
								<div class="direccion">{{ $encabezado->direccion }}</div>
							</td>
						</tr>
						<tr style="height: 1px;">
							<td>
								<div class="nit">{{ $encabezado->nit }}</div>
							</td>
						</tr>
						<tr>
							<table id="detalle" class="table table-sm table-borderless">
								<tbody>
									@foreach($detalle as $d)
										<tr>
											<td>{{ $d->cantidad }}</td>
											<td>{{ $d->descripcion }}</td>
											<td style="text-align: right;">{{ $d->precio_bruto }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</tr>
					</table>
				</section>
			</td>
		</tr>
		<tr>
			<td>
				<section id="section_conta" class="section">
					<table>
						<tr style="height: 1px;">
							<td>
								<div class="fecha">{{ \Carbon\Carbon::parse($encabezado->fecha_emision)->format('d/m/Y') }}</div>
							</td>
						</tr>
						<tr style="height: 1px;">
							<td>
								<div class="nombre">{{ $encabezado->nombre }}</div>
							</td>
							<td>
								<div class="documento">{{ $encabezado->serie}}{{'-'}}{{ $encabezado->correlativo }}</div>
							</td>
						</tr>
						<tr style="height: 1px;">
							<td>
								<div class="direccion">{{ $encabezado->direccion }}</div>
							</td>
						</tr>
						<tr style="height: 1px;">
							<td>
								<div class="nit">{{ $encabezado->nit }}</div>
							</td>
						</tr>
						<tr>
							<table id="detalle" class="table table-sm table-borderless">
								<tbody>
									@foreach($detalle as $d)
										<tr>
											<td>{{ $d->cantidad }}</td>
											<td>{{ $d->descripcion }}</td>
											<td style="text-align: right;">{{ $d->precio_bruto }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</tr>
					</table>
				</section>
			</td>
		</tr>
		<tr>
			<td>
				<section id="section_archivo" class="section">
					<table>
						<tr style="height: 1px;">
							<td>
								<div class="fecha">{{ \Carbon\Carbon::parse($encabezado->fecha_emision)->format('d/m/Y') }}</div>
							</td>
						</tr>
						<tr style="height: 1px;">
							<td>
								<div class="nombre">{{ $encabezado->nombre }}</div>
							</td>
							<td>
								<div class="documento">{{ $encabezado->serie}}{{'-'}}{{ $encabezado->correlativo }}</div>
							</td>
						</tr>
						<tr style="height: 1px;">
							<td>
								<div class="direccion">{{ $encabezado->direccion }}</div>
							</td>
						</tr>
						<tr style="height: 1px;">
							<td>
								<div class="nit">{{ $encabezado->nit }}</div>
							</td>
						</tr>
						<tr>
							<table id="detalle" class="table table-sm table-borderless">
								<tbody>
									@foreach($detalle as $d)
										<tr>
											<td>{{ $d->cantidad }}</td>
											<td>{{ $d->descripcion }}</td>
											<td style="text-align: right;">{{ $d->precio_bruto }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</tr>
					</table>
				</section>
			</td>
		</tr>
	</table>
	
	

</body>
</html>