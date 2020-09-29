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
                <div class="col-md-3 offset-md-9" style="text-align: right;">
                    @if( $encabezado->estado == 'A')
                        <a href="#" onclick="fn_anular(); return false;" class="btn btn-sm btn-anular" title="Anular Factura"><i class="fas fa-ban"></i></a>
                        <a href="{{ route('factura_pdf', $encabezado->id ) }}" class="btn btn-sm btn-reporte" title="Impresión" target="_blank"><i class="fas fa-file-pdf"></i></a>
                        <a href="#" onclick="fn_renumerar(); return false;" class="btn btn-sm btn-renumera" title="Cambio de correlativo"><i class="fa fa-edit"></i></a>
                        <a href="#" onclick="fn_refacturar(); return false;" class="btn btn-sm btn-refactura" title="Re facturar"><i class="far fa-clone"></i></a>
                    @endif
                    <a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Admisiones" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </div>
        <form class="form-horizontal">
            <div class="card-body">
                <input type="hidden" id="tipo_documento_id" name="tipo_documento_id" value="{{ $encabezado->tipodocumento_id }}">
                <input type="hidden" id="resolucion_id" name="resolucion_id" value="{{ $encabezado->resolucion_id }}">
                <input type="hidden" id="caja_id" name="caja_id" value="{{ $encabezado->caja_id }}">
                <input type="hidden" id="factura_id" name="factura_id" value="{{ $encabezado->id }}">
                <input type="hidden" id="factura_estado" name="factura_estado" value="{{ $encabezado->estado }}">
                <input type="hidden" id="admision_id" name="admision_id" value="{{ $admision_id }}">
                <input type="hidden" id="caja_editar_documento" name="caja_editar_documento" value="{{ $caja->editar_documento}}">
                <input type="hidden" id="condicion" name="condicion" value="{{ $encabezado->condicion }}">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4 offset-md-1 mb-1">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Documento</label>
                                    </div>
                                    <input type="text" class="form-control form-control-sm text-center" id="documento_descripcion" name="documento_descripcion" value="{{ $documento->descripcion }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6 mb-1">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="paciente_id">Paciente</label>
                                    </div>
                                    <select class="custom-select  custom-select-sm select2 select2bs4" id="paciente_id"  name="paciente_id" tabindex="8" disabled>
                                        <option value="">Seleccionar...</option>
                                        @foreach($pacientes as $p)
                                            <option value="{{ $p->id}}" @if($p->id == $encabezado->paciente_id) then selected @endif>{{ $p->nombre_completo}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 offset-md-1">
                                <div class="input-group mb-1 input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Fecha</label>
                                    </div>
                                    <input type="date" class="form-control form-control-sm text-center" id="fecha_emision" name="fecha_emision" value="{{ $encabezado->fecha_emision}}" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group mb-1 input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">N.I.T.</label>
                                    </div>
                                    <input type="text" class="form-control form-control-sm text-center" id="nit" name="nit" tabindex="4" value="{{ $encabezado->nit }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 offset-md-1">
                                <div class="input-group mb-1 input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Serie</label>
                                    </div>
                                    <input type="text" class="form-control form-control-sm text-center" id="serie" name="serie" value="{{ $encabezado->serie }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-1 input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Nombre</label>
                                    </div>
                                    <input type="text" class="form-control form-control-sm text-center" id="nombre" name="nombre" tabindex="5" value="{{ $encabezado->nombre }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 offset-md-1">
                                <div class="input-group mb-1 input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Correlativo</label>
                                    </div>
                                    <input type="number" class="form-control form-control-sm text-center" id="correlativo" name="correlativo" tabindex="3" value="{{ $encabezado->correlativo }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-1 input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Dirección</label>
                                    </div>
                                    <input type="text" class="form-control form-control-sm text-center" id="direccion" name="direccion" tabindex="6" value="{{ $encabezado->direccion }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if( $encabezado->estado == 'I')
                    <div class="col-md-3">
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
                    </div>
                    @endif
                </div>
                <hr>
                <div class="card card-secondary">
                    <div class="card-header text-center">
                        <h6>Detalle</h6>
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
                                        <tbody>
                                            @foreach($detalle as $d)
                                            <tr>
                                                <td>{{ $d->cantidad }}</td>
                                                <td>{{ $d->descripcion }}</td>
                                                <td>{{ $d->precio_unitario }}</td>
                                                <td>{{ $d->precio_neto }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card card-secondary">
                    <div class="card-header text-center">
                        <h6>Forma de pago</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="table-responsive">
                                    <table id="tblDetalle" class="table table-sm table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>Forma de pago</th>
                                                <th>Entidad</th>
                                                <th>Cuenta</th>
                                                <th>Documento</th>
                                                <th>Autorizacion</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pago as $p)
                                                <tr>
                                                    <td>{{ $p->forma_pago }}</td>
                                                    <td>{{ $p->entidad_nombre }}</td>
                                                    <td>{{ $p->cuenta_no }}</td>
                                                    <td>{{ $p->documento_no }}</td>
                                                    <td>{{ $p->autoriza_no }}</td>
                                                    <td>{{ $p->monto }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- re facturacion -->
    <div class="modal fade" id="refacturaModal" tabindex="-1" role="dialog" aria-labelledby="refacturaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="refacturaForm" name="refacturaForm" action="#">
                    @csrf
                    <div class="card card-navy">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6>Re Facturación</h6>
                                </div>
                                <div class="col-md-4" style="text-align: right;">
                                    <button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                                    <!--<a href="#" class="btn btn-sm btn-danger" title="Regresar a documento" data-dismiss="modal"><i class="fas fa-sign-out-alt"></i></a>-->
                                    <button type="button" class="btn btn-sm btn-danger" title="Salir" onclick="cerrar_modal('refacturaModal'); return false;"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="refactura_admision_id1" name="refactura_admision_id1" value="{{ $admision_id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-1 input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Fecha</label>
                                        </div>
                                        <input type="date" class="form-control form-control-sm text-center" id="refactura_fecha_emision" name="refactura_fecha_emision" value="{{ $hoy }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-1 input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Serie</label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm text-center" id="refactura_serie" name="refactura_serie" value="{{ $encabezado->serie }}" onchange="fn_resolucion_x_serie(); return false;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-1 input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Correlativo</label>
                                        </div>
                                        <input type="number" class="form-control form-control-sm text-center" id="refactura_correlativo" name="refactura_correlativo" value="{{ $encabezado->correlativo }}"  required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-1 input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">N.I.T.</label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm text-center" id="refactura_nit" name="refactura_nit" value="{{ $encabezado->nit }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="input-group mb-1 input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Nombre</label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm text-center" id="refactura_nombre" name="refactura_nombre" value="{{ $encabezado->nombre }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1 mb-1">
                                    <div class="input-group mb-1 input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Dirección</label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm text-center" id="refactura_direccion" name="refactura_direccion" value="{{ $encabezado->direccion }}" required>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-10 offset-md-1 mb-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="refactura_motivo_id">Motivo</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="refactura_motivo_id"  name="refactura_motivo_id" autofocus required>
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
                                        <textarea class="form-control form-control-sm" aria-label="With textarea" id="observacion_refactura" name="observacion_refactura" rows="3" maxlength="150" style="text-align: justify;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /re facturacion -->
    <!-- cambio de correlativo -->
    <div class="modal fade" id="renumeraModal" tabindex="-1" role="dialog" aria-labelledby="renumeraModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="renumeraForm" name="renumeraForm" action="#">
                    @csrf
                    <div class="card card-navy">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6>Cambio de Correlativo</h6>
                                </div>
                                <div class="col-md-4" style="text-align: right;">
                                    <button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                                    <!--<a href="#" class="btn btn-sm btn-danger" title="Regresar a documento" data-dismiss="modal"><i class="fas fa-sign-out-alt"></i></a>-->
                                    <button type="button" class="btn btn-sm btn-danger" title="Salir" onclick="cerrar_modal('renumeraModal'); return false;"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="refactura_admision_id" name="refactura_admision_id" value="{{ $admision_id }}">
                            <div class="row">
                                <div class="col-md-5 offset-md-1">
                                    <div class="input-group mb-1 input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Serie</label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm text-center" id="renumera_serie" name="renumera_serie" value="{{ $encabezado->serie }}" onchange="fn_resolucion_x_serie(); return false;" required>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group mb-1 input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Correlativo</label>
                                        </div>
                                        <input type="number" class="form-control form-control-sm text-center" id="renumera_correlativo" name="renumera_correlativo" value="{{ $encabezado->correlativo }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /cambio de correlativo -->
    <!-- anulación de facturas -->
    <div class="modal fade" id="anulacionModal" role="dialog" aria-labelledby="mensajeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="anulacionForm" name="anulacionForm" action="#">
                    @csrf
                    <div class="card card-navy">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5>Anulación de Factura</h5>
                                </div>
                                <div class="col-md-4" style="text-align: right;">
                                    <button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                                    <!--<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" title="Salir"><i class="fas fa-sign-out-alt"></i></button>-->
                                    <button type="button" class="btn btn-sm btn-danger" title="Salir" onclick="cerrar_modal('anulacionModal'); return false;"><i class="fas fa-sign-out-alt"></i></button>
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
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="motivo_id"  name="motivo_id" autofocus required>
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
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
    <script type="text/javascript">
        $(function(){
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
        });

        window.addEventListener('load', function(){
            var caja_id               = document.getElementById('caja_id').value;
            var admision_id           = document.getElementById('admision_id').value;
            var caja_editar_documento = document.getElementById('caja_editar_documento').value;
            var tipo_documento_id     = document.getElementById('tipo_documento_id').value;
        });

        function fn_anular(){
            var factura_id = document.getElementById('factura_id').value;
            $("#anulacionModal").modal('show');
        }

        function fn_renumerar(){
            $("#renumeraModal").modal('show');
        }

        function fn_refacturar(){
            /*=================================================================
            Verifica si el usuario puede modificar el numero de serie y correlativo
            de factura
            =================================================================*/
            var caja_id               = document.getElementById('caja_id').value;
            var admision_id           = document.getElementById('admision_id').value;
            var caja_editar_documento = document.getElementById('caja_editar_documento').value;
            var tipo_documento_id     = document.getElementById('tipo_documento_id').value;

            if (caja_editar_documento == 'N') {
                document.getElementById('refactura_serie').disabled = true;
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
                        document.getElementById('refactura_serie').value = info.serie;
                        //document.getElementById('correlativo').value = info.correlativo;
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }else {
                document.getElementById('refactura_serie').disabled = false;
                document.getElementById('refactura_serie').focus();
            }

            $("#refacturaModal").modal('show');
        }

        function fn_resolucion_x_serie(){
            var caja_id  = document.getElementById('caja_id').value;
            var serie    = document.getElementById('refactura_serie').value;
            var tipo_documento_id = document.getElementById('tipo_documento_id').value;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('trae_resolucion_x_serie')}}",
                method: "POST",
                data: { caja_id           : caja_id,
                        tipo_documento_id : tipo_documento_id,
                        serie             : serie},
                success: function(response){
                    var info = response;
                    if (info.resolucion_id == 0) {
                        document.getElementById('resolucion_id').value = 0;
                        document.getElementById('refactura_serie').value = '';
                        document.getElementById('refactura_correlativo').value = '';
                        swal({
                            title: 'Error !!!',
                            text: 'No existe resolucion en la caja que permita emitir documentos con la serie '+serie,
                            type: 'error',
                        });
                        /*$('.modal_texto').html('No existe resolucion en la caja que permita emitir documentos con la serie '+serie);
                        $("#mensajeModal").modal('show');*/
                    } else {
                        document.getElementById('resolucion_id').value = info.resolucion_id;
                        /*document.getElementById('correlativo').value = info.correlativo;*/
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
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

        // anulacion de documentos
        $(function(){
            $("#anulacionForm").submit(function(){
                var documento_id = document.getElementById('factura_id').value;
                var motivo_id    = document.getElementById('motivo_id').value;
                var observacion  = document.getElementById('observacion_anulacion').value;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('documento_anular')}}",
                    method: "POST",
                    data: { documento_id  : documento_id,
                            motivo_id     : motivo_id,
                            observacion   : observacion},
                    success: function(response){
                        if (response.parametro == 0) {
                            swal({
                                title: 'Trabajo Finalizado',
                                text: response.respuesta,
                                type: 'success',
                            }, function(){
                                location.reload();
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

        // cambio de correlativo
        $(function(){
            $("#renumeraForm").submit(function(){
                var documento_id      = document.getElementById('factura_id').value;
                var tipodocumento_id  = document.getElementById('tipo_documento_id').value;
                var nueva_serie       = document.getElementById('renumera_serie').value;
                var nuevo_correlativo = document.getElementById('renumera_correlativo').value;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('documento_renumerar')}}",
                    method: "POST",
                    data: { documento_id      : documento_id,
                            tipodocumento_id  : tipodocumento_id,
                            nueva_serie       : nueva_serie,
                            nuevo_correlativo : nuevo_correlativo
                        },
                    success: function(response){
                        if (response.parametro == 0) {
                            swal({
                                title: 'Trabajo Finalizado',
                                text: response.respuesta,
                                type: 'success',
                            }, function(){
                                location.reload();
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

        // re facturacion
        $(function(){
            $("#refacturaForm").submit(function(){
                var documento_id      = document.getElementById('factura_id').value;
                var paciente_id       = document.getElementById('paciente_id').value;
                var tipodocumento_id  = document.getElementById('tipo_documento_id').value;
                var nueva_fecha       = document.getElementById('refactura_fecha_emision').value;
                var nueva_serie       = document.getElementById('refactura_serie').value;
                var nuevo_correlativo = document.getElementById('refactura_correlativo').value;
                var nueva_condicion   = document.getElementById('condicion').value;
                var nuevo_nit         = document.getElementById('refactura_nit').value;
                var nuevo_nombre      = document.getElementById('refactura_nombre').value;
                var nueva_direccion   = document.getElementById('refactura_direccion').value;
                var motivo_id         = document.getElementById('refactura_motivo_id').value;
                var observaciones     = document.getElementById('observacion_refactura').value;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('documento_refacturar')}}",
                    method: "POST",
                    data: { documento_id      : documento_id,
                            paciente_id       : paciente_id,
                            tipodocumento_id  : tipodocumento_id,
                            nueva_fecha       : nueva_fecha,
                            nueva_serie       : nueva_serie,
                            nuevo_correlativo : nuevo_correlativo,
                            nueva_condicion   : nueva_condicion,
                            nuevo_nit         : nuevo_nit,
                            nuevo_nombre      : nuevo_nombre,
                            nueva_direccion   : nueva_direccion,
                            motivo_id         : motivo_id,
                            observaciones     : observaciones
                        },
                    success: function(response){
                        if (response.parametro == 0) {
                            var ruta = asset+"ventas/editar_factura/"+response.id+"/0";
                            swal({
                                title: 'Trabajo Finalizado',
                                text: response.respuesta,
                                type: 'success',
                            }, function(){
                                window.location.href = ruta;
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