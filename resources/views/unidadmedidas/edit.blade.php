@extends('admin.layout')

@section('titulo')
	Unidades de Médida
@endsection
@section('subtitulo')
	Agregar
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
	<form role="form" method="POST" action="{{route('actualizar_unidadmedida', $pUnidadmedida->id)}}">
		@csrf
		<div class="card card-navy">
			<div class="card-header">
				<div class="row">
					<div class="col-md-2 offset-md-10" style="text-align: right;">
						<button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
						<!--<a href="{{route('unidadmedidas')}}" class="btn btn-sm btn-danger" title="Regresar a lista Unidades de medida"><i class="fas fa-sign-out-alt"></i></a>-->
						<a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
					</div>
				</div>
			</div>
			<form class="form-horizontal">
				<div class="card-body">
					<div class="row text-center">
					    <div class="input-group mb-5 col-md-5 offset-md-1">
					  		<div class="input-group-prepend">
						    	<span class="input-group-text">Descripción</span>
						  	</div>
						  	<input type="text" class="form-control" placeholder="Observaciones" id="descripcion" name="descripcion" autofocus required value="{{ $pUnidadmedida->descripcion }}">
						</div>
						<div class="input-group mb-5 col-md-3">
					  		<div class="input-group-prepend">
						    	<span class="input-group-text">Siglas</span>
						  	</div>
						  	<input type="text" class="form-control" placeholder="Siglas" id="siglas" name="siglas" required value="{{ $pUnidadmedida->siglas }}">
						</div>
			    	</div>
			    	<div class="row">
						<div class="form-group offset-md-1">
				            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
				              	<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A" @if($pUnidadmedida->estado == 'A') checked then @endif>
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
                    window.location.href = "{{ route('unidadmedidas') }}";
                } 
            }
        );
    }
</script>
@endsection