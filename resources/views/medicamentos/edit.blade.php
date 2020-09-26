@extends('admin.layout')

@section('titulo')
	Medicamento
@endsection
@section('subtitulo')
	Edición
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
	<form role="form" method="POST" action="{{route('actualizar_medicamento', $pMedicamento->id)}}">
		@csrf
		<div class="card card-navy">
			<div class="card-header">
				<div class="row">
					<div class="col-md-2 offset-md-10" style="text-align: right;">
						<button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
						<!--<a href="{{route('medicamentos')}}" class="btn btn-sm btn-danger" title="Regresar a lista de Dosis"><i class="fas fa-sign-out-alt"></i></a>-->
						<a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
					</div>
				</div>
			</div>
			<form class="form-horizontal">
				<div class="card-body">
					<input type="hidden" id="medicamento_id" name="medicamento_id" value="{{ $pMedicamento->id }}">
					<div class="row">
					    <div class="input-group mb-5 col-md-5 offset-md-1">
			                <div class="input-group-prepend">
			                    <span class="input-group-text">Nombre</span>
			                </div>
			                <input type="text" class="form-control" placeholder="nombre medicamento" aria-label="Username" aria-describedby="basic-addon1" id="nombre" name="nombre" autofocus required value="{{ $pMedicamento->nombre }}">
			            </div>
			            <div class="form-group offset-md-1">
			                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
			                    <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A" @if($pMedicamento->estado == 'A') then checked @endif>
			                    <label class="custom-control-label" for="estado">Activar</label>
			                </div>
			            </div>
			        </div>
			        <div class="row">
						<div class="col-md-1 offset-md-11">
							<a href="#" class="btn btn-primary btn-sm" title="Agregar Dosis" onclick="agregarDestino(); return false;">
								<i class="fas fa-plus-circle"></i>
							</a>
						</div>
					</div>
					<div class="row">
			    		<div class="col-md-10 offset-md-1">
			    			<div class="table-responsive">
			    				<table class="table table-striped table-hover text-center" id="tableDestinos">
			        				<thead>
			                            <tr>
			                                <th class="text-center">Dosis</th>
			                                <th class="text-center">Descripción</th>
			                                <th class="text-center">Estado</th>
			                                <th width="35px"></th>
			                            </tr>
			                        </thead>
			                        <tbody>
			                        </tbody>
			        			</table>
			    			</div>
			    		</div>
			    	</div>
				</div>
			</form>
		</div>
    </form>
@endsection
@section('js')
	<script type="text/javascript">
		var nDestino = 0;
		var nlinea = 0;
		var linea = {};
		var local_db = [];
		localStorage.clear(local_db);
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
                        window.location.href = "{{ route('medicamentos') }}";
                                    } 
                    else { 
                        swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                        }
                }
            );
        }

		window.addEventListener('load', function(){
			ruta = document.referrer;
			llenarTabla();
		});

		function llenarTabla(){
			var medicamento_id = document.getElementById("medicamento_id").value;

			$.ajax({
				url: "{{ route('trae_dosis_medicamento') }}",
		        type: "POST",
		        dataType: 'json',
		        data: {"_token": "{{ csrf_token() }}",medicamento_id : medicamento_id},
		        success: function(response){
		        	var info = response;
					info.sort();
					html = '';
					for(var i=0; i < info.length; i++){
						var linea = {
							id:                 info[i]['id'],
							dosis_id:           info[i]['dosis_id'],
							descripcion:        info[i]['descripcion'],
							descripcion_receta: info[i]['descripcion_receta'],
							estado:             info[i]['estado']
						}
						if(!localStorage.local_db){
							localStorage.local_db = JSON.stringify([linea]);
						}else{
							var local_db = JSON.parse(localStorage.local_db);
							local_db.push(linea);
							localStorage.local_db = JSON.stringify(local_db);
						}
						nlinea   += 1;
					}
					actualizarTabla();
		        },
		        error: function(error){
		            console.log(error);
		        }
			});
		}

		function actualizarTabla(){
			var get_localStorage = JSON.parse(localStorage.local_db);
			var html = '';
			for(var i = 0; i < get_localStorage.length; i++){
				html += '<tr>'
				html += '<td>'
				html += get_localStorage[i]['descripcion']
				html += '</td>'
				html += '<td>'
				html += '<input type="text" class="form-control" step="any" name="destinos['+nDestino+'][descripcion_receta]" value="'+get_localStorage[i]['descripcion_receta']+'" />';
				html += '</td>'
				html += '<td style="width:200px;">'
				html += '<select name="destinos['+nDestino+'][estado]"  class="form-control" data-required="true">';
        		html += '<option value="A" @if('+get_localStorage[i]["estado"]+' == "A") then selected @endif>Alta</option>';
        		html += '<option value="I" @if('+get_localStorage[i]["estado"]+' == "I") then selected @endif>Baja</option>';
            	html += '</select>';
				html += '</td>'
				html += '<td>'
				html += '<input type="hidden" class="form-control" step="any" name="destinos['+nDestino+'][dosis_id]" value="'+get_localStorage[i]['dosis_id']+'" />';
				html += '</td>'
				html += '</tr>'
				nDestino += 1;
			}
			$("#tableDestinos tbody tr").remove();
            $('#tableDestinos tbody').append(html);
		}
		
		function agregarDestino()
        {
            var html = '';
            html += '<tr>';
            html += '<td width="125px">';
            html += '<select name="destinos['+nDestino+'][dosis_id]" class="form-control" data-required="true">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($destinos as $destino)
            html += '<option value="{{$destino->id}}">{{$destino->descripcion}}</option>';
            @endforeach
            html += '</select>';
            html += '</td>';
            
            html += '<td width="250px">';
            html += '<input type="text" class="form-control" step="any" name="destinos['+nDestino+'][descripcion_receta]" />';
            html += '</td>';
            html += '<td width="50px" style="text-align: center;">';
            html += '<p> Alta </p>';
            html += '</td>';

            html += '<td width="35px"><a href="#" class="btn btn-danger eliminar"><i class="fas fa-trash-alt"></i></a></td>';
            
            html += '</tr>';
            $('#tableDestinos tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nDestino++;
        }
        function eliminar()
        {
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
        }

	</script>
@endsection