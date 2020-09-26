@extends('admin.layout')

@section('titulo')
	Medicos
@endsection
@section('subtitulo')
	Edición
@endsection

@section('contenido')
	<div class="row">
        <div class="col">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <form role="form" method="POST" action="{{route('actualizar_medico', $medico->id )}}" enctype="multipart/form-data">
    	@csrf
    	<div class="card card-navy">
	    	<div class="card-header">
	    		<div class="row">
					<div class="col-md-2 offset-md-10" style="text-align: right;">
						<button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
						<!--<a href="{{route('medicos')}}" class="btn btn-sm btn-danger" title="Regresar a lista de medicos"><i class="fas fa-sign-out-alt"></i></a>-->
						<a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
					</div>
				</div>
	    	</div>
	    	<div class="card-body">
	    		<ul class="nav nav-tabs ml-auto p-2">
			  		<li class="nav-item">
				    	<a class="nav-link active" href="#generales" data-toggle="tab">Generales</a>
				  	</li>
				  	<li class="nav-item">
				    	<a class="nav-link" href="#firma" data-toggle="tab">Firma</a>
				  	</li>
				  	<li class="nav-item">
				    	<a class="nav-link" href="#especialidades" data-toggle="tab">Especialidades</a>
				  	</li>
				  	<li class="nav-item">
				    	<a class="nav-link" href="#receta" data-toggle="tab">Receta</a>
				  	</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="generales">
						<br>
						<div class="row text-center">
					    	<div class="input-group mb-1 col-md-5 offset-md-1">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Nombres</span>
				          		</div>
				          		<input type="text" class="form-control" placeholder="nombres" id="nombres" name="nombres" autofocus required value="{{ $medico->nombres }}">
				        	</div>
				        	<div class="input-group mb-1 col-md-5">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Apellidos</span>
				          		</div>
				          		<input type="text" class="form-control" placeholder="Apellidos" id="apellidos" name="apellidos" required value="{{ $medico->apellidos }}">
				        	</div>
						</div>
						<div class="row text-center">
							<div class="input-group mb-1 col-md-10 offset-md-1">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Dirección</span>
				          		</div>
				          		<input type="text" class="form-control" placeholder="direccion" id="direccion" name="direccion" value="{{ $medico->direccion }}">
				        	</div>
						</div>

						<div class="row text-center">
					    	<div class="input-group mb-1 col-md-5 offset-md-1">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Titulo</span>
				          		</div>
				          		<input type="text" class="form-control" placeholder="Titulo" id="titulo" name="titulo" required value="{{ $medico->titulo }}">
				        	</div>
				        	<div class="input-group mb-1 col-md-5">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">N.I.T.</span>
				          		</div>
				          		<input type="text" class="form-control" placeholder="# Identificacion Tributaria" id="nit" name="nit" value="{{ $medico->nit }}">
				        	</div>
						</div>

						<div class="row text-center">
					    	<div class="input-group mb-1 col-md-3 offset-md-1">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Teléfono</span>
				          		</div>
				          		<input type="text" class="form-control" placeholder="# telefono" id="telefono" name="telefono" value="{{ $medico->telefono }}">
				        	</div>
				        	<div class="input-group mb-1 col-md-3">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Celular</span>
				          		</div>
				          		<input type="text" class="form-control" placeholder="# celular" id="celular" name="celular" value="{{ $medico->celular }}">
				        	</div>
				        	<div class="input-group mb-1 col-md-3">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Localizador</span>
				          		</div>
				          		<input type="text" class="form-control" placeholder="# localizador" id="localizador" name="localizador" value="{{ $medico->localizador }}">
				        	</div>
						</div>
						<div class="row">
		            		<div class="form-group offset-md-1">
			                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
		                      		<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A" @if($medico->estado == 'A') then checked @endif>
			                      	<label class="custom-control-label" for="estado">Activar</label>
			                    </div>
		                  	</div>
		                  	<div class="form-group offset-md-1">
			                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
		                      		<input type="checkbox" class="custom-control-input" id="principal" name="principal" value="S" @if($medico->principal == 'S') then checked @endif>
			                      	<label class="custom-control-label" for="principal">Medico principal</label>
			                    </div>
		                  	</div>
		            	</div>
					</div>
					<div class="tab-pane" id="firma">
						<br>
						@if($medico->firma == '')
							<div class="row">
		    					<div class="input-group col col-md-offset-1">
				                	<label for="medico_firma">Firma Digital:</label>
				                	<input type="file" class="form-control" id="medico_firma" name="medico_firma" accept="image/*" />
				                </div>
		    				</div>
							<br>
						@endif
						<img src="{{ asset('/') }}{{ $medico->firma }}" class="rounded mx-auto d-block" alt="firma digital" style="width: 300px;">
						<div class="row">
							<div class="col-md-2 offset-md-10">
								<a href="{{ route('borrar_foto_medico', $medico->id) }}" class="btn btn-md btn-danger" title="Eliminar firma"><i class="fas fa-trash-alt"></i></a>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="especialidades">
						<br>
						<div class="row text-center">
							<div class="col-md-6 offset-md-3">
								<div class="table-responsive">
									<table class="table table-sm table-hover">
										<thead>
											<tr>
												<th>
													Especialidad
												</th>
											</tr>
										</thead>
										<tbody>
											@foreach($pEspecialidades as $p)
												<tr>
													<td>{{ $p->descripcion }}</td>
													<td colspan="1">
														<div class="form-check">
														    <input type="checkbox" class="form-check-input form-group" id="check[]" name="check[]" value="{{ $p->id }}" @if($p->detestado == 'A') checked @endif>
														</div>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="receta">
						<br>
						<div class="row text-center">
							<div class="col-md-12">
								<p class="bg-secondary">Pagina</p>
							</div>
						</div>
						<div class="row">
							<div class="input-group mb-1 col-md-2 offset-md-1">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Largo</span>
				          		</div>
				          		<input type="number" class="form-control" placeholder="largo de pagina" id="pagina_x" name="pagina_x" value="{{ $receta->pagina_alto }}" step="0.01" style="text-align: right;">
				        	</div>
				        	<div class="input-group mb-1 col-md-2">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Ancho</span>
				          		</div>
				          		<input type="number" class="form-control" placeholder="Ancho de pagina" id="pagina_y" name="pagina_y" value="{{ $receta->pagina_ancho }}" step="0.01" style="text-align: right;">
				        	</div>
							<div class="input-group mb-1 col-md-3">
						  		<div class="input-group-prepend">
							    	<span class="input-group-text" for="unidad_medida">Medída</span>
							  	</div>
							  	<select class="custom-select select2 select2bs4" id="unidad_medida" name="unidad_medida">
							    	<option value=""> Seleccionar... </option>
						        	<option value="in" @if($receta->unidad_medida == 'in') then selected @endif> Pulgadas </option>
						        	<option value="cm" @if($receta->unidad_medida == 'cm') then selected @endif> Centimetros </option>
						        	<option value="mm" @if($receta->unidad_medida == 'mm') then selected @endif> Milimetros </option>
							  	</select>
							</div>
							<div class="input-group mb-1 col-md-3">
						  		<div class="input-group-prepend">
							    	<span class="input-group-text" for="orientacion">Orientación</span>
							  	</div>
							  	<select class="custom-select select2 select2bs4" id="orientacion" name="orientacion">
							    	<option value=""> Seleccionar... </option>
						        	<option value="P" @if($receta->orientacion == 'P') then selected @endif> Vertical </option>
						        	<option value="L" @if($receta->orientacion == 'L') then selected @endif> Horizontal </option>
							  	</select>
							</div>
						</div>
						<br>
						<div class="row text-center">
							<div class="col-md-12">
								<p class="bg-secondary">Fecha</p>
							</div>
						</div>
						<div class="row">
							<div class="input-group mb-1 col-md-2">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Dia X</span>
				          		</div>
				          		<input type="number" class="form-control" id="dia_x" name="dia_x" value="{{ $receta->dia_x }}" step="0.01" style="text-align: right;">
				        	</div>
				        	<div class="input-group mb-1 col-md-2">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Dia Y</span>
				          		</div>
				          		<input type="number" class="form-control" id="dia_y" name="dia_y" value="{{ $receta->dia_y }}" step="0.01" style="text-align: right;">
				        	</div>
				        	<div class="input-group mb-1 col-md-2">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Mes X</span>
				          		</div>
				          		<input type="number" class="form-control" id="mes_x" name="mes_x" value="{{ $receta->mes_x }}" step="0.01" style="text-align: right;">
				        	</div>
				        	<div class="input-group mb-1 col-md-2">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Mes Y</span>
				          		</div>
				          		<input type="number" class="form-control" id="mes_y" name="mes_y" value="{{ $receta->mes_y }}" step="0.01" style="text-align: right;">
				        	</div>
				        	<div class="input-group mb-1 col-md-2">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Año X</span>
				          		</div>
				          		<input type="number" class="form-control" id="anio_x" name="anio_x" value="{{ $receta->anio_x }}" step="0.01" style="text-align: right;">
				        	</div>
				        	<div class="input-group mb-1 col-md-2">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text">Año Y</span>
				          		</div>
				          		<input type="number" class="form-control" id="anio_y" name="anio_y" value="{{ $receta->anio_y }}" step="0.01" style="text-align: right;">
				        	</div>
						</div>
						<br>
						<div class="row text-center">
							<div class="col-md-6">
								<p class="bg-secondary">Paciente</p>
							</div>
							<div class="col-md-6">
								<p class="bg-secondary">Tratamiento</p>
							</div>
						</div>
						<div class="row">
							<div class="input-group mb-1 col-md-2 offset-md-1">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text"> X </span>
				          		</div>
				          		<input type="number" class="form-control" id="paciente_x" name="paciente_x" value="{{ $receta->paciente_x }}" step="0.01" style="text-align: right;">
				        	</div>
				        	<div class="input-group mb-1 col-md-2">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text"> Y </span>
				          		</div>
				          		<input type="number" class="form-control" id="paciente_y" name="paciente_y" value="{{ $receta->paciente_y }}" step="0.01" style="text-align: right;">
				        	</div>
				        	<div class="input-group mb-1 col-md-2 offset-md-2">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text"> X </span>
				          		</div>
				          		<input type="number" class="form-control" id="tratamiento_x" name="tratamiento_x" value="{{ $receta->tratamiento_x }}" step="0.01" style="text-align: right;">
				        	</div>
				        	<div class="input-group mb-1 col-md-2">
				          		<div class="input-group-prepend">
				        			<span class="input-group-text"> Y </span>
				          		</div>
				          		<input type="number" class="form-control" id="tratamiento_y" name="tratamiento_y" value="{{ $receta->tratamiento_y }}" step="0.01" style="text-align: right;">
				        	</div>
						</div>
					</div>
				</div>
	    	</div>
	    </div>
    </form>

    
	<div class="tab-content">
		
	</div>
@endsection
@section('js')
<script type="text/javascript">
	function confirma_salida(){
        swal({
            title: 'Confirmación',
            text: 'Seguro de Salir, si ha realizado cambios estos no seran guardados  ?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-success',
            cancelButtonClass: 'btn-danger',
            confirmButtonText: 'Si',
            cancelButtonText: 'No',
            closeOnConfirm: false,
            allowEscapeKey: true
            },
            function(isConfirm) {
                if (isConfirm) { 
                    window.location.href = "{{ route('medicos') }}";
                                } 
                /*else { 
                    swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                    }*/
            }
        );
    }
</script>
@endsection