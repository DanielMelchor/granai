@extends('admin.layout')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@endsection
@section('titulo')
	Recibos
@endsection
@section('subtitulo')
	Edición
@endsection

@section('contenido')
	@if(Session::has('message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" arial-label="Close"><span aria-hidden="true">x</span>
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
                    @if($encabezado->estado == 'A')
                    <a href="#" onclick="fn_anular(); return false;" class="btn btn-sm btn-anular" title="Anular Recibo"><i class="fas fa-ban"></i></a>
                    @endif
                    <a href="{{ route('recibos_listado') }}" class="btn btn-sm btn-danger" title="Regresar a lista de Recibos"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <input type="hidden" id="tipo_documento_id" name="tipo_documento_id" value="{{ $documento->id }}">
            <input type="hidden" id="resolucion_id" name="resolucion_id" value="{{ $encabezado->resolucion_id }}">
            <input type="hidden" id="caja_id" name="caja_id" value="{{ $caja->id }}">
            <input type="hidden" id="caja_editar_documento" name="caja_editar_documento" value="{{ $caja->editar_documento}}">
            <input type="hidden" id="recibo_id" name="recibo_id" value="{{ $encabezado->id}}">
            <input type="hidden" id="recibo_estado" name="recibo_estado" value="{{ $encabezado->estado }}">
            <input type="hidden" id="total_saldo" name="total_saldo">
            <input type="hidden" id="total_pago" name="total_pago">
            <input type="hidden" id="recibo_total_pago" name="recibo_total_pago">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-4 offset-md-1">
                            <div class="input-group mb-1 input-group-sm">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Documento</label>
                                </div>
                                <input type="text" class="form-control form-control-sm text-center" id="documento_descripcion" name="documento_descripcion" value="{{ $documento->descripcion }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group mb-1 input-group-sm">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Fecha</label>
                                </div>
                                <input type="date" class="form-control form-control-sm text-center" id="fecha_emision" name="fecha_emision" value="{{ $encabezado->fecha_emision }}" @if($caja->editar_documento == 'N') then disabled @endif tabindex="1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 offset-md-1">
                            <div class="input-group mb-1 input-group-sm">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Serie</label>
                                </div>
                                <input type="text" class="form-control form-control-sm text-center" id="serie" name="serie" value="{{ $encabezado->serie }}" style="text-transform: uppercase;" onchange="fn_resolucion_x_serie(); return false;" @if($caja->editar_documento == 'N') then disabled @endif tabindex="2" autofocus>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group mb-1 input-group-sm">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Correlativo</label>
                                </div>
                                <input type="text" class="form-control form-control-sm text-center" id="correlativo" name="correlativo" value="{{ $encabezado->correlativo }}" @if($caja->editar_documento == 'N') then disabled @endif tabindex="3">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-1 col-md-8 offset-md-1">
                            <div class="input-group mb-1 input-group-sm">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Paciente</label>
                                </div>
                                <input type="text" class="form-control form-control-sm text-center" id="paciente_id" name="paciente_id" value="{{ $paciente->nombre_completo }}" disabled tabindex="8">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    @if($encabezado->estado == 'I')
                    <div class="card card-danger">
                        <div class="card-header text-center">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>Motivo Anulación</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <span>{{ $encabezado->observacion_anulacion }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <hr>
            <div class="card card-secondary">
                <div class="card-header text-center">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <h6>Documentos Asociados</h6>
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
                                            <th>Tipo</th><th>Fecha</th><th>Documento</th><th>N.I.T.</th><th>Nombre</th><th>Total</th><th>Saldo</th><th>Pago</th><th>&nbsp;</th>
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
                        <!--<div class="col-md-1" style="text-align: right;">
                            <a href="#" class="btn btn-sm btn-primary" title="Agregar forma de pago" onclick="fn_forma_pago(); return false;"><i class="fas fa-plus-circle"></i></a>
                        </div> -->
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
    </div>
    <!-- anulación de facturas -->
    <div class="modal fade" id="anulacionModal" role="dialog" aria-labelledby="mensajeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form role="form" method="POST" action="{{ route('recibo_anular', $encabezado->id) }}">
                    @csrf
                    <div class="card card-navy">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5>Anulación de Recibo</h5>
                                </div>
                                <div class="col-md-4" style="text-align: right;">
                                    <button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" title="Salir"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10 offset-md-1 mb-3">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" for="motivo_id">Motivo</span>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="motivo_id" name="motivo_id" autofocus required>
                                            <option value="">Seleccionar...</option>
                                            @foreach($listado as $l)
                                                <option value="{{ $l->id}}">{{ $l->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Observaciones</span>
                                        </div>
                                        <textarea class="form-control form-control-sm" aria-label="With textarea" id="observacion_anulacion" name="observacion_anulacion" rows="3" maxlength="150" style="text-align: justify;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    <!-- /anulación de facturas -->
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
        $("input[name=fpago]").click(function () {    
            forma_pago = $(this).val();
            if (forma_pago == 'E') {
                 $("#banco").hide();
                 $("#cuenta").hide();
                 $("#tarjeta1").hide();
                 $("#documento").hide();
                 $("#autorizacion").hide();
            }
            if (forma_pago == 'B') {
                 $("#banco").show();
                 $("#cuenta").show();
                 $("#tarjeta1").hide();
                 $("#documento").show();
                 $("#autorizacion").show();
            }
            if (forma_pago == 'T') {
                 $("#banco").hide();
                 $("#cuenta").hide();
                 $("#tarjeta1").show();
                 $("#documento").show();
                 $("#autorizacion").show();
            }
        });

        window.addEventListener('load', function(){
            var local_db = [];
            var pago_db = [];
            localStorage.clear(local_db);
            localStorage.clear(pago_db);
            localStorage.local_db     = JSON.stringify(local_db);
            localStorage.pago_db      = JSON.stringify(pago_db);
            var caja_id               = document.getElementById('caja_id').value;
            var recibo_id             = document.getElementById('recibo_id').value;
            var caja_editar_documento = document.getElementById('caja_editar_documento').value;
            /*=======================================================
            trae detalle de recibo
            =======================================================*/
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                url: "{{route('trae_detalle_recibo')}}",
                method: "POST",
                data: { recibo_id  : recibo_id},
                success: function(response){
                    var info = response;
                    if (info.length > 0) {
                        for (var i = 0; i < info.length; i++) {
                            if (info[i]['total_aplicado'] > 0) {
                                var linea = {
                                    id                        : info[i]['id'],
                                    tipo_documento_id         : info[i]['tipodocumento_id'],
                                    tipodocumento_descripcion : info[i]['descripcion'],
                                    fecha_emision             : info[i]['fecha_emision'],
                                    serie                     : info[i]['serie'],
                                    correlativo               : info[i]['correlativo'],
                                    nit                       : info[i]['nit'],
                                    nombre                    : info[i]['nombre'],
                                    total_documento           : info[i]['saldo_documento'],
                                    pago                      : info[i]['total_aplicado'],
                                    saldo                     : info[i]['saldo_documento'] - info[i]['total_aplicado']
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
                        }
                        actualizarTabla();
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });

            /*
            Trae pago de recibo
            */
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                url: "{{route('trae_pago_recibo')}}",
                method: "POST",
                data: { recibo_id  : recibo_id},
                success: function(response){
                    var info = response;
                    if (info.length > 0) {
                        for (var i = 0; i < info.length; i++) {
                            var linea = {
                                id : info[i]['id'],
                                forma_pago : info[i]['forma_pago'],
                                formapago_descripcion : info[i]['forma_pago_descripcion'],
                                banco_id : info[i]['banco_id'],
                                entidad_descripcion : info[i]['emisor_nombre'],
                                cuenta_no : info[i]['cuenta_no'],
                                documento_no : info[i]['documento_no'],
                                autoriza_no  : info[i]['autoriza_no'],
                                monto : info[i]['monto']
                            }
                            if(!localStorage.pago_db){
                                localStorage.pago_db = JSON.stringify([linea]);
                            }
                            else{
                                pago_db = JSON.parse(localStorage.pago_db);
                                pago_db.push(linea);
                                localStorage.pago_db = JSON.stringify(pago_db);
                            }
                        }
                        actualizarTablaPago();
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });

            /*=================================================================
            Verifica si el usuario puede modificar el numero de serie y correlativo
            de factura
            =================================================================*/
            /*if (caja_editar_documento == 'N') {
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
            }*/
        });

        function actualizarTabla(){
            var local_db = JSON.parse(localStorage.local_db);
            local_db.sort();
            var html = '';
            var htmlf = '';
            var total_saldo = 0;
            var total_pago  = 0;
            for (var i = 0; i < local_db.length; i++) {
                total_saldo += parseFloat(local_db[i]['saldo']);
                total_pago  += parseFloat(local_db[i]['pago']);
                html += '<tr>'
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
                /*html += '<td>'
                html += '<a href="#" class="btn btn-sm btn-outline-success" onclick="pagar_documento('+local_db[i]['id']+');" title="pagar documento"><i class="fas fa-check-circle"></a>'
                html += '</td>'*/
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

            document.getElementById('total_saldo').value = total_saldo;
            document.getElementById('total_pago').value = total_pago;

            $("#tblDocumentos tbody tr").remove();
            $('#tblDocumentos tbody').append(html);
            $("#tblDocumentos tfoot tr").remove();
            $('#tblDocumentos tfoot').append(htmlf);
        }

        function actualizarTablaPago(){
            var pago_db = JSON.parse(localStorage.pago_db);
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
                /*html += '<td style="text-align: right;">'
                html += '<button class="btn btn-sm btn-danger" onclick="eliminar_registro_pago('+i+');" title="Eliminar Registro"><i class="fa fa-trash-alt"></i></button>'
                html += '</td>'*/
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

        function fn_anular(){
            var recibo_id = document.getElementById('recibo_id').value;
            $("#anulacionModal").modal('show');
        }
    </script>
@endsection