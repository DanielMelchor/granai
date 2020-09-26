@extends('admin.layout')
@section('titulo')
	Empresas
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
    <!-- form start -->
    <form role="form" method="POST" action="{{ route('actualizar_empresa', $pEmpresa->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="card card-navy">
            <div class="card-header">
                <div class="col-md-2 offset-md-10" style="text-align: right;">
                    <button type="submit" class="btn btn-sm btn-secondary btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                    <a href="{{route('empresas')}}" class="btn btn-sm btn-danger" title="Regresar a lista de Empresas"><i class="fas fa-sign-out-alt"></i></a>   
                </div>
            </div>
            <form class="form-horizontal">
                <div class="card-body">
                    <ul class="nav nav-tabs ml-auto p-2">
                      <li class="nav-item">
                        <a class="nav-link active" href="#generales" data-toggle="tab">Generales</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#logotipo" data-toggle="tab">Logotipo</a>
                      </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="generales">
                            <br>
                            <div class="row">
                                <div class="input-group mb-1 col-md-5 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Razon social</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Razon social" aria-label="Username" aria-describedby="basic-addon1" placeholder="Razón Social" id="razon_social" name="razon_social" autofocus required value="{{ $pEmpresa->razon_social }}">
                                </div>

                                <div class="input-group mb-1 col-md-5">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Nombre Comercial</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="nombre comercial" aria-label="Username" aria-describedby="basic-addon1" placeholder="nombre comercial" id="nombre_comercial" name="nombre_comercial" required value="{{ $pEmpresa->nombre_comercial }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Dirección</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="direccion" aria-label="Username" aria-describedby="basic-addon1" placeholder="direccion" id="direccion" name="direccion" value="{{ $pEmpresa->direccion }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 col-md-5 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Telefonos</span>
                                    </div>
                                    <input type="phone" class="form-control" placeholder="Telefonos" aria-label="Username" aria-describedby="basic-addon1" placeholder="Telefonos" id="telefonos" name="telefonos" required value="{{ $pEmpresa->telefonos }}">
                                </div>

                                <div class="input-group mb-1 col-md-5">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Fecha Constitución</span>
                                    </div>
                                    <input type="date" class="form-control" placeholder="fecha constitucion" aria-label="Username" aria-describedby="basic-addon1" placeholder="Fecha de creacion" id="fecha_constitucion" name="fecha_constitucion" required value="{{ $pEmpresa->Fecha_constitucion }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 col-md-5 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">N.I.T.</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="numero identificacion tributaria" aria-label="Username" aria-describedby="basic-addon1" id="igss_empresa" name="igss_empresa" value="{{ $pEmpresa->igss_empresa }}">
                                </div>

                                <div class="input-group mb-1 col-md-5">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">% I.V.A.</span>
                                    </div>
                                    <input type="number" class="form-control" placeholder="porcentaje de impuesto" aria-label="Username" aria-describedby="basic-addon1" id="porcentaje_impuesto" name="porcentaje_impuesto" value="{{ $pEmpresa->porcentaje_impuesto }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group offset-md-1">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                      <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A" @if($pEmpresa->estado == 'A') then checked @endif>
                                      <label class="custom-control-label" for="estado">Activar</label>
                                    </div>
                                  </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="logotipo">
                            <br>
                            <div class="row">
                                <div class="form-group col offset-md-1">
                                    <label for="logo_empresa">Seleccionar:</label>
                                    <input type="file" name="logo_empresa" id="logo_empresa" accept="image/*" />
                                </div>
                            </div>
                            @if (!empty( $pEmpresa->ruta_logo ))
                                <div class="image_wrapper col offset-md-5" id="img1">
                                    <img src="{{ asset('') }}{{ $pEmpresa->ruta_logo }}">
                                    <a href="{{ route('borrar_logo', $pEmpresa->id) }}"><i class="fas fa-trash-alt" style="color: red;"></i></a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </form>
    <!-- /form start -->
@endsection
