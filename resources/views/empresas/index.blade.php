@extends('admin.layout')

@section('titulo')
	Empresas
@endsection
@section('subtitulo')
	Listado
@endsection

@section('contenido')
	<div class="card card-navy">
		<div class="card-header">
			<div class="col-md-1 offset-md-11" style="text-align: right;">
				<a href="{{ route('crear_empresa')}}" class="btn btn-sm btn-primary" title="Crear Empresa"><i class="fas fa-plus-circle"></i></a>
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
										<th scope="col" class="text-center">Nombre Comercial</th>
										<th scope="col" class="text-center">Dirección</th>
										<th scope="col" class="text-center">Teléfonos</th>
										<th scope="col" class="text-center">Estado</th>
										<th>&nbsp;</th>
									</tr>	
								</thead>
								<tbody>
									@foreach($pEmpresas as $pEmpresa)
										<tr class="text-center">
											<td>{{ $pEmpresa->nombre_comercial}}</td>
											<td>{{ $pEmpresa->direccion }}</td>
											<td>{{ $pEmpresa->telefonos }}</td>
											@if($pEmpresa->estado == 'A')
												<td>Alta</td>
											@else
												<td>Baja</td>
											@endif
											<td><a href="{{route('editar_empresa' , $pEmpresa->id)}}" class="btn btn-sm btn-warning" title="Editar Empresa"><i class="fas fa-edit"></i></a></td>
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
	<div class="row">
		<div class="col-md-10 offset-md-1">
			<div class="panel panel-primary">
				<div class="panel-header">
				</div>
				<div class="panel-body">
					
					<!-- {{ trans('adminlte_lang::message.logged') }} -->
				</div>
			</div>
		</div>
	</div>
@endsection