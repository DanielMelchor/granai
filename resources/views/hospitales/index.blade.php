@extends('admin.layout')

@section('titulo')
	Hospitales
@endsection
@section('subtitulo')
	Listado
@endsection

@section('contenido')
	<div class="card card-navy">
		<div class="card-header">
			<div class="row">
				<div class="col-md-1 offset-md-11" style="text-align: right;">
					<a href="{{ route('crear_hospital')}}" class="btn btn-sm btn-primary" title="Crear Hospital"><i class="fas fa-plus-circle"></i></a>
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
										<th>Nombre</th>
										<th>Dirección</th>
										<th>Teléfonos</th>
										<th>Estado</th>
										<th>&nbsp;</th>
									</tr>	
								</thead>
								<tbody>
									@foreach($pHospitales as $pHospital)
										<tr class="text-center">
											<td>
												{{ $pHospital->nombre}}
											</td>
											<td>
												{{ $pHospital->direccion}}
											</td>
											<td>
												{{ $pHospital->telefonos}}
											</td>
											@if($pHospital->estado == 'A')
												<td>Alta</td>
											@else
												<td>Baja</td>
											@endif
											<td>
												<a href="{{route('editar_hospital' , $pHospital->id)}}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i>
												</a>
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