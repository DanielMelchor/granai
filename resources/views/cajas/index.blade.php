@extends('admin.layout')

@section('titulo')
	Cajas
@endsection
@section('subtitulo')
	Listado
@endsection

@section('contenido')
	<div class="card card-navy">
		<div class="card-header">
			<div class="col-md-1 offset-md-11" style="text-align: right;">
				<a href="{{ route('crear_caja')}}" class="btn btn-sm btn-primary" title="Crear Caja"><i class="fas fa-plus-circle"></i></a>
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
										<th class="text-center">Número</th>
										<th class="text-center">Descripción</th>
										<th class="text-center">Estado</th>
										&nbsp;
									</tr>	
								</thead>
								<tbody>
									@foreach($pCajas as $pCaja)
										<tr class="text-center">
											<td>{{ $pCaja->id }}</td>
											<td>{{ $pCaja->nombre_maquina }}</td>
											@if($pCaja->estado == 'A')
												<td>Alta</td>
											@else
												<td>Baja</td>
											@endif
											<td>
												<a href="{{route('editar_caja' , $pCaja->id)}}" class="btn btn-sm btn-warning" title="Editar Caja"><i class="fas fa-edit"></i></a>
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