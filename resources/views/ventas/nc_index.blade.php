@extends('admin.layout')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@endsection
@section('titulo')
	Documentos
@endsection
@section('subtitulo')
	Notas de Crédito
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
    <div class="card card-navy">
        <div class="card-header">
            <div class="row">
                <div class="col-md-1 offset-md-11" style="text-align: right;">
                    <a href="{{ route('nueva_nc') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-circle"></i></a>
                </div>
            </div>
        </div>
        <form class="form-horizontal">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-12">
                        <div class="table-responsive">
                        <table id="tblDocumentos" class="table table-sm table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>Tipo</th>
                                    <th>Fecha</th>
                                    <th>Documento</th>
                                    <th>Docto Afecto</th>
                                    <th>N.I.T.</th>
                                    <th>Nombre</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listado as $l)
                                    <tr>
                                        <td>{{ $l->tipo_descripcion }}</td>
                                        <td>{{ \Carbon\Carbon::parse($l->fecha_emision)->format('d/m/Y') }}</td>
                                        <td>{{ $l->serie }}-{{ $l->correlativo }}</td>
                                        <td>{{ $l->serie_afecta }}-{{ $l->correlativo_afecto }}</td>
                                        <td>{{ $l->nit }}</td>
                                        <td>{{ $l->nombre }}</td>
                                        <td>{{ $l->precio_neto }}</td>
                                        <td>{{ $l->estado_descripcion}} </td>
                                        <td><a href="{{ route('editar_nc', [$l->id,0]) }}" class="btn btn-sm btn-warning" title="Editar Documento"><i class="fas fa-edit"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
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
    <script>
      $(function () {
        $('#tblDocumentos').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": true,
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