@extends('admin.layout')
@section('css')
	<link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@endsection
@section('titulo')
	Antiguedad de Saldos
@endsection
@section('subtitulo')
	Reporte
@endsection
@section('contenido')
	<div class="card card-navy">
		<div class="card-header text-center">
			<div class="row">
				<div class="col-md-9 offset-md-1">
					<h5>Antiguedad de Saldos</h5>
				</div>
				<div class="col-md-2" style="text-align: right;">
					<!--<button type="button" class="btn btn-md btn-outline-primary" title="Parámetros" data-toggle="modal" data-target="#parametrosModal"><i class="fas fa-cog"></i></button> -->
					<a href="{{ route('rpt_antiguedad_saldos_pdf') }}" class="btn btn-sm btn-reporte" title="Impresión" target="_blank"><i class="fas fa-file-pdf"></i></a>
					<a href="{{ route('rpt_antiguedad_saldos_xls') }}" class="btn btn-sm btn-excel" title="Excel"><i class="fas fa-file-excel"></i></a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tblSaldos" class="table table-sm table-hover text-center">
					<thead>
						<tr>
							<th># Admisión</th><th>Cliente</th><th>Documento</th>
							<th>Fecha</th><th>Total</th><th>Saldo</th><th>30 Dias</th><th>60 Dias</th><th>90 Dias</th><th>120 Dias</th><th>Mas de 120 Dias</th>
						</tr>
					</thead>
					<tbody>
						@foreach($facturas as $f)
							<tr>
								<td>{{ $f->admision }}</td>
								<td>{{ $f->cliente_nombre }}</td>
								<td>{{ $f->serie }}-{{ $f->correlativo }}</td>
								<td>{{ \Carbon\Carbon::parse($f->fecha_emision)->format('d/m/Y') }}</td>
								<td>{{ $f->total_documento }}</td>
								<td>{{ $f->saldo }}</td>
								<td>{{ $f->dias_30 }}</td>
								<td>{{ $f->dias_mayor_30 }}</td>
								<td>{{ $f->dias_mayor_60 }}</td>
								<td>{{ $f->dias_mayor_90 }}</td>
								<td>{{ $f->dias_mayor_120 }}</td>
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
			                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
			                        </div>
		      					</div>
		      				</div>
		      				<div class="row">
		      					<div class="col-md-12">
		      						<div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
			                            <div class="input-group-prepend">
			                                <span class="input-group-text">Fecha Final</span>
			                            </div>
			                            <input type="date" class="form-control" id="fecha_final" name="fecha_final">
			                        </div>
		      					</div>
		      				</div>
		      			</div>
		      			<div class="card-footer">
		      				<div class="row">
		      					<div class="col-md-4 offset-md-8" style="text-align: right;">
		      						<a href="#" class="btn btn-outline-secondary" title="filtrar" onclick="fn_buscar(); return false;"><i class="fas fa-search"></i></a>
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
	    $('#tblSaldos').DataTable({
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
    	if(fecha_inicial == '' || fecha_final == '') return false;
		return window.location.href = "{{route('inicio')}}/reportes/admision_consultas/"+fecha_inicial+"/"+fecha_final;
    }
	</script>
@endsection