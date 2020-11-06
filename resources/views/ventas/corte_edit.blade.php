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
                <div class="col-md-1 offset-md-11" style="text-align: right;">
                    <a href="{{ route('rpt_arqueo_pdf', $corte->id) }}" class="btn btn-sm btn-reporte" title="Impresión" target="_blank"><i class="fas fa-file-pdf"></i></a>
                    <!--<a href="{{ route('rpt_arqueo_xls', $corte->id) }}" class="btn btn-sm btn-excel" title="Excel"><i class="fas fa-file-excel"></i></a>-->
                    <a href="{{ route('listado_cortes') }}" class="btn btn-sm btn-danger" title="Regresar a lista de Cortes"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 offset-md-4">
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
                                            <tr><td>{{ $rd->descripcion }}</td><td style="text-align: right;">{{ $rd->total_documento}}</td></tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #BBD1D1;"><th>Total</th><th style="text-align: right;">{{ $totalresumend->total }}</th></tr>
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
                                        @foreach($resumenp as $rp)
                                            <tr><td>{{ $rp->descripcion}}</td><td style="text-align: right;">{{ $rp->total }}</td></tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #BBD1D1;"><th>Total</th><th style="text-align: right;">{{ $totalresumenp->total }}</th></tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <form class="form-horizontal">
                        <div class="row text-center">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                <table id="tblrecibos" class="table table-sm table-striped table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Recibo</th><th>Tipo</th><th>Admision</th><th>Paciente</th><th>Total</th><th>Efectivo</th><th>Cheque</th><th>Transferencia</th><th>Tarjeta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pagos as $p)
                                            <tr>
                                                <td>{{ $p->serie }}-{{ $p->correlativo }}</td>
                                                <td>{{ $p->tipo_admision }}</td>
                                                <td>{{ $p->admision }}</td>
                                                <td>{{ $p->nombre_completo }}</td>
                                                <td>{{ $p->total_recibo }}</td>
                                                <td>{{ $p->Efectivo }}</td>
                                                <td>{{ $p->Cheque }}</td>
                                                <td>{{ $p->Transferencia }}</td>
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
            </div>
            <div class="row">
                <div class="col-md-10 offset-md-1">
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
                                                    <td>{{ $d->tipodocumento_descripcion }}</td>
                                                    <td>{{ $d->serie }}-{{ $d->correlativo }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($d->fecha_emision)->format('d/m/Y') }}</td>
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

@endsection