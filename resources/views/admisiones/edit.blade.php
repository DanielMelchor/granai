@extends('admin.layout')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/alertifyjs/css/alertify.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/alertifyjs/css/themes/semantic.rtl.min.css') }}">
@endsection
@section('titulo')
    Admisiones
@endsection
@section('subtitulo')
    Edición
@endsection

@section('contenido')
    <div class="row">
        <div class="col">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" arial-label="Close"><span aria-hidden="true">x</span>
                    </button>
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
                <div class="col-md-9">
                    <h5>Admisión # {{ $pAdmision->admision }}</h5>
                </div>
                <div class="col-md-3" style="text-align: right;">
                    @if($pAdmision->estado == 'P')
                    <a href="#" class="btn btn-sm btn-success" title="Grabar" onclick="actualizar_admision(); return false;"><i class="fas fa-save"></i></a>
                    @endif
                    <a href="{{ route('rpt_cargos_pdf', $pAdmision->id) }}" class="btn btn-sm btn-reporte" title="Detalle de Cargos" target="_blank"><i class="fas fa-file-pdf"></i></a>
                    <a href="#" class="btn btn-sm btn-reporte" title="Estado de Cuenta"><i class="fas fa-file-pdf"></i></a>
                    @if($pAdmision->estado == 'P')
                        <a href="#" onclick="mensaje(); return false;" class="btn btn-sm btn-outline-danger" title="Cerrar Admision"><i class="fas fa-lock"></i></a>
                    @endif
                    @if($pAdmision->estado == 'C')
                        <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#reapertura" title="Apertura de Admision"><i class="fas fa-unlock-alt"></i>
                        </button>
                    @endif
                    <!--<a href="{{route('admisiones')}}" class="btn btn-sm btn-danger" title="Regresar a lista de Admisiones"><i class="fas fa-sign-out-alt"></i></a>-->
                    <a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Admisiones" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <input type="hidden" id="admision_id" name="admision_id" value="{{ $pAdmision->id }}">
                <input type="hidden" id="admision_estado" name="admision_estado" value="{{ $pAdmision->estado }}">
                <div class="row">
                    <div class="col-md-5 offset-md-1">
                        <div class="form-group form-control-sm clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="consulta" name="tipo_admision" value="C" @if($pAdmision->tipo_admision == 'C') then checked @endif>
                                <label for="consulta">Consulta</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="procedimiento" name="tipo_admision" value="P" @if($pAdmision->tipo_admision == 'P') then checked @endif>
                                <label for="procedimiento">Procedimiento</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="hospitalizacion" name="tipo_admision" value="H" @if($pAdmision->tipo_admision == 'H') then checked @endif>
                                <label for="hospitalizacion">Hospitalización</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        @if($pAdmision->estado == 'P')
                            <button class="btn btn-sm btn-block btn-success text-center">Proceso</button>
                        @endif
                        @if($pAdmision->estado == 'C')
                            <button class="btn btn-sm btn-block btn-danger text-center">Cerrada</button>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Fecha Admisión</span>
                        </div>
                        <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" required value="{{ $pAdmision->fecha }}">
                    </div>
                </div>
                <div class="row">
                    <!-- paciente -->
                    <div class="col-md-5 offset-md-1 mb-1">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" for="paciente_id">Paciente</span>
                            </div>
                            <select class="custom-select custom-select-sm select2 select2bs4" id="paciente_id" name="paciente_id" required @if($pAdmision->estado == 'C') then disabled @endif>
                                <option value="" selected="selected">Seleccionar.....</option>
                                @foreach($pPacientes as $p)
                                    <option value="{{ $p->id }}" @if($pAdmision->paciente_id == $p->id) then selected @endif> {{ $p->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- /paciente -->
                    <!-- medico -->
                    <div class="mb-1 col-md-5">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" for="medico_id">Médico</span>
                            </div>
                            <select class="custom-select custom-select-sm select2 select2bs4" id="medico_id" name="medico_id" required @if($pAdmision->estado == 'C') then disabled @endif>
                                <option value="" selected="selected">Seleccionar.....</option>
                                @foreach($pMedicos as $pMedico)
                                    <option value="{{ $pMedico->id }}" @if($pAdmision->medico_id == $pMedico->id) then selected @endif> {{ $pMedico->nombre_completo}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- /medico -->
                </div>
                <div class="row">
                    <!-- hospital -->
                    <div class="mb-1 input-group-sm col-md-5 offset-md-1">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" for="hospital_id">Hospital</span>
                            </div>
                            <select class="custom-select custom-select-sm select2 select2bs4" id="hospital_id" name="hospital_id" required @if($pAdmision->estado == 'C') then disabled @endif>
                                <option value="" selected="selected">Seleccionar.....</option>
                                @foreach($pHospitales as $pHospital)
                                    <option value="{{ $pHospital->id }}" @if($pAdmision->hospital_id == $pHospital->id) then selected @endif> {{ $pHospital->nombre}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- /hospital -->
                    <!-- admision terceros -->
                    <div class="input-group mb-1 input-group-sm col-md-5">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Admisión Terceros</span>
                        </div>
                        <input type="text" class="form-control" id="admision_tercero" name="admision_tercero" value="{{ $pAdmision->admision_tercero }}" @if($pAdmision->estado == 'C') then disabled @endif>
                    </div>
                    <!-- /admision terceros -->
                </div>
                <div class="row">
                    <!-- aseguradora -->
                    <div class="mb-1 input-group-sm col-md-5 offset-md-1">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" for="aseguradora_id">Aseguradora</span>
                            </div>
                            <select class="custom-select custom-select-sm select2 select2bs4" id="aseguradora_id" name="aseguradora_id" @if($pAdmision->estado == 'C') then disabled @endif>
                                <option value="" selected="selected">Seleccionar.....</option>
                                @foreach($pAseguradoras as $pAseguradora)
                                        <option value="{{ $pAseguradora->id }}"> {{ $pAseguradora->nombre}} </option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- /aseguradora -->
                    <!-- poliza -->
                    <div class="input-group mb-1 input-group-sm col-md-5">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Póliza No.</span>
                        </div>
                        <input type="text" class="form-control" id="poliza_no" name="poliza_no" value="{{ $pAdmision->poliza_no }}" @if($pAdmision->estado == 'C') then disabled @endif>
                    </div>
                    <!-- /poliza -->
                </div>
                <div class="row">
                    <!-- deducible -->
                    <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Deducible</span>
                        </div>
                        <input type="number" class="form-control" id="deducible" name="deducible" value="{{ $pAdmision->deducible }}" placeholder="0.00" style="text-align: right;" @if($pAdmision->estado == 'C') then disabled @endif>
                    </div>
                    <!-- /deducible -->
                    <!-- co pago -->
                    <div class="input-group mb-1 input-group-sm col-md-5">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Co pago</span>
                        </div>
                        <input type="number" class="form-control" id="copago" name="copago" value="{{ $pAdmision->copago }}" placeholder="0.00" style="text-align: right;" @if($pAdmision->estado == 'C') then disabled @endif>
                    </div>
                    <!-- /co pago -->
                </div>
                <hr>
                <div class="card card-info">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h6>Detalle</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs ml-auto p-2">
                            <li class="nav-item">
                                <a class="nav-link active" href="#cargos" data-toggle="tab">Cargos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#facturacion" data-toggle="tab">Facturación</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#bitacora" data-toggle="tab">Bitacora</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="cargos">
                                @if( $pAdmision->estado == 'P')
                                <br>
                                <div class="row">
                                    <div class="col-md-10 offset-md-1" style="text-align: right;">
                                        <a href="#" class="btn btn-sm btn-primary" onclick="agregar_modal(); return false;">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                                @endif
                                <br>
                                <div class="row">
                                    <div class="col-md-10 offset-md-1">
                                        <div class="table-responsive">
                                            <table id="tblCargos" class="table table-sm table-hover text-center">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 50%;">Producto</th>
                                                        <th style="width: 10%;">Cantidad</th>
                                                        <th style="width: 10%;">Prc. Unitario</th>
                                                        <th style="width: 10%;">Prc. Total</th>
                                                        <th style="width: 10%;">Cliente</th>
                                                        <th style="width: 10%;">Aseguradora</th>
                                                        <th style="width: 10%;">&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="facturacion">
                                <br>
                                @if( $pAdmision->estado == 'P')
                                <div class="row">
                                        <div class="col-md-10 offset-md-1" style="text-align: right;">
                                            <a href="{{ route('nueva_factura', [$pAdmision->id, $pAdmision->paciente_id]) }}" class="btn btn-sm btn-primary" title="Facturar"><i class="fas fa-plus"></i></a>
                                        </div>
                                        <br>
                                </div>
                                @endif
                                <br>
                                <div class="row">
                                    <div class="col-md-10 offset-md-1">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped table-hover text-center">
                                                <thead>
                                                    <tr>
                                                        <th>Tipo</th>
                                                        <th>Documento</th>
                                                        <th>Fecha</th>
                                                        <th>Nombre</th>
                                                        <th>Total</th>
                                                        <th>Estado</th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($facturas as $f)
                                                    <tr>
                                                        <td>{{ $f->tipodocumento_descripcion }}</td>
                                                        <td>{{ $f->serie}} - {{ $f->correlativo }}</td>
                                                        <td>{{ $f->fecha_emision }}</td>
                                                        <td>{{ $f->factura_nombre }}</td>
                                                        <td>{{ $f->total }}</td>
                                                        <td>{{ $f->estado_descripcion }}</td>
                                                        <td><a href="{{ route('editar_factura', [$f->factura_id, $pAdmision->id]) }}" class="btn btn-sm btn-warning" title="Editar Documento"><i class="fas fa-edit"></i></a></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="bitacora">
                                <br>
                                <div class="row">
                                    <div class="col-md-10 offset-md-1">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped table-hover text-center">
                                                <thead>
                                                    <tr>
                                                        <th>Fecha</th>
                                                        <th>Usuario</th>
                                                        <th>Descripcion</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($pBitacora as $B)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($B->created_at)->format('d/m/Y h:i') }}</td>
                                                        <td>{{ $B->name }}</td>
                                                        <td>{{ $B->observaciones }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            {!! $pBitacora->render() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- re apertura -->
    <div class="modal fade" id="reapertura" tabindex="-1" role="dialog" aria-labelledby="reaperturaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form" method="POST" action="{{route('reapertura_admision', $pAdmision->id)}}">
                    @csrf
                    <div class="card-navy">
                        <div class="card-header text-center">
                            <div class="row">
                                <div class="col-md-8 offset-md-1 text-center">
                                    Apertura de Admisión
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                                    <!--<button type="button" data-dismiss="modal" class="btn btn-sm btn-danger" title="Regresar a lista de admisiones"><i class="fas fa-sign-out-alt"></i></button>-->
                                    <button type="button" class="btn btn-sm btn-danger" title="Salir" onclick="cerrar_modal('reapertura'); return false;"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" for="observacion_id">Clasificación</span>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="observacion_id" name="observacion_id" required>
                                            <option value="" selected="selected">Seleccionar.....</option>
                                            @foreach($pObservaciones as $O)
                                                    <option value="{{ $O->id }}"> {{ $O->descripcion}} </option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-10 offset-md-1">
                                    <label for="observaciones">Observaciones</label>
                                    <textarea class="form-control form-control-sm" id="observaciones" name="observaciones" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /re apertura-->
    <!-- add cargo -->
    <div class="modal fade" id="addCargo" role="dialog" aria-labelledby="addCargoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="addCargoForm" name="cargos" action="#">
                    <div class="card-navy">
                        <div class="card-header text-center">
                            <div class="row">
                                <div class="col-md-8 offset-md-1">
                                    Nuevo Cargo
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button class="btn btn-sm btn-success" title="Guardar"><i class="fas fa-save"></i></button>&nbsp;
                                    <!--<a href="#" class="btn btn-sm btn-success" title="Grabar" onclick="grabar_local(); return false;"><i class="fas fa-save"></i></a>-->
                                    <!--<button type="button" data-dismiss="modal" class="btn btn-sm btn-danger" title="Regresar a detalle de admision"><i class="fas fa-sign-out-alt"></i></button>-->
                                    <button type="button" class="btn btn-sm btn-danger" title="Salir" onclick="cerrar_modal('addCargo'); return false;"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h6>Producto / Servicio</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-10 offset-md-1 mb-3">
                                    <div class="input-group">
                                        <select class="custom-select select2bs4"  id="producto_id" name="producto_id" style="width: 100%;" required>
                                            <option value="" selected="selected">Seleccionar.....</option>
                                                @foreach($pProductos as $pP)
                                                    <option value="{{ $pP->id }}"> {{ $pP->descripcion}} </option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Cantidad</span>
                                    </div>
                                    <input type="number" class="form-control" id="cantidad" name="cantidad" style="text-align: right;" min="1" required onchange="fn_precio_total(); return false;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Precio</span>
                                    </div>
                                    <input type="number" class="form-control" id="precio_unitario" name="precio_unitario" style="text-align: right;" min="1" required onchange="fn_precio_total(); return false;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Total</span>
                                    </div>
                                    <input type="number" class="form-control" id="precio_total" name="precio_total" style="text-align: right;" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Total Cliente</span>
                                    </div>
                                    <input type="number" class="form-control" id="total_cliente" name="total_cliente" style="text-align: right;" disabled="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Total Aseguradora</span>
                                    </div>
                                    <input type="number" class="form-control" id="total_aseguradora" name="total_aseguradora" style="text-align: right;" disabled="">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /add cargo-->
    <!-- mensaje modal -->
    <div class="modal fade" id="mensajeModal" role="dialog" aria-labelledby="mensajeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h3 class="modal_texto text-center" style="color: green;"></h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /mensaje modal -->
@endsection
@section('js')
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <script src="{{ asset('assets/alertifyjs/alertify.min.js') }}"></script>
    <script type="text/javascript">
        var nLinea = 0;

        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
            $('.select2bs4').select2({ theme: 'bootstrap4' })
        });

        window.onload = function() {
            var local_db = [];
            var eliminar_db = [];
            var agregar_db = [];
            localStorage.clear(local_db);
            localStorage.local_db = JSON.stringify(local_db);
            localStorage.eliminar_db = JSON.stringify(eliminar_db);
            localStorage.agregar_db = JSON.stringify(agregar_db);
            var admision = document.getElementById('admision_id').value;
            var admision_estado = document.getElementById('admision_estado').value;
            llenarTabla(admision);

            if (admision_estado == 'P') {
                document.getElementById('consulta').disabled = false;
                document.getElementById('procedimiento').disabled = false;
                document.getElementById('hospitalizacion').disabled = false;
                document.getElementById('fecha').disabled = false;
                document.getElementById('paciente_id').disabled = false;
                document.getElementById('medico_id').disabled = false;
                document.getElementById('hospital_id').disabled = false;
                document.getElementById('admision_tercero').disabled = false;
                document.getElementById('aseguradora_id').disabled = false;
                document.getElementById('poliza_no').disabled = false;
                document.getElementById('deducible').disabled = false;
                document.getElementById('copago').disabled = false;
            }else {
                document.getElementById('consulta').disabled = true;
                document.getElementById('procedimiento').disabled = true;
                document.getElementById('hospitalizacion').disabled = true;
                document.getElementById('fecha').disabled = true;
                document.getElementById('paciente_id').disabled = true;
                document.getElementById('medico_id').disabled = true;
                document.getElementById('hospital_id').disabled = true;
                document.getElementById('admision_tercero').disabled = true;
                document.getElementById('aseguradora_id').disabled = true;
                document.getElementById('poliza_no').disabled = true;
                document.getElementById('deducible').disabled = true;
                document.getElementById('copago').disabled = true;
            }
        }

        $(function(){
            $("#addCargoForm").submit(function(){
                grabar_local();
                return false;
            })
        });

        function mensaje(){
            var admision_id = document.getElementById('admision_id').value;

            swal({
                title: 'Confirmación',
                text: 'Seguro de cerrar la Admisión ?',
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
                            url: "{{ route('cerrar_admision') }}",
                            type: "POST",
                            async: true,
                            data: {"_token": "{{ csrf_token() }}", admision_id: admision_id},
                            success: function(response){
                                var info = response;
                                swal({
                                    title: 'Trabajo Finalizado !!!',
                                    text: response,
                                    type: 'success',
                                    },
                                    function(){
                                        location.reload();
                                    }
                                );
                            },
                            error: function(error){
                                console.log(error);
                            }
                        });
                    } 
                }
            );
        }

        function llenarTabla(id){
            $.ajax({
                url: "{{ route('trae_cargos') }}",
                type: "POST",
                dataType: 'json',
                data: {"_token": "{{ csrf_token() }}", admision_id : id},
                success: function(response){
                    if (response != 'null') {
                        var info = response;
                        info.sort();
                        html = '';
                        for(var i=0; i < info.length; i++){
                            var linea = {
                                admision_id : id,
                                linea       : i,
                                producto_id : info[i]['producto_id'],
                                producto_descripcion : info[i]['descripcion'],
                                cantidad : info[i]['cantidad'],
                                precio_unitario : info[i]['precio_unitario'],
                                precio_total    : info[i]['precio_total'],
                                total_cliente   : info[i]['total_cliente'],
                                total_aseguradora : info[i]['total_aseguradora'],
                                facturado : info[i]['facturado']
                            }

                            if(!localStorage.local_db){
                                localStorage.local_db = JSON.stringify([linea]);
                            }
                            else{
                                var local_db = JSON.parse(localStorage.local_db);
                                local_db.push(linea);
                                localStorage.local_db = JSON.stringify(local_db);
                            }
                        }
                        actualizarTabla();
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        //===================================================================
        // actualizar tabla
        //===================================================================
        function actualizarTabla(){
            var estado = document.getElementById('admision_estado').value;
            var local_db = JSON.parse(localStorage.local_db);
            var html = '';
            
            for(var i = 0; i < local_db.length; i++){
                //console.log(local_db[i]);
                html += '<tr>'
                html += '<td>'
                html += local_db[i]['producto_descripcion']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['cantidad']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['precio_unitario']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['precio_total']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['total_cliente']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['total_aseguradora']
                html += '</td>'
                html += '<td>'
                /*if (local_db[i]['estado'] != 'A') {
                    html += '<button class="btn btn-sm btn-success" onclick="editarRegistro('+i+')">Editar <i class="fa fa-edit"></i></button> &nbsp;'
                    html += '<button class="btn btn-sm btn-danger" onclick="eliminarRegistro('+i+');">Borrar <i class="fa fa-trash-alt"></i></button>'
                }*/
                if (estado == 'P' && local_db[i]['facturado'] != 'A') {
                    html += '<button class="btn btn-sm btn-danger" onclick="eliminarRegistro('+i+');" title="Eliminar Registro"><i class="fa fa-trash-alt"></i></button>';    
                }
                
                html += '</td>'
                html += '</tr>'
            }

            $("#tblCargos tbody tr").remove();
            $('#tblCargos tbody').append(html);
        }

        //===================================================================
        // grabar localmente cargos
        //===================================================================
        function grabar_local(){
            var producto = document.getElementById('producto_id');
            var producto_id = producto.options[producto.selectedIndex].value;
            var producto_descripcion = producto.options[producto.selectedIndex].text;
            var cantidad = document.getElementById('cantidad').value;
            var precio_unitario = document.getElementById('precio_unitario').value;
            var precio_total = cantidad * precio_unitario;
            var total_cliente = document.getElementById('total_cliente').value;
            var total_aseguradora = document.getElementById('total_aseguradora').value;
            var admision_id = document.getElementById('admision_id').value;
            var linea = {
                admision_id : admision_id,
                linea       : nLinea,
                producto_id : producto_id,
                producto_descripcion : producto_descripcion,
                cantidad : cantidad,
                precio_unitario : precio_unitario,
                precio_total    : precio_total,
                total_cliente   : total_cliente,
                total_aseguradora : total_aseguradora,
                facturado : 'N'
            }

            if(!localStorage.local_db){
                localStorage.local_db = JSON.stringify([linea]);
            }
            else{
                var local_db = JSON.parse(localStorage.local_db);
                local_db.push(linea);
                localStorage.local_db = JSON.stringify(local_db);
            }

            if(!localStorage.agregar_db){
                localStorage.agregar_db = JSON.stringify([linea]);
            }
            else{
                var agregar_db = JSON.parse(localStorage.agregar_db);
                agregar_db.push(linea);
                localStorage.agregar_db = JSON.stringify(agregar_db);
            }

            nLinea += 1;

            actualizarTabla();
            $('#addCargo').modal('hide');
        }

        function fn_precio_total(){
            cantidad = document.getElementById('cantidad').value;
            precio_unitario = document.getElementById('precio_unitario').value;
            precio_total    = cantidad * precio_unitario;
            deducible = parseFloat(document.getElementById('deducible').value) / 100;
            if (deducible == 0) {
                total_cliente   = precio_total;
                total_aseguradora = 0;
            }else{
                total_cliente   = precio_total * deducible;
                total_aseguradora =  precio_total - total_cliente;
            }
            
            document.getElementById('precio_total').value = precio_total;
            document.getElementById('total_cliente').value = total_cliente;
            document.getElementById('total_aseguradora').value = total_aseguradora;

        }

        function agregar_modal(){
            //document.getElementById('producto_id').value = '';
            document.getElementById("producto_id").options.selectedIndex = 0;
            document.getElementById('cantidad').value = '';
            document.getElementById('precio_unitario').value = '';
            document.getElementById('precio_total').value = '';
            $("#addCargo").modal("show");
        }

        function actualizar_admision(){
            var admision_id   = document.getElementById('admision_id').value;
            var tipo_admision = '';
            var x = document.getElementById("consulta").checked;
            var y = document.getElementById("procedimiento").checked;
            var z = document.getElementById("hospitalizacion").checked;
            if(x){
                tipo_admision = 'C';
            }
            if(y){
                tipo_admision = 'P';
            }
            if(z){
                tipo_admision = 'H';
            }
            var fecha         = document.getElementById('fecha').value;
            var paciente_id   = document.getElementById('paciente_id').value;
            var medico_id     = document.getElementById('medico_id').value;
            var hospital_id   = document.getElementById('hospital_id').value;
            var admision_tercero = document.getElementById('admision_tercero').value;
            var aseguradora_id   = document.getElementById('aseguradora_id').value;
            var poliza_no        = document.getElementById('poliza_no').value;
            var deducible     = document.getElementById('deducible').value;
            var copago        = document.getElementById('copago').value;
            var local_db      = JSON.parse(localStorage.local_db);
            var eliminar_db   = JSON.parse(localStorage.eliminar_db);
            var agregar_db    = JSON.parse(localStorage.agregar_db);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('actualizar_admision_ajax')}}",
                method: "POST",
                data: { admision_id      : admision_id,
                        tipo_admision    : tipo_admision,
                        fecha            : fecha,
                        paciente_id      : paciente_id,
                        medico_id        : medico_id,
                        hospital_id      : hospital_id,
                        admision_tercero : admision_tercero,
                        aseguradora_id   : aseguradora_id,
                        poliza_no        : poliza_no,
                        deducible        : deducible,
                        copago           : copago,
                        'agregar': JSON.stringify(agregar_db),
                        'eliminar': JSON.stringify(eliminar_db)},
                success: function(response){
                    var info = response;
                    swal({
                        title: 'Trabajo Finalizado !!!',
                        text: info,
                        type: 'success',
                        });
                    /*$('.modal_texto').html(info);
                    $("#mensajeModal").modal('show');*/
                },
                error: function(error){
                    console.log(error);
                }   
            });
        }

        function eliminarRegistro(id){
            var local_db = JSON.parse(localStorage.local_db);
            var eliminar_db = JSON.parse(localStorage.eliminar_db);
            for (var i = 0; i < local_db.length; i++) {
                if (local_db[i]['linea'] == id) {

                    /*==========================================================
                    Se almacenan registros a eliminar
                    ==========================================================*/
                    var linea = {
                        admision_id : local_db[i]['admision_id'],
                        linea       : local_db[i]['linea'],
                        producto_id : local_db[i]['producto_id'],
                        producto_descripcion : local_db[i]['producto_descripcion'],
                        cantidad : local_db[i]['cantidad'],
                        precio_unitario : local_db[i]['precio_unitario'],
                        precio_total    : local_db[i]['precio_total'],
                        total_cliente   : local_db[i]['total_cliente'],
                        total_aseguradora : local_db[i]['total_aseguradora']
                    }

                    if(!localStorage.eliminar_db){
                        localStorage.eliminar_db = JSON.stringify([linea]);
                    }
                    else{
                        var eliminar_db = JSON.parse(localStorage.eliminar_db);
                        eliminar_db.push(linea);
                        localStorage.eliminar_db = JSON.stringify(eliminar_db);
                    }
                    /*==========================================================
                    Se elimina localmente el registro
                    ==========================================================*/
                    local_db.splice(i, 1);
                }
            }
            localStorage.local_db = JSON.stringify(local_db);
            actualizarTabla();
        }

        function cerrar_modal(modal){
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
                        if (modal == 'reapertura') {
                            $('#reapertura').modal('hide');   
                        }else{
                            $('#addCargo').modal('hide');
                        }
                        swal.close();
                    }
                }
            );
        }

        function confirma_salida(){
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
                        window.location.href = "{{ route('admisiones') }}";
                    } 
                }
            );
        }
    </script>
@endsection