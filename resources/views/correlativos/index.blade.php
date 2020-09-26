@extends('admin.layout')

@section('titulo')
	Correlativos
@endsection
@section('subtitulo')
	Listado
@endsection

@section('contenido')
	<div class="card card-navy">
		<div class="card-header">
			<div class="row">
				<div class="col-md-1 offset-md-11" style="text-align: right;">
					<a href="{{ route('crear_correlativo_1')}}" class="btn btn-sm btn-primary" title="Crear Correlativo"><i class="fas fa-plus-circle"></i></a>
				</div>
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
										<th class="text-center">Tipo</th>
										<th class="text-center">Correlativo</th>
										&nbsp;
									</tr>	
								</thead>
								<tbody>
									@foreach($pCorrelativos as $pC)
										<tr class="text-center">		
											<td>
												@if($pC->tipo == 'A')
													<p>Admisiones</p>
												@endif
												@if($pC->tipo == 'P')
													<p>Pacientes</p>
												@endif
											</td>
											<td>{{ $pC->correlativo }}</td>
											<td><a href="{{route('editar_correlativo_1' , $pC->id)}}" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i></a></td>
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