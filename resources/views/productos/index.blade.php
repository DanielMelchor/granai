@extends('admin.layout')

@section('titulo')
	Productos
@endsection
@section('subtitulo')
	Listado
@endsection
@section('contenido')
	<div class="card card-navy">
		<div class="card-header">
			<div class="row">
				<div class="col-md-1 offset-md-11" style="text-align: right;">
					<a href="{{ route('crear_producto')}}" class="btn btn-sm btn-primary" title="Crear Producto"><i class="fas fa-plus-circle"></i></a>
				</div>
			</div>
		</div>
		<form class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-10 offset-md-1">
						<div class="table-responsive">
							<table class="table table-sm table-striped table-hover text-center" style="width: 100%;">
								<thead class="thead-primary">
									<tr>
										<th>Clasificación</th>
										<th>Descripción</th>
										<th>Estado</th>
										<th>&nbsp;</th>
									</tr>	
								</thead>
								<tbody>
									@foreach($pProductos as $pProducto)
										<tr>
											<td>
												@if($pProducto->clasificacion == 'SERV') <p>Servicio</p>
												@endif
												@if($pProducto->clasificacion == 'PROC') <p>Procedimiento</p>
												@endif
												@if($pProducto->clasificacion == 'PROD') <p>Producto</p>
												@endif
											</td>
											<td>{{ $pProducto->descripcion}}</td>
											@if($pProducto->estado == 'A')
												<td>Alta</td>
											@else
												<td>Baja</td>
											@endif
											<td>
                                                <a href="{{ route('editar_producto', $pProducto->id) }}" class="btn btn-sm btn-warning" title="Editar"><i class="fa fa-edit"></i></a>
                                            </td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
@endsection
@section('js')
	@if(Session::has('success'))
        <script>
            swal("Trabajo Finalizado", "{!! Session::get('success') !!}", "success")
        </script>
    @endif
    @if(Session::has('error'))
        <script>
            swal("Error !!!", "{!! Session::get('error') !!}", "error")
        </script>
    @endif
@endsection