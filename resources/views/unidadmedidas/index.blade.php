@extends('admin.layout')

@section('titulo')
	Unidades de Medida
@endsection
@section('subtitulo')
	Listado
@endsection

@section('contenido')
	<div class="card card-navy">
		<div class="card-header">
			<div class="row">
				<div class="col-md-1 offset-md-11" style="text-align: right;">
					<a href="{{ route('crear_unidadmedida')}}" class="btn btn-sm btn-primary" title="Crear Unidad de Médida"><i class="fas fa-plus-circle"></i></a>
				</div>
			</div>
		</div>
		<form class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-10 offset-md-1">
						<div class="table-responsive">
							<table class="table table-sm table-striped table-hover">
								<thead class="thead-primary text-center">
									<tr>
										<th>Descripción</th>
										<th>Abreviatura</th>
										<th>Estado</th>
									</tr>	
								</thead>
								<tbody>
									@foreach($pUnidadmedidas as $pUnidadmedida)
										<tr class="text-center">
											<td>{{ $pUnidadmedida->descripcion}}</td>
											<td>{{ $pUnidadmedida->siglas}}</td>
											@if($pUnidadmedida->estado == 'A')
												<td>Alta</td>
											@else
												<td>Baja</td>
											@endif
											<td>
												<a href="{{route('editar_unidadmedida' , $pUnidadmedida->id)}}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
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