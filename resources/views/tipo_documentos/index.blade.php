@extends('admin.layout')

@section('titulo')
	Tipos de Documento
@endsection
@section('subtitulo')
	Listado
@endsection

@section('contenido')
	<div class="card card-navy">
		<div class="card-header">
			<div class="row">
				<div class="col-md-1 offset-md-11" style="text-align: right;">
					<a href="{{ route('crear_tipodocumento')}}" class="btn btn-sm btn-primary" title="Crear Tipo de documento"><i class="fas fa-plus-circle"></i></a>
				</div>
			</div>
		</div>
		<form class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6 offset-md-3">
						<div class="table-responsive">
							<table class="table table-sm table-striped table-hover">
								<thead class="thead-primary text-center">
									<tr>
										<th>Descripción</th>
										<th>Estado</th>
										<th>&nbsp;</th>
									</tr>	
								</thead>
								<tbody>
									@foreach($pTipoDocumentos as $pTipoDocumento)
										<tr class="text-center">
											<td class="col-md-4">	{{$pTipoDocumento->descripcion}}
											</td>
											@if($pTipoDocumento->estado == 'A')
												<td class="col-md-1">Alta</td>
											@else
												<td class="col-md-1">Baja</td>
											@endif
											<td>
												<a href="{{route('editar_tipodocumento' , $pTipoDocumento->id)}}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
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