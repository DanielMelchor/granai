@extends('admin.layout')

@section('titulo')
	Medicos
@endsection
@section('subtitulo')
	Listado
@endsection
@section('contenido')
	<div class="card card-navy">
		<div class="card-header">
			<div class="col-md-1 offset-md-11" style="text-align: right;">
				<a href="{{ route('crear_medico')}}" class="btn btn-sm btn-primary" title="Crear Caja"><i class="fas fa-plus-circle"></i></a>
			</div>
		</div>
		<form class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-10 offset-md-1">
						<div class="table-responsive">
							<table class="table table-sm table-striped table-hover text-center">
								<thead class="thead-primary">
									<tr>
										<th>Nombres</th>
										<th>Apellidos</th>
										<th>Celular</th>
										<th>Teléfono</th>
										<th>Localizador</th>
										<th>Estado</th>
										<th>&nbsp;</th>
									</tr>	
								</thead>
								<tbody>
									@foreach($pMedicos as $pMedico)
										<tr class="text-center">
											<td>{{ $pMedico->nombres}}</td>
											<td>{{ $pMedico->apellidos}}</td>
											<td>{{ $pMedico->celular }}</td>
											<td>{{ $pMedico->telefono }}</td>
											<td>{{ $pMedico->localizador }}</td>
											@if($pMedico->estado == 'A')
												<td>Alta</td>
											@else
												<td>Baja</td>
											@endif
											<td>
												<a href="{{route('editar_medico' , $pMedico->id)}}" class="btn btn-sm btn-warning" title="Editar Médico"><i class="fas fa-edit"></i>
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
	<!-- Modal -->
	<div class="modal fade" id="recetaModal" tabindex="-1" role="dialog" aria-labelledby="recetaModalLabel" aria-hidden="true">
  		<div class="modal-dialog" role="document">
	    	<div class="modal-content">
	      		<div class="modal-body">
	        		<div class="panel panel-primary">
						<div class="panel-heading text-center">
							<h3>Impresión de Receta</h3>
						</div>

						<div class="panel-body">
							<div class="row text-center">
								<div class="col-md-12">
									<p class="bg-primary">Pagina</p>
								</div>
							</div>
							<div class="row text-center">
								<div class="col-md-4 offset-md-2">
									<label>Largo</label>
									<input type="number" class="form-control" id="pagina_x" name="pagina_x">
								</div>
								<div class="col-md-4">
									<label>Ancho</label>
									<input type="number" class="form-control" id="pagina_y" name="pagina_y">
								</div>
							</div>
							<div class="row text-center">
								<div class="form-group col-md-4 offset-md-2">
				                	<label>Medida</label>
				                	<select id ="unidad_medida" name="unidad_medida" class="form-control has-success select2" style="width: 100%;">
							        	<option value=""> Seleccionar... </option>
							        	<option value="in"> Pulgadas </option>
							        	<option value="cm"> Centimetros </option>
							        	<option value="mm"> Milimetros </option>
				                	</select>
								</div>
								<div class="form-group col-md-4">
				                	<label>Orientación</label>
				                	<select id ="unidad_medida" name="unidad_medida" class="form-control has-success select2" style="width: 100%;">
							        	<option value=""> Seleccionar... </option>
							        	<option value="portrait"> Vertical </option>
							        	<option value="landscape"> Horizontal </option>
				                	</select>
								</div>
							</div>
							<div class="row text-center">
								<div class="col-md-12">
									<p class="bg-primary">Fecha</p>
								</div>
							</div>
							<div class="row text-center">
								<div class="col-md-4">
									<label>Día</label>
								</div>
								<div class="col-md-4">
									<label>Mes</label>
								</div>
								<div class="col-md-4">
									<label>Año</label>
								</div>
							</div>
							<div class="row text-center">
								<div class="col-md-2">
									<input type="number" class="form-control" id="dia_x" name="dia_x">
								</div>
								<div class="col-md-2">
									<input type="number" class="form-control" id="dia_y" name="dia_y">
								</div>
								<div class="col-md-2">
									<input type="number" class="form-control" id="mes_x" name="mes_x">
								</div>
								<div class="col-md-2">
									<input type="number" class="form-control" id="mes_y" name="mes_y">
								</div>
								<div class="col-md-2">
									<input type="number" class="form-control" id="anio_x" name="anio_x">
								</div>
								<div class="col-md-2">
									<input type="number" class="form-control" id="anio_y" name="anio_y">
								</div>
							</div>
							<br>
							<div class="row text-center">
								<div class="col-md-6">
									<p class="bg-primary">Paciente</p>
								</div>
								<div class="col-md-6">
									<p class="bg-primary">Tratamiento</p>
								</div>
							</div>
							<div class="row text-center">
								<div class="col-md-3">
									<input type="number" class="form-control" id="paciente_x" name="paciente_x">
								</div>
								<div class="col-md-3">
									<input type="number" class="form-control" id="paciente_y" name="paciente_y">
								</div>
								<div class="col-md-3">
									<input type="number" class="form-control" id="tratamiento_x" name="tratamiento_x">
								</div>
								<div class="col-md-3">
									<input type="number" class="form-control" id="tratamiento_y" name="tratamiento_y">
								</div>
							</div>
						</div>
						<div class="panel-footer">
							<div class="row">
								<div class="col-md-3 offset-md-9" style="text-align: right;">
									<a href="#" id="actualizar_receta" class="btn btn-sm btn-primary fa fa-save" title="Grabar" onclick="actualizar_config_receta()"></a>
									<a href="#" id="grabar_receta" class="btn btn-sm btn-primary fa fa-save" title="Grabar" onclick="grabar_config_receta()"></a>
									<button type="button" class="btn btn-sm btn-warning fa fa-sign-out" title="Cerrar" data-dismiss="modal"></button>
								</div>
							</div>
						</div>
					</div>
	      		</div>
    		</div>
	  	</div>
	</div>
@endsection
@section('js')
	<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
	<script src="{{ asset('AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
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
		function recetaModal(id){
			$.ajax({
				url: "{{ route('existe_config_receta') }}",
				type: "POST",
		        dataType: 'json',
		        data: {"_token": "{{ csrf_token() }}",medico_id : id},
		        success: function(response){
		        	var info = response;
		        	if (info == 0) {
		        		document.getElementById("actualizar_receta").style.display="none";
		        		$('#recetaModal').modal('show');
		        	} else{
		        		document.getElementById("grabar_receta").style.display="none";
		        		document.getElementById("pagina_x").value = 15;
		        		$('#recetaModal').modal('show');
		        	}
		        },
		        error: function(error){
		            console.log(error);
		        }
			});
		}
		function grabar_config_receta(){
			
		}
	</script>
@endsection
