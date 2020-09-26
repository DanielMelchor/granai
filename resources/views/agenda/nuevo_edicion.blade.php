@extends('admin.layout')
@section('css')
    <!-- Select2 -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endsection

@section('titulo')
    Agenda
@endsection
@section('subtitulo')
    Edición
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
    <form role="form" method="POST" action="{{ route('actualizar_nueva_agenda', $cita->id)}}">
        @CSRF
        <div class="card card-navy">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4 offset-md-8" style="text-align: right;">
                        @if( $cita->estado == 'A')
                            <button type="submit" class="btn btn-sm btn-success" title="Grabar cambios"><i class="fas fa-save"></i></button>
                            @if($admision_id == 0)
                                <a href="#" class="btn btn-sm btn-primary" onclick="admision(); return false;" title="Crear Admisión"><i class="fas fa-plus-circle"></i></a>
                            @endif
                            </button>
                            <a href="#" onclick="mensaje('cancelar');" class="btn btn-sm btn-outline-danger" title="Marcar cita como cancelada"><i class="fas fa-ban"></i></a>
                            <a href="#" onclick="mensaje('realizar');" class="btn btn-sm btn-outline-success" title="Marcar cita como realizada"><i class="fas fa-check-circle"></i></a>
                        @endif
                        <!--<a href="{{ route('nueva_agenda',[$cita->medico_id, 'T', $fecha]) }}" class="btn btn-sm btn-danger" title="Regresar a agenda"><i class="fas fa-sign-out-alt"></i></a> -->
                        <a href="#" class="btn btn-sm btn-danger" title="Regresar a agenda" onclick="confirma_salida({{ $cita->id }}); return false;"><i class="fas fa-sign-out-alt"></i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" id="cita_id" name="cita_id" value="{{ $cita->id }}">
                <div class="row">
                    <div class="input-group mb-1 input-group-sm col-md-3 offset-md-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Fecha</label>
                        </div>
                        <input type="date" class="form-control" id="fecha_cita" name="fecha_cita" required value="{{ $fecha }}" @if($cita->estado != 'A' || $admision_id != 0) disabled @endif>
                    </div>
                    <div class="input-group mb-1 input-group-sm col-md-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Hora Inicio</label>
                        </div>
                        <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required value="{{ $hora_inicio }}" @if($cita->estado != 'A' || $admision_id != 0) disabled @endif>
                    </div>
                    <div class="input-group mb-1 input-group-sm col-md-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Hora Fin</label>
                        </div>
                        <input type="time" class="form-control" id="hora_final" name="hora_final" required value="{{ $hora_final }}" @if($cita->estado != 'A' || $admision_id != 0) disabled @endif>
                    </div>
                </div>
                <div class="row">
                    <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Nombre</label>
                        </div>
                        <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required value="{{ $cita->nombre_completo }}" @if($cita->estado != 'A' || $admision_id != 0) disabled @endif>
                    </div>
                    <div class="mb-1 col-md-5">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="paciente_id">Paciente</label>
                            </div>
                            <select class="form-control input-group input-group-sm select2 select2bs4" id="paciente_id"  name="paciente_id" @if($cita->estado != 'A' || $admision_id != 0) disabled @endif>
                                <option value="">Seleccionar...</option>
                                @foreach($pacientes as $p)
                                    <option value="{{ $p->id }}" @if($p->id == $cita->paciente_id) then selected @endif> {{ $p->nombre_completo}} </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <a href="{{ route('crear_paciente',['A', $cita->id])}}" class="btn btn-sm btn-primary" title="Crear Paciente"><i class="fas fa-plus-circle"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Telefonos</label>
                        </div>
                        <input type="text" class="form-control" id="telefonos" name="telefonos" required value="{{ $cita->telefonos }}" @if($cita->estado != 'A' || $admision_id != 0) disabled @endif>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1 col-md-5 offset-md-1">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="hospital_id">Lugar</label>
                            </div>
                            <select class="custom-select-sm form-control select2 select2bs4" id="hospital_id"  name="hospital_id" @if($cita->estado != 'A' || $admision_id != 0) disabled @endif>
                                <option value="">Seleccionar...</option>
                                @foreach($hospitales as $h)
                                    <option value="{{ $h->id }}" @if($h->id == $cita->hospital_id) then selected @endif> {{ $h->nombre }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-1 col-md-5">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="medico_id">Medico</label>
                            </div>
                            <select class="custom-select-sm form-control select2 select2bs4" id="medico_id"  name="medico_id" @if($cita->estado != 'A' || $admision_id != 0) disabled @endif>
                                <option value="">Seleccionar...</option>
                                @foreach($medicos as $m)
                                    <option value="{{ $m->id }}" @if($m->id == $cita->medico_id) then selected @endif> {{ $m->nombre_completo }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="form-group col-md-10 offset-md-1">
                        <label for="antmedico_descripcion">Observaciones</label>
                        <textarea class="form-control form-control-sm" id="observaciones" name="observaciones" rows="3" maxlength="190" @if($cita->estado != 'A' || $admision_id != 0) disabled @endif>{{ $cita->observaciones }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Modal -->
    <div class="modal fade" id="admisionModal" role="dialog" aria-labelledby="admisionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <!--<form class="form-horizontal" id="crea_admision" name="crea_admision" action="#"> -->
                <div class="modal-content">
                    <div class="card card-navy">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6>Crear Admisión</h6>
                                </div>
                                <div class="col-md-4" style="text-align: right;">
                                    <button type="button" class="btn btn-sm btn-success" title="Grabar" onclick="grabar_admision(); return false;"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" title="Salir"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
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

                            <!-- admision terceros -->
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Admisión Terceros</span>
                                    </div>
                                    <input type="text" class="form-control" id="admision_tercero" name="admision_tercero">
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
                                            <option value="">Seleccionar.....</option>
                                            @foreach($aseguradoras as $aseguradora)
                                                    <option value="{{ $aseguradora->id }}"> {{ $aseguradora->nombre}} </option>
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
                                    <input type="text" class="form-control" id="poliza_no" name="poliza_no">
                                </div>
                            </div>
                            <!-- /poliza -->
                            <div class="row">
                                <!-- deducible -->
                                <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Deducible</span>
                                    </div>
                                    <input type="number" class="form-control" id="deducible" name="deducible" placeholder="0.00" style="text-align: right;">
                                </div>
                                <!-- /deducible -->
                                <!-- co pago -->
                                <div class="input-group mb-1 input-group-sm col-md-5">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Co pago</span>
                                    </div>
                                    <input type="number" class="form-control" id="copago" name="copago" placeholder="0.00" style="text-align: right;">
                                </div>
                                <!-- /co pago -->
                            </div>
                        </div>
                    </div>
                </div>
            <!-- </form> -->
        </div>
    </div>
    <!-- modal mensajes -->
    <div class="modal fade" id="mensajeModal" tabindex="-1" role="dialog" aria-labelledby="mensajeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h3 id="texto_mensaje" class="modal_texto text-center" style="color: green;"></h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /modal mensajes -->
@endsection
@section('js')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @if(Session::has('success'))
        <script>
            swal("Trabajo Finalizado", "{!! Session::get('success') !!}", "success")
        </script>
    @endif
    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        });

        function admision(){
            var paciente_id      = document.getElementById('paciente_id').value;
            if (paciente_id == '') {
                swal({
                    title: 'Error !!!',
                    text: 'Para realizar la admisión debe asignar una ficha de pacienta a la cita',
                    type: 'error',
                });
            }else{
                $('#admisionModal').modal('show');
            }
        }

        function grabar_admision(){
            var cita_id = document.getElementById('cita_id').value;
            var tipo_admision = $('input[name="tipo_admision"]:checked').val();
            var admision_tercero = document.getElementById('admision_tercero').value;
            var aseguradora_id   = document.getElementById('aseguradora_id').value;
            var poliza_no        = document.getElementById('poliza_no').value;
            var deducible        = document.getElementById('deducible').value;
            var copago           = document.getElementById('copago').value;
            var paciente_id      = document.getElementById('paciente_id').value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('crea_admision_x_cita') }}",
                method: "POST",
                data: {cita_id: cita_id,
                       tipo_admision: tipo_admision,
                       admision_tercero: admision_tercero,
                       aseguradora_id: aseguradora_id,
                       poliza_no: poliza_no,
                       deducible: deducible,
                       copago: copago
                      },
                success: function(response){
                    var info = JSON.stringify(response);
                    swal({
                        title: 'Trabajo Finalizado',
                        text: info,
                        type: 'success',
                        },
                        function(){
                            return window.location.href = "{{route('nueva_agenda')}}";
                        }
                    );
                    /*alertify.alert("<b>"+info+"</b>", function () {
                        location.reload();
                    }).setHeader('<em> Notificación </em> ');*/

                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function confirma_salida($id){
            swal({
                title: 'Confirmación',
                text: 'Seguro de Salir de cita ?',
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
                        return window.location.href = "{{route('nueva_agenda')}}";
                    }
                }
            );

            /*alertify.confirm('<i class="fas fa-sign-out-alt"></i> Salir', '<h4>Esta seguro de salir de Cita ? </h4>', function(){ 
            history.back();
                }
                , function(){ alertify.error('Se deja sin efecto')}
            );*/
        }

        function mensaje(proceso){
            var cita_id = document.getElementById('cita_id').value;
            if (proceso == 'cancelar') {
                swal({
                    title: 'Confirmación',
                    text: 'Seguro de Cancelar la Cita ?',
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
                            $.ajax({
                                url: "{{ route('cancelar_cita') }}",
                                type: "POST",
                                async: true,
                                data: {"_token": "{{ csrf_token() }}", cita_id: cita_id},
                                success: function(response){
                                    var info = response;
                                    swal({
                                        title: 'Trabajo Finalizado',
                                        text: 'Cita Cancelada con Exito !!!',
                                        type: 'success',
                                        },
                                        function(){
                                            location.reload();
                                        }
                                    );
                                    //alertify.success('Compra eliminada con exito');
                                },
                                error: function(error){
                                    console.log(error);
                                }
                            });
                                        } 
                        else { 
                            swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                            }
                    }
                );
                /*alertify.confirm('<i class="fas fa-ban"></i> Cancelar', '<h4>Esta seguro de cancelar Cita ? </h4>', function(e){ 
                    if(e){
                        $.ajax({
                            url: "{{ route('cancelar_cita') }}",
                            type: "POST",
                            async: true,
                            data: {"_token": "{{ csrf_token() }}", cita_id: cita_id},
                            success: function(response){
                                var info = response;
                                location.reload();
                                alertify.success('Cita cancelada con exito');
                            },
                            error: function(error){
                                console.log(error);
                            }
                        });
                    }
                }
                , function(){ alertify.error('Se deja sin efecto')});*/
            }else{
                swal({
                    title: 'Confirmación',
                    text: 'Seguro de Finalizar la Cita ?',
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
                            $.ajax({
                                url: "{{ route('realizar_cita') }}",
                                type: "POST",
                                async: true,
                                data: {"_token": "{{ csrf_token() }}", cita_id: cita_id},
                                success: function(response){
                                    var info = response;
                                    swal({
                                        title: 'Trabajo Finalizado',
                                        text: 'Cita Finalizada con Exito !!!',
                                        type: 'success',
                                        },
                                        function(){
                                            location.reload();
                                        }
                                    );
                                    //alertify.success('Compra eliminada con exito');
                                },
                                error: function(error){
                                    console.log(error);
                                }
                            });
                                        } 
                        else { 
                            swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                            }
                    }
                );
                /*alertify.confirm('<i class="fas fa-check-circle"></i> Finalizar', '<h4>Esta seguro de Finalizar Cita ? </h4>', function(e){ 
                    if(e){
                        $.ajax({
                            url: "{{ route('realizar_cita') }}",
                            type: "POST",
                            async: true,
                            data: {"_token": "{{ csrf_token() }}", cita_id: cita_id},
                            success: function(response){
                                var info = response;
                                location.reload();
                                alertify.success('Cita finalizada con exito');
                            },
                            error: function(error){
                                console.log(error);
                            }
                        });
                    }
                }
                , function(){ alertify.error('Se deja sin efecto')});*/
            }
        }
    </script>
@endsection