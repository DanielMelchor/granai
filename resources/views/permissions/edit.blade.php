@extends('admin.layout')
@section('title')
	Edición de Permiso
@endsection
@section('content')
	<div class="content-fluid">
		<div class="row">
			<div class="col-md-10 offset-md-1">
				<form role="form" method="POST" action="{{ route('actualizar_permiso', $permiso->id) }}">
					@csrf
					{{ csrf_field() }}
					<div class="card card-secondary">
						<div class="card-header text-center">
	        				<h3>Edición de Permiso</h3>
	        			</div>
					</div>
					<div class="card-body">
        				<div class="row text-center">
		        			<div class="col-md-10 offset-md-1">
		        				<label for="name">Nombre</label>
		        			</div>
		        		</div>
        				<div class="row text-center">
		        			<div class="input-group col-md-10 offset-md-1">
		        				<input type="text" class="form-control" placeholder="nombre de permiso" aria-label="Username" aria-describedby="addon-wrapping" id="name" name="name" value="{{ $permiso->name }}" autofocus required>
		        			</div>
		        		</div>
        			</div>
        			<div class="card-footer">
        				<div class="row">
        					<div class="col-md-4 offset-md-8" style="text-align: right;">
        						<button type="submit" class="btn btn-success btn-md fa fa-save" title="Guardar"> Grabar</button>
        						<a href="{{ route('permisos') }}" class="btn btn-danger btn-md fa fa-save" title="Rregresar a lista de familias"> Salir</a>
        					</div>
        				</div>
        			</div>
				</form>
			</div>
		</div>
	</div>
@endsection