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
					<h5>Admisiones Activas</h5>
				</div>
				<div class="col-md-2" style="text-align: right;">
					<button type="button" class="btn btn-sm btn-config" title="Parámetros" data-toggle="modal" data-target="#parametrosModal"><i class="fas fa-cog"></i></button>
					<a href="{{ route('rpt_admisiones_activas_pdf',[$fecha_inicial, $fecha_final, $tipo_admision]) }}" class="btn btn-sm btn-reporte" title="Impresión" target="_blank"><i class="fas fa-file-pdf"></i></a>
					<a href="{{ route('rpt_admisiones_activas_xls',[$fecha_inicial, $fecha_final, $tipo_admision]) }}" class="btn btn-sm btn-excel" title="Excel"><i class="fas fa-file-excel"></i></a>
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
							<th>Estado</th>
						</tr>
					</thead>
					<tbody>
						@foreach($admisiones as $a)
							<tr>
								<td>{{ $a->admision }}</td>
								<td>{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</td>
								<td>{{ $a->usuario_nombre }}</td>
								<td>{{ $a->tipo_admision }}</td>
								<td>{{ $a->expediente_no }}</td>
								<td>{{ $a->paciente_nombre }}</td>
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
	      						<div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
		                            <div class="input-group-prepend">
		                                <label class="input-group-text">Fecha Inicio</label>
		                            </div>
		                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $inicio }}">
		                        </div>
		      				</div>
		      				<div class="row">
	      						<div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
		                            <div class="input-group-prepend">
		                                <label class="input-group-text">Fecha Final</label>
		                            </div>
		                            <input type="date" class="form-control" id="fecha_final" name="fecha_final" value="{{ $hoy }}">
		                        </div>
		      				</div>
		      				<div class="row">
                                <div class="input-group input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="tipo_admision">Tipo Admisiones</label>
                                    </div>
                                    <select class="custom-select  custom-select-sm" id="tipo_admision"  name="tipo_admision">
                                        <option value="T" selected> Todas </option>
							        	<option value="C"> Consultas </option>
							        	<option value="H"> Hospitalizaciones </option>
							        	<option value="P"> Procedimientos </option>
                                    </select>
                                </div>
							</div>
		      			</div>
		      			<div class="card-footer">
		      				<div class="row">
		      					<div class="col-md-4 offset-md-8" style="text-align: right;">
		      						<a href="#" class="btn btn-sm btn-outline-secondary" title="filtrar" onclick="fn_buscar(); return false;"><i class="fas fa-search"></i></a>
		      						<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" title="Salir"><i class="fas fa-sign-out-alt"></i></button>
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

	function fn_buscar(){
    	var fecha_inicial = document.getElementById('fecha_inicio').value;
    	var fecha_final   = document.getElementById('fecha_final').value;
    	var tipo_admision = document.getElementById('tipo_admision').value;
    	alert(fecha_inicial);

    	if(fecha_inicial == '' || fecha_final == '') return false;
    	var url = "{{ route('rpt_admisiones_activas', ['fecha_inicial' => '2020-01-01', 'fecha_final' => '2020-01-02', 'tipo_admision' => 'T']) }}";
    	url = url.replace('2020-01-01', fecha_inicial);
    	url = url.replace('2020-01-02', fecha_final);
    	url = url.replace('T', tipo_admision);
    	location.href = url;
		/*window.location.href = "{{ route('rpt_admisiones_activas',["+fecha_inicial+","+fecha_final+", 'T']) }}";*/
    }
	</script>
@endsection