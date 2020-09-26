@extends('admin.layout')

@section('titulo')
	Motivos Anulación de Documentos
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
	<form role="form" method="POST" action="{{route('actualizar_motivorechazo', $pMotivoRechazo->id)}}">
		@csrf
		<div class="card card-navy">
			<div class="card-header">
				<div class="row">
					<div class="col-md-2 offset-md-10" style="text-align: right;">
						<button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
						<!--<a href="{{route('motivoRechazos')}}" class="btn btn-sm btn-danger" title="Regresar a lista Motivos de Rechazo"><i class="fas fa-sign-out-alt"></i></a>-->
						<a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
					</div>
				</div>
			</div>
			<form class="form-horizontal">
				<div class="card-body">
					<div class="row">
						<div class="input-group mb-5 col-md-5 offset-md-1">
					  		<div class="input-group-prepend">
						    	<span class="input-group-text">Descripcion</span>
						  	</div>
						  	<input type="text" class="form-control" placeholder="nombre aseguradora" aria-label="Username" aria-describedby="basic-addon1" id="descripcion" name="descripcion" autofocus required value="{{ $pMotivoRechazo->descripcion }}">
						</div>
						<div class="form-group offset-md-1">
				            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
				              	<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A" @if($pMotivoRechazo->estado == 'A') then checked @endif>
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
                    window.location.href = "{{ route('motivoRechazos') }}";
                } 
            }
        );
    }
</script>
@endsection