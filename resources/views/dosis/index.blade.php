@extends('admin.layout')

@section('titulo')
	Dosís
@endsection
@section('subtitulo')
	Listado
@endsection

@section('contenido')
	<div class="card card-navy">
		<div class="card-header">
			<div class="row">
				<div class="col-md-1 offset-md-11" style="text-align: right;">
					<a href="{{ route('crear_dosis')}}" class="btn btn-sm btn-primary" title="Crear Dosis"><i class="fas fa-plus-circle"></i></a>
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
										<th>Estado</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									@foreach($pDosis as $pDos)
										<tr class="text-center">
											<td>{{ $pDos->descripcion }}</td>
											@if($pDos->estado == 'A')
												<td>Alta</td>
											@else
												<td>Baja</td>
											@endif
											<td>
												<a href="{{route('editar_dosis' , $pDos->id)}}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
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
	<!-- modal -->
	<div class="modal fade" id="nuevaDosisModal" tabindex="-1" role="dialog" aria-labelledby="nuevaDosisModalLabel" aria-hidden="true">
  		<div class="modal-dialog" role="document">
	    	<div class="modal-content">
	      		<div class="modal-body">
	        		<div class="container-fluid spark-screen">
						<div class="row">
							<div class="col-md-12">
								<form role="form" method="POST" action="{{route('grabar_dosis')}}">
				            		@csrf
				            		{{csrf_field()}}
									<div class="panel panel-primary">
										<div class="panel-heading text-center">
											<h3>Nueva Dosís</h3>
										</div>

										<div class="panel-body">
											<div class="row">
											    <div class="form-group col-md-10 col-md-offset-1">
							                  		<label for="descripcion">Descripción</label>
						                  			<input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="descripcion" value="{{ old('descripcion')}}">
								                </div>
								            </div>
						                	<div>
							                	<div class="row">
								                	<div class="col-md-3 col-md-offset-1">
								                  		<label>
								                    		<input type="checkbox" data-size="md" id="estado" name="estado" value="A" data-on="Alta" data-off="Baja"> Activar
								                  		</label>
							                		</div>
						                		</div>
											</div>
										</div>
										<div class="panel-footer">
											<div class="row">
												<div class="col-md-3 col-md-offset-9" style="text-align: right;">
													<button type="submit" class="btn btn-primary btn-sm fa fa-save" title="Grabar"></button>
													<a href="{{route('dosis')}}" class="btn btn-warning btn-sm fa fa-sign-out" title="Regresar a lista de Dosís" data-dismiss="modal"></a>
												</div>
											</div>
										</div>
										<br>
									</div>
								</form>
							</div>
						</div>
					</div>
	      		</div>
    		</div>
	  	</div>
	</div>
	<!-- /modal -->
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
	<script type="text/javascript">
		$(document).ready(function(){
		    $("#nuevaDosisModal").on('shown.bs.modal', function(){
		        $(this).find('#descripcion').focus();
		    });
		});
	</script>
@endsection
