@extends('admin.layout')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@endsection
@section('titulo')
	Pacientes
@endsection
@section('subtitulo')
	Listado
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
            <div class="col-md-1 offset-md-11" style="text-align: right;">
                <a href="{{ route('crear_paciente', ['P', '0'])}}" class="btn btn-sm btn-primary" title="Crear Paciente"><i class="fas fa-plus-circle"></i></a>
            </div>
        </div>
        <form class="form-horizontal">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-10 offset-md-1">
                        <div class="table-responsive">
                            <table id="pacientes" class="table table-sm table-striped table-hover" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Expediente</th>
                                        <th>Nombre</th>
                                        <th>Fecha Nacimiento</th>
                                        <th>Telefono</th>
                                        <th>Celular</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pPacientes as $p)
                                        <tr>
                                            <td>{{ $p->expediente_no }}</td>
                                            <td>{{ $p->nombre_completo }}</td>
                                            <td>{{ \Carbon\Carbon::parse($p->fecha_nacimiento)->format('d/m/Y') }}</td>
                                            <td>{{ $p->telefonos }}</td>
                                            <td>{{ $p->celular }}</td>
                                            <td>
                                                <a href="{{ route('editar_paciente', $p->id) }}" class="btn btn-sm btn-warning" title="Editar"><i class="fa fa-edit"></i></a>
                                            </td>
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
        $('#pacientes').DataTable({
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