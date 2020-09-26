@extends('admin.layout')

@section('titulo')
	Aseguradoras
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
	<form role="form" method="POST" action="{{route('grabar_aseguradora')}}">
		@csrf
		<div class="card card-navy">
			<div class="card-header">
				<div class="col-md-2 offset-md-10" style="text-align: right;">
					<button type="submit" class="btn btn-sm btn-secondary btn-success" title="Grabar"><i class="fas fa-save"></i></button>
					<!--<a href="{{route('aseguradoras')}}" class="btn btn-sm btn-danger" title="Regresar a lista de Aseguradoras"><i class="fas fa-sign-out-alt"></i></a>	-->
					<a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
				</div>
			</div>
			<form class="form-horizontal">
				<div class="card-body">
					<ul class="nav nav-tabs ml-auto p-2">
					  <li class="nav-item">
					    <a class="nav-link active" href="#generales" data-toggle="tab">Generales</a>
					  </li>
					  <li class="nav-item">
					    <a class="nav-link" href="#facturacion" data-toggle="tab">Facturación</a>
					  </li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="generales">
							<br>
							<div class="row">
								<div class="input-group mb-5 col-md-5 offset-md-1">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">Nombre</span>
								  	</div>
								  	<input type="text" class="form-control" placeholder="nombre aseguradora" aria-label="Username" aria-describedby="basic-addon1" id="nombre" name="nombre" autofocus required value="{{ old('nombre')}}">
								</div>

								<div class="input-group mb-5 col-md-5">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">Dirección</span>
								  	</div>
								  	<input type="text" class="form-control" placeholder="direccion" aria-label="Username" aria-describedby="basic-addon1" id="direccion" name="direccion" value="{{ old('direccion')}}">
								</div>
			            	</div>
			            	<div class="row">
								<div class="input-group mb-5 col-md-5 offset-md-1">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">Teléfonos</span>
								  	</div>
								  	<input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="telefonos" name="telefonos" value="{{ old('telefonos')}}">
								</div>

								<div class="input-group mb-5 col-md-5">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">Contacto</span>
								  	</div>
								  	<input type="text" class="form-control" placeholder="nombre contacto" aria-label="Username" aria-describedby="basic-addon1" id="contacto" name="contacto" value="{{ old('contacto')}}">
								</div>
			            	</div>
			            	<div class="row">
								<div class="input-group mb-5 col-md-3 offset-md-1">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">Co pago</span>
								  	</div>
								  	<input type="number" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="copago" name="copago" placeholder="0.00" value="{{ old('copago')}}" style="text-align: right;">
								</div>
								<div class="input-group mb-5 col-md-3">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">Coaseguro</span>
								  	</div>
								  	<input type="number" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="coaseguro" name="coaseguro" placeholder="0.00" value="{{ old('coaseguro')}}" style="text-align: right;">
								</div>
								<div class="form-group offset-md-1">
				                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
				                      	<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
			                      		<label class="custom-control-label" for="estado">Activar</label>
			                    	</div>
			                  	</div>
			            	</div>
						</div>
						<div class="tab-pane" id="facturacion">
							<br>
							<div class="row">
								<div class="input-group mb-5 col-md-3 offset-md-1">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">N.I.T.</span>
								  	</div>
								  	<input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="facturacion_nit" name="facturacion_nit" value="{{ old('facturacion_nit')}}">
								</div>

								<div class="input-group mb-5 col-md-7">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">Nombre</span>
								  	</div>
								  	<input type="text" class="form-control" placeholder="nombre para Factura" aria-label="Username" aria-describedby="basic-addon1" id="facturacion_nombre" name="facturacion_nombre" value="{{ old('facturacion_nombre')}}">
								</div>
							</div>
							<div class="row">
								<div class="input-group mb-5 col-md-10 offset-md-1">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">Dirección</span>
								  	</div>
								  	<input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="facturacion_direccion" name="facturacion_direccion" value="{{ old('facturacion_direccion')}}">
								</div>					
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
                    window.location.href = "{{ route('aseguradoras') }}";
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