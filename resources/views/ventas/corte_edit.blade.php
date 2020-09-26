@extends('admin.layout')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('titulo')
    Corte
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
                <div class="col-md-1 offset-md-11">
                    <a href="{{ route('listado_cortes') }}" class="btn btn-sm btn-secondary" title="Regresar a lista de Cortes"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-pills ml-auto p-2">
                <li class="nav-item">
                    <a class="nav-link active" href="#resumen" data-toggle="tab">Resumen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#recibos" data-toggle="tab">Recibos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#facturas" data-toggle="tab">Facturas</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="resumen">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card card-secondary">
                                <div class="card-header text-center">
                                    <strong>Parámetros</strong>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-10 offset-md-1">
                                        <div class="input-group input-group-sm mb-1">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" id="basic-addon1">Fecha</label>
                                            </div>
                                            <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $corte->fecha }}" style="text-align: center;" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10 offset-md-1">
                                        <div class="input-group mb-1 input-group-sm">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">Caja</label>
                                            </div>
                                            <input type="text" class="form-control form-control-sm text-center" id="caja_descripcion" name="caja_descripcion" value="{{ $caja->nombre_maquina }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10 offset-md-1">
                                        <div class="input-group mb-1 input-group-sm">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">Corte</label>
                                            </div>
                                            <input type="text" class="form-control form-control-sm text-center" id="corte" name="corte" value="{{ $corte->corte }}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="card card-secondary">
                                        <div class="card-header text-center">
                                            <strong>Documentos</strong>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table id="tblResumenDocumentos" class="table table-sm table-bordered">
                                            <tbody>
                                                @foreach($resumend as $rd)
                                                    <tr><td>{{ $rd->descripcion }}</td><td style="text-align: right;">{{ $rd->total_documento }}</td></tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr><th>Total</th><td style="text-align: right;"><strong>{{ $totalresumend->total }}</strong></td></tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="card card-secondary">
                                        <div class="card-header text-center">
                                            <strong>Formas de Pago</strong>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table id="tblResumenPago" class="table table-sm table-bordered">
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr><th>Total</th><td style="text-align: right;">0.00</td></tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="recibos">
                    <br>
                    <form class="form-horizontal">
                        <div class="row text-center">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                <table id="tblrecibos" class="table table-sm table-striped table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Recibo</th><th>Tipo</th><th>Admision</th><th>Paciente</th><th>Total</th><th>Efectivo</th><th>Cheque</th><th>Tarjeta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pagos as $p)
                                            <tr>
                                                <td>{{ $p->serie }}-{{ $p->correlativo }}</td>
                                                <td>{{ $p->tipo_admision }}</td>
                                                <td>{{ $p->admision }}</td>
                                                <td>{{ $p->nombre_completo }}</td>
                                                <td>{{ $p->total_recibo}}</td>
                                                <td>{{ $p->Efectivo }}</td>
                                                <td>{{ $p->Cheque }}</td>
                                                <td>{{ $p->Tarjeta }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="facturas">
                    <br>
                    <form class="form-horizontal">
                        <div class="row text-center">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                <table id="tblDocumentos" class="table table-sm table-striped table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Tipo</th><th>Documento</th><th>Fecha</th><th>N.I.T.</th><th>Nombre</th><th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($documentos as $d)
                                            <tr>
                                                <td>{{ $d->tipodocumento_descripcion}}</td>
                                                <td>{{ $d->serie }}-{{ $d->correlativo }}</td>
                                                <td>{{ $d->fecha_emision }}</td>
                                                <td>{{ $d->nit }}</td>
                                                <td>{{ $d->nombre }}</td>
                                                <td>{{ $d->total_documento }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('assets/adminlte/plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
            $('.select2bs4').select2({ theme: 'bootstrap4' })
        });

        $(document).ready( function () {
            $('#tblDocumentos, #tblrecibos').DataTable({
                "paging": true,
              "lengthChange": false,
              "searching": true,
              "ordering": true,
              "info": true,
              "autoWidth": true,
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


        function fn_trae_totales(){
            var caja_id = document.getElementById('caja_id').value;
            var fecha   = document.getElementById('fecha').value;
            var info = {};
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('trae_resumen_documentos')}}",
                method: "POST",
                data: { caja_id  : caja_id, 
                        fecha    : fecha },
                dataSrc: "",
                success: function(response){
                    var info  = response;
                    var total = 0;
                    var html  = '';
                    var html1 = '';
                    for (var i = 0; i < info.length; i++) {
                        total += parseFloat(info[i]['total_documento'])
                        html += '<tr>'
                        html += '<td>'
                        html += info[i]['descripcion']
                        html += '</td>'
                        html += '<td style="text-align: right;">'
                        html += info[i]['total_documento']
                        html += '</td>'
                        html += '</tr>'
                    }
                    html1 += '<tr>'
                    html1 += '<th>Total</th>'
                    html1 += '<td style="text-align: right;">'
                    html1 += '<strong>'+total+'</strong>'
                    html1 += '</td>'
                    html1 += '</tr>'
                    $("#tblResumenDocumentos tfoot tr").remove();
                    $("#tblResumenDocumentos tbody tr").remove();
                    $('#tblResumenDocumentos tbody').append(html);
                    $('#tblResumenDocumentos tfoot').append(html1);
                },
                error: function(error){
                    console.log(error);
                }
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('trae_resumen_pagos')}}",
                method: "POST",
                data: { caja_id  : caja_id, 
                        fecha    : fecha },
                dataSrc: "",
                success: function(response){
                    var info  = response;
                    var total = 0;
                    var html  = '';
                    var html1 = '';
                    for (var i = 0; i < info.length; i++) {
                        total += parseFloat(info[i]['total'])
                        html += '<tr>'
                        html += '<td>'
                        html += info[i]['descripcion']
                        html += '</td>'
                        html += '<td style="text-align: right;">'
                        html += info[i]['total']
                        html += '</td>'
                        html += '</tr>'
                    }
                    html1 += '<tr>'
                    html1 += '<th>Total</th>'
                    html1 += '<td style="text-align: right;">'
                    html1 += '<strong>'+total+'</strong>'
                    html1 += '</td>'
                    html1 += '</tr>'
                    $("#tblResumenPago tfoot tr").remove();
                    $("#tblResumenPago tbody tr").remove();
                    $('#tblResumenPago tbody').append(html);
                    $('#tblResumenPago tfoot').append(html1);
                },
                error: function(error){
                    console.log(error);
                }
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('trae_detalle_pagos')}}",
                method: "POST",
                data: { caja_id  : caja_id, 
                        fecha    : fecha },
                dataSrc: "",
                success: function(response){
                    var info  = response;
                    var total = 0;
                    var html  = '';
                    var html1 = '';
                    for (var i = 0; i < info.length; i++) {
                        total += parseFloat(info[i]['total'])
                        html += '<tr>'
                        html += '<td>'
                        html += info[i]['serie']+'-'+info[i]['correlativo']
                        html += '</td>'
                        html += '<td>'
                        html += info[i]['tipo_admision']
                        html += '</td>'
                        html += '<td>'
                        html += info[i]['admision']
                        html += '</td>'
                        html += '<td>'
                        html += info[i]['nombre_completo']
                        html += '</td>'
                        html += '<td style="text-align: right;">'
                        html += info[i]['total_recibo']
                        html += '</td>'
                        html += '<td style="text-align: right;">'
                        html += info[i]['efectivo']
                        html += '</td>'
                        html += '<td style="text-align: right;">'
                        html += info[i]['cheque']
                        html += '</td>'
                        html += '<td style="text-align: right;">'
                        html += info[i]['tarjeta']
                        html += '</td>'
                        html += '</tr>'
                    }
                    html1 += '<tr>'
                    html1 += '<th>Total</th>'
                    html1 += '<td style="text-align: right;">'
                    html1 += '<strong>'+total+'</strong>'
                    html1 += '</td>'
                    html1 += '</tr>'
                    $("#tblrecibos tfoot tr").remove();
                    $("#tblrecibos tbody tr").remove();
                    $('#tblrecibos tbody').append(html);
                    $('#tblrecibos tfoot').append(html1);
                },
                error: function(error){
                    console.log(error);
                }
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('trae_detalle_documentos')}}",
                method: "POST",
                data: { caja_id  : caja_id, 
                        fecha    : fecha },
                dataSrc: "",
                success: function(response){
                    var info  = response;
                    var total = 0;
                    var html  = '';
                    var html1 = '';
                    for (var i = 0; i < info.length; i++) {
                        console.log(info);
                        total += parseFloat(info[i]['total_documento'])
                        html += '<tr>'
                        html += '<td>'
                        html += info[i]['tipodocumento_descripcion']
                        html += '</td>'
                        html += '<td>'
                        html += info[i]['serie']+'-'+info[i]['correlativo']
                        html += '</td>'
                        html += '<td>'
                        html += info[i]['fecha_emision']
                        html += '</td>'
                        html += '<td>'
                        html += info[i]['nit']
                        html += '</td>'
                        html += '<td>'
                        html += info[i]['nombre']
                        html += '</td>'
                        html += '<td style="text-align: right;">'
                        html += info[i]['total_documento']
                        html += '</td>'
                        html += '</tr>'
                    }
                    html1 += '<tr>'
                    html1 += '<td></td>'
                    html1 += '<td></td>'
                    html1 += '<td></td>'
                    html1 += '<td></td>'
                    html1 += '<th style="text-align: right;">Total</th>'
                    html1 += '<td style="text-align: right;">'
                    html1 += '<strong>'+total+'</strong>'
                    html1 += '</td>'
                    html1 += '</tr>'
                    $("#tblDocumentos tfoot tr").remove();
                    $("#tblDocumentos tbody tr").remove();
                    $('#tblDocumentos tbody').append(html);
                    $('#tblDocumentos tfoot').append(html1);
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
    </script>
@endsection