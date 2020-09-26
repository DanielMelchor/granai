@extends('admin.layout')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('titulo')
	Productos
@endsection
@section('subtitulo')
	Agregar
@endsection

@section('contenido')
	<div class="row">
        <div class="col">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" arial-label="Close"><span aria-hidden="true">x</span>
	    			</button>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

	<form role="form" method="POST" action="{{route('grabar_producto')}}">
		@csrf
		<div class="card card-navy">
			<div class="card-header">
				<div class="row">
					<div class="col-md-2 offset-md-10" style="text-align: right;">
						<button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
						<!--<a href="{{route('productos')}}" class="btn btn-sm btn-danger" title="Regresar a lista de Productos"><i class="fas fa-sign-out-alt"></i></a>	-->
						<a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
					</div>
				</div>
			</div>
			<form class="form-horizontal">
				<div class="card-body">
					<div class="row">
						<div class="col-md-5 offset-md-1">
                            <div class="form-group form-control-sm clearfix">
                                <label for="producto">Tipo</label>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="producto" name="clasificacion_producto" value="PROD" checked onclick="fn_actualizar_tipo();">
                                    <label for="producto">Producto</label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="servicio" name="clasificacion_producto" value="SERV" onclick="fn_actualizar_tipo();">
                                    <label for="servicio">Servicio</label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="procedimiento" name="clasificacion_producto" value="PROC" onclick="fn_actualizar_tipo();">
                                    <label for="procedimiento">Procedimiento</label>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="row">
						<div id="grpsiglas" class="input-group mb-1 col-md-5 offset-md-1">
					  		<div class="input-group-prepend">
						    	<span class="input-group-text">Siglas</span>
						  	</div>
						  	<input type="text" class="form-control" placeholder="solo para procedimientos" aria-label="Username" aria-describedby="basic-addon1" id="siglas" name="siglas" value="{{ old('siglas')}}">
						</div>
					</div>
					<div class="row">
						<div id="grpdescripcion" class="input-group mb-1 col-md-10 offset-md-1">
					  		<div class="input-group-prepend">
						    	<span class="input-group-text">Descripción</span>
						  	</div>
						  	<input type="text" class="form-control" placeholder="Descripcion interna" aria-label="Username" aria-describedby="basic-addon1" id="descripcion" name="descripcion" required value="{{ old('descripcion') }}">
						</div>
					</div>
					<div class="row">
						<div id="grpdescripcionm" class="input-group mb-1 col-md-10 offset-md-1">
					  		<div class="input-group-prepend">
						    	<span class="input-group-text">Texto a mostrar</span>
						  	</div>
						  	<input type="text" class="form-control" placeholder="texto a mostrar en documentos del cliente" aria-label="Username" aria-describedby="basic-addon1" id="descripcion_a_mostrar" name="descripcion_a_mostrar" required value="{{ old('descripcion_a_mostrar') }}">
						</div>
					</div>
					<div class="row">
                        <div id="grpmedida" class="mb-1 col-md-5 offset-md-1">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" for="medida_id">Unidad de médida</span>
                                </div>
                                <select class="custom-select custom-select-sm select2 select2bs4" id="medida_id"  name="medida_id">
                                    <option value="">Seleccionar...</option>
                                    @foreach($pUnidades as $U)
                                        <option value="{{ $U->id}}">{{ $U->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
					<div class="row">
						<div class="form-group offset-md-1">
				            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
				              	<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
				          		<label class="custom-control-label" for="estado">Activar</label>
				        	</div>
				      	</div>
					</div>
				</div>
			</form>
		</div>
	</form>
@endsection
@section('js')
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
    <script type="text/javascript">
    	var tipo = 'PROD';

    	//========================================================================
        // inicializar librerias
        //========================================================================
        $(function () {
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        });

    	$(function() {
		    $('input:radio[name="clasificacion_producto"]').change(function(){
		    	tipo = $(this).val();
		    });
		});

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
	                    window.location.href = "{{ route('productos') }}";
	                } 
	            }
	        );
	    }

		window.addEventListener('load', function(){
			ruta = document.referrer;
			$("#grpsiglas").hide();
			$("#grpmedida").show();
			document.getElementById('siglas').required = false;
			document.getElementById('medida_id').required = true;
		});
		
		function fn_actualizar_tipo(){
			if (document.getElementById('producto').checked == true) {
				tipo = 'PROD';
				document.getElementById('siglas').required = false;
				document.getElementById('medida_id').required = true;
				$("#grpsiglas").hide();
				$("#grpmedida").show();
			}
			if (document.getElementById('servicio').checked == true) {
				tipo = 'SERV';
				document.getElementById('siglas').required = false;
				document.getElementById('medida_id').required = false;
				$("#grpsiglas").hide();
				$("#grpmedida").hide();
			}
			if (document.getElementById('procedimiento').checked == true) {
				tipo = 'PROC';
				document.getElementById('siglas').required = true;
				document.getElementById('medida_id').required = false;
				$("#grpmedida").hide();
				$("#grpsiglas").show();
			}
		}
    </script>
@endsection