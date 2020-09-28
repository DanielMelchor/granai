@extends('admin.layout')
@section('css')
	<link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@endsection
@section('titulo')
	Bancos
@endsection
@section('subtitulo')
	Listado
@endsection

@section('contenido')
	
	<div class="card card-navy">
		<div class="card-header">
			<div class="row">
				<div class="col-md-1 offset-md-11" style="text-align: right;">
					<a href="{{ route('crear_banco')}}" class="btn btn-sm btn-primary" title="Crear Banco"><i class="fas fa-plus-circle"></i></a>
				</div>
			</div>
		</div>
		<form class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-10 offset-md-1">
						<div class="table-responsive">
							<table id="listado" class="table table-sm table-striped table-hover">
								<thead class="thead-primary">
									<tr>
										<th scope="col" class="text-center">Descripción</th>
										<th scope="col" class="text-center">Referencia</th>
										<th scope="col" class="text-center">Estado</th>
										<th>&nbsp</th>
									</tr>	
								</thead>
								<tbody>
									@foreach($pBancos as $pBanco)
										<tr class="text-center">
											<td>{{ $pBanco->nombre}}</td>
											@if($pBanco->tipo_referencia == 'B')
												<td>Banco</td>
											@else
												<td>Emisor Tarjeta</td>
											@endif
											@if($pBanco->estado == 'A')
												<td>Alta</td>
											@else
												<td>Baja</td>
											@endif
											<td><a href="{{route('editar_banco' , $pBanco->id)}}" class="btn btn-sm btn-warning" title="Editar Banco"><i class="fas fa-edit"></i></a></td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer">
			</div>
		</form>
	</div>
@endsection
@section('js')
	<script src="{{asset('assets/adminlte/plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
	@if(Session::has('success'))
        <script>
            swal("Trabajo Finalizado", "{!! Session::get('success') !!}", "success")
        </script>
    @endif
    @if(Session::has('error'))
        <script>
            swal("Error !!!", "{!! Session::get('error') !!}", "error")
        </script>
    @endif
    <script type="text/javascript">
    	$(function () {
	        $('#listado').DataTable({
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
        	})
	    });
    </script>
@endsection