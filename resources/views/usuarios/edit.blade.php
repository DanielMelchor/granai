@extends('admin.layout')
@section('css')
	<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('titulo')
	Usuarios
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

	<!-- form start -->
	<form role="form" method="POST" action="{{route('actualizar_usuario', $usuario->id)}}">
		@csrf
		<div class="card card-navy">
			<div class="card-header">
				<div class="col-md-2 offset-md-10" style="text-align: right;">
					<button type="submit" class="btn btn-sm btn-secondary btn-success" title="Grabar"><i class="fas fa-save"></i></button>
					<a href="{{route('usuarios')}}" class="btn btn-sm btn-danger" title="Regresar a lista de Usuarios"><i class="fas fa-sign-out-alt"></i></a>	
				</div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="input-group mb-3 col-md-5 offset-md-1">
				  		<div class="input-group-prepend">
					    	<span class="input-group-text">Usuario</span>
					  	</div>
					  	<input type="text" class="form-control" placeholder="username" aria-label="username" aria-describedby="basic-addon1" placeholder="usuario" id="username" name="username" autofocus required value="{{ $usuario->username }}">
					</div>

					<div class="input-group mb-3 col-md-5">
				  		<div class="input-group-prepend">
					    	<span class="input-group-text">Nombre Colaborador</span>
					  	</div>
					  	<input type="text" class="form-control" placeholder="Nombre de Colaborador" aria-label="name" aria-describedby="basic-addon1" placeholder="nombre Colaborador" id="name" name="name" required value="{{ $usuario->name }}">
					</div>
            	</div>
            	<div class="row">
            		<div class="input-group mb-3 col-md-5 offset-md-1">
				  		<div class="input-group-prepend">
					    	<span class="input-group-text" for="empresa_id">Empresa</span>
					  	</div>
					  	<select class="custom-select" id="empresa_id" name="empresa_id" onchange="trae_cajas(); return false;">
					    	<option selected>Seleccionar...</option>
					    	@foreach($empresas as $e)
					    		<option value="{{ $e->id }}" @if($e->id == $usuario->empresa_id) selected @endif>{{ $e->nombre_comercial }}</option>
					    	@endforeach
					  	</select>
					</div>
					<div class="input-group mb-3 col-md-5">
				  		<div class="input-group-prepend">
					    	<span class="input-group-text" for="caja_id">Caja</span>
					  	</div>
					  	<select class="custom-select" id="caja_id" name="caja_id">
					    	<option selected>Seleccionar...</option>
					    	@foreach($cajas as $c)
					    		<option value="{{ $c->id }}" @if($c->id == $usuario->caja_id) selected @endif>{{ $c->nombre_maquina }}</option>
					    	@endforeach
					  	</select>
					</div>
            	</div>
            	<div class="row">
            		<div class="form-group col-md-5 offset-md-1">
	                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                      		<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A" @if($usuario->estado == 'A') checked @endif>
	                      	<label class="custom-control-label" for="estado">Activar</label>
	                    </div>
                  	</div>
            	</div>
            </div>
	    </div>
	</form>
@endsection
@section('js')
	<script src="{{ asset('assets/adminlte/dist/js/demo.js')}}"></script>
	<script type="text/javascript">
		function trae_cajas(){
			var empresa_id = document.getElementById('empresa_id').value;
			var html = '';
			$.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('cajas_por_empresa')}}",
                method: "POST",
                data: { empresa_id  : empresa_id},
                success: function(response){
                    //console.log(response);
                    var html = '<option value="" >Seleccionar...</option>';
                    $.each(response, function(r, response){
                    	//console.log(response.id+' '+response.nombre_maquina);
                    	html += '<option value="'+response.id+'" >'+response.nombre_maquina+'</option>';
                    });
                    $('#caja_id').html(html);
                },
                error: function(error){
                    console.log(error);
                }
            });
		}
	</script>
@endsection