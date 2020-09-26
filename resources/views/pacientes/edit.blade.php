@extends('admin.layout')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('titulo')
    Pacientes
@endsection
@section('subtitulo')
    Agregar
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

    <form role="form" method="POST" action="{{route('actualizar_paciente', $pPaciente->id)}}">
        @csrf
        <div class="card card-navy">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-2 offset-md-10" style="text-align: right;">
                        <button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                        <a href="{{route('nueva_admision', [$pPaciente->id, 'P'])}}" class="btn btn-sm btn-info" title="ver Consultas"><i class="fas fa-user-md"></i></a>
                        <!--<a href="{{route('pacientes')}}" class="btn btn-sm btn-danger" title="Regresar a lista de Pacientes"><i class="fas fa-sign-out-alt"></i></a>    -->
                        <a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                    </div>
                </div>
            </div>
            <form class="form-horizontal">
                <div class="card-body">
                    <div class="row">
                        <div class="input-group mb-1 input-group-sm col-md-4 offset-md-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Código&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            </div>
                            <input type="number" class="form-control" id="codigo_id" name="codigo_id" autofocus required value="{{ $pPaciente->codigo_id }}" style="text-align: right;" readonly tabindex="10">
                        </div>
                        <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Nombres</span>
                            </div>
                            <input type="text" class="form-control" id="nombres" name="nombres" required value="{{ $pPaciente->nombres }}" tabindex="13">
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group mb-1 input-group-sm col-md-4 offset-md-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Expediente&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            </div>
                            <input type="number" class="form-control form-control-sm" id="expediente_no" name="expediente_no" required value="{{ $pPaciente->expediente_no }}" style="text-align: right;" tabindex="11" onchange="verificar_expediente(); return false;">
                        </div>
                        <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Apellidos</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="apellidos" name="apellidos" required value="{{ $pPaciente->apellidos }}" tabindex="14">
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group mb-1 input-group-sm col-md-4 offset-md-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Expediente Anterior</span>
                            </div>
                            <input type="number" class="form-control form-control-sm" id="expediente_anterior_no" name="expediente_anterior_no" value="{{ $pPaciente->expediente_anterior_no }}" style="text-align: right;" tabindex="12">
                        </div>
                        <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Apellido Casada</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="apellido_casada" name="apellido_casada" value="{{ $pPaciente->apellido_casada }}" tabindex="15">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Fch. de Nacimiento</span>
                            </div>
                            <input type="date" class="form-control form-control-sm" id="fecha_nacimiento" name="fecha_nacimiento" required value="{{ $pPaciente->fecha_nacimiento }}" tabindex="16">
                        </div>
                        <div class="col-md-5">
                            <div class="form-group form-control-sm clearfix">
                                <label for="masculino">Genero</label>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="masculino" name="genero" value="M" tabindex="17" @if($pPaciente->genero == 'M') then checked @endif>
                                    <label for="masculino">Masculino</label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="negativo" name="genero" value="F" tabindex="18" @if($pPaciente->genero == 'F') then checked @endif>
                                    <label for="negativo">Femenino</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Dirección</span>
                            </div>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $pPaciente->direccion }}" tabindex="19">
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group mb-1 input-group-sm col-md-4 offset-md-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">email</span>
                            </div>
                            <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="{{ $pPaciente->correo_electronico }}" tabindex="20">
                        </div>
                        <div class="input-group mb-1 input-group-sm col-md-4 offset-md-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">telefonos</span>
                            </div>
                            <input type="text" class="form-control" id="telefonos" name="telefonos" value="{{ $pPaciente->telefonos }}" tabindex="21">
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group mb-1 input-group-sm col-md-4 offset-md-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">fax</span>
                            </div>
                            <input type="text" class="form-control" id="fax" name="fax" value="{{ $pPaciente->fax }}" tabindex="22">
                        </div>
                        <div class="input-group mb-1 input-group-sm col-md-4 offset-md-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">celular</span>
                            </div>
                            <input type="text" class="form-control" id="celular" name="celular" value="{{ $pPaciente->celular }}" tabindex="23">
                        </div>
                    </div>
                    <hr>
                    <div class="row" style="background-color: #ECF4FF;">
                        <div class="col-md-10 offset-md-1">
                            <ul class="nav nav-tabs ml-auto p-2">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#generales" data-toggle="tab">Generales</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#antecedentes" data-toggle="tab">Antecedentes</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="generales">
                                    <div class="row">
                                        <div class="mb-1 input-group-sm col-md-5">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" for="religion">Religion</span>
                                                </div>
                                                <select class="custom-select custom-select-sm select2 select2bs4" id="religion" name="religion" tabindex="24">
                                                    <option value="">Seleccionar...</option>
                                                    <option value="C" @if($pPaciente->religion == 'C') then selected @endif>Católico</option>
                                                    <option value="E" @if($pPaciente->religion == 'E') then selected @endif>Evangélico</option>
                                                    <option value="T" @if($pPaciente->religion == 'T') then selected @endif>Testigo de Jehova</option>
                                                    <option value="M" @if($pPaciente->religion == 'M') then selected @endif>Mormon</option>
                                                    <option value="O" @if($pPaciente->religion == 'O') then selected @endif>Otros</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="input-group mb-1 input-group-sm col-md-6 offset-md-1">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Recomendado</span>
                                            </div>
                                            <input type="text" class="form-control" id="referido_por" name="referido_por" value="{{ $pPaciente->referido_por }}" tabindex="25">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-5">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" for="aseguradora_id">Aseguradora</span>
                                                </div>
                                                <select class="custom-select  custom-select-sm select2 select2bs4" id="aseguradora_id"  name="aseguradora_id" tabindex="26">
                                                    <option value="">Seleccionar...</option>
                                                    @foreach($pAseguradoras as $pAseguradora)
                                                        <option value="{{ $pAseguradora->id}}" @if($pPaciente->aseguradora_id == $pAseguradora->id) then selected @endif>{{ $pAseguradora->nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="input-group mb-1 input-group-sm col-md-6 offset-md-1">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Póliza</span>
                                            </div>
                                            <input type="text" class="form-control" id="seguro_no" name="seguro_no" value="{{ $pPaciente->seguro_no }}" tabindex="27">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group mb-1">
                                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                <input type="checkbox" class="custom-control-input" id="recordar_cita" name="recordar_cita" value="S" tabindex="28" @if($pPaciente->recordar_cita == 'S') then checked @endif>
                                                <label class="custom-control-label" for="recordar_cita">Recordar Cita</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ul class="nav nav-tabs ml-auto p-2">
                                                <li class="nav-item">
                                                    <a class="nav-link active" href="#profesional" data-toggle="tab">Profesional</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#conyugue" data-toggle="tab">Conyugue</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#emergencia" data-toggle="tab">Emergencia</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#facturacion" data-toggle="tab">Facturación</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="profesional">
                                                    <div class="row">
                                                        <div class="input-group mb-1 input-group-sm col-md-5">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Profesion</span>
                                                            </div>
                                                            <input type="text" class="form-control" id="profesion" name="profesion" value="{{ $pPaciente->profesion }}" tabindex="29">
                                                        </div>
                                                        <div class="input-group mb-1 input-group-sm col-md-6 offset-md-1">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Empresa</span>
                                                            </div>
                                                            <input type="text" class="form-control" id="trabajo_nombre" name="trabajo_nombre" value="{{ $pPaciente->trabajo_nombre }}" tabindex="30">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="input-group mb-1 input-group-sm col-md-5">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Telefono</span>
                                                            </div>
                                                            <input type="text" class="form-control" id="trabajo_telefono" name="trabajo_telefono" value="{{ $pPaciente->trabajo_telefono }}" tabindex="31">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="conyugue">
                                                    <div class="row">
                                                        <div class="input-group mb-1 input-group-sm col-md-5">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" for="estado_civil">Estado Civil</span>
                                                            </div>
                                                            <select class="custom-select custom-select-sm select2 select2bs4" id="estado_civil" name="estado_civil" tabindex="32">
                                                                <option value="">Seleccionar...</option>
                                                                <option value="S" @if($pPaciente->estado_civil == 'S') then selected @endif>Soltero /(a)</option>
                                                                <option value="C" @if($pPaciente->estado_civil == 'C') then selected @endif>Casado /(a)</option>
                                                                <option value="V" @if($pPaciente->estado_civil == 'V') then selected @endif>Viudo /(a)</option>
                                                                <option value="D" @if($pPaciente->estado_civil == 'D') then selected @endif>Divorciado /(a)</option>
                                                                <option value="U" @if($pPaciente->estado_civil == 'U') then selected @endif>Unido /(a)</option>
                                                            </select>
                                                        </div>
                                                        <div class="input-group mb-1 input-group-sm col-md-6 offset-md-1">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Nombre</span>
                                                            </div>
                                                            <input type="text" class="form-control" id="conyugue_nombre" name="conyugue_nombre" value="{{ $pPaciente->conyugue_nombre }}" tabindex="33">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="input-group mb-1 input-group-sm col-md-5">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Ocupación</span>
                                                            </div>
                                                            <input type="text" class="form-control" id="conyugue_ocupacion" name="conyugue_ocupacion" value="{{ $pPaciente->conyugue_ocupacion }}" tabindex="34">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="emergencia">
                                                    <div class="row">
                                                        <div class="input-group mb-1 input-group-sm col-md-5">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" for="emergencia_parentesco_id">Parentesco</span>
                                                            </div>
                                                            <select class="custom-select custom-select-sm select2 select2bs4" id="emergencia_parentesco_id" name="emergencia_parentesco_id" tabindex="35">
                                                                <option value="">Seleccionar...</option>
                                                                <option value="AB" @if($pPaciente->emergencia_parentesco_id == 'AB') then selected @endif>Abuelo /(a)</option>
                                                                <option value="HI" @if($pPaciente->emergencia_parentesco_id == 'HI') then selected @endif>Hijo /(a)</option>
                                                                <option value="HE" @if($pPaciente->emergencia_parentesco_id == 'HE') then selected @endif>Hermano /(a)</option>
                                                                <option value="ES" @if($pPaciente->emergencia_parentesco_id == 'ES') then selected @endif>Esposo /(a)</option>
                                                                <option value="PA" @if($pPaciente->emergencia_parentesco_id == 'PA') then selected @endif>Padre</option>
                                                                <option value="MA" @if($pPaciente->emergencia_parentesco_id == 'MA') then selected @endif>Madre</option>
                                                                <option value="TI" @if($pPaciente->emergencia_parentesco_id == 'TI') then selected @endif>Tio /(a)</option>
                                                                <option value="PR" @if($pPaciente->emergencia_parentesco_id == 'PR') then selected @endif>Primo /(a)</option>
                                                                <option value="CU" @if($pPaciente->emergencia_parentesco_id == 'CU') then selected @endif>Cuñado /(a)</option>
                                                                <option value="AM" @if($pPaciente->emergencia_parentesco_id == 'AM') then selected @endif>Amigo /(a)</option>
                                                            </select>
                                                        </div>
                                                        <div class="input-group mb-1 input-group-sm col-md-6 offset-md-1">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Nombre</span>
                                                            </div>
                                                            <input type="text" class="form-control" id="emergencia_nombre" name="emergencia_nombre" value="{{ $pPaciente->emergencia_nombre }}" tabindex="36">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="input-group mb-1 input-group-sm col-md-5">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Teléfonos</span>
                                                            </div>
                                                            <input type="text" class="form-control" id="emergencia_telefonos" name="emergencia_telefonos" value="{{ $pPaciente->emergencia_telefonos }}" tabindex="37">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="facturacion">
                                                    <div class="row">
                                                        <div class="input-group mb-1 input-group-sm col-md-5">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">N.I.T.</span>
                                                            </div>
                                                            <input type="text" class="form-control" id="factura_nit" name="factura_nit" value="{{ $pPaciente->factura_nit }}" tabindex="38">
                                                        </div>
                                                        <div class="input-group mb-1 input-group-sm col-md-6 offset-md-1">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Nombre</span>
                                                            </div>
                                                            <input type="text" class="form-control" id="factura_nombre" name="factura_nombre" value="{{ $pPaciente->factura_nombre }}" tabindex="39">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="input-group mb-1 input-group-sm col-md-12">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Dirección</span>
                                                            </div>
                                                            <input type="text" class="form-control" id="factura_direccion" name="factura_direccion" value="{{ $pPaciente->factura_direccion }}" tabindex="40">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="antecedentes">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ul class="nav nav-tabs ml-auto p-2">
                                                <li class="nav-item">
                                                    <a class="nav-link active" href="#medico" data-toggle="tab">Medico</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#quirurgico" data-toggle="tab">Quirurgico - Traumas</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#alergia" data-toggle="tab">Alergias</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#ginecologico" data-toggle="tab">Gineco-Obstetra</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#familiar" data-toggle="tab">Familiares</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#medicamento" data-toggle="tab">Medicamentos</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#habito" data-toggle="tab">Habitos</a>
                                                </li>
                                            </ul>
                                            <hr>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="medico">
                                                    <div class="row text-center">
                                                        <div class="form-group col-md-10 offset-md-1">
                                                            <label for="antmedico_descripcion">Descripción</label>
                                                            <textarea class="form-control form-control-sm" id="antmedico_descripcion" name="antmedico_descripcion" rows="3">{{ $pPaciente->antmedico_descripcion }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="quirurgico">
                                                    <div class="row text-center">
                                                        <div class="form-group col-md-10 offset-md-1">
                                                            <label for="antquirurgico_descripcion">Descripción</label>
                                                            <textarea class="form-control form-control-sm" id="antquirurgico_descripcion" name="antquirurgico_descripcion" rows="3">{{ $pPaciente->antquirurgico_descripcion }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="alergia">
                                                    <div class="row text-center">
                                                        <div class="form-group col-md-10 offset-md-1">
                                                            <label for="antalergia_descripcion">Descripción</label>
                                                            <textarea class="form-control form-control-sm" id="antalergia_descripcion" name="antalergia_descripcion" rows="3">{{ $pPaciente->antalergia_descripcion }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="ginecologico">
                                                    <div class="row text-center">
                                                        <div class="form-group col-md-10 offset-md-1">
                                                            <label for="antgineco_descripcion">Descripción</label>
                                                            <textarea class="form-control form-control-sm" id="antgineco_descripcion" name="antgineco_descripcion" rows="3">{{ $pPaciente->antgineco_descripcion }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="familiar">
                                                    <div class="row text-center">
                                                        <div class="form-group col-md-10 offset-md-1">
                                                            <label for="antfamiliar_descripcion">Descripción</label>
                                                            <textarea class="form-control form-control-sm" id="antfamiliar_descripcion" name="antfamiliar_descripcion" rows="3">{{ $pPaciente->antfamiliar_descripcion }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="medicamento">
                                                    <div class="row text-center">
                                                        <div class="form-group col-md-10 offset-md-1">
                                                            <label for="antmedicamento_descripcion">Descripción</label>
                                                            <textarea class="form-control form-control-sm" id="antmedicamento_descripcion" name="antmedicamento_descripcion" rows="3">{{ $pPaciente->antmedicamento_descripcion }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="habito">
                                                    <div class="row text-center">
                                                        <div class="col-md-5 offset-md-1">
                                                            <div class="card card-secondary">
                                                                <div class="card-header">Fumador</div>
                                                                <div class="card-body">
                                                                    <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Cantidad</span>
                                                                        </div>
                                                                        <input type="number" class="form-control" id="tabaco_cnt" name="tabaco_cnt" value="{{ $pPaciente->tabaco_cnt }}" style="text-align: right;">
                                                                    </div>
                                                                    <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Tiempo</span>
                                                                        </div>
                                                                        <input type="number" class="form-control" id="tabaco_tiempo" name="tabaco_tiempo" value="{{ $pPaciente->tabaco_tiempo }}" style="text-align: right;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5 offset-md-1">
                                                            <div class="card card-secondary">
                                                                <div class="card-header">Bebida</div>
                                                                <div class="card-body">
                                                                    <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Cantidad</span>
                                                                        </div>
                                                                        <input type="number" class="form-control" id="alcohol_cnt" name="alcohol_cnt" value="{{ $pPaciente->alcohol_cnt }}" style="text-align: right;">
                                                                    </div>
                                                                    <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Tiempo</span>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="alcohol_tiempo" name="alcohol_tiempo" value="{{ $pPaciente->alcohol_cnt }}" style="text-align: right;">
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
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </form>
@endsection
@section('js')
    <script src="{{ asset('assets/adminlte/dist/js/demo.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
    <script type="text/javascript">
        //========================================================================
        // inicializar librerias
        //========================================================================
        $(function () {
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        });

        var validaciones = true;

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
                        window.location.href = "{{ route('pacientes') }}";
                                    } 
                    else { 
                        swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                        }
                }
            );
            /*alertify.confirm('<i class="fas fa-sign-out-alt"></i> Salir', '<h4>Esta seguro de salir de Paciente ? </h4>', function(){ 
            history.back();
                }
                , function(){ alertify.error('Se deja sin efecto')}
            );*/
        }

        function verificar_expediente(){
            var expediente = document.getElementById('expediente_no').value;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('verificar_expediente') }}",
                method: "POST",
                data: {expediente: expediente
                      },
                success: function(response){
                    if (response != 0) {
                        swal({
                              title: "Precaución",   
                              text: "Numero de Expediente ya existe, Favor verifique",   
                              type: "warning" 
                        },
                        function(){
                            //document.getElementById('expediente_no').value = '';
                            document.getElementById("expediente_no").focus();
                            //validaciones = false;
                        });
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });

        }
    </script>
@endsection