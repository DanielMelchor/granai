@extends('admin.layout')
@section('titulo')
	Usuario
@endsection
@section('subtitulo')
	Cambio de Contraseña
@endsection

@section('contenido')
	@if (session('success'))
	    <div class="col-sm-12">
	        <div class="alert alert-success alert-dismissible fade show" role="alert">
	            {{ session('success') }}
	            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	            	<span aria-hidden="true">&times;</span>
                </button>
	        </div>
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
    <form role="form" method="POST" action="{{route('actualizar_contrasena')}}">
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
					<div class="input-group mb-3 col-md-5 offset-md-4">
				  		<div class="input-group-prepend">
					    	<span class="input-group-text">Clave Actual</span>
					  	</div>
					  	<input type="password" class="form-control" placeholder="" aria-label="oldpass" aria-describedby="basic-addon1" placeholder="usuario" id="oldpass" name="oldpass" autofocus required>
					</div>
				</div>
				<div class="row">
					<div class="input-group mb-3 col-md-5 offset-md-4">
				  		<div class="input-group-prepend">
					    	<span class="input-group-text">Nueva Clave</span>
					  	</div>
					  	<input type="password" class="form-control" placeholder="" aria-label="nueva_clave" aria-describedby="basic-addon1" placeholder="usuario" id="nueva_clave" name="nueva_clave" autofocus required>
					</div>
				</div>
				<div class="row">
					<div class="input-group mb-3 col-md-5 offset-md-4">
				  		<div class="input-group-prepend">
					    	<span class="input-group-text">Confirmación</span>
					  	</div>
					  	<input type="password" class="form-control" placeholder="" aria-label="confirmacion" aria-describedby="basic-addon1" placeholder="" id="confirmacion" name="confirmacion" autofocus required>
					</div>
				</div>
			</div>
		</div>
    </form>
@endsection
@section('js')
@endsection
