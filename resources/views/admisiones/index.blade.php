@extends('admin.layout')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endsection
@section('titulo')
	Admisiones
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
            <div class="row">
                <div class="col-md-1 offset-md-11" style="text-align: right;">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#nuevaAdmisionModal" id="btnNueva" name="btnNueva" title="Crear Admision"><i class="fas fa-plus-circle"></i>
                    </button>
                </div>
            </div>
        </div>
        <form class="form-horizontal">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <table id="admisiones" class="table table-sm table-striped table-hover text-center" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>No. Admision</th>
                                    <th>Fecha</th>
                                    <th>Expediente</th>
                                    <th>Paciente</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pAdmisiones as $A)
                                    <tr>
                                        <td>{{ $A->admision }}</td>
                                        <td>{{ \Carbon\Carbon::parse($A->fecha)->format('d/m/Y') }}</td>
                                        <td>{{ $A->expediente_no }}</td>
                                        <td>{{ $A->nombre_completo }}</td>
                                        <td>0</td>
                                        <td>
                                            @if($A->estado == 'P')
                                                <p>Proceso</p>
                                            @endif
                                            @if($A->estado == 'C')
                                                <p>Cerrada</p>
                                            @endif
                                        </td>
                                        <td><a href="{{ route('editar_admision', $A->id) }}" class="btn btn-sm btn-warning" title="Editar Admision"><i class="fas fa-edit"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Modal nueva admision-->
    <div class="modal fade" id="nuevaAdmisionModal" role="dialog" aria-labelledby="nuevaAdmisionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form" method="POST" action="{{route('grabar_admision')}}">
                    @csrf
                    <div class="card-navy">
                        <div class="card-header text-center">
                            <div class="row">
                                <div class="col-md-8 offset-md-1 text-center">
                                    Nueva Admisión
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                                    <!--<button type="button" data-dismiss="modal" class="btn btn-sm btn-danger" title="Regresar a lista de admisiones"><i class="fas fa-sign-out-alt"></i></button>-->
                                    <button type="button" class="btn btn-sm btn-danger" title="Salir" onclick="cerrar_modal('agregar'); return false;"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- tipo de admision -->
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="form-group form-control-sm clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="consulta" name="tipo_admision" value="C" checked>
                                            <label for="consulta">Consulta</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="procedimiento" name="tipo_admision" value="P">
                                            <label for="procedimiento">Procedimiento</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="hospitalizacion" name="tipo_admision" value="H">
                                            <label for="hospitalizacion">Hospitalización</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /tipo de admision -->
                            <!-- fecha admision -->
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Fecha Admisión</span>
                                    </div>
                                    <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" required value="{{ $hoy }}">
                                </div>
                            </div>
                            <!-- /fecha admision -->
                            <!-- paciente -->
                            <div class="row">
                                <div class="mb-1 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" for="paciente_id">Paciente</span>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="paciente_id" name="paciente_id" required>
                                            <option value="">Seleccionar.....</option>
                                            @foreach($pPacientes as $p)
                                                <option value="{{ $p->id }}"> {{ $p->nombre_completo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /paciente -->
                            <!-- medico -->
                            <div class="row">
                                <div class="mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" for="medico_id">Médico</span>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="medico_id" name="medico_id" required>
                                            <option value="" selected="selected">Seleccionar.....</option>
                                            @foreach($pMedicos as $pMedico)
                                                <option value="{{ $pMedico->id }}" @if($pMedico->principal == 'S') selected @endif> {{ $pMedico->nombre_completo}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /medico -->
                            <!-- hospital -->
                            <div class="row">
                                <div class="mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" for="hospital_id">Hospital</span>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="hospital_id" name="hospital_id" required>
                                            <option value="" selected="selected">Seleccionar.....</option>
                                            @foreach($pHospitales as $pHospital)
                                                <option value="{{ $pHospital->id }}" @if($pHospital->principal_agenda == 'S') selected @endif > {{ $pHospital->nombre}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /hospital -->
                            <!-- admision terceros -->
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Admisión Terceros</span>
                                    </div>
                                    <input type="text" class="form-control" id="admision_tercero" name="admision_tercero" value="{{ old('admision_tercero')}}">
                                </div>
                            </div>
                            <!-- /admision terceros -->
                            <!-- aseguradora -->
                            <div class="row">
                                <div class="mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" for="aseguradora_id">Aseguradora</span>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="aseguradora_id" name="aseguradora_id">
                                            <option value="" selected="selected">Seleccionar.....</option>
                                            @foreach($pAseguradoras as $pAseguradora)
                                                    <option value="{{ $pAseguradora->id }}"> {{ $pAseguradora->nombre}} </option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /aseguradora -->
                            <!-- poliza -->
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Póliza No.</span>
                                    </div>
                                    <input type="text" class="form-control" id="poliza_no" name="poliza_no" value="{{ old('poliza_no')}}">
                                </div>
                            </div>
                            <!-- /poliza -->
                            <div class="row">
                                <!-- deducible -->
                                <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Deducible</span>
                                    </div>
                                    <input type="number" class="form-control" id="deducible" name="deducible" value="{{ old('deducible')}}" placeholder="0.00" style="text-align: right;">
                                </div>
                                <!-- /deducible -->
                                <!-- co pago -->
                                <div class="input-group mb-1 input-group-sm col-md-5">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Co pago</span>
                                    </div>
                                    <input type="number" class="form-control" id="copago" name="copago" value="{{ old('copago')}}" placeholder="0.00" style="text-align: right;">
                                </div>
                                <!-- /co pago -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Modal nueva admision-->
@endsection
@section('js')
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <script src="{{asset('assets/adminlte/plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}">
    </script>
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
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
        $(function(){
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
        });

      $(function () {
        $('#admisiones').DataTable({
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

        function cerrar_modal(proceso){
            swal({
                title: 'Confirmación',
                text: 'Seguro de Salir, si ha realizado cambios estos no seran guardados  ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'btn-success',
                cancelButtonClass: 'btn-danger',
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                closeOnConfirm: false,
                allowEscapeKey: true
                },
                function(isConfirm) {
                    if (isConfirm) { 
                        //window.location.href = "{{ route('pacientes') }}";
                        if (proceso == 'agregar') {
                            $('#nuevaAdmisionModal').modal('hide');   
                        }
                        swal.close();
                    }
                }
            );
        }
    </script>
@endsection