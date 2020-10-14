@extends('admin.layout')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('titulo')
	Recibos
@endsection
@section('subtitulo')
	Agregar
@endsection

@section('contenido')
	@if(Session::has('message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" arial-label="Close"><label aria-hidden="true">x</label>
            </button>
            {{ Session::get('message') }}  
        </div>
    @endif
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
                <div class="col-md-2 offset-md-10" style="text-align: right;">
                    <!--<a href="#" class="btn btn-sm btn-success" title="Grabar" onclick="fn_grabar_recibo(); return false;"><i class="fas fa-save"></i></a> -->
                    <a href="#" class="btn btn-sm btn-success" title="Grabar" onclick="fn_valida(); return false;"><i class="fas fa-save"></i></a>
                    <!--<a href="{{ route('recibos_listado') }}" class="btn btn-sm btn-danger" title="Regresar a lista de Recibos"><i class="fas fa-sign-out-alt"></i></a>-->
                    <a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Admisiones" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </div>
        <form class="form-horizontal">
            <div class="card-body">
                <input type="hidden" id="tipo_documento_id" name="tipo_documento_id" value="{{ $documento->id }}">
                <input type="hidden" id="resolucion_id" name="resolucion_id">
                <input type="hidden" id="caja_id" name="caja_id" value="{{ $caja->id }}">
                <input type="hidden" id="caja_editar_documento" name="caja_editar_documento" value="{{ $caja->editar_documento}}">
                <input type="hidden" id="recibo_estado" name="recibo_estado" value="P">
                <input type="hidden" id="total_saldo" name="total_saldo">
                <input type="hidden" id="total_pago" name="total_pago">
                <input type="hidden" id="recibo_total_pago" name="recibo_total_pago">
                <div class="row">
                    <div class="col-md-3 offset-md-1">
                        <div class="input-group mb-1 input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Documento</label>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center" id="documento_descripcion" name="documento_descripcion" value="{{ $documento->descripcion }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group mb-1 input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Fecha</label>
                            </div>
                            <input type="date" class="form-control form-control-sm text-center" id="fecha_emision" name="fecha_emision" value="{{ $hoy }}" @if($caja->editar_documento == 'N') then disabled @endif tabindex="1">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 offset-md-1">
                        <div class="input-group mb-1 input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Serie</label>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center" id="serie" name="serie" style="text-transform: uppercase;" onchange="fn_resolucion_x_serie(); return false;" @if($caja->editar_documento == 'N') then disabled @endif tabindex="2" autofocus>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group mb-1 input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Correlativo</label>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center" id="correlativo" name="correlativo" @if($caja->editar_documento == 'N') then disabled @endif tabindex="3">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1 col-md-6 offset-md-1">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="paciente_id">Paciente</label>
                            </div>
                            <select class="custom-select  custom-select-sm select2 select2bs4" id="paciente_id"  name="paciente_id" tabindex="8" onchange="fn_documentos_con_saldo(); return false;">
                                <option value="">Seleccionar...</option>
                                @foreach($pacientes as $p)
                                    <option value="{{ $p->id}}">{{ $p->nombre_completo}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card card-secondary">
                    <div class="card-header text-center">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <h6>Documentos con Saldo</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="table-responsive">
                                    <table id="tblDocumentos" class="table table-sm table-hover text-center">
                                        <thead>
                                            <tr>
                                                <th>Admisión</th><th>Tipo</th><th>Fecha</th><th>Documento</th><th>N.I.T.</th><th>Nombre</th><th>Total</th><th>Saldo</th><th>Pago</th><th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot></tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-secondary">
                    <div class="card-header text-center">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <h6>Forma de pago</h6>
                            </div>
                            <div class="col-md-1" style="text-align: right;">
                                <a href="#" class="btn btn-sm btn-primary" title="Agregar forma de pago" onclick="fn_forma_pago(); return false;"><i class="fas fa-plus-circle"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="table-responsive">
                                    <table id="tblPago" class="table table-sm table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>Forma de pago</th>
                                                <th>Entidad</th>
                                                <th>Cuenta</th>
                                                <th>Documento</th>
                                                <th>Autorización</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot></tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- modal pago -->
    <div class="modal hide fade" id="pagoModal" tabindex="-1" role="dialog" aria-labelledby="pagoModalLabel" aria-hidden="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="aplica_pago" name="aplica_pago" action="#">
                    <div class="card card-navy">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-8 offset-md-1">
                                    <h6>Pago de documento</h6>
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <!--<a href="#" class="btn btn-sm btn-success" title="Grabar" onclick="grabar_local(); return false;"><i class="fas fa-save"></i></a>-->
                                    <button type="submit" class="btn btn-sm btn-success" title="grabar"><i class="fas fa-save"></i></button>
                                    <!--<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" title="Salir"><i class="fas fa-sign-out-alt"></i></button>-->
                                    <button type="button" class="btn btn-sm btn-danger" title="Salir" onclick="cerrar_modal('pagoModal'); return false;"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="documento_id" name="documento_id">
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" id="basic-addon1">Tipo</label>
                                        <input type="text" class="form-control" id="pago_tipo_documento" name="pago_tipo_documento" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" id="basic-addon1">Fecha</label>
                                        <input type="date" class="form-control" id="pago_fecha" name="pago_fecha" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" id="basic-addon1">Documento</label>
                                        <input type="text" class="form-control" id="pago_documento" name="pago_documento" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" id="basic-addon1">N.I.T</label>
                                        <input type="text" class="form-control" id="pago_nit" name="pago_nit" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" id="basic-addon1">Nombre</label>
                                        <input type="text" class="form-control" id="pago_nombre" name="pago_nombre" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" id="basic-addon1">Total</label>
                                        <input type="number" class="form-control" id="pago_total" name="pago_total" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" id="basic-addon1">Saldo</label>
                                        <input type="number" class="form-control" id="pago_saldo" name="pago_saldo" disabled style="text-align: right;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" id="basic-addon1">Pagar</label>
                                        <input type="number" class="form-control" placeholder="0.00" id="pago_pagar" name="pago_pagar" min="0" step="any" required style="text-align: right;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /modal pago -->
    <!-- forma de pago -->
    <div class="modal fade" id="fpagoModal" tabindex="-1" role="dialog" aria-labelledby="fpagoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="monto_pago" name="aplica_pago" action="#">
                    <div class="card card-navy">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-9">
                                    Forma de Pago
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <!--<a href="#" class="btn btn-sm btn-success" title="Grabar" onclick="fn_fpago_local(); return false;"><i class="fas fa-save"></i></a> -->
                                    <button type="submit" class="btn btn-sm btn-success" title="grabar"><i class="fas fa-sign-out-alt"></i></button>
                                    <!--<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" title="Salir"><i class="fas fa-sign-out-alt"></i></button>-->
                                    <button type="button" class="btn btn-sm btn-danger" title="Salir" onclick="cerrar_modal('fpagoModal'); return false;"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="fpago" class="row">
                                <div class="col-md-10 offset-md-1 mb-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="fpago_id">Forma de Pago</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="fpago_id"  name="fpago_id" onchange="verifica_campos(); return false;">
                                            <option value="">Seleccionar...</option>
                                            @foreach($formas_pago as $fp)
                                                <option value="{{ $fp->id }}">{{ $fp->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="banco" class="row">
                                <div class="col-md-10 offset-md-1 mb-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="banco_id">Banco</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="banco_id"  name="banco_id">
                                            <option value="">Seleccionar...</option>
                                            @foreach($bancos as $b)
                                                <option value="{{ $b->id}}">{{ $b->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="tarjeta1" class="row">
                                <div class="col-md-10 offset-md-1 mb-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="tarjeta_id">Tarjeta</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="tarjeta_id"  name="tarjeta_id">
                                            <option value="">Seleccionar...</option>
                                            @foreach($tarjetas as $t)
                                                <option value="{{ $t->id}}">{{ $t->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="cuenta" class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">No. Cuenta</label>
                                    </div>
                                    <input type="text" class="form-control" id="cuenta_no" name="cuenta_no" value="{{ old('cuenta_no')}}">
                                </div>
                            </div>
                            <div id="documento" class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">No. Documento</label>
                                    </div>
                                    <input type="text" class="form-control" id="documento_no" name="documento_no" value="{{ old('documento_no')}}">
                                </div>
                            </div>
                            <div id="autorizacion" class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">No. Autorización</label>
                                    </div>
                                    <input type="text" class="form-control" id="autoriza_no" name="autoriza_no" value="{{ old('autoriza_no')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Monto</label>
                                    </div>
                                    <input type="number" class="form-control" id="monto" name="monto" value="{{ old('monto')}}" style="text-align: right;" autofocus required min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /forma de pago -->
@endsection
@section('js')
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
    <script type="text/javascript">
        var forma_pago = 'E';
        var nlineaPago = 0;

        $(function(){
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
        });

        $(function(){
            $("#aplica_pago").submit(function(){
                grabar_local();
                return false;
            })
        });

        $(function(){
            $("#monto_pago").submit(function(){
                fn_fpago_local();
                return false;
            })
        });

        function verifica_campos(){
            var fpago_id = document.getElementById('fpago_id').value;
            if (fpago_id == '1') {
                $("#banco").hide();
                $("#cuenta").hide();
                $("#tarjeta1").hide();
                $("#documento").hide();
                $("#autorizacion").hide();
                $('#banco_id').prop("required", false);
                $("#cuenta_no").prop('required', false);
                $("#documento_no").prop('required', false);
                $("#autoriza_no").prop('required', false);
            }
            if (fpago_id == '2') {
                $("#banco").show();
                $('#banco_id').prop("required", true);
                $("#cuenta").show();
                $("#cuenta_no").prop('required', true);
                $("#tarjeta1").hide();
                $("#documento").show();
                $("#documento_no").prop('required', true);
                $("#autorizacion").show();
                $("#autoriza_no").prop('required', true);
            }
            if (fpago_id == '3') {
                $('#banco_id').prop("required", false);
                $('#cuenta_no').prop("required", false);
                $("#banco").hide();
                $("#cuenta").hide();
                $("#tarjeta1").show();
                $("#tarjeta_id").prop('required', true);
                $("#documento").show();
                $("#documento_no").prop('required', true);
                $("#autorizacion").show();
                $("#autoriza_no").prop('required', true);
            }

            if (fpago_id == '4') {
                $("#banco").hide();
                $("#cuenta").hide();
                $("#tarjeta1").hide();
                $("#autorizacion").hide();
                $('#banco_id').prop("required", false);
                $("#cuenta_no").prop('required', false);
                $("#autoriza_no").prop('required', false);
                $("#documento").show();
                $("#documento_no").prop('required', true);
            }

            if (fpago_id == '5') {
                $("#banco").hide();
                $("#cuenta").hide();
                $("#tarjeta1").hide();
                $("#autorizacion").hide();
                $('#banco_id').prop("required", false);
                $("#cuenta_no").prop('required', false);
                $("#autoriza_no").prop('required', false);
                $("#documento").show();
                $("#documento_no").prop('required', true);
            }
        }

        window.addEventListener('load', function(){
            var local_db = [];
            var pago_db = [];
            localStorage.clear(local_db);
            localStorage.clear(pago_db);
            localStorage.local_db     = JSON.stringify(local_db);
            localStorage.pago_db      = JSON.stringify(pago_db);
            var caja_id               = document.getElementById('caja_id').value;
            var caja_editar_documento = document.getElementById('caja_editar_documento').value;

            /*=================================================================
            Verifica si el usuario puede modificar el numero de serie y correlativo
            de factura
            =================================================================*/
            if (caja_editar_documento == 'N') {
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('resolucion_recibo_x_caja')}}",
                    method: "POST",
                    data: { caja_id  : caja_id},
                    success: function(response){
                        var info = response;
                        document.getElementById('resolucion_id').value = info.resolucion_id;
                        document.getElementById('serie').value = info.serie;
                        document.getElementById('correlativo').value = info.correlativo;
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }else {
                document.getElementById('serie').focus();
            }
        });

        function fn_documentos_con_saldo(){
            var paciente = document.getElementById('paciente_id');
            var paciente_id   = paciente.options[paciente.selectedIndex].value;
            localStorage.clear();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('documentos_con_saldo')}}",
                method: "POST",
                data: { paciente_id : paciente_id,
                },
                success: function(response){
                    var info = response;
                    info.sort();
                    for (var i = 0; i < info.length; i++) {
                        if (info[i]['total_documento'] != info[i]['total_pagado']) {
                            var linea = {
                                id : info[i]['id'],
                                tipodocumento_id : info[i]['tipodocumento_id'],
                                tipodocumento_descripcion : info[i]['tipodocumento_descripcion'],
                                fecha_emision : info[i]['fecha_emision'],
                                serie : info[i]['serie'],
                                correlativo : info[i]['correlativo'],
                                nit : info[i]['nit'],
                                nombre : info[i]['nombre'],
                                total_documento : info[i]['total_documento'],
                                saldo : info[i]['total_documento'] - info[i]['total_pagado'],
                                pago  : '0.00',
                                admision_id : info[i]['admision_id'],
                                admision : info[i]['admision']
                            }

                            //console.log(linea);

                            if(!localStorage.local_db){
                                localStorage.local_db = JSON.stringify([linea]);
                            }
                            else{
                                var local_db = JSON.parse(localStorage.local_db);
                                local_db.push(linea);
                                localStorage.local_db = JSON.stringify(local_db);
                            }
                        }
                    }
                    actualizarTabla();
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function fn_resolucion_x_serie(){
            var caja_id           = document.getElementById('caja_id').value;
            var tipo_documento_id = document.getElementById('tipo_documento_id').value;
            var serie             = document.getElementById('serie').value;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('trae_resolucion_x_serie')}}",
                method: "POST",
                data: { caja_id  : caja_id,
                        tipo_documento_id : tipo_documento_id,
                        serie    : serie},
                success: function(response){
                    var info = response;
                    if (info.resolucion_id == 0) {
                        document.getElementById('resolucion_id').value = 0;
                        document.getElementById('serie').value = '';
                        document.getElementById('correlativo').value = '';
                        swal({
                            title: 'Error !!!',
                            text: 'No existe resolucion en la caja que permita emitir documentos con la serie '+serie,
                            type: 'error',
                        });
                    } else {
                        document.getElementById('resolucion_id').value = info.resolucion_id;
                        document.getElementById('correlativo').value = info.correlativo;
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function actualizarTabla(){
            $("#tblDocumentos tbody tr").remove();
            $("#tblDocumentos tfoot tr").remove();

            if(localStorage.local_db){
                var local_db = JSON.parse(localStorage.local_db);
                local_db.sort(compare);
                //local_db.sort();

                var html = '';
                var htmlf = '';
                var total_saldo = 0;
                var total_pago  = 0;
                
                for (var i = 0; i < local_db.length; i++) {
                    total_saldo += parseFloat(local_db[i]['saldo']);
                    total_pago  += parseFloat(local_db[i]['pago']);
                    html += '<tr>'
                    html += '<td>'
                    html += local_db[i]['admision']
                    html += '</td>'
                    html += '<td>'
                    html += local_db[i]['tipodocumento_descripcion']
                    html += '</td>'
                    html += '<td>'
                    html += local_db[i]['fecha_emision']
                    html += '</td>'
                    html += '<td>'
                    html += local_db[i]['serie']+'-'+local_db[i]['correlativo']
                    html += '</td>'
                    html += '<td>'
                    html += local_db[i]['nit']
                    html += '</td>'
                    html += '<td>'
                    html += local_db[i]['nombre']
                    html += '</td>'
                    html += '<td>'
                    html += local_db[i]['total_documento']
                    html += '</td>'
                    html += '<td>'
                    html += local_db[i]['saldo']
                    html += '</td>'
                    html += '<td>'
                    html += local_db[i]['pago']
                    html += '</td>'
                    html += '<td>'
                    html += '<a href="#" class="btn btn-sm btn-outline-success" onclick="pagar_documento('+local_db[i]['id']+');" title="pagar documento"><i class="fas fa-check-circle"></a>'
                    html += '</td>'
                    html += '</tr>'
                }

                htmlf += '<tr>'
                htmlf += '<td>'
                htmlf += '</td>'
                htmlf += '<td>'
                htmlf += '</td>'
                htmlf += '<td>'
                htmlf += '</td>'
                htmlf += '<td>'
                htmlf += '</td>'
                htmlf += '<td>'
                htmlf += '</td>'
                htmlf += '<td>'
                htmlf += '</td>'
                htmlf += '<td>'
                htmlf += total_saldo
                htmlf += '</td>'
                htmlf += '<td>'
                htmlf += total_pago
                htmlf += '</td>'
                htmlf += '</tr>'
            }

            document.getElementById('total_saldo').value = total_saldo;
            document.getElementById('total_pago').value = total_pago;

            $("#tblDocumentos tbody tr").remove();
            $('#tblDocumentos tbody').append(html);
            $("#tblDocumentos tfoot tr").remove();
            $('#tblDocumentos tfoot').append(htmlf);
        }

        function pagar_documento(id){
            var local_db = JSON.parse(localStorage.local_db);
            local_db.sort();
            for (var i = 0; i < local_db.length; i++) {
                if (local_db[i]['id'] == id) {
                    document.getElementById('documento_id').value = local_db[i]['id'];
                    document.getElementById('pago_tipo_documento').value = local_db[i]['tipodocumento_descripcion'];
                    document.getElementById('pago_fecha').value = local_db[i]['fecha_emision'];
                    document.getElementById('pago_documento').value = local_db[i]['serie']+'-'+local_db[i]['correlativo'];
                    document.getElementById('pago_nit').value = local_db[i]['nit'];
                    document.getElementById('pago_nombre').value = local_db[i]['nombre'];
                    document.getElementById('pago_total').value = local_db[i]['total_documento'];
                    document.getElementById('pago_saldo').value = local_db[i]['saldo'];
                    document.getElementById('pago_pagar').value = local_db[i]['saldo'];
                }
            }
            
            $("#pagoModal").modal('show');
        }

        function grabar_local(){
            id   = document.getElementById('documento_id').value;            
            pago = document.getElementById('pago_pagar').value;
            var local_db = JSON.parse(localStorage.local_db);
            local_db.sort(compare);
            for (var i = 0; i < local_db.length; i++) {
                if (local_db[i]['id'] == id) {
                    if (local_db[i]['saldo'] < pago) {
                        swal({
                            title: 'Error !!!',
                            text: 'Valor a pagar no puede ser mayor a saldo de documento ',
                            type: 'error',
                        });
                    }else{
                        var linea = {
                            id                        : id,
                            tipodocumento_id          : local_db[i]['tipodocumento_id'],
                            tipodocumento_descripcion : local_db[i]['tipodocumento_descripcion'],
                            fecha_emision             : local_db[i]['fecha_emision'],
                            serie                     : local_db[i]['serie'],
                            correlativo               : local_db[i]['correlativo'],
                            nit                       : local_db[i]['nit'],
                            nombre                    : local_db[i]['nombre'],
                            total_documento           : local_db[i]['total_documento'],
                            saldo                     : local_db[i]['saldo'],
                            pago                      : pago,
                            admision_id               : local_db[i]['admision_id'],
                            admision                  : local_db[i]['admision']
                        }
                        local_db.splice(i, 1);
                        localStorage.local_db = JSON.stringify(local_db);
                        if(!localStorage.local_db){
                            localStorage.local_db = JSON.stringify([linea]);
                        }
                        else{
                            var local_db = JSON.parse(localStorage.local_db);
                            local_db.push(linea);
                            localStorage.local_db = JSON.stringify(local_db);
                        }
                        localStorage.local_db = JSON.stringify(local_db);
                        $('#pagoModal').modal('hide');
                        $('.modal-backdrop').hide();
                        actualizarTabla();
                    }
                }
            }
        }

        function fn_forma_pago(){
            document.getElementById('fpago_id').value     = '';
            $('#fpago_id').change();
            document.getElementById('banco_id').value     = '';
            document.getElementById('cuenta_no').value    = '';
            document.getElementById('documento_no').value = '';
            document.getElementById('autoriza_no').value  = '';
            document.getElementById('monto').value        = '';
            $("#banco").hide();
            $("#cuenta").hide();
            $("#tarjeta1").hide();
            $("#documento").hide();
            $("#autorizacion").hide();
            $("#fpagoModal").modal('show');
        }

        function fn_fpago_local(){
            var forma_pago = document.getElementById('fpago_id').value;
            if (forma_pago == '1') {
                var entidad_id            = null;
                var entidad_descripcion   = '';
                var cuenta_no             = '';
                var documento_no          = '';
                var autoriza_no           = '';
                var monto                 = document.getElementById('monto').value;
                var formapago_descripcion = 'Efectivo';
            }
            if (forma_pago == '2') {
                var banco        = document.getElementById('banco_id');
                var entidad_id   = banco.options[banco.selectedIndex].value;
                var entidad_descripcion = banco.options[banco.selectedIndex].text;
                var cuenta_no    = document.getElementById('cuenta_no').value;
                var documento_no = document.getElementById('documento_no').value;
                var autoriza_no  = document.getElementById('autoriza_no').value;
                var monto        = document.getElementById('monto').value;
                var formapago_descripcion = 'Cheque';
            }
            if (forma_pago == '3') {
                var tarjeta = document.getElementById('tarjeta_id');
                var entidad_id = tarjeta.options[tarjeta.selectedIndex].value;
                var entidad_descripcion = tarjeta.options[tarjeta.selectedIndex].text;
                var cuenta_no     = document.getElementById('cuenta_no').value;
                var documento_no  = document.getElementById('documento_no').value;
                var autoriza_no   = document.getElementById('autoriza_no').value;
                var monto         = document.getElementById('monto').value;
                var formapago_descripcion = 'Tarjeta';
            }
            if (forma_pago == '4') {
                var entidad_id            = null;
                var entidad_descripcion   = '';
                var cuenta_no             = '';
                var documento_no          = document.getElementById('documento_no').value;
                var autoriza_no           = '';
                var monto                 = document.getElementById('monto').value;
                var formapago_descripcion = 'Transferencia Bancaria';
            }
            if (forma_pago == '5') {
                var entidad_id            = null;
                var entidad_descripcion   = '';
                var cuenta_no             = '';
                var documento_no          = document.getElementById('documento_no').value;
                var autoriza_no           = '';
                var monto                 = document.getElementById('monto').value;
                var formapago_descripcion = 'Transferencia Bancaria';
            }

            var linea = {
                id               : nlineaPago,
                forma_pago       : forma_pago,
                formapago_descripcion : formapago_descripcion,
                entidad_id            : entidad_id,
                entidad_descripcion   : entidad_descripcion,
                cuenta_no        : cuenta_no,
                documento_no     : documento_no,
                autoriza_no      : autoriza_no,
                monto            : monto
            };

            if(!localStorage.pago_db){
                localStorage.pago_db = JSON.stringify([linea]);
            }
            else{
                pago_db = JSON.parse(localStorage.pago_db);
                pago_db.push(linea);
                localStorage.pago_db = JSON.stringify(pago_db);
            }
            actualizarTablaPago();
            $('#fpagoModal').modal('hide');

            nlineaPago += 1;
        }

        //========================================================================
        // funcion para ordenar detalle
        //========================================================================

        function compare(a,b){
            const valorA = a.id;
            const valorB = b.id;
            let comparacion = 0;

            if (valorA < valorB) {
                comparacion = -1;
            }else{
                comparacion = 1;
            }
            return comparacion;
        }

        function actualizarTablaPago(){
            var pago_db = JSON.parse(localStorage.pago_db);
            pago_db.sort(compare);
            var html = '';
            var html1 = '';
            var total_pago = 0;
            var error = false;
            
            for(var i = 0; i < pago_db.length; i++){
                total_pago += parseFloat(pago_db[i]['monto']);
                html += '<tr>'
                html += '<td>'
                html += pago_db[i]['formapago_descripcion']
                html += '</td>'
                html += '<td>'
                html += pago_db[i]['entidad_descripcion']
                html += '</td>'
                html += '<td>'
                html += pago_db[i]['cuenta_no']
                html += '</td>'
                html += '<td>'
                html += pago_db[i]['documento_no']
                html += '</td>'
                html += '<td>'
                html += pago_db[i]['autoriza_no']
                html += '</td>'
                html += '<td>'
                html += pago_db[i]['monto']
                html += '</td>'
                html += '<td style="text-align: right;">'
                html += '<button class="btn btn-sm btn-danger" onclick="eliminar_registro_pago('+i+');" title="Eliminar Registro"><i class="fa fa-trash-alt"></i></button>'
                html += '</td>'
                html += '</tr>'
            }
            html1 += '<tr>'
            html1 += '<td></td>'
            html1 += '<td></td>'
            html1 += '<td></td>'
            html1 += '<td></td>'
            html1 += '<td><strong>Total Pago</strong></td>'
            html1 += '<td style="text-align:center;">'+total_pago+'</td>'
            html1 += '</tr>'

            document.getElementById('recibo_total_pago').value = total_pago;

            $("#tblPago tfoot tr").remove();
            $("#tblPago tbody tr").remove();
            $('#tblPago tbody').append(html);
            $('#tblPago tfoot').append(html1);
        }

        function eliminar_registro_pago(id){
            total = 0;
            var pago_db = JSON.parse(localStorage.pago_db);
            for(var i = 0; i < pago_db.length; i++){
                if (id == pago_db[i]['id']) {
                    pago_db.splice(i, 1);
                    localStorage.pago_db = JSON.stringify(pago_db);
                }
            }
            actualizarTablaPago();
        }

        function fn_valida(){
            var local_db       = JSON.parse(localStorage.local_db);
            
            if(localStorage.pago_db){
                var pago_db        = JSON.parse(localStorage.pago_db);
            }
            
            var total_facturas = 0;
            var total_pago     = 0;

            for (var i = 0; i < local_db.length; i++) {
                if (parseFloat(local_db[i]['pago']) != 0) {
                    total_facturas += 1;
                }
            }

            if(localStorage.pago_db){
                for (var i = 0; i < pago_db.length; i++) {
                    if (parseFloat(pago_db[i]['monto']) != 0) {
                        total_pago += 1;
                    }
                }
            }else{
                total_pago = 0;
            }
            
            if (total_facturas == 0 || total_pago == 0) {
                swal({
                    title: 'Error !!!',
                    text: 'No existen documentos a cancelar, Favor verifique',
                    type: 'error',
                });
            }else{
                fn_grabar_recibo();
            }
        }

        function fn_grabar_recibo(){
            var local_db          = JSON.parse(localStorage.local_db);
            var pago_db           = JSON.parse(localStorage.pago_db);
            var tipo_documento_id = document.getElementById('tipo_documento_id').value;
            var resolucion_id     = document.getElementById('resolucion_id').value;
            var fecha_emision     = document.getElementById('fecha_emision').value;
            var serie             = document.getElementById('serie').value;
            var correlativo       = document.getElementById('correlativo').value;
            var paciente_id       = document.getElementById('paciente_id').value;
            var total_saldo       = parseFloat(document.getElementById('total_saldo').value);
            var total_pago        = parseFloat(document.getElementById('total_pago').value);
            var recibo_total_pago = parseFloat(document.getElementById('recibo_total_pago').value);
            if (total_saldo < total_pago) {
                swal({
                    title: 'Error !!!',
                    text: 'Total a pagar no puede ser mayor a saldo pendiente',
                    type: 'error',
                });
            }else{
                if (total_pago != recibo_total_pago) {
                    swal({
                        title: 'Error !!!',
                        text: 'Total forma de pago no puede ser distinto al total a pagar en documentos',
                        type: 'error',
                    });
                }else{
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{route('recibo_grabar')}}",
                        method: "POST",
                        data: { tipo_documento_id  : tipo_documento_id,
                                resolucion_id      : resolucion_id,
                                fecha_emision      : fecha_emision,
                                serie              : serie,
                                correlativo        : correlativo,
                                paciente_id        : paciente_id,
                                documentos         : JSON.stringify(local_db),
                                pagos              : JSON.stringify(pago_db)},
                        success: function(response){
                            var info = response;
                            swal({
                                title: 'Trabajo Finalizado !!!',
                                text: info.respuesta,
                                type: 'success',
                            }, function(){
                                window.location.href = asset+"pagos/editar_recibo/"+info.recibo_id;
                            });
                        },
                        error: function(error){
                            console.log(error);
                        }
                    });
                }
            }
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
                        $('#'+modal).modal('hide');
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
                        window.location.href = "{{ route('recibos_listado') }}";
                    }
                }
            );
        }

    </script>
@endsection