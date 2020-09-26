@extends('admin.layout')

@section('titulo')
	Correlativos
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
	<form role="form" method="POST" action="{{ route('actualizar_correlativo_1', $correlativo->id) }}">
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
			            	<select id ="tipo_id" name="tipo_id" class="form-control" value="{{ $correlativo->tipo}}">
			              		<option value="">Seleccionar .....</option>
			              		<option value="A" @if($correlativo->tipo == 'A') then selected @endif)>Admision</option>
			              		<option value="P" @if($correlativo->tipo == 'P') then selected @endif>Paciente</option>
			            	</select>
						</div>
						<div class="form-group col-md-2">
			      			<label for="correlativo">Correlativo</label>
			  				<input type="text" class="form-control" id="correlativo" name="correlativo" placeholder="0" value="{{ $correlativo->correlativo }}" style="text-align: right;" required>
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
    }
</script>
@endsection