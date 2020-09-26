@extends('admin.layout')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endsection
@section('titulo')
	Banco
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
	<form role="form" method="POST" action="{{route('actualizar_banco', $pBanco->id)}}">
		@csrf
		<div class="card card-navy">
			<div class="card-header">
				<div class="row">
					<div class="col-md-1 offset-md-11" style="text-align: right;">
						<button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
						<!--<a href="{{route('bancos')}}" class="btn btn-sm btn-danger" title="Regresar a lista de Bancos"><i class="fas fa-sign-out-alt"></i></a>	-->
						<a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
					</div>
				</div>
			</div>
			<form class="form-horizontal">
				<div class="card-body">
					<div class="row">
						<div class="input-group mb-5 col-md-5 offset-md-1">
					  		<div class="input-group-prepend">
						    	<span class="input-group-text">Nombre</span>
						  	</div>
						  	<input type="text" class="form-control" placeholder="nombre aseguradora" aria-label="Username" aria-describedby="basic-addon1" id="nombre" name="nombre" autofocus required value="{{ $pBanco->nombre }}">
						</div>

						<div class="form-group form-control-sm clearfix">
                            <label for="tipo_referencia01">Referencia</label>
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="tipo_referencia01" name="tipo_referencia" value="B" @if($pBanco->tipo_referencia == 'B') checked @endif>
                                <label for="tipo_referencia01">Banco</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="tipo_referencia02" name="tipo_referencia" value="T" @if($pBanco->tipo_referencia == 'T') checked @endif>
                                <label for="tipo_referencia02">Casa Emisora</label>
                            </div>
                        </div>
			    	</div>
					<div class="form-group offset-md-1">
			            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
			              	<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A" @if($pBanco->estado == 'A') checked @endif>
			          		<label class="custom-control-label" for="estado">Activar</label>
			        	</div>
			      	</div>
				</div>
			</form>
		</div>
	</form>
@endsection
@section('js')
    <script src="{{ asset('assets/adminlte/dist/js/demo.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <script type="text/javascript">
    	let ruta = '';
		window.onload = function() {
	      ruta = document.referrer;
	    };

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
                        window.location.href = "{{ route('bancos') }}";
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
    </script>
@endsection