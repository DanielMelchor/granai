@extends('admin.layout')
@section('title')
	Listado de Permisos
@endsection
@section('content')
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
	<div class="content-fluid">
		<div class="row">
			<div class="col-md-2 offset-md-9" style="text-align: right;">
				<button type="button" class="btn btn-success btn-md far fa-plus-square" data-toggle="modal" data-target="#permisoModal" title="Crear nuevo permiso"> Nuevo</button>
			</div>
		</div>
		<div class="row text-center">
			<div class="col-md-5 offset-md-4">
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>Permiso</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@foreach($permisos as $p)
						<tr>
							<td>{{ $p->name }}</td>
							<td><a href="{{ route('editar_permiso', $p->id) }}" class="fa fa-edit" title="Editar Permiso"> Editar</a></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="permisoModal" tabindex="-1" role="dialog" aria-labelledby="permisoModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-dialog-centered" role="document">
	    	<div class="modal-content">
	      		<form role="form" method="POST" action="{{ route('grabar_permiso') }}">
	      			@csrf
	      			{{ csrf_field() }}
		      		<div class="modal-body">
		        		<div class="card card-secondary">
		        			<div class="card-header text-center">
		        				<h3>Nuevo Permiso</h3>
		        			</div>
		        			<div class="card-body">
		        				<br>
		        				<div class="row text-center">
		        					<div class="col-md-10 offset-md-1">
		        						<label for="name">Nombre</label>
		        					</div>
		        				</div>
		        				<div class="row text-center">
				        			<div class="input-group col-md-10 offset-md-1">
				        				<input type="text" class="form-control" placeholder="nombre de permiso" aria-label="name" aria-describedby="addon-wrapping" id="name" name="name">
				        			</div>
				        		</div>	
		        			</div>
		        			<div class="row">
		        				<div class="col-md-5 offset-md-7" style="text-align: right;">
	        						<button type="submit" class="btn btn-success btn-md fa fa-save" title="Grabar"> Grabar</button>
	        						<button type="button" class="btn btn-danger btn-md fa fa-save" title="Regresar a lista de familias" data-dismiss="modal"> Salir</button>
		        				</div>
		        			</div>
		        		</div>
		      		</div>
	      		</form>
	    	</div>
	  	</div>
	</div>
@endsection
@section('js')
	<script type="text/javascript">
		$('#permisoModal').on('shown.bs.modal', function() {
		  $('#name').focus();
		});
	</script>
@endsection