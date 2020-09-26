@extends('admin.layout')
@section('css')
	<link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@endsection
@section('titulo')
	Admisiones
@endsection
@section('subtitulo')
	Reporte
@endsection
@section('contenido')
	<div class="card card-navy">
		<div class="card-header text-center">
			<div class="row">
				<div class="col-md-9 offset-md-1">
					<h5>Admisiones</h5>
				</div>
				<div class="col-md-2" style="text-align: right;">
					<button type="button" class="btn btn-md btn-outline-primary" title="Parámetros" data-toggle="modal" data-target="#parametrosModal"><i class="fas fa-cog"></i></button>
					<a href="#" class="btn btn-md btn-outline-danger" title="Impresión"><i class="fas fa-file-pdf"></i></a>
					<a href="#" class="btn btn-md btn-outline-success" title="Excel"><i class="fas fa-file-excel"></i></a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tbladmisiones" class="table table-sm table-hover text-center">
					<thead>
						<tr>
							<th># Admisión</th><th>Fecha</th><th>Creada Por</th>
							<th>Tipo</th><th>Expediente</th><th>Paciente</th>
							<th>Medico</th><th>Hospital</th><th>Fecha Ingreso</th>
							<th>Fecha Egreso</th><th>Estado</th>
						</tr>
					</thead>
					<tbody>
						@foreach($admisiones as $a)
							<tr>
								<td>{{ $a->admision }}</td>
								<td>{{ \Carbon\Carbon::parse($a->created_at)->format('d/m/Y') }}</td>
								<td>{{ $a->usuario_nombre }}</td>
								<td>{{ $a->tipo_admision }}</td>
								<td>{{ $a->expediente_no }}</td>
								<td>{{ $a->paciente_nombre }}</td>
								<td>{{ $a->medico_nombre }}</td>
								<td>{{ $a->hospital_nombre }}</td>
								<td>{{ \Carbon\Carbon::parse($a->fecha_inicio)->format('d/m/Y') }}</td>
								<td>{{ \Carbon\Carbon::parse($a->fecha_fin)->format('d/m/Y') }}</td>
								<td>{{ $a->estado_descripcion }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="parametrosModal" tabindex="-1" role="dialog" aria-labelledby="parametrosModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
  			<div class="modal-content">
		      	<div class="modal-body">
		      		<div class="card card-navy">
		      			<div class="card-header text-center">
		      				<h5 class="modal-title" id="parametrosModalLabel">Parámetros</h5>
		      			</div>
		      			<div class="card-body">
		      				<div class="row">
		      					<div class="col-md-12">
		      						<div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
			                            <div class="input-group-prepend">
			                                <span class="input-group-text">Fecha Inicio</span>
			                            </div>
			                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $hoy }}">
			                        </div>
		      					</div>
		      				</div>
		      				<div class="row">
		      					<div class="col-md-12">
		      						<div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
			                            <div class="input-group-prepend">
			                                <span class="input-group-text">Fecha Final</span>
			                            </div>
			                            <input type="date" class="form-control" id="fecha_final" name="fecha_final" value="{{ $hoy }}">
			                        </div>
		      					</div>
		      				</div>
		      			</div>
		      			<div class="card-footer">
		      				<div class="row">
		      					<div class="col-md-4 offset-md-8" style="text-align: right;">
		      						<a href="#" class="btn btn-outline-secondary" title="filtrar"><i class="fas fa-search"></i></a>
		      						<a href="#" class="btn btn-outline-secondary" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
		      					</div>
		      				</div>
		      			</div>
		      		</div>
		      	</div>
  			</div>
  		</div>
  	</div>
@endsection
@section('js')
	<!-- DataTables -->
	<script src="{{asset('assets/adminlte/plugins/datatables/jquery.dataTables.js')}}"></script>
	<script src="{{asset('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
	<script>
	  $(function () {
	    $('#tbladmisiones').DataTable({
	      "paging": true,
	      "lengthChange": false,
	      "searching": true,
	      "ordering": true,
	      "info": true,
	      "autoWidth": false,
	      language: {
		        "sProcessing":     "Procesando...",
            	"sLengthMenu":     "Mostrar _MENU_ registros",
            	"sZeroRecords":    "No se encontraron resultados",
            	"sEmptyTable":     "Ningún dato disponible en esta tabla =(",
            	"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            	"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            	"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            	"sInfoPostFix":    "",
            	"sSearch":         "Buscar:",
            	"sUrl":            "",
            	"sInfoThousands":  ",",
            	"sLoadingRecords": "Cargando...",
            	"oPaginate": {
                				"sFirst":    "Primero",
                				"sLast":     "Último",
                				"sNext":     "Siguiente",
                				"sPrevious": "Anterior"
        					}
		    },
		    dom: 'Bfrtip'
	    });
	  });
	</script>
@endsection