@extends('admin.layout')

@section('titulo')
	Aseguradoras
@endsection
@section('subtitulo')
	Listado
@endsection

@section('contenido')
	<div class="card card-navy">
		<div class="card-header">
			<div class="col-md-1 offset-md-11" style="text-align: right;">
				<a href="{{ route('crear_aseguradora')}}" class="btn btn-sm btn-primary" title="Crear Aseguradora"><i class="fas fa-plus-circle"></i></a>
			</div>
		</div>
		<form class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-10 offset-md-1">
						<div class="table-responsive">
							<table class="table table-sm table-striped table-hover">
								<thead class="thead-primary">
									<tr>
										<th scope="col" class="text-center">Nombre</th>
										<th scope="col" class="text-center">Dirección</th>
										<th scope="col" class="text-center">Teléfonos</th>
										<th scope="col" class="text-center">Contacto</th>
										<th scope="col" class="text-center">Estado</th>
									</tr>	
								</thead>
								<tbody>
									@foreach($pAseguradoras as $pAseguradora)
										<tr class="text-center">
											<td>{{ $pAseguradora->nombre}}</td>
											<td>{{ $pAseguradora->direccion}}</td>
											<td>{{ $pAseguradora->telefonos }}</td>
											<td>{{ $pAseguradora->contacto }}</td>
											@if($pAseguradora->estado == 'A')
												<td>Alta</td>
											@else
												<td>Baja</td>
											@endif
											<td><a href="{{route('editar_aseguradora' , $pAseguradora->id)}}" class="btn btn-sm btn-warning" title="Editar Aseguradora"><i class="fas fa-edit"></i></a></td>
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