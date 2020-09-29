@extends('admin.layout')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link href="{{ asset('assets/summernote-0.8.18-dist/summernote-bs4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/dropzone-5.7.0/css/dropzone.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <!--<link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-fileinput/css/fileinput.min.css') }}"> -->
    <style type="text/css">
        .button-container{
            display:inline-block;
            position:relative;
        }
        .button-container a{
            position: absolute;
            bottom:4em;
            right:4em;
            background-color:#8F0005;
            border-radius:1.5em;
            color:white;
            text-transform:uppercase;
            padding:1em 1.5em;
        }
    </style>
@endsection
@section('contenido')
    <div class="card card-navy">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-9">
                    <label>Codigo:&nbsp;</label>{{ $pPaciente->codigo_id }}&nbsp;&nbsp;&nbsp;
                    <label>Paciente:&nbsp;</label>{{ $pPaciente->nombre_completo }}&nbsp;&nbsp;&nbsp;
                    <label>Fecha Nacimiento:&nbsp;</label>{{ \Carbon\Carbon::parse($pPaciente->fecha_nacimiento)->format('d/m/Y') }}
                </div>
                <div class="col-sm-3" style="text-align: right;">
                    <a href="#" onclick="grabar(); return false;" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></a>
                    @if($origen == 'A')
                        <!--<a href="{{ route('nueva_agenda', [0,'T',date('Y-m-d')]) }}" class="btn btn-sm btn-danger" title="Salir"><i class="fas fa-sign-out-alt"></i></a>-->
                        <a href="#" class="btn btn-sm btn-danger" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                    @else
                        <a href="#" class="btn btn-sm btn-danger" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <input type="hidden" id="origen" name="origen" value="{{ $origen }}">
                <input type="hidden" id="paciente_id" name="paciente_id" value="{{$pPaciente->id}}">
                <input type="hidden" id="admision_id" name="admision_id" value="">
                <input type="hidden" id="cdetalle_id" name="cdetalle_id" value="">
                <input type="hidden" id="pdetalle_id" name="pdetalle_id" value="">
                <input type="hidden" id="hdetalle_id" name="hdetalle_id" value="">
                <input type="hidden" id="ultimotab" name="ultimotab" value="">
            </div>
            
            <ul class="nav nav-tabs ml-auto p-2">
                <li class="nav-item">
                    <a class="nav-link active" href="#consulta" data-toggle="tab" onclick="SeleccionarTab('C');">Consulta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#procedimiento" data-toggle="tab" onclick="SeleccionarTab('P');">Procedimiento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#hospitalizacion" data-toggle="tab" onclick="SeleccionarTab('H');">Hospitalización</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="consulta">
                    <div class="row">
                        <!-- lista de consultas -->
                        <div class="col-md-3">
                            <div class="card card-info">
                                <div class="card-header text-center">
                                    <p>Admisiones</p>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Admisión</th><th>Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pListaC as $pLc)
                                            <tr>
                                                <td>
                                                    <a href="#" onclick="trae_consulta({{ $pLc->id }})"> {{ $pLc->admision }}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($pLc->fecha)->format('d/m/Y') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    {!! $pListaC->render() !!}
                                </div>
                            </div>
                        </div>
                        <!-- /lista de consultas -->
                        <!-- detalle de consulta -->
                        <div class="col-md-9">
                            <br>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Admisión</label>
                                    </div>
                                    <input type="text" id="cadmision" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-4">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Medico</label>
                                    </div>
                                    <input type="text" id="cmedico_nombre" class="form-control" style="text-align: center;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-4">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Hospital</label>
                                    </div>
                                    <input type="text" id="chospital_nombre" class="form-control" style="text-align: center;" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Fecha</label>
                                    </div>
                                    <input type="text" id="cfecha" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Edad</label>
                                    </div>
                                    <input type="text" id="cedad" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-6">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Aseguradora</label>
                                    </div>
                                    <input type="text" id="caseguradora_nombre" class="form-control" style="text-align: right;" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-5">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Póliza</label>
                                    </div>
                                    <input type="text" id="cpoliza" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Deducible</label>
                                    </div>
                                    <input type="text" id="cdeducible" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Co pago</label>
                                    </div>
                                    <input type="text" id="ccopago" class="form-control" style="text-align: right;" disabled>
                                </div>
                            </div>
                            <ul class="nav nav-tabs ml-auto p-2">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#vitales" data-toggle="tab">Vitales</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#subjetivo" data-toggle="tab">Subjetivos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#objetivo" data-toggle="tab">Objetivos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#impresion_clinica" data-toggle="tab">Impresión Clinica</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#plan" data-toggle="tab">Plan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tratamiento" data-toggle="tab">Tratamiento</a>
                                </li>
                            </ul>
                            <div class="tab-content" style="background-color: #ECF4FF;">
                                <div class="active tab-pane" id="vitales">
                                    <br>
                                    <div class="row">
                                        <div class="col-md-8 offset-md-1">
                                            <div class="row">
                                                <div class="input-group mb-1 input-group-sm col-md-4">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text">Peso</label>
                                                    </div>
                                                    <input type="text" id="peso" name="peso" class="form-control" style="text-align: right;" disabled onchange="fn_calcula_bmi(); return false;">
                                                </div>
                                                <div class="input-group mb-1 input-group-sm col-md-4">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text">Talla</label>
                                                    </div>
                                                    <input type="text" id="talla" name="talla" class="form-control" style="text-align: right;" disabled onchange="fn_calcula_bmi(); return false;">
                                                </div>
                                                <div class="input-group mb-1 input-group-sm col-md-4">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text">Pulso</label>
                                                    </div>
                                                    <input type="text" id="pulso" name="pulso" class="form-control" style="text-align: right;" disabled>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-group mb-1 input-group-sm col-md-4">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text">Temperatura</label>
                                                    </div>
                                                    <input type="text" id="temperatura" name="temperatura" class="form-control" style="text-align: right;" disabled>
                                                </div>
                                                <div class="input-group mb-1 input-group-sm col-md-4">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text">Respiraciones</label>
                                                    </div>
                                                    <input type="text" id="respiracion" name="respiracion" class="form-control" style="text-align: right;" disabled>
                                                </div>
                                                <div class="input-group mb-1 input-group-sm col-md-4">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text">Presion Arterial</label>
                                                    </div>
                                                    <input type="text" id="presion" name="presion" class="form-control" style="text-align: right;" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="card card-navy text-center">
                                                <div class="card-header">
                                                    BMI
                                                </div>
                                                <div class="card-body">
                                                    <input type="hidden" id="bmi" name="bmi" class="form-control" style="text-align: right;" disabled value="0.00">
                                                    <div id="bmi_show"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="subjetivo">
                                    <div class="row text-center">
                                        <div class="form-group col-md-10 offset-md-1">
                                            <label for="consulta_subjetivo">Descripción</label>
                                            <textarea class="form-control form-control-sm" id="consulta_subjetivo" name="consulta_subjetivo" rows="5" maxlength="975" style="text-align: justify;" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="objetivo">
                                    <div class="row text-center">
                                        <div class="form-group col-md-10 offset-md-1">
                                            <label for="consulta_objetivo">Descripción</label>
                                            <textarea class="form-control form-control-sm" id="consulta_objetivo" name="consulta_objetivo" rows="5" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="impresion_clinica">
                                    <div class="row text-center">
                                        <div class="form-group col-md-10 offset-md-1">
                                            <label for="consulta_impresion_clinica">Descripción</label>
                                            <textarea class="form-control form-control-sm" id="consulta_impresion_clinica" name="consulta_impresion_clinica" rows="5" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="plan">
                                    <div class="row text-center">
                                        <div class="form-group col-md-10 offset-md-1">
                                            <label for="consulta_plan">Descripción</label>
                                            <textarea class="form-control form-control-sm" id="consulta_plan" name="consulta_plan" rows="5" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tratamiento">
                                    <br>
                                    <div class="row">
                                        <div class="mb-1 col-md-5 offset-md-1">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" for="tratamiento_medicamento_id">Medicamento</span>
                                                </div>
                                                <select class="custom-select  custom-select-sm select2 select2bs4" id="tratamiento_medicamento_id"  name="tratamiento_medicamento_id">
                                                    <option selected>Seleccionar...</option>
                                                    @foreach($pMedicamentos as $pM)
                                                    <option value="{{ $pM->id }}">{{ $pM->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-1 col-md-4">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" for="tratamiento_dosis_id">Dosis</span>
                                                </div>
                                                <select class="custom-select  custom-select-sm select2 select2bs4" id="tratamiento_dosis_id"  name="tratamiento_dosis_id">
                                                    <option selected>Seleccionar...</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-1 col-md-2">
                                            <a href="#" class="btn btn-sm btn-primary" onclick="copiarDosis(); return false;"><i class="fas fa-plus-circle"></i></a>
                                            <a href="#" id="impresion_receta" class="btn btn-sm btn-reporte" target="_blank" title="Impresion de receta">
                                                            <i class="fa fa-print"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-10 offset-md-1">
                                            <label for="consulta_tratamiento">Descripción</label>
                                            <textarea class="form-control form-control-sm" id="consulta_tratamiento" name="consulta_tratamiento" rows="10" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /detalle de consulta -->
                    </div>
                </div>
                <div class="tab-pane" id="procedimiento">
                    <div class="row">
                        <!-- lista de procedimientos -->
                        <div class="col-md-3">
                            <div class="card card-info">
                                <div class="card-header text-center">
                                    <p>Admisiones</p>
                                </div>
                                <div class="card-body">
                                    <tr>
                                        <td>
                                            <table class="table table-sm text-center">
                                                <thead>
                                                    <tr>
                                                        <th>Admision</th>
                                                        <th>Fecha</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($pListaP as $pLp)
                                                    <tr>
                                                        <td>
                                                            <a href="#" onclick="trae_procedimiento({{ $pLp->id }})"> {{ $pLp->admision }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($pLp->fecha)->format('d/m/Y') }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </div>
                                <div class="card-footer">
                                    {!! $pListaC->render() !!}
                                </div>
                            </div>
                        </div>
                        <!-- /lista de procedimientos -->
                        <!-- detalle de procedimientos -->
                        <div class="col-md-9">
                            <br>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Admisión</label>
                                    </div>
                                    <input type="text" id="padmision" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-4">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Medico</label>
                                    </div>
                                    <input type="text" id="pmedico_nombre" class="form-control" style="text-align: center;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-4">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Hospital</label>
                                    </div>
                                    <input type="text" id="phospital_nombre" class="form-control" style="text-align: center;" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Fecha</label>
                                    </div>
                                    <input type="text" id="pfecha" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Edad</label>
                                    </div>
                                    <input type="text" id="pedad" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-6">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Aseguradora</label>
                                    </div>
                                    <input type="text" id="paseguradora_nombre" class="form-control" style="text-align: right;" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-5">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Póliza</label>
                                    </div>
                                    <input type="text" id="ppoliza" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Deducible</label>
                                    </div>
                                    <input type="text" id="pdeducible" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Co pago</label>
                                    </div>
                                    <input type="text" id="pcopago" class="form-control" style="text-align: right;" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                <ul class="nav nav-tabs ml-auto p-2">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#generalesprocedimiento" data-toggle="tab">Generales</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#indicaciones" data-toggle="tab">Indicaciónes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#hallazgos" data-toggle="tab">Hallazgos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#endoscopico" data-toggle="tab">Diagnostico Endoscopico</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#recomendaciones" data-toggle="tab">Recomendaciones</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#imagenes" data-toggle="tab">Imagenes</a>
                                    </li>
                                </ul>
                                <div class="tab-content" style="background-color: #ECF4FF;">
                                    <div class="tab-pane" id="imagenes">
                                        <br>
                                        <div class="row">
                                            <div class="col-md-2 offset-md-9" style="text-align: right;">
                                                <button type="button" class="btn btn-primary btn-sm" title="Cargar Imagenes" data-toggle="modal" data-target="#CargaImagenes">
                                                    <i class="fas fa-upload"></i>
                                                </button>
                                                <a href="#" id="informe" class="btn btn-sm btn-reporte" target="_blank" title="Informe Medico"><i class="fas fa-file-pdf"></i></a>
                                            </div>
                                        </div>
                                        <hr>
                                        <div id="mostrar_fotos" class="row">
                                            <div class="col-md-10 col-md-offset-1">
                                                <table id="tblimagenes" class="table table-sm table-borderless">
                                                    <tbody>
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane active" id="generalesprocedimiento">
                                        <br>
                                        <div class="row">
                                            <div class="mb-1 col-md-5 offset-md-1">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="procedimiento_procedimiento_id">Procedimiento</label>
                                                    </div>
                                                    <select class="custom-select  custom-select-sm select2 select2bs4" id="procedimiento_procedimiento_id"  name="procedimiento_procedimiento_id" disabled>
                                                        <option value="">Seleccionar...</option>
                                                        @foreach($pProcedimientos as $p)
                                                            <option value="{{ $p->id}}">{{ $p->descripcion}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="input-group mb-1 input-group-sm col-md-5">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text">Premedicación</label>
                                                </div>
                                                <input type="text" id="procedimiento_premedicacion" name="procedimiento_premedicacion" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group form-control-sm clearfix offset-md-6">
                                                <label for="masculino">Tolerancia</label>
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="buena" name="procedimiento_tolerancia" value="B" checked disabled>
                                                    <label for="buena">Buena</label>
                                                </div>
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="regular" name="procedimiento_tolerancia" value="R" disabled>
                                                    <label for="regular">Regular</label>
                                                </div>
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="mala" name="procedimiento_tolerancia" value="M" disabled>
                                                    <label for="mala">Mala</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text">Anestesiologo</label>
                                                </div>
                                                <input type="text" id="anestesiologo" name="anestesiologo" class="form-control" disabled>
                                            </div>
                                            <div class="input-group mb-1 input-group-sm col-md-5">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text">Patologo</label>
                                                </div>
                                                <input type="text" id="patologo" name="patologo" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                    <div class="tab-pane" id="indicaciones">
                                        <div class="row text-center">
                                            <div class="form-group col-md-10 offset-md-1">
                                                <label for="procedimiento_indicacion">Descripción</label>
                                                <textarea class="form-control form-control-sm" id="procedimiento_indicacion" name="procedimiento_indicacion" rows="5" maxlength="975" style="text-align: justify;" disabled></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="hallazgos">
                                        <div class="row text-center">
                                            <div class="form-group col-md-10 offset-md-1">
                                                <label for="procedimiento_hallazgos">Descripción</label>
                                                <textarea class="form-control form-control-sm" id="procedimiento_hallazgos" name="procedimiento_hallazgos" rows="5" maxlength="975" style="text-align: justify;" disabled></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="endoscopico">
                                        <div class="row text-center">
                                            <div class="form-group col-md-10 offset-md-1">
                                                <label for="procedimiento_diagnostico">Descripción</label>
                                                <textarea class="form-control form-control-sm" id="procedimiento_diagnostico" name="procedimiento_diagnostico" rows="5" maxlength="975" style="text-align: justify;" disabled></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="recomendaciones">
                                        <div class="row text-center">
                                            <div class="form-group col-md-10 offset-md-1">
                                                <label for="procedimiento_recomendacion">Descripción</label>
                                                <textarea class="form-control form-control-sm" id="procedimiento_recomendacion" name="procedimiento_recomendacion" rows="5" maxlength="975" style="text-align: justify;" disabled></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- /detalle de procedimientos -->
                    </div>
                </div>
                <div class="tab-pane" id="hospitalizacion">
                    <div class="row">
                        <!-- lista de hospitalizaciones -->
                        <div class="col-md-3">
                            <div class="card card-info">
                                <div class="card-header text-center">
                                    <p>Admisiones</p>
                                </div>
                                <div class="card-body">
                                    
                                    <tr>
                                        <td>
                                            <table class="table table-sm text-center">
                                                <thead>
                                                    <tr>
                                                        <th>Admision</th>
                                                        <th>Fecha</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($pListaH as $pLh)
                                                        <tr>
                                                            <td>
                                                                <a href="#" onclick="trae_egreso({{ $pLh->id }}); return false;"> {{ $pLh->admision }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                {{ \Carbon\Carbon::parse($pLh->fecha)->format('d/m/Y') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </div>
                                <div class="card-footer">
                                    {!! $pListaC->render() !!}
                                </div>
                            </div>
                        </div>
                        <!-- /lista de hospitalizaciones -->
                        <!-- detalle de hospitalizaciones -->
                        <div class="col-md-9">
                            <br>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Admisión</label>
                                    </div>
                                    <input type="text" id="hadmision" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-4">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Medico</label>
                                    </div>
                                    <input type="text" id="hmedico_nombre" class="form-control" style="text-align: center;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-4">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Hospital</label>
                                    </div>
                                    <input type="text" id="hhospital_nombre" class="form-control" style="text-align: center;" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Fecha</label>
                                    </div>
                                    <input type="text" id="hfecha" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Edad</label>
                                    </div>
                                    <input type="text" id="hedad" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-6">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Aseguradora</label>
                                    </div>
                                    <input type="text" id="haseguradora_nombre" class="form-control" style="text-align: right;" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-5">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Póliza</label>
                                    </div>
                                    <input type="text" id="hpoliza" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Deducible</label>
                                    </div>
                                    <input type="text" id="hdeducible" class="form-control" style="text-align: right;" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Co pago</label>
                                    </div>
                                    <input type="text" id="hcopago" class="form-control" style="text-align: right;" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Fecha Inicio</span>
                                    </div>
                                    <input type="date" class="form-control form-control-sm" id="fecha_inicio" name="fecha_inicio" required value="{{ old('fecha_inicio')}}" disabled>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-5">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Fecha Fin</span>
                                    </div>
                                    <input type="date" class="form-control form-control-sm" id="fecha_fin" name="fecha_fin" required value="{{ old('fecha_fin')}}" disabled>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="form-group col-md-10 offset-md-1">
                                    <label for="resumen_egreso">Descripción</label>
                                    <textarea class="form-control form-control-sm" id="resumen_egreso" name="resumen_egreso" rows="5" maxlength="975" style="text-align: justify;" disabled></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- /detalle de hospitalizaciones -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal mensajes -->
    <div class="modal fade" id="mensajeModal" tabindex="-1" role="dialog" aria-labelledby="mensajeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h3 class="modal_texto text-center" style="color: green;"></h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /modal mensajes -->
    <!-- Modal Cargar imagenes-->
    <div class="modal fade" id="CargaImagenes" role="dialog" aria-labelledby="CargaImagenesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!--<form action="{{ route('Admision_SubirImagen') }}" method="POST" enctype="multipart/form-data">-->
                <!--<div class="card">
                    <div class="card-body">
                        <form action="{{ route('Admision_SubirImagen') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" id="imagen_admision_id" name="imagen_admision_id">
                                <input type="file" name="file" id="" accept="image/*">
                                @error('file')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Subir Imagen</button>
                        </form>
                    </div>
                </div>-->
                <div class="card card-navy">
                    <div class="card-header">
                        <h6>Subir Imagenes</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('Admision_SubirImagen') }}" method="POST" class="dropzone text-center" id="cargaImagenesForm">
                            @csrf
                            <input type="hidden" id="imagen_admision_id" name="imagen_admision_id">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /modal cargar imagenes -->
@endsection
@section('js')
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminlte/plugins/moment/moment.min.js') }}">
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <!--<script type="text/javascript" src="{{ asset('assets/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/bootstrap-fileinput/js/locales/es.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/bootstrap-fileinput/themes/fas/theme.min.js') }}"></script> -->
    <script src="{{ asset('assets/summernote-0.8.18-dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('assets/dropzone-5.7.0/js/dropzone.js') }}"></script>
    <script type="text/javascript">
        
        //Dropzone.autoDiscover = false;
        Dropzone.options.cargaImagenesForm = {
            headers:{
                'X-CSRF-TOKEN' : "{{ csrf_token() }}"
            },
            paramName: 'file', // The name that will be used to transfer the file
            parallelUploads: 10,
            uploadMultiple: false,
            maxFilesize: 5, // MB
            ignoreHiddenFiles: true,
            acceptedFiles: "image/*",
            addRemoveLinks: false,
            dictDefaultMessage: 'presione clic Aqui para cargar las imagenes',
            //previewsContainer: false,
            //autoProcessQueue: true,
            success: function(file, response){
                swal({
                    title: 'Trabajo Finalizado !!!',
                    text: 'Imagenes cargadas con exito !!!',
                    type: 'success',
                },
                function(){
                    $('#CargaImagenes').modal('hide');    
                });
            }
            /*success:function(file, response) {
                console.log(response.payload);
            }*/
        };

        var dosis = [];


        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
            $('.select2bs4').select2({ theme: 'bootstrap4' })
        });

        /*$(document).ready(function() {
            $('#consulta_tratamiento').summernote();
        });*/

        $(function(){
            $('#consulta_tratamiento').summernote({
                height: 200,
                width: 800,
                fontSize: 12,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    //['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    //['para', ['ul', 'ol', 'paragraph']],
                    //['height', ['height']]
                ],
            });
        });

        window.onload = function() {
            SeleccionarTab('C');
            cargarUltimaConsulta();
            var imagenes_db = [];
            localStorage.clear(imagenes_db);
            localStorage.imagenes_db = JSON.stringify(imagenes_db);

            /*Dropzone.options.cargaImagenesForm = {
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
                },
                uploadMultiple: true,
                maxFileSize: 5,
                acceptedFiles: 'image/*',
                addRemoveLinks: true,
                dictDefaultMessage: "Cargar imagenes aqui !!!",

                init: function init(){
                    this.on('error', function(){
                        alert('error al subir el archivo');
                    });
                }
            }*/

        };

        function SeleccionarTab(id){
            document.getElementById('ultimotab').value = id;
            switch (id) {
                case 'C': cargarUltimaConsulta();
                break;
                case 'P': cargarUltimoProcedimiento();
                break;
                case 'H': cargarUltimoEgreso();
                break;
            }
        };

        //=======================================================================
        // actualizar dosis cuando cambia el medicamento
        //=======================================================================
        $(document).ready(function(){
            var da = null;
            @foreach($pDosis as $d)
                da = {'id' : {{$d->id}}, 'descripcion': '{{ $d->dosis->descripcion }}', 'medicamento_id' : {{ $d->medicamento_id }}, 'descripcion_receta' : '{{ $d->dosis->descripcion_receta }}'
                    };
                dosis.push(da);
            @endforeach;
            $('#tratamiento_medicamento_id').on('change', function(){
                var medicamentoId = $(this).val();
                var html = '<option value="" >Seleccione una dosis</option>';
                dosis.forEach(function(value,index,array){
                    if(value.medicamento_id == medicamentoId)
                    {
                        html += '<option value="'+value.id+'" >'+value.descripcion+'</option>';
                    }
                });
                $('#tratamiento_dosis_id').html(html);
            });
        });

        //=======================================================================
        // convertir formato de fecha
        //=======================================================================
        function convertDateFormat(string) {
            var info = string.split('-').reverse().join('/');
            return moment(string).format('DD/MM/YYYY');
        }

        function isBlank(str) {
            return (!str || /^\s*$/.test(str));
        }
        
        //=======================================================================
        // llena datos de consulta seleccionada
        //=======================================================================
        function trae_consulta($id){
            $.ajax({
                url: "{{ route('trae_consulta') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}",id: $id},
                success: function(response){
                    //console.log(response);
                    var info = response;
                    if (isBlank(info.detalle_id)) {
                        document.getElementById("cdetalle_id").value = 0;
                    } else {
                        document.getElementById("cdetalle_id").value = info.detalle_id;
                    }
                    //console.log(info);
                    document.getElementById("admision_id").value = info.id;
                    document.getElementById("cadmision").value = info.admision;
                    document.getElementById("cmedico_nombre").value = info.medico_nombre;
                    document.getElementById("chospital_nombre").value = info.hospital_nombre;
                    document.getElementById("cfecha").value = convertDateFormat(info.fecha);
                    document.getElementById("cedad").value = info.edad;
                    document.getElementById("caseguradora_nombre").value = info.aseguradora_nombre;
                    document.getElementById("cpoliza").value = info.poliza_no;
                    document.getElementById("cdeducible").value = info.deducible;
                    document.getElementById("ccopago").value = info.copago;
                    document.getElementById("peso").value = info.peso;
                    document.getElementById("talla").value = info.talla;
                    document.getElementById("bmi").value = parseFloat(info.bmi);
                    document.getElementById("pulso").value = info.pulso;
                    document.getElementById("temperatura").value = info.temperatura;
                    document.getElementById("respiracion").value = info.respiracion;
                    document.getElementById("presion").value = info.presion_sistolica+'/'+info.presion_diastolica;
                    document.getElementById("consulta_subjetivo").value = info.subjetivo;
                    document.getElementById("consulta_objetivo").value = info.objetivo;
                    document.getElementById("consulta_impresion_clinica").value = info.impresion_clinica;
                    document.getElementById("consulta_plan").value = info.plan;
                    $("#consulta_tratamiento").summernote("code", info.tratamiento);
                    document.getElementById("peso").disabled = false;
                    document.getElementById("talla").disabled = false;
                    document.getElementById("pulso").disabled = false;
                    document.getElementById("temperatura").disabled = false;
                    document.getElementById("respiracion").disabled = false;
                    document.getElementById("presion").disabled = false;
                    document.getElementById("consulta_subjetivo").disabled = false;
                    document.getElementById("consulta_objetivo").disabled = false;
                    document.getElementById("consulta_impresion_clinica").disabled = false;
                    document.getElementById("consulta_plan").disabled = false;
                    document.getElementById("consulta_tratamiento").disabled = false;

                    document.createElement("buttom").append();

                    document.getElementById("impresion_receta").setAttribute('href', asset+'admisiones/generar_receta/'+info.id );
                    document.getElementById('bmi_show').innerHTML = '<h3>'+parseFloat(info.bmi).toFixed(2)+'</h3>';
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
        //=======================================================================
        // trae procedimiento
        //=======================================================================
        function trae_procedimiento($id){
            $.ajax({
                url: "{{ route('trae_procedimiento') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}",id: $id},
                success: function(response){
                    var info = JSON.parse(response);
                    if (isBlank(info.detalle_id)) {
                        document.getElementById("pdetalle_id").value = 0;
                    } else {
                        document.getElementById("pdetalle_id").value = info.detalle_id;
                    }
                    document.getElementById("admision_id").value = info.id;
                    document.getElementById("imagen_admision_id").value = info.id;
                    document.getElementById("padmision").value = info.admision;
                    document.getElementById("pmedico_nombre").value = info.medico_nombre;
                    document.getElementById("phospital_nombre").value = info.hospital_nombre;
                    document.getElementById("pfecha").value = convertDateFormat(info.fecha);
                    document.getElementById("pedad").value = info.edad;
                    document.getElementById("paseguradora_nombre").value = info.aseguradora_nombre;
                    document.getElementById("ppoliza").value = info.poliza_no;
                    document.getElementById("pdeducible").value = info.deducible;
                    document.getElementById("pcopago").value = info.copago;
                    document.getElementById("procedimiento_procedimiento_id").value = info.procedimiento_id;
                    document.getElementById("procedimiento_premedicacion").value = info.premedicacion;
                    if (info.tolerancia == 'B') {
                        radiobtn = document.getElementById("buena");
                        radiobtn.checked = true;
                    }
                    if (info.tolerancia == 'R') {
                        radiobtn = document.getElementById("regular");
                        radiobtn.checked = true;
                    }
                    if (info.tolerancia == 'M') {
                        radiobtn = document.getElementById("mala");
                        radiobtn.checked = true;
                    }
                    document.getElementById("anestesiologo").value = info.anestesiologo;
                    document.getElementById("patologo").value = info.patologo;
                    document.getElementById("procedimiento_indicacion").value = info.indicacion;
                    document.getElementById("procedimiento_hallazgos").value = info.hallazgos;
                    document.getElementById("procedimiento_diagnostico").value = info.diagnostico;
                    document.getElementById("procedimiento_recomendacion").value = info.recomendaciones;

                    document.getElementById("procedimiento_procedimiento_id").disabled = false;
                    document.getElementById("procedimiento_premedicacion").disabled = false;
                    document.getElementById("buena").disabled = false;
                    document.getElementById("regular").disabled = false;
                    document.getElementById("mala").disabled = false;
                    document.getElementById("anestesiologo").disabled = false;
                    document.getElementById("patologo").disabled = false;
                    document.getElementById("procedimiento_indicacion").disabled = false;
                    document.getElementById("procedimiento_hallazgos").disabled = false;
                    document.getElementById("procedimiento_diagnostico").disabled = false;
                    document.getElementById("procedimiento_recomendacion").disabled = false;
                    trae_fotos($id);
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function trae_fotos($id){
            var x = $id;
            $.ajax({
                url: "{{ route('trae_imagenes_procedimiento') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}",admision_id: $id},
                success: function(response){
                    var info = response;
                    if (info.length > 0) {
                        var html = '';
                        for (var i = 0; i < info.length; i++) {
                            //console.log(info[i]['admision_id']);
                            var admision_id = info[i]['admision_id']
                            var nombre_imagen_mini = info[i]['nombre_imagen_mini']
                            html += `<div class="col-md-3">
                                        <div class="card border-primary mb-3 ml-3" style="max-width: 18rem;">
                                            <div class=“button-container”>
                                                <a data-fancybox="gallery" href="{{ asset('storage/`+admision_id+`/originales/`+nombre_imagen_mini+`') }}">
                                                <img src="{{ asset('storage/`+admision_id+`/mini/`+nombre_imagen_mini+`')}}" class="test-popup-link" style="display: flex; max-width: 180px; width: auto; height: auto; margin-bottom: 10px; margin-right: 5px; object-fit: cover; border: 1; align: middle">
                                                </a>
                                            </div>`;
                            if (info[i]['informe'] == 'N') {
                                html += '<a href=“#” class="btn btn-sm btn-danger" title="Agregar a Informe" onclick="fn_agregar_informe('+info[i]['id']+'); return false;">Agregar</a>'
                            } else {
                                html += '<a href=“#” class="btn btn-sm btn-success" title="Agregar a Informe" onclick="fn_agregar_informe('+info[i]['id']+'); return false;">Agregar</a>'
                            }


                            html += `
                                        </div>
                                     </div>`;
                        }
                        //$('#tblimagenes tbody').append(html);
                        document.getElementById('mostrar_fotos').innerHTML = html;

                        document.getElementById('informe').setAttribute('href', asset+'admisiones/generar_informe/'+admision_id);
                    }else{
                        html += '';
                        document.getElementById('mostrar_fotos').innerHTML = html;
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
        //=======================================================================
        // trae egreso
        //=======================================================================
        function trae_egreso($id){
            $.ajax({
                url: "{{ route('trae_egreso') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}",id: $id},
                success: function(response){
                    var info = JSON.parse(response);
                    if (isBlank(info.detalle_id)) {
                        document.getElementById("hdetalle_id").value = 0;
                    } else {
                        document.getElementById("hdetalle_id").value = info.detalle_id;
                    }
                    document.getElementById("admision_id").value = info.id;
                    document.getElementById("hadmision").value = info.admision;
                    document.getElementById("hmedico_nombre").value = info.medico_nombre;
                    document.getElementById("hhospital_nombre").value = info.hospital_nombre;
                    document.getElementById("hfecha").value = convertDateFormat(info.fecha);
                    document.getElementById("hedad").value = info.edad;
                    document.getElementById("haseguradora_nombre").value = info.aseguradora_nombre;
                    document.getElementById("hpoliza").value = info.poliza_no;
                    document.getElementById("hdeducible").value = info.deducible;
                    document.getElementById("hcopago").value = info.copago;
                    document.getElementById("fecha_inicio").value = info.fecha_inicio;
                    document.getElementById("fecha_fin").value = info.fecha_fin;
                    document.getElementById("resumen_egreso").value = info.resumen_egreso;
                    document.getElementById("fecha_inicio").disabled = false;
                    document.getElementById("fecha_fin").disabled = false;
                    document.getElementById("resumen_egreso").disabled = false;
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
        //=======================================================================
        // cargar ultima consulta
        //=======================================================================
        function cargarUltimaConsulta(){
            var paciente_id = document.getElementById("paciente_id").value;
            if (document.getElementById("cdetalle_id").value == 0) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: "{{ route('ultimaconsulta_ajax') }}",
                    data: {paciente_id: paciente_id},
                    success: function(response) {           
                        //console.log(response);
                        var info = JSON.parse(response);
                        trae_consulta(info)
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
        }

        function cargarUltimoProcedimiento(){
            var paciente_id = document.getElementById("paciente_id").value;

            if (document.getElementById("pdetalle_id").value == 0) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: "{{ route('ultimoprocedimiento_ajax') }}",
                    data: {paciente_id: paciente_id},
                    success: function(response) {           
                        var info = JSON.parse(response);
                        trae_procedimiento(info)
                        trae_fotos(info)
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }               
        }

        //=======================================================================
        // Cargar ultima Hospitalizacion
        //=======================================================================
        function cargarUltimoEgreso(){
            var paciente_id = document.getElementById("paciente_id").value;
            if (document.getElementById("hdetalle_id").value == 0) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: "{{ route('ultimoegreso_ajax') }}",
                    data: {paciente_id: paciente_id},
                    success: function(response) {           
                        //console.log(response);
                        var info = JSON.parse(response);
                        trae_egreso(info)
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
        }


        //=======================================================================
        // copiar Dosis
        //=======================================================================
        function copiarDosis(){
            var x = document.getElementById("tratamiento_medicamento_id").selectedIndex;
            var y = document.getElementById("tratamiento_medicamento_id").options;
            var medicamento_descripcion = y[x].text;
            var medicamento_id = $('#tratamiento_medicamento_id').val();
            var dosisId = $('#tratamiento_dosis_id').val();

            if(dosisId == ''){
                    alert('Seleccione una dosis.');
                    return;
            }else{
                $.ajax({
                    url: "{{ route('receta_descripcion') }}",
                    type: "POST",
                    async: true,
                    data: {"_token": "{{ csrf_token() }}", dosis_id:dosisId},
                    success: function(response){
                        //console.log(response);
                        if (response != 'null') {
                            var info = response;
                            var anterior = document.getElementById("consulta_tratamiento").value;
                            /*document.getElementById("consulta_tratamiento").value = document.getElementById("consulta_tratamiento").value + medicamento_descripcion + ' -> '+ info.descripcion_receta;*/
                            $("#consulta_tratamiento").summernote("code", anterior+' '+medicamento_descripcion+' '+info.descripcion_receta);
                        }
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
        }

        //=====================================================================
        // proceso de acutalizacion
        //=====================================================================
        function grabar(){
            switch (document.getElementById('ultimotab').value) {
                case 'C': update_consulta();
                break;
                case 'P': update_procedimiento();
                break;
                case 'H': update_egreso();
                break;
            }
        }

        //=====================================================================
        // actualizar consulta
        //=====================================================================
        function update_consulta(){
            var detalle_id   = document.getElementById("cdetalle_id").value;
            //alert('detalle_id = '+detalle_id);
            console.log(detalle_id);
            var admision_id  = document.getElementById("admision_id").value;
            var paciente_id  = document.getElementById("paciente_id").value;
            var peso         = document.getElementById("peso").value;
            var talla        = document.getElementById("talla").value;
            var pulso        = document.getElementById("pulso").value;
            var bmi          = document.getElementById("bmi").value;
            var temperatura  = document.getElementById("temperatura").value;
            var respiracion  = document.getElementById("respiracion").value;
            var presion      = document.getElementById("presion").value;
            var consulta_subjetivo   = document.getElementById("consulta_subjetivo").value;
            var consulta_objetivo    = document.getElementById("consulta_objetivo").value;
            var consulta_impresion_clinica  = document.getElementById("consulta_impresion_clinica").value;
            var consulta_plan        = document.getElementById("consulta_plan").value;
            var consulta_tratamiento = document.getElementById("consulta_tratamiento").value;

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: "{{ route('actconsulta_ajax') }}",
                data: {detalle_id: detalle_id, admision_id: admision_id, paciente_id: paciente_id, peso: peso, talla: talla, pulso: pulso, temperatura: temperatura, respiracion: respiracion, presion: presion, consulta_subjetivo: consulta_subjetivo, consulta_objetivo: consulta_objetivo, consulta_impresion_clinica: consulta_impresion_clinica, consulta_plan: consulta_plan, consulta_tratamiento: consulta_tratamiento, bmi: bmi},
                success: function(response) {           
                    //$('#consdetalle_id').val(response.id);
                    document.getElementById("cdetalle_id").value = response.id;
                    swal({
                        title: 'Trabajo Finalizado !!!',
                        text: response.respuesta,
                        type: 'success',
                    });
                    /*$('.modal_texto').html(response.respuesta);
                    $("#mensajeModal").modal('show');*/
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        //=====================================================================
        // actualizar procedimiento
        //=====================================================================
        function update_procedimiento(){
            var detalle_id       = document.getElementById("pdetalle_id").value;
            var admision_id      = document.getElementById("admision_id").value;
            var paciente_id      = document.getElementById("paciente_id").value;
            var procedimiento_id = document.getElementById("procedimiento_procedimiento_id").value;
            var premedicacion    = document.getElementById("procedimiento_premedicacion").value;
            if (document.getElementById("buena").checked == true) {
                var tolerancia       = 'B';
            }
            if (document.getElementById("regular").checked == true) {
                var tolerancia       = 'R';
            }
            if (document.getElementById("mala").checked == true) {
                var tolerancia       = 'M';
            }
            var anestesiologo    = document.getElementById("anestesiologo").value;
            var patologo         = document.getElementById("patologo").value;
            var indicacion       = document.getElementById("procedimiento_indicacion").value;
            var hallazgos        = document.getElementById("procedimiento_hallazgos").value;
            var diagnostico      = document.getElementById("procedimiento_diagnostico").value;
            var recomendaciones  = document.getElementById("procedimiento_recomendacion").value;
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: "{{ route('actprocedimiento_ajax') }}",
                data: {detalle_id: detalle_id, admision_id: admision_id, paciente_id: paciente_id, procedimiento_id: procedimiento_id, premedicacion: premedicacion, tolerancia: tolerancia, anestesiologo: anestesiologo, patologo: patologo,indicacion: indicacion, hallazgos: hallazgos, diagnostico:diagnostico, recomendaciones: recomendaciones },
                success: function(response) {           
                    $('#pdetalle_id').val(response.id);
                    swal({
                        title: 'Trabajo Finalizado !!!',
                        text: response.respuesta,
                        type: 'success',
                    });
                    /*
                    $('.modal_texto').html(response.respuesta);
                    $("#mensajeModal").modal('show');*/
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
        //=====================================================================
        // actualizar hospitalizacion
        //=====================================================================
        function update_egreso(){
            var detalle_id     = document.getElementById("hdetalle_id").value;
            var admision_id    = document.getElementById("admision_id").value;
            var paciente_id    = document.getElementById("paciente_id").value;
            var fecha_inicio   = document.getElementById("fecha_inicio").value;
            var fecha_fin      = document.getElementById("fecha_fin").value;
            var resumen_egreso = document.getElementById("resumen_egreso").value;

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: "{{ route('acthospitalizacion_ajax') }}",
                data: {detalle_id: detalle_id, admision_id: admision_id, paciente_id: paciente_id, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, resumen_egreso: resumen_egreso},
                success: function(response) {           
                    swal({
                        title: 'Trabajo Finalizado !!!',
                        text: response.respuesta,
                        type: 'success',
                    });
                    /*$('.modal_texto').html(response.respuesta);
                    $("#mensajeModal").modal('show');*/
                },
                error: function(error){
                    swal({
                        title: 'Error !!!',
                        text: error,
                        type: 'error',
                    });
                    /*$('.modal_texto').html(error);
                    $("#mensajeModal").modal('show');*/
                }
            });
        }

        function fn_agregar_informe($id_imagen){
            var admision_id = document.getElementById('admision_id').value;
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: "{{ route('imagen_informe') }}",
                data: {id : $id_imagen},
                success: function(response){
                    trae_fotos(admision_id);
                },
                error: function(error){
                    swal({
                        title: 'Error !!!',
                        text: error,
                        type: 'error',
                    });
                    /*
                    $('.modal_texto').html(error);
                    $("#mensajeModal").modal('show');*/
                }
            });
        }

        function confirma_salida(){
            var origen   = document.getElementById('origen').value;
            var paciente = document.getElementById('paciente_id').value;
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
                        /*if (origen == 'P') {
                            window.location.href = "http://localhost:8888/granai/public/pacientes/editar/"+paciente;
                        }
                        if (origen == 'A') {
                            window.location.href = "{{ route('nueva_agenda') }}";
                        }*/
                        history.back();
                        
                    } 
                }
            );
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
                closeOnConfirm: true,
                allowEscapeKey: true
                },
                function(isConfirm) {
                    if (isConfirm) { 
                        $('#'+modal).modal('hide');
                        //$(".modal-backdrop").remove();
                        //swal.close();
                    }
                }
            );
        }

        $(function(){
            $("#cargaImagenesForm").submit(function(){
                carga_imagenes();
                return false;
            })
        });

        function carga_imagenes(){
           var admision_id = document.getElementById('admision_id').value;
           //alert(fileList);
        }

        function fn_calcula_bmi(){
            var peso  = document.getElementById('peso').value;
            var talla = document.getElementById('talla').value;
            if (peso > 0 && talla > 0) {
                peso = peso / 2.2;
                talla *= talla
                document.getElementById('bmi').value = peso / talla;
                document.getElementById('bmi_show').innerHTML = '<h3>'+(peso / talla).toFixed(2)+'</h3>';
            }
        }
    </script>
@endsection