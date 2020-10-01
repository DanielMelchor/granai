@extends('admin.layout')

@section('css')
	<script src="{{ asset('assets/adminlte/plugins/moment/moment.min.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
	<style>
		.tooltip-inner {
		    background-color: #00cc00;
		}
		.tooltip.bs-tooltip-right .arrow:before {
		    border-right-color: #00cc00 !important;
		}
		.tooltip.bs-tooltip-left .arrow:before {
		    border-right-color: #00cc00 !important;
		}
		.tooltip.bs-tooltip-bottom .arrow:before {
		    border-right-color: #00cc00 !important;
		}
		.tooltip.bs-tooltip-top .arrow:before {
		    border-right-color: #00cc00 !important;
		}
	</style>
@endsection

@section('titulo')
	Agenda Médica
@endsection
@section('contenido')
    <div class="card card-navy">
		<div class="card-header">
			<div class="col-md-1 offset-md-11" style="text-align: right;">
				<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#agregarEvento" title="Crear Cita"><i class="fas fa-plus-circle"></i></button>
			</div>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-3">
					<div class="card card-secondary">
						<div class="card-header text-center">
							<p>Busqueda</p>
						</div>
						<div class="card-body">
							<div class="row">
                                <div class="mb-1 col-md-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Medico</label>
                                        </div>
                                        <select class="custom-select custom-select-sm" id="f_medico_id"  name="f_medico_id">
                                            <option value="">Seleccionar...</option>
                                            @foreach($medicos as $m)
									        	<option value="{{ $m->id }}" @if($m->principal == 'S') then selected @endif> {{ $m->nombre_completo}} </option>
									        @endforeach
                                        </select>
                                    </div>
                                </div>
							</div>
							<div class="row">
                                <div class="mb-1 col-md-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="estado">Estatus</label>
                                        </div>
                                        <select class="custom-select  custom-select-sm" id="estado"  name="estado">
                                            <option value="T"> Todas </option>
								        	<option value="A" selected> Activas </option>
								        	<option value="C"> Canceladas </option>
								        	<option value="R"> Realizadas </option>
                                        </select>
                                    </div>
                                </div>
							</div>
							<div class="row">
								<div class="input-group mb-1 input-group-sm col-md-12">
		                            <div class="input-group-prepend">
								    	<button class="btn btn-primary" type="button" id="button-addon1" onclick="restar_fecha(); return false;"><i class="fas fa-chevron-circle-left"></i></button>
								  	</div>
		                            <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $today }}" style="text-align: center;">
		                            <div class="input-group-prepend">
								    	<button class="btn btn-primary" type="button" id="button-addon1" onclick="sumar_fecha(); return false;"><i class="fas fa-chevron-circle-right"></i></button>
								  	</div>
		                        </div>
							</div>
							<div class="row">
								<div class="col-md-12 form-group">
									<a href="#" onclick="buscar(); return false;" class="btn btn-secondary btn-sm btn-block" title="buscar"><i class="fas fa-search"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<!-- <div class="table-responsive"> -->
						<table id="tblcitas" class="table table-sm table-condensed table-hover">
							<thead>
								<tr class="text-center">
									<th collabel="1">Hora</th>
									<th collabel="3">Paciente</th>
									<th collabel="1">Telefono</th>
									<th collabel="1">Expediente</th>
									<th collabel="1">Estado</th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<tr></tr>
							</tbody>
						</table>							
					<!-- </div> -->
				</div>
			</div>
		</div>
	</div>
		<!-- modal crear cita -->
	<div class="modal fade" id="agregarEvento" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<form role="form" action="{{route('nuevo_grabar_agenda')}}" method="post">
	      				@CSRF
	      				<div class="card card-navy">
	      					<div class="card-header">
	      						<div class="row">
	      							<div class="col-md-8">
										<p>Nueva Cita</p>
	      							</div>
	      							<div class="col-md-4" style="text-align: right;">
	      								<button type="submit" id="btnAgregarEvento" class="btn btn-success btn-sm" title="Grabar"><i class="fas fa-save"></i></button>
	      								<!--<button type="button" class="btn btn-sm btn-danger fa fa-sign-out" title="Salir" data-dismiss="modal" aria-label="Close
	      								"><i class="fas fa-sign-out-alt"></i>
	      								</button>-->
	      								<a href="#" class="btn btn-sm btn-danger" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
	      							</div>
	      						</div>
	      					</div>
	      					<div class="card-body">
	      						<div class="row">
			      					<div class="input-group mb-1 input-group-sm col-md-6 offset-md-3">
			                            <div class="input-group-prepend">
			                                <label class="input-group-text">Fecha</label>
			                            </div>
			                            <input type="date" class="form-control" id="fecha_cita" name="fecha_cita" required value="{{ $today }}">
			                        </div>
			      				</div>	
			      				<div class="row">
			      					<div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
			                            <div class="input-group-prepend">
			                                <label class="input-group-text">Hora Inicio</label>
			                            </div>
			                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required value="{{ old('hora_inicio') }}">
			                        </div>
			                        <div class="input-group mb-1 input-group-sm col-md-5">
			                            <div class="input-group-prepend">
			                                <label class="input-group-text">Hora Fin</label>
			                            </div>
			                            <input type="time" class="form-control" id="hora_final" name="hora_final" required value="{{ old('hora_final') }}">
			                        </div>
			      				</div>
			      				<!-- paciente -->
			      				<div class="row">
				                    <div class="col-md-10 offset-md-1 mb-1 input-group input-group-sm">
			                            <div class="input-group-prepend">
			                                <label class="input-group-text" for="paciente_id">Paciente</label>
			                            </div>
			                            <select class="custom-select custom-select-sm select2 select2bs4" id="paciente_id" name="paciente_id" onchange="actualiza_nombre_completo();">
			                                <option value="" selected="selected">Seleccionar.....</option>
			                                @foreach($pacientes as $p)
			                                    <option value="{{ $p->id }}"> {{ $p->nombre_completo }}</option>
			                                @endforeach
			                            </select>
				                    </div>
			                    </div>
			                    <!-- /paciente -->
	      						<div class="row">
			      					<div class="col-md-10 offset-md-1 input-group mb-1 input-group-sm">
			                            <div class="input-group-prepend">
			                                <label class="input-group-text">Nombre</label>
			                            </div>
			                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="{{ old('nombre_completo') }}" required>
			                        </div>
			      				</div>
			      				<div class="row">
			      					<div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
			                            <div class="input-group-prepend">
			                                <label class="input-group-text">Telefonos</label>
			                            </div>
			                            <input type="text" class="form-control" id="telefonos" name="telefonos" required value="{{ old('telefonos') }}">
			                        </div>
			      				</div>
			      				<div class="row">
		                            <div class="mb-1 col-md-10 offset-md-1">
		                                <div class="input-group input-group-sm">
		                                    <div class="input-group-prepend">
		                                        <label class="input-group-text" for="medico_id">Médico</label>
		                                    </div>
		                                    <select class="custom-select custom-select-sm select2 select2bs4" id="medico_id" name="medico_id" required>
		                                        <option value="">Seleccionar...</option>
		                                        @foreach($medicos as $m)
										        	<option value="{{ $m->id }}" selected> {{ $m->nombre_completo}} </option>
										        @endforeach
		                                    </select>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="row">
		                            <div class="mb-1 col-md-10 offset-md-1">
		                                <div class="input-group input-group-sm">
		                                    <div class="input-group-prepend">
		                                        <label class="input-group-text" for="hospital_id">Hospital</label>
		                                    </div>
		                                    <select class="custom-select custom-select-sm select2 select2bs4" id="hospital_id"  name="hospital_id" required>
		                                        <option value="">Seleccionar...</option>
		                                        @foreach($hospitales as $h)
										        	<option value="{{ $h->id }}" @if($h->principal_agenda == 'S') selected @endif> {{ $h->nombre}} </option>
										        @endforeach
		                                    </select>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="row text-center">
		                            <div class="form-group col-md-10 offset-md-1">
		                                <label for="antmedico_descripcion">Observaciones</label>
		                                <textarea class="form-control form-control-sm" id="observaciones" name="observaciones" rows="3"></textarea>
		                            </div>
		                        </div>
	      					</div>
	      				</div>
	      			</form>
				</div>
			</div>
		</div>
	</div>
	<!-- /modal crear cita -->

@endsection
@section('js')
	<script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
	@if(Session::has('success'))
		<script>
			swal("Trabajo Finalizado", "{!! Session::get('success') !!}", "success")
		</script>
	@endif
	<script type="text/javascript">
		$(function() {
	  		$('[data-toggle="tooltip"]').tooltip()
		});

		function actualiza_nombre_completo(){
			var paciente = document.getElementById('paciente_id');
			var paciente_id = paciente.options[paciente.selectedIndex].value;
            var paciente_nombre = paciente.options[paciente.selectedIndex].text;
            if (paciente_id == '') {
            	document.getElementById('nombre_completo').value = '';
            }else{
            	document.getElementById('nombre_completo').value = paciente_nombre;
            }
		}

		$(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
            $('.select2bs4').select2({ theme: 'bootstrap4' })
        });

        $(document).ready(function(){
	  		$('[data-toggle="tooltip"]').tooltip();   
		});

		var linea = {}
		window.onload = function() {
			var local_db = [];
			localStorage.clear(local_db);
			localStorage.local_db = JSON.stringify(local_db);
			buscar();
		}

		$('#agregarEvento').on('shown.bs.modal', function() {
		  $('#hora_inicio').focus();
		});

		$('[data-toggle="tooltip"]').tooltip();

		function confirma_salida($id){
            swal({
                title: 'Confirmación',
                text: 'Seguro de Salir ?',
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
                        //return window.location.href = "{{route('nueva_agenda')}}";
                        $('#agregarEvento').modal('hide');
                        swal.close();
                    }
                }
            );
            /*alertify.confirm('<i class="fas fa-sign-out-alt"></i> Salir', '<h4>Esta seguro de salir ? </h4>', function(){ 
            $('#agregarEvento').modal('hide');
                }
                , function(){ alertify.error('Se deja sin efecto')}
            );*/
        }

		function buscar()
		{
			var fecha = $('#fecha').val();
			var medico = $('#f_medico_id').val();
			var estado = $('#estado').val();

			$.ajax({
				url: "{{ route('trae_citas') }}",
		        type: "POST",
		        dataType: 'json',
		        data: {"_token": "{{ csrf_token() }}",fecha : fecha, medico_id : medico, estado : estado},
		        success: function(response){
		        	var info = response;
		        	if(localStorage.local_db){
		                window.localStorage.clear(local_db);
		            }
		        	if (info.length > 0) {
		        		for (var i = 0; i < info.length; i++) {
		        			var linea = {
				                id              : info[i]['id'],
				                fecha_inicio    : info[i]['fecha_inicio'],
				                estado          : info[i]['estado'],
				                observaciones   : info[i]['observaciones'],
				                paciente_id     : info[i]['paciente_id'],
				                nombre_completo : info[i]['nombre_completo'],
				                telefonos       : info[i]['telefonos'],
				                expediente_no   : info[i]['expediente_no']
				            }

				            //console.log(linea)

				            if(!localStorage.local_db){
				                localStorage.local_db = JSON.stringify([linea]);
				            }
				            else{
				                var local_db = JSON.parse(localStorage.local_db);
				                local_db.push(linea);
				                localStorage.local_db = JSON.stringify(local_db);
				            }
		        		}
		        	}
		        	actualizarTabla();
		        },
		        error: function(error){
		            console.log(error);
		        }
			});
			//return window.location.href = "{{route('inicio')}}/agenda/nueva_agenda/"+medico+"/"+estado+"/"+fecha;
		}

		function zeroPad(num, numZeros) { 
			var n = Math.abs(num); 
			var zeros = Math.max(0, numZeros - Math.floor(n).toString().length ); 
			var zeroString = Math.pow(10,zeros).toString().substr(1); 
			if( num < 0 ) { zeroString = '-' + zeroString; } return zeroString+n; 
	}

		//===================================================================
        // actualizar tabla
        //===================================================================
        function actualizarTabla(){
            if(localStorage.local_db){
                var local_db = JSON.parse(localStorage.local_db);
                local_db.push(linea);
                localStorage.local_db = JSON.stringify(local_db);
            }
            var html = '';

            if(localStorage.local_db){
            
	            for(var i = 0; i < local_db.length-1; i++){
	                var fecha = new Date(local_db[i]['fecha_inicio']);
	                var hora = zeroPad(fecha.getHours(),2)+':'+zeroPad(fecha.getMinutes(),2);
	                switch(local_db[i]['estado']){
	                	case 'C':
	                		html += '<tr class="text-center" style="background-color: #EF9A9A;">';
	                		break;
	                	case 'R':
	                		html += '<tr class="text-center" style="background-color: #CCFF99;">';
	                		break;
	                	default:
	                		html += '<tr class="text-center">';
	                		break;

	                }
	                html += '<td collabel="1" style="background-color: #E8F5E9;">'
	                html += hora
	                html += '</td>'
	                // data-toggle="tooltip" data-placement="top" title="'+local_db[i]['observaciones']+'"
	                html += '<td collabel="3">'
	                //html += '<a href data-toggle="tooltip" data-placement="right" title="Tooltip on right">Tooltip</a>';
	                if (local_db[i]['paciente_id'] != null) {
	                	html += '<a href="'+asset+'admisiones/nueva_admision/'+local_db[i]['paciente_id']+'/A" data-toggle="tooltip" data-placement="top" title="'+local_db[i]['observaciones']+'" class="red-tooltip" >'+local_db[i]['nombre_completo']+'</a>'
	                }else {
	                	html += '<p class="red-tooltip" data-toggle="tooltip" data-placement="top" title="'+local_db[i]['observaciones']+'">'+local_db[i]['nombre_completo']+'</p>';
	                }
	                html += '</td>'
	                html += '<td>'
	                html += local_db[i]['telefonos']
	                html += '</td>'
	                html += '<td>'
	                if (local_db[i]['expediente_no'] != null) {
	                	html += local_db[i]['expediente_no']
	                }
	                html += '</td>'
	                html += '<td>'
	                if (local_db[i]['estado'] == 'A') {
	                	html += 'Activa'
	                }
	                if (local_db[i]['estado'] == 'R') {
	                	html += 'Realizada'
	                }
	                if (local_db[i]['estado'] == 'C') {
	                	html += 'Cancelada'
	                }
	                html += '</td>'
	                html += '<td>'
					html += '<a href="'+asset+'agenda/edicion/'+local_db[i]['id']+'" class="btn btn-sm btn-warning" title="Editar Cita"><i class="fas fa-edit"></i></a>'
					html += '</td>'
	                html += '</tr>'
	            }
        	}

            $("#tblcitas tbody tr").remove();
            $('#tblcitas tbody').append(html);
        }

		function sumarDias(fecha, dias){
	  		fecha.setDate(fecha.getDate() + dias);
		  	return fecha;
		}

		function restar_fecha(){
			var fecha = document.getElementById('fecha').value;
			fecha = moment(fecha).subtract(1, 'd');
			document.getElementById('fecha').value = moment(fecha).format("YYYY-MM-DD");
			buscar();
		}

		function sumar_fecha(){
			var fecha = document.getElementById('fecha').value;
			fecha = moment(fecha).add(1, 'd');
			document.getElementById('fecha').value = moment(fecha).format("YYYY-MM-DD");
			buscar();
		}
	</script>
@endsection
