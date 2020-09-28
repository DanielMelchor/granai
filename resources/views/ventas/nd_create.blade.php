@extends('admin.layout')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('titulo')
	Nota de Débito
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
    <form class="form-horizontal" id="ndForm" name="ndForm" action="#">
        <div class="card card-navy">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-2 offset-md-10" style="text-align: right;">
                        <button class="btn btn-sm btn-success"><i class="fas fa-save" title="Guardar"></i></button>
                        <!--<a href="{{ route('nd_listado') }}" class="btn btn-sm btn-danger" title="Regresar a lista de notas de debito"><i class="fas fa-sign-out-alt"></i></a>-->
                        <a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Admisiones" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" id="tipo_documento_id" name="tipo_documento_id" value="{{ $documento->id }}">
                <input type="hidden" id="resolucion_id" name="resolucion_id">
                <input type="hidden" id="caja_id" name="caja_id" value="{{ $caja->id }}">
                <input type="hidden" id="caja_editar_documento" name="caja_editar_documento" value="{{ $caja->editar_documento}}">
                <input type="hidden" id="nd_estado" name="nd_estado" value="P">
                <input type="hidden" id="paciente_id" name="paciente_id">
                <input type="hidden" id="recibo_id" name="recibo_id">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-5 offset-md-1 mb-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Documento</span>
                                    </div>
                                    <input type="text" class="form-control text-center" id="documento_descripcion" name="documento_descripcion" value="{{ $documento->descripcion }}" disabled>
                                </div>
                            </div>
                            <div class="mb-1 col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" for="banco_id">Banco</span>
                                    </div>
                                    <select class="custom-select select2 select2bs4" id="banco_id" name="banco_id" autofocus required tabindex="2">
                                        <option value="">Seleccionar...</option>
                                        @foreach($bancos as $b)
                                            <option value="{{ $b->id }}">{{ $b->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 offset-md-1 mb-1">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Fecha</span>
                                    </div>
                                    <input type="date" class="form-control form-control-sm text-center" id="fecha_emision" name="fecha_emision" value="{{ $hoy }}" @if($caja->editar_documento == 'N') then disabled @endif>
                                </div>
                            </div>
                            <div class="col-md-6 mb-1">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">No. Cheque</span>
                                    </div>
                                    <input type="number" class="form-control form-control-sm" id="cheque" name="cheque" style="text-align: right;" required tabindex="3">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 offset-md-1 mb-1">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Serie</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm text-center" id="serie" name="serie" style="text-transform: uppercase;" onchange="fn_resolucion_x_serie(); return false;" @if($caja->editar_documento == 'N') then disabled @endif tabindex="2" autofocus tabindex="0">
                                </div>
                            </div>
                            <div class="col-md-6 mb-1">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Paciente</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm text-center" id="paciente_nombre" name="paciente_nombre" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 offset-md-1 mb-1">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Correlativo</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm text-center" id="correlativo" name="correlativo" @if($caja->editar_documento == 'N') then disabled @endif tabindex="1">
                                </div>
                            </div>
                            <div class="col-md-6 mb-1">
                                <a href="#" class="btn btn-sm btn-block btn-secondary" onclick="fn_buscar(); return false;" title="Buscar" tabindex="4"><i class="fas fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="input-group input-group-sm col-md-5 offset-md-1 mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">N.I.T.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="nit" name="nit" required tabindex="5">
                    </div>
                    <div class="input-group input-group-sm col-md-5 mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Nombre</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="nombre" name="nombre" required tabindex="6">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Dirección</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="direccion" name="direccion" required tabindex="7">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group col-md-5 offset-md-1 mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" for="motivo_id">Motivo</span>
                        </div>
                        <select class="custom-select select2 select2bs4" id="motivo_id" name="motivo_id" autofocus required tabindex="8">
                            <option value="">Seleccionar...</option>
                            @foreach($motivos as $m)
                                <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group col-md-5 mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Monto</span>
                        </div>
                        <input type="number" class="form-control" id="otros_cobros" name="otros_cobros" placeholder="0.00" style="text-align: right;" required tabindex="9">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 offset-md-1 text-center">
                        <span>Observaciones</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 offset-md-1 mb-1">
                        <div class="input-group">
                            <textarea class="form-control form-control-sm" aria-label="With textarea" id="observacion_anulacion" name="observacion_anulacion" rows="2" maxlength="150" style="text-align: justify;" tabindex="10"></textarea>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </form>
@endsection
@section('js')
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
    <script type="text/javascript">
        var nlinea = 0;
        var nlineaPago = 0;
        var total_detalle = 0;
        var linea = {};
        var linea1 = {};
        var local_db = [];
        var detalle_db = [];
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

        window.addEventListener('load', function(){
            var local_db = [];
            var detalle_db = [];
            localStorage.clear(local_db);
            localStorage.clear(detalle_db);
            localStorage.local_db     = JSON.stringify(local_db);
            localStorage.detalle_db   = JSON.stringify(detalle_db);
            var caja_id               = document.getElementById('caja_id').value;
            var caja_editar_documento = document.getElementById('caja_editar_documento').value;
            var tipo_documento_id     = document.getElementById('tipo_documento_id').value;
            /*=================================================================
            Verifica si el usuario puede modificar el numero de serie y correlativo
            de factura
            =================================================================*/
            if (caja_editar_documento == 'N') {
                document.getElementById('banco_id').focus();
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
        });

        //=========================================================================
        // buscar recibo
        //=========================================================================
        function fn_buscar(){
            var banco_id = document.getElementById('banco_id').value;
            var cheque   = document.getElementById('cheque').value;
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('trae_recibo')}}",
                method: "POST",
                data: { banco_id  : banco_id,
                        cheque    : cheque},
                success: function(response){
                    var info = response;
                    if (info.estado == '0') {
                        swal({
                            title: 'Error !!!',
                            text: info.mensaje,
                            type: 'error',
                        });
                        /*$('.modal_texto').html(info.mensaje);
                        $("#mensajeModal").modal('show');*/
                    }else{
                        document.getElementById('recibo_id').value = info['encabezado']['recibo_id'];
                        document.getElementById('paciente_id').value = info['encabezado']['paciente_id'];
                        document.getElementById('paciente_nombre').value = info['encabezado']['nombre_completo'];

                        var linea = {
                            recibo_id     : info['encabezado']['id'],
                            serie         : info['encabezado']['serie'],
                            correlativo   : info['encabezado']['correlativo'],
                            fecha_emision : info['encabezado']['fecha_emision'],
                            total         : info['encabezado']['total']
                        }
                        if(!localStorage.local_db){
                            localStorage.local_db = JSON.stringify([linea]);
                        }
                        else{
                            local_db = JSON.parse(localStorage.local_db);
                            local_db.push(linea);
                            localStorage.local_db = JSON.stringify(local_db);
                        }
                        actualizarTablaRecibo();

                        for (var i = 0; i < info['detalle'].length; i++) {
                            var linea1 = {
                                encabezado_id : info['detalle'][i]['factura_id'],
                                descripcion   : info['detalle'][i]['descripcion'],
                                serie         : info['detalle'][i]['serie'],
                                correlativo   : info['detalle'][i]['correlativo'],
                                fecha_emision : info['detalle'][i]['fecha_emision'],
                                nit           : info['detalle'][i]['nit'],
                                nombre        : info['detalle'][i]['nombre'],
                                direccion     : info['detalle'][i]['direccion']
                            }
                        }
                        if(!localStorage.detalle_db){
                            localStorage.detalle_db = JSON.stringify([linea1]);
                        }
                        else{
                            detalle_db = JSON.parse(localStorage.detalle_db);
                            detalle_db.push(linea1);
                            localStorage.detalle_db = JSON.stringify(detalle_db);
                        }
                        actualizarTablaFactura();
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        //=========================================================================
        // actualizar datos de recibo
        //=========================================================================

        function actualizarTablaRecibo(){
            var local_db = JSON.parse(localStorage.local_db);
            var html = '';
            for(var i = 0; i < local_db.length; i++){
                html += '<tr>'
                html += '<td>'
                html += 'Recibo'
                html += '</td>'
                html += '<td>'
                html += local_db[i]['serie']+'-'+local_db[i]['correlativo']
                html += '</td>'
                html += '<td>'
                html += local_db[i]['fecha_emision']
                html += '</td>'
                html += '<td style="text-align: right;">'
                html += local_db[i]['total']
                html += '</td>'
                html += '</tr>'
            }
            $("#tblRecibo tbody tr").remove();
            $('#tblRecibo tbody').append(html);
        }

        //=========================================================================
        // actualizar datos de facturas
        //=========================================================================

        function actualizarTablaFactura(){
            var detalle_db = JSON.parse(localStorage.detalle_db);
            var html = '';
            for(var i = 0; i < detalle_db.length; i++){
                document.getElementById('nit').value = detalle_db[i]['nit'];
                document.getElementById('nombre').value = detalle_db[i]['nombre'];
                document.getElementById('direccion').value = detalle_db[i]['direccion'];
                html += '<tr>'
                html += '<td>'
                html += detalle_db[i]['descripcion']
                html += '</td>'
                html += '<td>'
                html += detalle_db[i]['serie']+'-'+detalle_db[i]['correlativo']
                html += '</td>'
                html += '<td>'
                html += detalle_db[i]['fecha_emision']
                html += '</td>'
                html += '<td>'
                html += detalle_db[i]['nit']
                html += '</td>'
                html += '<td>'
                html += detalle_db[i]['nombre']
                html += '</td>'
                html += '<td style="text-align: right;">'
                html += 0
                html += '</td>'
                html += '</tr>'
            }
            $("#tblFactura tbody tr").remove();
            $('#tblFactura tbody').append(html);
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
                        window.location.href = "{{ route('nd_listado') }}";
                    }
                }
            );
        }

        //=========================================================================
        // Grabar nota de debito
        //=========================================================================
        $(function(){
            $("#ndForm").submit(function(){
                var tipo_documento_id = document.getElementById('tipo_documento_id').value;
                var resolucion_id     = document.getElementById('resolucion_id').value;
                var fecha_emision     = document.getElementById('fecha_emision').value;
                var serie             = document.getElementById('serie').value;
                var correlativo       = document.getElementById('correlativo').value;
                var banco_id          = document.getElementById('banco_id').value;
                var cheque_no         = document.getElementById('cheque').value;
                var paciente_id       = document.getElementById('paciente_id').value;
                var motivo_id         = document.getElementById('motivo_id').value;
                var otros_cobros      = document.getElementById('otros_cobros').value;
                var observaciones     = document.getElementById('observacion_anulacion').value;
                var nit               = document.getElementById('nit').value;
                var nombre            = document.getElementById('nombre').value;
                var direccion         = document.getElementById('direccion').value;
                var recibo_id         = document.getElementById('recibo_id').value;


                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('grabar_nd')}}",
                    method: "POST",
                    data: {tipo_documento_id : tipo_documento_id,
                           resolucion_id     : resolucion_id,
                           fecha_emision     : fecha_emision,
                           serie             : serie, 
                           correlativo       : correlativo,
                           banco_id          : banco_id,
                           cheque_no         : cheque_no,
                           paciente_id       : paciente_id,
                           motivo_id         : motivo_id,
                           otros_cobros      : otros_cobros,
                           observaciones     : observaciones,
                           nit               : nit,
                           nombre            : nombre,
                           direccion         : direccion,
                           recibo_id         : recibo_id
                    },
                    success: function(response){
                        if (response.parametro == 0) {
                            swal({
                                title: 'Trabajo Finalizado',
                                text: response.respuesta,
                                type: 'success',
                            }, function(){
                                //location.reload();
                                window.location.href = "http://localhost:8888/granai/public/ventas/nota_credito_editar/"+response.nd_id+"?0";
                            });
                        }else{
                            swal({
                                title: 'Error !!!',
                                text: response.respuesta,
                                type: 'error'
                            });
                        }
                    },
                    error: function(error){
                        console.log(error);
                    }
                });     
                return false;
            });
        });

    </script>
@endsection