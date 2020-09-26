@extends('admin.layout')

@section('titulo')
	Correlativos
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
	<form role="form" method="POST" action="{{ route('grabar_correlativo_1') }}">
		@csrf
		<div class="card card-navy">
			<div class="card-header">
				<div class="row">
					<div class="col-md-2 offset-md-10" style="text-align: right;">
						<button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
						<!--<a href="{{ route('correlativos')}}" class="btn btn-sm btn-danger" title="regresar a lista de correlativos"><i class="fas fa-sign-out-alt"></i></a>-->
						<a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
					</div>
				</div>
			</div>
			<form class="form-horizontal">
				<div class="card-body">
					<div class="row text-center">
						<div class="form-group col-md-2 offset-md-1">
			        		<label for="tipo_id">Tipo</label>
			            	<select id ="tipo_id" name="tipo_id" class="form-control select2" autofocus required>
			              		<option value="">Seleccionar .....</option>
			              		<option value="A">Admision</option>
			              		<option value="P">Paciente</option>
			            	</select>
						</div>
						<div class="form-group col-md-2">
			      			<label for="correlativo">Correlativo</label>
			  				<input type="text" class="form-control" id="correlativo" name="correlativo" placeholder="0" value="{{ old('correlativo')}}" style="text-align: right;" required>
						</div>
					</div>
				</div>
			</form>
		</div>
	</form>
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
                    window.location.href = "{{ route('correlativos') }}";
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