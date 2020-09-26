@extends('admin.layout')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('titulo')
	Nota de Crédito
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
                    <a href="#" class="btn btn-sm btn-success" title="Grabar" onclick="fn_grabar_nc(); return false;"><i class="fas fa-save"></i></a>
                    <!--<a href="#" onclick="fn_salir(); return false;" class="btn btn-sm btn-danger" title="Regresar a lista de Documentos"><i class="fas fa-sign-out-alt"></i></a>-->
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
                        <div class="form-group form-control-sm clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="factura" name="tipodocumento" value="1" checked tabindex="3">
                                <label for="factura">Factura</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="Debito" name="tipodocumento" value="3" tabindex="4">
                                <label for="Debito">Nota de Debito</label>
                            </div>
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
                    <div class="col-md-3 mb-1">
                        <div class="input-group mb-1 input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Serie</label>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center" id="serie_afecta" name="serie_afecta" style="text-transform: uppercase;" tabindex="5">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 offset-md-1">
                        <div class="input-group mb-1 input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Serie</label>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center" id="serie" name="serie" style="text-transform: uppercase;" onchange="fn_resolucion_x_serie(); return false;" @if($caja->editar_documento == 'N') then disabled @endif tabindex="1" autofocus>
                        </div>
                    </div>
                    <div class="col-md-3 mb-1">
                        <div class="input-group mb-1 input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Correlativo</label>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center" id="documento_afecto" name="documento_afecto" tabindex="6">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 offset-md-1">
                        <div class="input-group mb-1 input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Correlativo</label>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center" id="correlativo" name="correlativo" @if($caja->editar_documento == 'N') then disabled @endif tabindex="2">
                        </div>
                    </div>
                    <div class="col-md-3 mb-1">
                        <a href="#" class="btn btn-sm btn-block btn-secondary" onclick="fn_buscar(); return false;" title="Buscar" tabindex="6"><i class="fas fa-search"></i></a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="mb-2 col-md-5 offset-md-1">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="paciente_id">Paciente</label>
                            </div>
                            <select class="custom-select  custom-select-sm select2 select2bs4" id="paciente_id"  name="paciente_id" disabled>
                                <option value="">Seleccionar...</option>
                                @foreach($pacientes as $p)
                                    <option value="{{ $p->id}}">{{ $p->nombre_completo}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <label class="input-group-text">N.I.T.</label>
                            </div>
                            <input type="text" class="form-control text-center" id="nit" name="nit" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 offset-md-1 mb-2">
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Nombre</label>
                            </div>
                            <input type="text" class="form-control text-center" id="nombre" name="nombre" disabled>
                        </div>
                    </div>
                    <div class="col-md-5 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Dirección</label>
                            </div>
                            <input type="text" class="form-control text-center" id="direccion" name="direccion" disabled>
                        </div>
                    </div>
                </div>
                <div class="card card-info">
                    <div class="card-header text-center">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <h6>Detalle</h6>
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
                                                <th>Descripción</th>
                                                <th>Precio Unitario</th>
                                                <th>Precio total</th>
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
                <!--<div class="modal-body"> -->
                    <div class="card card-navy">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-9">
                                    Agregar Producto
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <a href="#" class="btn btn-sm btn-success" title="Grabar" onclick="fn_grabar_local(); return false;"><i class="fas fa-save"></i></a>
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
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="producto_id"  name="producto_id" onchange="fn_trae_descripcion(); return false;">
                                            <option value="">Seleccionar...</option>
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
                                    <input type="number" class="form-control" id="cantidad" name="cantidad" onchange="fn_precio_total(); return false;" style="text-align: right;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Prc. Unitario</span>
                                    </div>
                                    <input type="number" class="form-control" id="precio_unitario" name="precio_unitario" onchange="fn_precio_total(); return false;" style="text-align: right;">
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
                <!--</div>-->
            </div>
        </div>
    </div>
    <!-- /modal -->
    <!-- forma de pago -->
    <div class="modal fade" id="fpagoModal" tabindex="-1" role="dialog" aria-labelledby="mensajeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card card-navy">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9">
                                Forma de Pago
                            </div>
                            <div class="col-md-3" style="text-align: right;">
                                <a href="#" class="btn btn-sm btn-success" title="Grabar" onclick="fn_fpago_local(); return false;"><i class="fas fa-save"></i></a>
                                <!--<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" title="Salir"><i class="fas fa-sign-out-alt"></i></button>-->
                                <button type="button" class="btn btn-sm btn-danger" title="Salir" onclick="cerrar_modal('fpagoModal'); return false;"><i class="fas fa-sign-out-alt"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10 offset-md-1 text-center">
                                <div class="form-group form-control-sm clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="efectivo" name="fpago" value="E" checked>
                                        <label for="efectivo">Efectivo</label>
                                    </div>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="cheque" name="fpago" value="B" tabindex="9">
                                        <label for="cheque">Cheque</label>
                                    </div>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="tarjeta" name="fpago" value="T" tabindex="9">
                                        <label for="tarjeta">Tarjeta</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="banco" class="row">
                            <div class="col-md-10 offset-md-1 mb-1">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" for="banco_id">Banco</span>
                                    </div>
                                    <select class="custom-select custom-select-sm select2 select2bs4" id="banco_id"  name="banco_id">
                                        <option value="">Seleccionar...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="tarjeta1" class="row">
                            <div class="col-md-10 offset-md-1 mb-1">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" for="tarjeta_id">Tarjeta</span>
                                    </div>
                                    <select class="custom-select custom-select-sm select2 select2bs4" id="tarjeta_id"  name="tarjeta_id">
                                        <option value="">Seleccionar...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="cuenta" class="row">
                            <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">No. Cuenta</span>
                                </div>
                                <input type="text" class="form-control" id="cuenta_no" name="cuenta_no" value="{{ old('cuenta_no')}}">
                            </div>
                        </div>
                        <div id="documento" class="row">
                            <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">No. Documento</span>
                                </div>
                                <input type="text" class="form-control" id="documento_no" name="documento_no" value="{{ old('documento_no')}}">
                            </div>
                        </div>
                        <div id="autorizacion" class="row">
                            <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">No. Autorización</span>
                                </div>
                                <input type="text" class="form-control" id="autoriza_no" name="autoriza_no" value="{{ old('autoriza_no')}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Monto</span>
                                </div>
                                <input type="number" class="form-control" id="monto" name="monto" value="{{ old('monto')}}" style="text-align: right;" autofocus>
                            </div>
                        </div>
                    </div>
                </div>
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
        var forma_pago = 'E';

        $(function(){
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
        });

        function fn_buscar(){
            if (document.getElementById("factura").checked == true) {
                var tipodocumento_id       = '1';
            }else{
                var tipodocumento_id       = '3';
            }
            var serie            = document.getElementById('serie_afecta').value;
            var correlativo      = document.getElementById('documento_afecto').value;
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('nc_doctos_aplicar')}}",
                method: "POST",
                data: { tipodocumento_id  : tipodocumento_id,
                        serie             : serie,
                        correlativo       : correlativo},
                success: function(response){
                    var info = response;
                    if (info['resultado'] == 0) {
                        document.getElementById('paciente_id').value = info['encabezado']['paciente_id'];
                        $('#paciente_id').change();
                        document.getElementById('nit').value = info['encabezado']['nit'];
                        document.getElementById('nombre').value = info['encabezado']['nombre'];
                        document.getElementById('direccion').value = info['encabezado']['direccion'];
                        for (var i = 0; i < info['detalle'].length; i++) {
                            var linea = {
                                id               : i,
                                cargo_detalle_id : info['detalle'][i]['admision_cargo_detalle_id'],
                                producto_id      : info['detalle'][i]['producto_id'],
                                descripcion      : info['detalle'][i]['descripcion'],
                                cantidad         : info['detalle'][i]['cantidad'],
                                precio_unitario  : info['detalle'][i]['precio_unitario'],
                                precio_bruto     : info['detalle'][i]['precio_bruto'],
                                descuento        : info['detalle'][i]['descuento'],
                                recargo          : info['detalle'][i]['recargo'],
                                precio_neto      : info['detalle'][i]['precio_neto'],
                                precio_base      : info['detalle'][i]['precio_base'],
                                precio_impuesto  : info['detalle'][i]['precio_impuesto'],
                                estado           : info['detalle'][i]['estado']
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
                    } else{
                        swal({
                            title: 'Error !!!',
                            text: info.mensaje,
                            type: 'error',
                        });
                    }

                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function fn_salir(){
            window.location.replace("http://localhost:8888/granai/public/ventas/listado_notas_credito/");
        }

        window.addEventListener('load', function(){
            var local_db = [];
            localStorage.clear(local_db);
            localStorage.local_db     = JSON.stringify(local_db);
            var caja_id               = document.getElementById('caja_id').value;
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
                        document.getElementById('serie_afecta').focus();
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }else {
                document.getElementById('serie').focus();
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
                total_detalle += parseFloat(local_db[i]['precio_neto']);
                html += '<tr>'
                html += '<td>'
                html += local_db[i]['cantidad']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['descripcion']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['precio_unitario']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['precio_neto']
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
                        /*$('.modal_texto').html();
                        $("#mensajeModal").modal('show');*/
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

        function fn_precio_total(){
            var cantidad = document.getElementById('cantidad').value;
            var precio_unitario = document.getElementById('precio_unitario').value;
            if (cantidad == '' || precio_unitario == '') {
                document.getElementById('precio_total').value = 0;
            }else{
                document.getElementById('precio_total').value = cantidad * precio_unitario;
            }
        }


        function fn_grabar_nc(){
            var local_db          = JSON.parse(localStorage.local_db);
            var paciente_id       = document.getElementById('paciente_id').value;
            var resolucion_id     = document.getElementById('resolucion_id').value;
            var tipo_documento_id = document.getElementById('tipo_documento_id').value;
            var fecha             = document.getElementById('fecha_emision').value;
            var serie             = document.getElementById('serie').value;
            var correlativo       = document.getElementById('correlativo').value;
            if (document.getElementById("factura").checked == true) {
                var tipo_documento_afecto_id       = '1';
            }else{
                var tipo_documento_afecto_id       = '3';
            }
            var serie_afecta      = document.getElementById('serie_afecta').value;
            var documento_afecto  = document.getElementById('documento_afecto').value;
            var nit               = document.getElementById('nit').value;
            var nombre            = document.getElementById('nombre').value;
            var direccion         = document.getElementById('direccion').value;
            var condicion         = 0;
            error = false;
            checkSubmit();

            /*for (var i = 0; i < local_db.length; i++) {
                console.log(local_db[i]);
            }*/

            if (!error && local_db.length > 0) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('grabar_nc')}}",
                    method: "POST",
                    data: { arreglo           : JSON.stringify(local_db),
                            resolucion_id     : resolucion_id,
                            tipo_documento_id : tipo_documento_id,
                            fecha_emision     : fecha,
                            serie             : serie,
                            correlativo       : correlativo,
                            condicion         : condicion,
                            nit               : nit,
                            nombre            : nombre,
                            direccion         : direccion,
                            paciente_id       : paciente_id,
                            tipo_documento_afecto_id : tipo_documento_afecto_id,
                            serie_afecta      : serie_afecta,
                            documento_afecto  : documento_afecto
                    },
                    success: function(response){
                        var info = response;
                        if (info.estado == '1') {
                            swal({
                                title: 'Trabajo Finalizado !!!',
                                text: info.mensaje,
                                type: 'success',
                            }, function(){
                                window.location.href = "http://localhost:8888/granai/public/ventas/nota_credito_editar/"+info.nota_id;
                            });
                        }else {
                            swal({
                                title: 'Error !!!',
                                text: info.mensaje,
                                type: 'error',
                            });
                        }
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
        }

        function checkSubmit() {
            var local_db  = JSON.parse(localStorage.local_db);
            var resolucion_id = document.getElementById('resolucion_id').value;
            var tipo_documento_id = document.getElementById('tipo_documento_id').value;
            var fecha     = document.getElementById('fecha_emision').value;
            var serie     = document.getElementById('serie').value;
            var correlativo = document.getElementById('correlativo').value;
            var nit       = document.getElementById('nit').value;
            var nombre    = document.getElementById('nombre').value;
            var direccion = document.getElementById('direccion').value;
            var total_detalle = 0;

            for (var i = 0; i < local_db.length; i++) {
                total_detalle += parseFloat(local_db[i]['precio_total']);
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

            if (cadena.length > 0) {
                error = true;
                swal({
                    title: 'Error !!!',
                    text: 'Para grabar debe completar los campos '+cadena,
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
                        window.location.href = "{{ route('nc_listado') }}";
                    }
                }
            );
        }
    </script>
@endsection