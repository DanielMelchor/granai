@extends('admin.layout')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('titulo')
	Factura
@endsection
@section('subtitulo')
	Agregar
@endsection

@section('contenido')
    <div class="row">
        <div class="col">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error}}</li>
                            <button type="button" class="close" data-dismiss="alert" arial-label="Close"><span aria-hidden="true">x</span>
                            </button>
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
                    <a href="#" class="btn btn-sm btn-success" title="Grabar" onclick="fn_grabar_factura(); return false;"><i class="fas fa-save"></i></a>
                    <a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Admisiones" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </div>
        <form class="form-horizontal">
            <div class="card-body">
                <input type="hidden" id="tipo_documento_id" name="tipo_documento_id" value="{{ $documento->id }}">
                <input type="hidden" id="admision_id" name="admision_id" value="{{ $admision_id }}">
                <input type="hidden" id="resolucion_id" name="resolucion_id">
                <input type="hidden" id="caja_id" name="caja_id" value="{{ $caja->id }}">
                <input type="hidden" id="caja_editar_documento" name="caja_editar_documento" value="{{ $caja->editar_documento}}">
                <input type="hidden" id="factura_estado" name="factura_estado" value="P">
                <div class="row">
                    <div class="col-md-3 offset-md-1">
                        <div class="input-group mb-1 input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Documento</label>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center" id="documento_descripcion" name="documento_descripcion" value="{{ $documento->descripcion }}" disabled>
                        </div>
                    </div>
                    <div class="mb-1 col-md-5">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="paciente_id">Paciente</label>
                            </div>
                            <select class="custom-select  custom-select-sm select2 select2bs4" id="paciente_id"  name="paciente_id" tabindex="8" onchange="fn_paciente_facturacion(); return false;">
                                <option value="">Seleccionar...</option>
                                @foreach($pacientes as $p)
                                    <option value="{{ $p->id}}" @if($p->id == $paciente_id) then selected @endif>{{ $p->nombre_completo}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 offset-md-1">
                        <div class="input-group mb-1 input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Fecha</label>
                            </div>
                            <input type="date" class="form-control form-control-sm text-center" id="fecha_emision" name="fecha_emision" value="{{ $hoy }}" @if($caja->editar_documento == 'N') then disabled @endif tabindex="1">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group mb-1 input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text">N.I.T.</label>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center" id="nit" name="nit" required tabindex="9">
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
                    <div class="col-md-5">
                        <div class="input-group mb-1 input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Nombre</label>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center" id="nombre" name="nombre" required tabindex="10">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 offset-md-1">
                        <div class="input-group mb-1 input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Correlativo</label>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center" id="correlativo" name="correlativo" @if($caja->editar_documento == 'N') then disabled @endif tabindex="3">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group mb-1 input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Dirección</label>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center" id="direccion" name="direccion" required tabindex="11">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 offset-md-1">
                        <div class="form-group form-control-sm clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="contado" name="condicion" value="0" checked tabindex="4">
                                <label for="contado">Contado</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="credito" name="condicion" value="1" tabindex="5">
                                <label for="credito">Credito</label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card card-secondary">
                    <div class="card-header text-center">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <h6>Detalle</h6>
                            </div>
                            <div class="col-md-1" style="text-align: right;">
                                <button type="button" id="btnAgregarProducto" name="btnAgregarProducto" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#cargoModal" title="Agregar Producto">
                                    <i class="fas fa-plus-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="table-responsive">
                                    <table id="tblDetalle" class="table table-sm table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>Cantidad</th>
                                                <th>Descripcion</th>
                                                <th>Precio Unitario</th>
                                                <th>Precio Total</th>
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
                <hr>
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
    <!-- Modal -->
    <div class="modal fade" id="cargoModal" tabindex="-1" role="dialog" aria-labelledby="cargoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="cargoForm" name="cargoForm" action="#">
                    <div class="card card-navy">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-9">
                                    Agregar Producto
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                                    <!--<a href="#" class="btn btn-sm btn-success" title="Grabar" onclick="fn_grabar_local(); return false;"><i class="fas fa-save"></i></a>-->
                                    <!--<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" title="Salir"><i class="fas fa-sign-out-alt"></i></button>-->
                                    <button type="button" class="btn btn-sm btn-danger" title="Salir" onclick="cerrar_modal('cargoModal'); return false;"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-1 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" for="producto_id">Producto</span>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="producto_id"  name="producto_id" onchange="fn_trae_descripcion(); return false;" required>
                                            <option value="">Seleccionar...</option>
                                            @foreach($productos as $p)
                                                <option value="{{ $p->id}}">{{ $p->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Descripción</span>
                                    </div>
                                    <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Cantidad</span>
                                    </div>
                                    <input type="number" class="form-control" id="cantidad" name="cantidad" onchange="fn_precio_total(); return false;" style="text-align: right;" value="1" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Prc. Unitario</span>
                                    </div>
                                    <input type="number" class="form-control" id="precio_unitario" name="precio_unitario" onchange="fn_precio_total(); return false;" style="text-align: right;" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Prc. Total</span>
                                    </div>
                                    <input type="number" class="form-control" id="precio_total" name="precio_total" style="text-align: right;" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /modal -->
    <!-- forma de pago -->
    <div class="modal fade" id="fpagoModal" tabindex="-1" role="dialog" aria-labelledby="mensajeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="fpagoForm" name="fpagoForm" action="#">
                    <div class="card card-navy">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-9">
                                    Forma de Pago
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                                    <!--<a href="#" class="btn btn-sm btn-success" title="Grabar" onclick="fn_fpago_local(); return false;"><i class="fas fa-save"></i></a>-->
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
                                            <label class="input-group-text" for="fpago_id">Forma de pago</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="fpago_id"  name="fpago_id" onchange="verifica_campos(); return false;" required>
                                            <option value="" selected>Seleccionar...</option>
                                            @foreach($formas_pago as $fp)
                                                <option value="{{ $fp->id}}">{{ $fp->descripcion }}</option>
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
                            <div id="recibos_con_saldo" class="row">
                                <div class="col-md-10 offset-md-1 mb-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="tarjeta_id">Recibo</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="recibo_id"  name="recibo_id">
                                            <option value="">Seleccionar...</option>
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
                                    <input type="number" class="form-control" id="monto" name="monto" value="{{ old('monto')}}" style="text-align: right;" autofocus required>
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
        var nlinea = 0;
        var nlineaPago = 0;
        var total_detalle = 0;
        var linea = {};
        var statSend = false;
        var condicion = 0;
        var forma_pago = 1;

        $(function(){
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
        });

        $(function(){
            $("#fpagoForm").submit(function(){
                fn_fpago_local();
                return false;
            })
        });

        $(function(){
            $("#cargoForm").submit(function(){
                fn_grabar_local();
                return false;
            })
        });


        function fn_paciente_facturacion(){
            var paciente_id = document.getElementById('paciente_id').value;
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('datos_facturacion')}}",
                method: "POST",
                data: { paciente_id  : paciente_id},
                success: function(response){
                    var info = response;
                    document.getElementById('nit').value = info.factura_nit;
                    document.getElementById('nombre').value = info.factura_nombre;
                    document.getElementById('direccion').value = info.factura_direccion;
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function fn_forma_pago(){
            document.getElementById('fpago_id').value     = '';
            $('#fpago_id').change();
            document.getElementById('banco_id').value     = '';
            document.getElementById('cuenta_no').value    = '';
            document.getElementById('documento_no').value = '';
            document.getElementById('autoriza_no').value  = '';
            document.getElementById('monto').value        = '';
            document.getElementById('recibo_id').value    = '';
            $('#recibo_id').change();
            $("#banco").hide();
            $("#cuenta").hide();
            $("#tarjeta1").hide();
            $("#recibos_con_saldo").hide();
            $("#documento").hide();
            $("#autorizacion").hide();
            $("#monto").prop('disabled', false);
            $("#fpagoModal").modal('show');
        }

        function verifica_campos(){
            var fpago_id = document.getElementById('fpago_id').value;
            var paciente_id = document.getElementById('paciente_id').value;
            if (fpago_id != '') {
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('campos_requeridos')}}",
                    method: "POST",
                    data: { fpago_id  : fpago_id},
                    success: function(response){
                        $("#banco").hide();
                        $("#cuenta").hide();
                        $("#tarjeta1").hide();
                        $("#documento").hide();
                        $("#autorizacion").hide();
                        $("#recibos_con_saldo").hide();
                        $('#banco_id').prop("required", false);
                        $("#cuenta_no").prop('required', false);
                        $("#documento_no").prop('required', false);
                        $("#autoriza_no").prop('required', false);
                        $("#recibo_id").prop('required', false);
                        $("#monto").prop('disabled', false);
                        if (response.banco == 'S') {
                            $("#banco").show();
                            $('#banco_id').prop("required", true);
                        }
                        if (response.casa == 'S') {
                            $("#tarjeta1").show();
                            $("#tarjeta_id").prop('required', true);
                        }
                        if (response.cuenta == 'S') {
                            $("#cuenta").show();
                            $('#cuenta_no').prop("required", true);
                        }
                        if (response.documento == 'S') {
                            $("#documento").show();
                            $("#documento_no").prop('required', true);
                        }
                        if (response.autorizacion == 'S') {
                            $("#autorizacion").show();
                            $("#autoriza_no").prop('required', true);
                        }
                        if (response.recibos == 'S') {
                            $("#recibos_con_saldo").show();
                            $("#recibo_id").prop('required', true);
                            $("#monto").prop('disabled', true);
                            $.ajax({
                                headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: "{{route('recibos_con_saldo')}}",
                                method: "POST",
                                data: { paciente_id  : paciente_id},
                                success: function(response){
                                    var html = '<option value="" >Seleccionar...</option>';
                                    for (var i = 0; i < response.length; i++) {
                                        html += '<option value="'+response[i]['id']+'" >'+response[i]['serie']+'-'+response[i]['correlativo']+'</option>';
                                        $('#recibo_id').html(html);
                                    }
                                },
                                error: function(error){
                                    console.log(error);
                                }
                            });
                        }
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
        }

        $("#recibo_id").change(function(){
            var recibo_id = document.getElementById('recibo_id').value;
            if (recibo_id != '') {
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('trae_saldo_x_recibo')}}",
                    method: "POST",
                    data: { recibo_id  : recibo_id},
                    success: function(response){
                        document.getElementById('monto').value = parseFloat(response.total);
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
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
            var admision_id           = document.getElementById('admision_id').value;
            var caja_editar_documento = document.getElementById('caja_editar_documento').value;
            var tipo_documento_id     = document.getElementById('tipo_documento_id').value;
            var html = '';
            
            /*=================================================================
            Verifica si el usuario puede modificar el numero de serie y correlativo
            de factura
            =================================================================*/
            if (caja_editar_documento == 'N') {
                document.getElementById('paciente_id').focus();
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('resolucion_factura_x_caja')}}",
                    method: "POST",
                    data: { caja_id  : caja_id,
                            tipo_documento_id : tipo_documento_id},
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
            
            /*=================================================================
            Si se envia un numero de admision, se busca datos de facturacion del
            paciente y cargos asignados pendientes de facturar
            =================================================================*/
            if (admision_id != 0) {
                document.getElementById('paciente_id').disabled = true;
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('trae_datos_para_factura')}}",
                    method: "POST",
                    data: { admision_id  : admision_id},
                    success: function(response){
                        var info = response;
                        document.getElementById('nit').value = info['encabezado']['factura_nit'];
                        document.getElementById('nombre').value = info['encabezado']['factura_nombre'];
                        document.getElementById('direccion').value = info['encabezado']['factura_direccion'];

                        for (var i = 0; i < info['cargos'].length; i++) {
                            var linea = {
                                id               : nlinea,
                                cargo_detalle_id : info['cargos'][i]['cargo_detalle_id'],
                                producto_id      : info['cargos'][i]['producto_id'],
                                producto_descripcion : info['cargos'][i]['producto_descripcion'],
                                descripcion      : info['cargos'][i]['descripcion'],
                                cantidad         : info['cargos'][i]['cantidad'],
                                precio_unitario  : info['cargos'][i]['valor'],
                                precio_total     : info['cargos'][i]['valor']
                            };
                            if(!localStorage.local_db){
                                localStorage.local_db = JSON.stringify([linea]);
                            }
                            else{
                                local_db = JSON.parse(localStorage.local_db);
                                local_db.push(linea);
                                localStorage.local_db = JSON.stringify(local_db);
                            }
                        }

                        actualizarTabla();
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
        });
        //========================================================================
        // actualizar tabla detalle de articulos
        //========================================================================

        function actualizarTabla(){
            var local_db = JSON.parse(localStorage.local_db);
            var html = '';
            var html1 = '';
            var total_detalle = 0;
            var error = false;
            
            for(var i = 0; i < local_db.length; i++){
                total_detalle += parseFloat(local_db[i]['precio_total']);
                html += '<tr>'
                html += '<td>'
                html += local_db[i]['cantidad']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['producto_descripcion']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['precio_unitario']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['precio_total']
                html += '</td>'
                html += '<td style="text-align: right;">'
                html += '<button class="btn btn-sm btn-danger" onclick="eliminar_registro('+i+');" title="Eliminar Registro"><i class="fa fa-trash-alt"></i></button>'
                html += '</td>'
                html += '</tr>'
            }
            html1 += '<tr>'
            html1 += '<td></td>'
            html1 += '<td></td>'
            html1 += '<td><strong>Total Documento</strong></td>'
            html1 += '<td style="text-align:center;">'+total_detalle+'</td>'
            html1 += '</tr>'

            $("#tblDetalle tfoot tr").remove();
            $("#tblDetalle tbody tr").remove();
            $('#tblDetalle tbody').append(html);
            $('#tblDetalle tfoot').append(html1);
        }

        function eliminar_registro(id){
            total = 0;
            var local_db = JSON.parse(localStorage.local_db);
            for(var i = 0; i < local_db.length; i++){
                if (id == local_db[i]['id']) {
                    local_db.splice(i, 1);
                    localStorage.local_db = JSON.stringify(local_db);
                }
            }
            actualizarTabla();
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

        //========================================================================
        // actualizar tabla de pagos
        //========================================================================

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

            $("#tblPago tfoot tr").remove();
            $("#tblPago tbody tr").remove();
            $('#tblPago tbody').append(html);
            $('#tblPago tfoot').append(html1);
        }

        function fn_resolucion_x_serie(){
            var caja_id  = document.getElementById('caja_id').value;
            var serie    = document.getElementById('serie').value;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('trae_resolucion_x_serie')}}",
                method: "POST",
                data: { caja_id  : caja_id,
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

        function fn_trae_descripcion(){
            var producto = document.getElementById("producto_id");
            var producto_id = producto.options[producto.selectedIndex].value;
            var producto_descripcion = producto.options[producto.selectedIndex].text;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('descripcion')}}",
                method: "POST",
                data: { cod  : producto_id},
                success: function(response){
                    var info = response;
                    document.getElementById('descripcion').value = info;
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function fn_precio_total(){
            var cantidad = document.getElementById('cantidad').value;
            var precio_unitario = document.getElementById('precio_unitario').value;
            if (cantidad == '' || precio_unitario == '') {
                document.getElementById('precio_total').value = 0;
            }else{
                document.getElementById('precio_total').value = cantidad * precio_unitario;
            }
        }

        function fn_grabar_local(){
            var producto = document.getElementById('producto_id');
            var producto_id = producto.options[producto.selectedIndex].value;
            var producto_descripcion = producto.options[producto.selectedIndex].text;
            var descripcion = document.getElementById('descripcion').value;
            var cantidad    = document.getElementById('cantidad').value;
            var precio_unitario = document.getElementById('precio_unitario').value;
            var precio_total = document.getElementById('precio_total').value;
            var linea = {
                id               : nlinea,
                cargo_detalle_id : null,
                producto_id      : producto_id,
                producto_descripcion : producto_descripcion,
                descripcion      : descripcion,
                cantidad         : cantidad,
                precio_unitario  : precio_unitario,
                precio_total     : precio_total
            };

            if(!localStorage.local_db){
                localStorage.local_db = JSON.stringify([linea]);
            }
            else{
                local_db = JSON.parse(localStorage.local_db);
                local_db.push(linea);
                localStorage.local_db = JSON.stringify(local_db);
            }
            actualizarTabla();
            $('#cargoModal').modal('hide');

            nlinea += 1;
        }

        function fn_fpago_local(){
            forma_pago = document.getElementById('fpago_id').value;
            var recibo_id = 0;
            if (forma_pago == 1) {
                var entidad_id            = null;
                var entidad_descripcion   = '';
                var cuenta_no             = '';
                var documento_no          = '';
                var autoriza_no           = '';
                var monto                 = document.getElementById('monto').value;
                var formapago_descripcion = 'Efectivo';
            }
            if (forma_pago == 2) {
                var banco        = document.getElementById('banco_id');
                var entidad_id   = banco.options[banco.selectedIndex].value;
                var entidad_descripcion = banco.options[banco.selectedIndex].text;
                var cuenta_no    = document.getElementById('cuenta_no').value;
                var documento_no = document.getElementById('documento_no').value;
                var autoriza_no  = document.getElementById('autoriza_no').value;
                var monto        = document.getElementById('monto').value;
                var formapago_descripcion = 'Cheque';
            }
            if (forma_pago == 3) {
                var tarjeta = document.getElementById('tarjeta_id');
                var entidad_id = tarjeta.options[tarjeta.selectedIndex].value;
                var entidad_descripcion = tarjeta.options[tarjeta.selectedIndex].text;
                var cuenta_no     = document.getElementById('cuenta_no').value;
                var documento_no  = document.getElementById('documento_no').value;
                var autoriza_no   = document.getElementById('autoriza_no').value;
                var monto         = document.getElementById('monto').value;
                var formapago_descripcion = 'Tarjeta';
            }
            if (forma_pago == 4) {
                var banco        = document.getElementById('banco_id');
                var entidad_id   = banco.options[banco.selectedIndex].value;
                var entidad_descripcion = banco.options[banco.selectedIndex].text;
                var cuenta_no             = '';
                var documento_no          = document.getElementById('documento_no').value;
                var autoriza_no           = '';
                var monto                 = document.getElementById('monto').value;
                var formapago_descripcion = 'Transferencia Bancaria';
            }
            if (forma_pago == 5) {
                var recibo = document.getElementById('recibo_id');
                var recibo_id = recibo.options[recibo.selectedIndex].value;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('forma_pago_recibo')}}",
                    method: "POST",
                    data: { recibo_id  : recibo_id},
                    success: function(response){
                        for (var i = 0; i < response.length; i++) {
                            var linea = {
                                id                    : nlineaPago,
                                forma_pago            : response[i]['forma_pago'],
                                formapago_descripcion : response[i]['descripcion'],
                                entidad_id            : response[i]['banco_id'],
                                entidad_descripcion   : response[i]['nombre'],
                                recibo_id             : response[i]['maestro_pago_id'],
                                cuenta_no             : response[i]['cuenta_no'],
                                documento_no          : response[i]['documento_no'],
                                autoriza_no           : response[i]['autoriza_no'],
                                monto                 : response[i]['monto']
                            }

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
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }

            if (forma_pago != 5) {
                var linea = {
                    id               : nlineaPago,
                    forma_pago       : forma_pago,
                    formapago_descripcion : formapago_descripcion,
                    entidad_id            : entidad_id,
                    entidad_descripcion   : entidad_descripcion,
                    recibo_id        : 0,
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
        }

        function fn_grabar_factura(){
            var local_db          = JSON.parse(localStorage.local_db);
            var pago_db           = JSON.parse(localStorage.pago_db);
            var admision_id       = document.getElementById('admision_id').value;
            var paciente_id       = document.getElementById('paciente_id').value;
            var resolucion_id     = document.getElementById('resolucion_id').value;
            var tipo_documento_id = document.getElementById('tipo_documento_id').value;
            var fecha             = document.getElementById('fecha_emision').value;
            var serie             = document.getElementById('serie').value;
            var correlativo       = document.getElementById('correlativo').value;
            var nit               = document.getElementById('nit').value;
            var nombre            = document.getElementById('nombre').value;
            var direccion         = document.getElementById('direccion').value;
            error = false;
            checkSubmit();

            if (!error && local_db.length > 0) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('grabar_factura')}}",
                    method: "POST",
                    data: { arreglo           : JSON.stringify(local_db),
                            pagos             : JSON.stringify(pago_db),
                            resolucion_id     : resolucion_id,
                            tipo_documento_id : tipo_documento_id,
                            fecha_emision     : fecha,
                            serie             : serie,
                            correlativo       : correlativo,
                            condicion         : condicion,
                            nit               : nit,
                            nombre            : nombre,
                            direccion         : direccion,
                            admision_id       : admision_id,
                            paciente_id       : paciente_id
                    },
                    success: function(response){
                        var info = response;
                        swal({
                            title: 'Trabajo Finalizado !!!',
                            text: info.respuesta,
                            type: 'success',
                        }, function(){
                            window.location.href = asset+"ventas/editar_factura/"+info.factura_id+"/0";
                        });
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
        }

        function checkSubmit() {
            var local_db  = JSON.parse(localStorage.local_db);
            var pago_db   = JSON.parse(localStorage.pago_db);
            var resolucion_id = document.getElementById('resolucion_id').value;
            var tipo_documento_id = document.getElementById('tipo_documento_id').value;
            var fecha     = document.getElementById('fecha_emision').value;
            var serie     = document.getElementById('serie').value;
            var correlativo = document.getElementById('correlativo').value;
            var nit       = document.getElementById('nit').value;
            var nombre    = document.getElementById('nombre').value;
            var direccion = document.getElementById('direccion').value;
            var total_detalle = 0;
            var total_pago    = 0;

            for (var i = 0; i < local_db.length; i++) {
                total_detalle += parseFloat(local_db[i]['precio_total']);
            }

            for (var i = 0; i < pago_db.length; i++) {
                total_pago += parseFloat(pago_db[i]['monto']);
            }
            
            var cadena = '';

            if (tipo_documento_id == '') {
                cadena = cadena + ' tipo documento,';
            }
            if(fecha == ''){
                cadena = cadena + ' fecha,';
            }
            if (serie == '') {
                cadena = cadena + ' Serie,';
            }
            if (correlativo == '') {
                cadena = cadena + ' correlativo';
            }
            if (nit == '') {
                cadena = cadena + ' nit,';
            }
            if (nombre == '') {
                cadena = cadena + ' nombre,';
            }
            if (direccion == '') {
                cadena = cadena + ' direccion,';
            }
            if (local_db.length == 0) {
                cadena = cadena + ' Detalle de factura';
            }
            if (pago_db.length == 0  && condicion == 0) {
                cadena = cadena + 'Forma de pago de documento';
            }
            if (total_detalle != total_pago && condicion == 0) {
                cadena = cadena + 'el total del documento '+total_detalle+' no concuerda con el total pagado '+total_pago+' '+condicion;
            }


            if (cadena.length > 0) {
                error = true;
                swal({
                    title: 'Error !!!',
                    text: 'Para Guardar debe completar '+cadena,
                    type: 'error',
                });
            }else{
                if (!statSend) {
                    statSend = true;
                    return true;
                } else {
                    alert("El formulario ya se esta enviando...");
                    return false;
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
                        window.location.href = "{{ route('documentos_listado') }}";
                    }
                }
            );
        }
    </script>
@endsection