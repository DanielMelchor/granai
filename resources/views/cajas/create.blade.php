@extends('admin.layout')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('titulo')
	Cajas
@endsection
@section('subtitulo')
	Agregar
@endsection

@section('contenido')
	@if(Session::has('message'))
   		<div class="alert alert-success alert-dismissible" role="alert">
	    	<button type="button" class="close" data-dismiss="alert" arial-label="Close"><span aria-hidden="true">x</span>
	    	</button>
	        {{ Session::get('message') }}  
	    </div>
	@endif
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
	<!-- form start -->
		<div class="card card-navy">
			<div class="card-header">
				<div class="col-md-2 offset-md-10" style="text-align: right;">
					<button type="button" onclick="fn_grabar_caja(); return false;" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
					<!--<a href="{{route('cajas')}}" class="btn btn-sm btn-danger" title="Regresar a lista de Cajas"><i class="fas fa-sign-out-alt"></i></a>	-->
					<a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
				</div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="input-group mb-5 col-md-4 offset-md-1">
				  		<div class="input-group-prepend">
					    	<span class="input-group-text">Nombre</span>
					  	</div>
					  	<input type="text" class="form-control" placeholder="nombre Caja" aria-label="Username" aria-describedby="basic-addon1" id="caja_nombre" name="caja_nombre" autofocus required value="{{ old('caja_nombre')}}">
					</div>
				
					<div class="form-group clearfix">
		              	<div class="icheck-primary d-inline">
		              		<label for="editar_documento">Editar Documento</label> &nbsp;
							<input type="checkbox" data-bootstrap-switch data-off-color="danger" data-on-color="success" id="editar_documento" name="editar_documento">	
		              	</div>
		          	</div>
					<div class="form-group offset-md-1">
						<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
			              	<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
			          		<label class="custom-control-label" for="estado">Activar</label>
			        	</div>
			        </div>
				</div>
				<hr>
				<div class="card card-secondary">
					<div class="card-header">
						<div class="row">
							<div class="col-md-8">
								<h6>Resoluciones</h6>
							</div>
							<div class="col-md-4" style="text-align: right;">
								<a href="#" class="btn btn-sm btn-primary" title="Agregar Resolucion" onclick="fn_agregar_registro(); return false;">
									<i class="fas fa-plus-circle"></i>
								</a>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-10 offset-md-1 text-center">
								<div class="table-responsive">
									<table id="tblResolucion" class="table table-sm table-hover">
										<thead>
											<tr>
												<th>Tipo Documento</th><th>Serie</th><th>Inicial</th><th>Final</th><th>Actual</th><th>Estado</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- form end -->
	<!-- agregar resolucion -->
	<div class="modal fade" id="nuevaResolucionModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  		<div class="modal-dialog" role="document">
	    	<div class="modal-content">
	      		<div class="modal-body">
	        		<form role="form" onsubmit="fn_grabar_local(); return false;">
			  			<div class="card card-navy">
			  				<div class="card-header">
			  					<div class="row">
			  						<div class="col-md-8">
			  							<h6>Nueva Resolución</h6>
			  						</div>
			  						<div class="col-md-4" style="text-align: right;">
			  							<button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
			  							<button type="button" class="btn btn-sm btn-danger" title="Salir" onclick="cerrar_modal('agregar'); return false;"><i class="fas fa-sign-out-alt"></i></button>
			  						</div>
			  					</div>
			  				</div>
			  				<div class="card-body">
			  					<div class="row">
					        		<div class="col-md-10 offset-md-1 mb-1">
				                        <div class="input-group input-group-sm">
				                            <div class="input-group-prepend">
				                                <span class="input-group-text" for="tipo_documento_id">Documento</span>
				                            </div>
				                            <select class="custom-select custom-select-sm select2 select2bs4" id="tipo_documento_id" name="tipo_documento_id" autofocus required>
				                                <option value="">Seleccionar...</option>
				                                @foreach($tipo_documentos as $td)
				                                    <option value="{{ $td->id}}">{{ $td->descripcion }}</option>
				                                @endforeach
				                            </select>
				                        </div>
				                    </div>
			                	</div>
			                	<div class="row">
				                    <div class="col-md-5 offset-md-1">
				                        <div class="input-group mb-1 input-group-sm">
				                            <div class="input-group-prepend">
				                                <span class="input-group-text">Serie</span>
				                            </div>
				                            <input type="text" class="form-control form-control-sm text-center" id="serie" name="serie" style="text-transform: uppercase;" required>
				                        </div>
				                    </div>
				                    <div class="col-md-5">
				                        <div class="input-group mb-1 input-group-sm">
				                            <div class="input-group-prepend">
				                                <span class="input-group-text">Inicial</span>
				                            </div>
				                            <input type="number" class="form-control form-control-sm text-center" id="correlativo_inicial" name="correlativo_inicial" style="text-align: right;" min="0" required>
				                        </div>
				                    </div>
				                </div>
				                <div class="row">
				                	<div class="col-md-5 offset-md-1">
				                        <div class="input-group mb-1 input-group-sm">
				                            <div class="input-group-prepend">
				                                <span class="input-group-text">Final</span>
				                            </div>
				                            <input type="number" class="form-control form-control-sm text-center" id="correlativo_final" name="correlativo_final" style="text-align: right;" min="0" required>
				                        </div>
				                    </div>
				                    <div class="col-md-5">
				                        <div class="input-group mb-1 input-group-sm">
				                            <div class="input-group-prepend">
				                                <span class="input-group-text">Ultimo</span>
				                            </div>
				                            <input type="number" class="form-control form-control-sm text-center" id="ultimo_correlativo" name="ultimo_correlativo" style="text-align: right;" min="0" required>
				                        </div>
				                    </div>
				                </div>
				                <div class="row">
				                	<div class="form-group offset-md-1">
										<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
							              	<input type="checkbox" class="custom-control-input" id="resolucion_estado" name="resolucion_estado" value="A">
							          		<label class="custom-control-label" for="resolucion_estado">Activar</label>
							        	</div>
							        </div>
				                </div>
			  				</div>
			  			</div>
		  			</form>
	      		</div>
	    	</div>
	  	</div>
	</div>
	<!-- /agregar resolucion -->
	<!-- editar resolucion -->
	<div class="modal fade" id="editarResolucionModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  		<div class="modal-dialog" role="document">
	    	<div class="modal-content">
	      		<div class="modal-body">
	        		<form role="form" onsubmit="fn_actualizar_local(); return false;">
			  			<div class="card card-navy">
			  				<div class="card-header">
			  					<div class="row">
			  						<div class="col-md-8">
			  							<h6>Edición de Resolución</h6>
			  						</div>
			  						<div class="col-md-4" style="text-align: right;">
			  							<button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
			  							<!--<button type="button" class="btn btn-sm btn-danger" title="Salir" data-dismiss="modal"><i class="fas fa-sign-out-alt"></i></button>-->
			  							<button type="button" class="btn btn-sm btn-danger" title="Salir" onclick="cerrar_modal('editar'); return false;"><i class="fas fa-sign-out-alt"></i></button>
			  						</div>
			  					</div>
			  				</div>
			  				<div class="card-body">
			  					<input type="hidden" id="elinea" name="elinea">
			  					<div class="row">
					        		<div class="col-md-10 offset-md-1 mb-1">
				                        <div class="input-group input-group-sm">
				                            <div class="input-group-prepend">
				                                <span class="input-group-text" for="etipo_documento_id">Documento</span>
				                            </div>
				                            <select class="custom-select custom-select-sm select2 select2bs4" id="etipo_documento_id" name="etipo_documento_id" autofocus required>
				                                <option value="">Seleccionar...</option>
				                                @foreach($tipo_documentos as $td)
				                                    <option value="{{ $td->id}}">{{ $td->descripcion }}</option>
				                                @endforeach
				                            </select>
				                        </div>
				                    </div>
			                	</div>
			                	<div class="row">
				                    <div class="col-md-5 offset-md-1">
				                        <div class="input-group mb-1 input-group-sm">
				                            <div class="input-group-prepend">
				                                <span class="input-group-text">Serie</span>
				                            </div>
				                            <input type="text" class="form-control form-control-sm text-center" id="eserie" name="eserie" style="text-transform: uppercase;" required>
				                        </div>
				                    </div>
				                    <div class="col-md-5">
				                        <div class="input-group mb-1 input-group-sm">
				                            <div class="input-group-prepend">
				                                <span class="input-group-text">Inicial</span>
				                            </div>
				                            <input type="number" class="form-control form-control-sm text-center" id="ecorrelativo_inicial" name="ecorrelativo_inicial" style="text-align: right;" min="0" required>
				                        </div>
				                    </div>
				                </div>
				                <div class="row">
				                	<div class="col-md-5 offset-md-1">
				                        <div class="input-group mb-1 input-group-sm">
				                            <div class="input-group-prepend">
				                                <span class="input-group-text">Final</span>
				                            </div>
				                            <input type="number" class="form-control form-control-sm text-center" id="ecorrelativo_final" name="ecorrelativo_final" style="text-align: right;" min="0" required>
				                        </div>
				                    </div>
				                    <div class="col-md-5">
				                        <div class="input-group mb-1 input-group-sm">
				                            <div class="input-group-prepend">
				                                <span class="input-group-text">Ultimo</span>
				                            </div>
				                            <input type="number" class="form-control form-control-sm text-center" id="eultimo_correlativo" name="eultimo_correlativo" style="text-align: right;" min="0" required>
				                        </div>
				                    </div>
				                </div>
				                <div class="row">
				                	<div class="form-group offset-md-1">
										<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
							              	<input type="checkbox" class="custom-control-input" id="eresolucion_estado" name="eresolucion_estado" value="A">
							          		<label class="custom-control-label" for="eresolucion_estado">Activar</label>
							        	</div>
							        </div>
				                </div>
			  				</div>
			  			</div>
		  			</form>
	      		</div>
	    	</div>
	  	</div>
	</div>
	<!-- /editar resolucion -->
	<!-- mensaje modal -->
    <div class="modal fade" id="mensajeModal" tabindex="-1" role="dialog" aria-labelledby="mensajeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h3 class="modal_texto text-center" style="color: green;"></h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /mensaje modal -->
@endsection
@section('js')
	<script src="{{ asset('assets/adminlte/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
	<script type="text/javascript">
		$(function(){
			$("input[data-bootstrap-switch]").each(function(){
		      $(this).bootstrapSwitch('state', $(this).prop('checked'));
		    });
		});

        //========================================================================
        // inicializar librerias
        //========================================================================
        $(function () {
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        });


        var nlinea   = 0;
        var error    = false;
        var statSend = false;
        let ruta = '';

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
                        window.location.href = "{{ route('cajas') }}";
                                    } 
                    else { 
                        swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                        }
                }
            );
	        /*alertify.confirm('<i class="fas fa-sign-out-alt"></i> Salir', '<h6>Seguro de salir si ha realizado cambios estos no seran grabados ? </h6>', function(){ 
	        window.location.href = ruta;
	            }
	            , function(){ alertify.error('Se deja sin efecto')}
	        );*/
	    }

        window.addEventListener('load', function(){
            var local_db = [];
            localStorage.clear(local_db);
            localStorage.local_db     = JSON.stringify(local_db);
            var linea = {};
            ruta = document.referrer;
        });

		function fn_agregar_registro(){
			document.getElementById('tipo_documento_id').value = '';
			$('#tipo_documento_id').change();
			document.getElementById('serie').value = '';
			document.getElementById('correlativo_inicial').value = '';
			document.getElementById('correlativo_final').value = '';
			document.getElementById('ultimo_correlativo').value = '';
			$("#nuevaResolucionModal").modal('show');
		}

		function fn_editar_registro(id){
			var local_db = JSON.parse(localStorage.local_db);
			for (var i = 0; i < local_db.length; i++) {
				if (local_db[i]['linea'] == id) {
					document.getElementById('eserie').value = local_db[i]['serie'];
				}
			}
			var local_db = JSON.parse(localStorage.local_db);
			for (var i = 0; i < local_db.length; i++) {
				if (local_db[i]['linea'] == id) {
					document.getElementById('elinea').value = local_db[i]['linea'];
					document.getElementById('etipo_documento_id').value = local_db[i]['tipo_documento_id'];
					$('#etipo_documento_id').change();
					document.getElementById('eserie').value = local_db[i]['serie'];
					document.getElementById('ecorrelativo_inicial').value = local_db[i]['correlativo_inicial'];
					document.getElementById('ecorrelativo_final').value = local_db[i]['correlativo_final'];
					document.getElementById('eultimo_correlativo').value = local_db[i]['ultimo_correlativo'];
				}
			}
			$("#editarResolucionModal").modal('show');
		}

		function eliminar_registro(id){
			var local_db          = JSON.parse(localStorage.local_db);
			for (var i = 0; i < local_db.length; i++) {
				if (local_db[i]['linea'] == id) {
					local_db.splice(i, 1);
					localStorage.local_db = JSON.stringify(local_db);
				}
			}
			actualizarTabla();
		}

		function fn_grabar_local(){
			var tipo_documento = document.getElementById('tipo_documento_id');
			var tipo_documento_id = tipo_documento.options[tipo_documento.selectedIndex].value;
            var tipo_documento_descripcion = tipo_documento.options[tipo_documento.selectedIndex].text;
            var serie   = document.getElementById('serie').value.toUpperCase();
            var inicial = document.getElementById('correlativo_inicial').value;
            var final   = document.getElementById('correlativo_final').value;
            var ultimo  = document.getElementById('ultimo_correlativo').value;
            if (document.getElementById('resolucion_estado').checked == true) {
				var resolucion_estado = 'A';
			}else {
				var resolucion_estado = 'I';
			}

            var linea = {
            	linea                      : nlinea,
            	tipo_documento_id          : tipo_documento_id,
            	tipo_documento_descripcion : tipo_documento_descripcion,
            	serie                      : serie,
            	correlativo_inicial        : inicial,
            	correlativo_final          : final,
            	ultimo_correlativo         : ultimo,
            	resolucion_estado          : resolucion_estado
            }
            if(!localStorage.local_db){
                localStorage.local_db = JSON.stringify([linea]);
            }
            else{
                local_db = JSON.parse(localStorage.local_db);
                local_db.push(linea);
                localStorage.local_db = JSON.stringify(local_db);
            }
            nlinea += 1;
            actualizarTabla();
            $('#nuevaResolucionModal').modal('hide');
		}

		function checksubmit(){
			error = false;
			var caja_nombre = document.getElementById('caja_nombre').value;
			if (caja_nombre.length > 0) {
				if (!statSend) {
                    statSend = true;
                    return true;
                } else {
                    alert("El formulario ya se esta enviando...");
                    return false;
                }
			}else{
                error = true;
                swal({
	                title: 'Error !!!',
	                text: 'Nombre de caja no definido, favor verifique',
	                type: 'error',
	                });
				/*$('.modal_texto').html('Nombre de caja no definido, favor verifique');
                $("#mensajeModal").modal('show');*/
            }
		}

		function fn_actualizar_local(){
			var local_db          = JSON.parse(localStorage.local_db);
			var tipo_documento = document.getElementById('etipo_documento_id');
			var tipo_documento_id = tipo_documento.options[tipo_documento.selectedIndex].value;
            var tipo_documento_descripcion = tipo_documento.options[tipo_documento.selectedIndex].text;
            var id      = document.getElementById('elinea').value;
            var serie   = document.getElementById('eserie').value.toUpperCase();
            var inicial = document.getElementById('ecorrelativo_inicial').value;
            var final   = document.getElementById('ecorrelativo_final').value;
            var ultimo  = document.getElementById('eultimo_correlativo').value;
            if (document.getElementById('eresolucion_estado').checked == true) {
				var resolucion_estado = 'A';
			}else {
				var resolucion_estado = 'I';
			}
			var linea = {
            	linea                      : id,
            	tipo_documento_id          : tipo_documento_id,
            	tipo_documento_descripcion : tipo_documento_descripcion,
            	serie                      : serie,
            	correlativo_inicial        : inicial,
            	correlativo_final          : final,
            	ultimo_correlativo         : ultimo,
            	resolucion_estado          : resolucion_estado
            }

            for (var i = 0; i < local_db.length; i++) {
            	if (local_db[i]['linea'] == id) {
            		local_db.splice(i, 1);
            		localStorage.local_db = JSON.stringify(local_db);
            	}
            }

            if(!localStorage.local_db){
                localStorage.local_db = JSON.stringify([linea]);
            }
            else{
                local_db = JSON.parse(localStorage.local_db);
                local_db.push(linea);
                localStorage.local_db = JSON.stringify(local_db);
            }

            actualizarTabla();
            $('#editarResolucionModal').modal('hide');
		}


		function fn_grabar_caja(){
			checksubmit();

			if(!error){
				var local_db          = JSON.parse(localStorage.local_db);
				if (document.getElementById('editar_documento').checked == true) {
					var editar_documento = 'S';
				}else {
					var editar_documento = 'N';
				}
				if (document.getElementById('estado').checked == true) {
					var caja_estado = 'A';
				}else {
					var caja_estado = 'I';
				}
				var caja_nombre = document.getElementById('caja_nombre').value;
				$.ajax({
					headers: {
	                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	                },
	                url: "{{route('grabar_caja')}}",
	                method: "POST",
	                data: { arreglo           : JSON.stringify(local_db),
	                	    caja_nombre       : caja_nombre,
	                	    editar_documento  : editar_documento,
	                	    caja_estado       : caja_estado
	                },
	                success: function(response){
	                	var info = response;
	                    swal({
	                        title: 'Trabajo Finalizado',
	                        text: info.respuesta,
	                        type: 'success',
	                        },
	                        function(){
	                        	return window.location.href = asset+'cajas/editar/'+info.caja_id;
	                        });
	                    /*$('.modal_texto').html(info.respuesta);
	                    $("#mensajeModal").modal('show');*/
	                    
	                },
	                error: function(error){
	                    console.log(error);
	                }
				});
			}
		}

		function actualizarTabla(){
			var local_db          = JSON.parse(localStorage.local_db);
			var html = '';
			for(var i = 0; i < local_db.length; i++){
				html += '<tr>'
                html += '<td>'
                html += local_db[i]['tipo_documento_descripcion']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['serie']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['correlativo_inicial']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['correlativo_final']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['ultimo_correlativo']
                html += '</td>'
                html += '<td>'
                html += 'Activo'
                html += '</td>'
                html += '<td style="text-align: right;">'
                html += '<button class="btn btn-sm btn-warning" onclick="fn_editar_registro('+i+'); return false;" title="Editar Registro"><i class="fas fa-edit"></i></button> &nbsp;'
                html += '<button class="btn btn-sm btn-danger" onclick="eliminar_registro('+i+');" title="Eliminar Registro"><i class="fa fa-trash-alt"></i></button>'
                html += '</td>'
                html += '</tr>'
			}
			$("#tblResolucion tbody tr").remove();
            $('#tblResolucion tbody').append(html);
		}

		function cerrar_modal(proceso){
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
                        //window.location.href = "{{ route('pacientes') }}";
                        if (proceso == 'agregar') {
		            		$('#nuevaResolucionModal').modal('hide');	
		            	}else{
		            		$('#editarResolucionModal').modal('hide');
		            	}
		            	swal.close();
                                    } 
                    else { 
                        swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                        }
                }
            );
		}

	</script>
@endsection