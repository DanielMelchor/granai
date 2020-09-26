@extends('admin.layout')

@section('titulo')
	Medicamentos
@endsection
@section('subtitulo')
	Agregar
@endsection

@section('contenido')
	@if(Session::has('message'))
   		<div class="alert alert-success alert-dismissible" role="alert">
	    	<button type="button" class="close" data-dismiss="alert" arial-label="Close"><span aria-      hidden="true">x</span>
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
	<form role="form" method="POST" action="{{route('grabar_medicamento')}}">
		@csrf
        <div class="card card-navy">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-2 offset-md-10" style="text-align: right;">
                        <button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                        <!--<a href="{{route('medicamentos')}}" class="btn btn-sm btn-danger" title="Regresar a lista de Dosis"><i class="fas fa-sign-out-alt"></i></a>-->
                        <a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                    </div>
                </div>
            </div>
            <form class="form-horizontal">
                <div class="card-body">
                    <div class="row">
                        <div class="input-group mb-5 col-md-5 offset-md-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Nombre</span>
                            </div>
                            <input type="text" class="form-control" placeholder="nombre medicamento" aria-label="Username" aria-describedby="basic-addon1" id="nombre" name="nombre" autofocus required value="{{ old('nombre')}}">
                        </div>

                        <div class="form-group offset-md-1">
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
                                <label class="custom-control-label" for="estado">Activar</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1 offset-md-11">
                            <a href="#" class="btn btn-primary btn-sm" title="Agregar Dosis" onclick="agregarDestino(); return false;">
                                <i class="fas fa-plus-circle"></i>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="table-responsive">
                                <table class="table table-stripped table-hover" id="tableDestinos">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Dosis</th>
                                            <th class="text-center">Descripción</th>
                                            <th class="text-center">Estado</th>
                                            <th width="35px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </form>
@endsection
@section('scripts')
	<script type="text/javascript">
		var nDestino = 0;
		function agregarDestino()
        {
            var html = '';
            html += '<tr>';
            html += '<td width="125px">';
            html += '<select name="destinos['+nDestino+'][destino_id]" class="form-control" data-required="true">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($destinos as $destino)
            html += '<option value="{{$destino->id}}">{{$destino->descripcion}}</option>';
            @endforeach
            html += '</select>';
            html += '</td>';
            
            html += '<td width="250px">';
            html += '<input type="text" class="form-control" step="any" name="destinos['+nDestino+'][descripcion]" />';
            html += '</td>';
            html += '<td width="50px" style="text-align: center;">';
            html += '<p> Alta </p>';
            html += '</td>';

            html += '<td width="35px"><a href="#" class="btn btn-sm btn-danger eliminar"><i class="fas fa-trash-alt"></i></a></td>';
            
            html += '</tr>';
            $('#tableDestinos tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nDestino++;
        }
        function eliminar()
        {
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
        }
	</script>
@endsection
@section('js')
    <script type="text/javascript">
        var nDestino = 0;
        let ruta = '';
        
        window.onload = function() {
          ruta = document.referrer;
        };

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
                        window.location.href = "{{ route('medicamentos') }}";
                                    } 
                    else { 
                        swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                        }
                }
            );
        }


        function agregarDestino()
        {
            var html = '';
            html += '<tr>';
            html += '<td width="125px">';
            html += '<select name="destinos['+nDestino+'][destino_id]" class="form-control" data-required="true">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($destinos as $destino)
            html += '<option value="{{$destino->id}}">{{$destino->descripcion}}</option>';
            @endforeach
            html += '</select>';
            html += '</td>';
            
            html += '<td width="250px">';
            html += '<input type="text" class="form-control" step="any" name="destinos['+nDestino+'][descripcion]" />';
            html += '</td>';
            html += '<td width="50px" style="text-align: center;">';
            html += '<h4> Alta </h4>';
            html += '</td>';

            html += '<td width="35px"><a href="#" class="btn btn-sm btn-danger eliminar"><i class="fas fa-trash-alt"></i></a></td>';
            
            html += '</tr>';
            $('#tableDestinos tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nDestino++;
        }
        function eliminar()
        {
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
        }

    </script>
@endsection