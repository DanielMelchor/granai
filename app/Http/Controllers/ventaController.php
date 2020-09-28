<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Redirect;
use Response;
use Carbon\carbon;
use Illuminate\Http\Request;
use App\maestro_documento;
use App\detalle_documento;
use App\Tipo_documento;
use App\forma_pago;
use App\Banco;
use App\Caja;
use App\Resolucion;
use App\Paciente;
use App\Producto;
use App\bitacora_admision;
use App\maestro_pago;
use App\detalle_pago;
use App\pago_documento;
use App\MotivoAnulacion;
use App\MotivoRechazo;
use App\vw_venta_documento;
use App\Corte;

class ventaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$listado = DB::table('maestro_documentos as md')
    			->join('tipo_documentos as td', 'md.tipodocumento_id', 'td.id')
    			->join('detalle_documentos as dd', 'md.id', 'dd.maestro_documento_id')
                ->leftjoin('vw_venta_pago_documentos as pd', 'md.id', 'pd.maestro_documento_id')
    			->where('md.empresa_id', Auth::user()->empresa_id)
                ->where('md.tipodocumento_id', 1)
    			->select('md.id','td.descripcion as tipo_descripcion', 'md.serie', 'md.correlativo', 'md.fecha_emision', 'md.nit', 'md.nombre', 'md.estado',
    				DB::raw('(CASE WHEN md.estado = "A" THEN "Vigente" ELSE "Anulada" END) AS estado_descripcion'), DB::raw('SUM(dd.precio_bruto) as precio_bruto'), DB::raw('SUM(dd.descuento) as descuento'), DB::raw('SUM(dd.recargo) as recargo'), DB::raw('SUM(dd.precio_neto) as precio_neto'), 'pd.total_pagado')
    			->groupBy('md.id','td.descripcion', 'md.serie', 'md.correlativo', 'md.fecha_emision', 'md.nit', 'md.nombre', 'md.estado', 'pd.total_pagado')
    			->get();
    	return view('ventas.documentos_index', compact('listado'));

    }

    public function index_nc(){
        $listado = DB::table('maestro_documentos as md')
                   ->join('tipo_documentos as td', 'md.tipodocumento_id', 'td.id')
                   ->join('detalle_documentos as dd', 'md.id', 'dd.maestro_documento_id')
                   ->where('md.empresa_id', Auth::user()->empresa_id)
                   ->where('md.tipodocumento_id', 2)
                   ->select('md.id', 'td.descripcion as tipo_descripcion', 'md.serie', 'md.correlativo', 'md.fecha_emision', 'md.serie_afecta', 'md.correlativo_afecto', 'md.nit', 'md.nombre', 'md.estado', DB::raw('(CASE WHEN md.estado = "A" THEN "Vigente" ELSE "Anulada" END) AS estado_descripcion'), DB::raw('SUM(dd.precio_neto) as precio_neto'))
                   ->groupBy('md.id', 'td.descripcion', 'md.serie', 'md.correlativo', 'md.fecha_emision', 'md.serie_afecta', 'md.correlativo_afecto', 'md.nit', 'md.nombre', 'md.estado')
                   ->get();
        return view('ventas.nc_index', compact('listado'));
    }

    public function index_nd(){
        $listado = DB::table('maestro_documentos as md')
                   ->join('tipo_documentos as td', 'md.tipodocumento_id', 'td.id')
                   ->join('detalle_documentos as dd', 'md.id', 'dd.maestro_documento_id')
                   ->where('md.empresa_id', Auth::user()->empresa_id)
                   ->where('md.tipodocumento_id', 3)
                   ->select('md.id', 'td.descripcion as tipo_descripcion', 'md.serie', 'md.correlativo', 'md.fecha_emision', 'md.serie_afecta', 'md.correlativo_afecto', 'md.nit', 'md.nombre', 'md.estado', DB::raw('(CASE WHEN md.estado = "A" THEN "Vigente" ELSE "Anulada" END) AS estado_descripcion'), DB::raw('SUM(dd.precio_neto) as precio_neto'))
                   ->groupBy('md.id', 'td.descripcion', 'md.serie', 'md.correlativo', 'md.fecha_emision', 'md.serie_afecta', 'md.correlativo_afecto', 'md.nit', 'md.nombre', 'md.estado')
                   ->get();
        //dd($listado);
        return view('ventas.nd_index', compact('listado'));
    }

    public function factura_create($admision_id, $paciente_id){
    	$hoy         = Carbon::now()->format('Y-m-d');
    	$documento   = Tipo_documento::findOrFail(1);
    	$productos   = Producto::where('estado', 'A')->get();
        $pacientes   = Paciente::all();
    	$caja        = Caja::where('id', Auth::user()->caja_id)->first();
        $formas_pago = forma_pago::where('estado', 'A')->get();
        $bancos      = Banco::where('tipo_referencia', 'B')->where('estado', 'A')->get();
        $tarjetas    = Banco::where('tipo_referencia', 'T')->where('estado', 'A')->get();
    	if (empty($caja)) {
    		return Redirect::back()->withErrors('Usuario no permitido para emitir Facturas');
    	} else{
    		$resolucion = Resolucion::where('caja_id', Auth::user()->caja_id)->where('tipo_documento', 1)->where('estado', 'A')->count();
    		if ($resolucion == 0) {
    			return Redirect::back()->withErrors('Caja no cuenta con una resolucion Activa que permita emitir Facturas');
    		} else{
    			return view('ventas.factura_create', compact('documento', 'hoy', 'caja', 'pacientes', 'productos', 'admision_id', 'paciente_id', 'bancos', 'tarjetas', 'formas_pago'));
    		}
    	}
    }

    public function nc_create(){
        $hoy       = Carbon::now()->format('Y-m-d');
        $documento = Tipo_documento::findOrFail(2);
        $pacientes = Paciente::all();
        $caja      = Caja::where('id', Auth::user()->caja_id)->first();
        if (empty($caja)) {
            return Redirect::back()->withErrors('Usuario no permitido para emitir Notas de Crédito');
        } else{
            $resolucion = Resolucion::where('caja_id', Auth::user()->caja_id)->where('tipo_documento', 2)->where('estado', 'A')->count();
            if ($resolucion == 0) {
                return Redirect::back()->withErrors('Caja no cuenta con una resolucion Activa que permita emitir Notas de Crédito');
            } else{
                return view('ventas.nc_create', compact('documento', 'hoy', 'caja', 'pacientes'));
            }
        }   
    }

    public function nd_create(){
        $hoy       = Carbon::now()->format('Y-m-d');
        $documento = Tipo_documento::findOrFail(3);
        $pacientes = Paciente::all();
        $bancos    = Banco::where('tipo_referencia', 'B')->get();
        $caja      = Caja::where('id', Auth::user()->caja_id)->first();
        $motivos   = MotivoRechazo::where('estado', 'A')->get();
        if (empty($caja)) {
            return Redirect::back()->withErrors('Usuario no permitido para emitir Notas de Debito');
        } else{
            $resolucion = Resolucion::where('caja_id', Auth::user()->caja_id)->where('tipo_documento', 3)->where('estado', 'A')->count();
            if ($resolucion == 0) {
                return Redirect::back()->withErrors('Caja no cuenta con una resolucion Activa que permita emitir Notas de Débito');
            } else{
                return view('ventas.nd_create', compact('documento', 'hoy', 'caja', 'pacientes', 'bancos', 'motivos'));
            }
        }   
    }

    public function factura_edit($id, $admision_id){
        $hoy        = Carbon::now()->format('Y-m-d');
        $documento  = Tipo_documento::findOrFail(1);
        $pacientes  = Paciente::all();
        $encabezado = maestro_documento::findOrFail($id);
        $detalle    = detalle_documento::where('maestro_documento_id', $id)->get();
        $listado    = MotivoAnulacion::where('estado', 'A')->get();
        $caja       = Caja::where('id', Auth::user()->caja_id)->first();
        $pago = DB::table('maestro_documentos as md')
                ->join('pago_documentos as pd', 'md.id', 'pd.maestro_documento_id')
                ->leftjoin('maestro_pagos as mp', 'pd.maestro_pago_id', 'mp.id')
                ->leftjoin('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                ->leftjoin('bancos as b', 'dp.banco_id', 'b.id')
                ->where('md.id', $id)
                ->select(DB::raw('(CASE dp.forma_pago when "E" then "Efectivo" when "B" then "Cheque" else "Tarjeta" end) as forma_pago'), 'b.nombre as entidad_nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                ->groupBy(DB::raw('(CASE dp.forma_pago when "E" then "Efectivo" when "B" then "Cheque" else "Tarjeta" end)'), 'b.nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                ->get();
        return view('ventas.factura_edit', compact('documento', 'encabezado', 'detalle', 'admision_id', 'listado', 'pacientes', 'pago', 'caja', 'hoy'));
    }

    public function nc_edit($id){
        $hoy        = Carbon::now()->format('Y-m-d');
        $encabezado = maestro_documento::findOrFail($id);
        $caja       = Caja::where('id', Auth::user()->caja_id)->first();
        $detalle    = detalle_documento::where('maestro_documento_id', $id)->get();
        $total      = detalle_documento::where('maestro_documento_id', $id)->sum('precio_neto');
        $documento  = Tipo_documento::findOrFail($encabezado->tipodocumento_id);
        $pacientes  = Paciente::all();
        $listado    = MotivoAnulacion::where('estado', 'A')->get();
        return view('ventas.nc_edit', compact('documento', 'encabezado', 'detalle', 'listado', 'pacientes', 'total', 'hoy', 'caja'));
    }

    public function factura_store(){
        $resolucion_id    = $_POST['resolucion_id'];
        $admision_id    = $_POST['admision_id'];
        $paciente_id    = $_POST['paciente_id'];
        $tipodocumento_id = $_POST['tipo_documento_id'];
        $fecha = $_POST['fecha_emision'];
        $serie = strtoupper($_POST['serie']);
        $correlativo = $_POST['correlativo'];
        $condicion   = $_POST['condicion'];
        $nit = $_POST['nit'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $data = (array) json_decode($_POST['arreglo'], true);
        $dataPago = (array) json_decode($_POST['pagos'], true);
        $totalRegistros = count($data);
        $totalRegistrosPago = count($dataPago);
        $cadena_error = '';
        $hoy = Carbon::now()->format('Y-m-d');

        $tipo_documento = Tipo_documento::findOrFail($tipodocumento_id)->first();
        
        $existe = maestro_documento::where('empresa_id', Auth::user()->empresa_id)->where('tipodocumento_id', 1)->where('serie', $serie)->where('correlativo', $correlativo)->count();

        if ($totalRegistros == 0) {
            $cadena_error = $cadena_error.', No existe detalle de factura ';
        }
        if ($existe > 0) {
            $cadena_error = $cadena_error.', Factura '.$serie.' - '.$correlativo.' Ya existe';
        }
        if ($condicion == 0 && $totalRegistrosPago == 0) {
            $cadena_error = $cadena_error.', No existe forma de pago de documento ';
        }

        $recibo_resolucion = Resolucion::where('caja_id', Auth::user()->caja_id)->where('tipo_documento',4)->where('estado', 'A')->first();

        if (!isset($recibo_resolucion)) {
            $recibo_resolucion_id = 0;
            $recibo_resolucion_serie = '0';
            $recibo_resolucion_correlativo = 0;
            $cadena_error = $cadena_error. ', Caja no cuenta con una resolucion que permita generar recibos de pago';
        }else {
            $recibo_resolucion_id          = $recibo_resolucion->id;
            $recibo_resolucion_serie       = $recibo_resolucion->serie;
            $recibo_resolucion_correlativo = $recibo_resolucion->ultimo_correlativo + 1;
        }

        if ($cadena_error != '') {
            $respuesta1 = array('estado' => '0','respuesta' => $cadena_error);
        }
        else{
            $detalle_total = 0;
            $pago_total    = 0;

            /*Encabezado de Factura*/
            $maestro = new maestro_documento();
            $maestro->empresa_id = Auth::user()->empresa_id;
            $maestro->caja_id    = Auth::user()->caja_id;
            $maestro->tipodocumento_id = $tipodocumento_id;
            $maestro->resolucion_id    = $resolucion_id;
            $maestro->fecha_emision    = $fecha;
            $maestro->serie       = $serie;
            $maestro->correlativo = $correlativo;
            $maestro->paciente_id = $paciente_id;
            $maestro->condicion   = $condicion;
            $maestro->nit         = $nit;
            $maestro->nombre      = $nombre;
            $maestro->direccion   = $direccion;
            $maestro->estado      = 'A';
            $maestro->save();

            /*Actualiza la resolucion*/
            $factura_resolucion = Resolucion::findOrFail($resolucion_id);
            $factura_resolucion->ultimo_correlativo = $correlativo;
            $factura_resolucion->save();

            /*Detalle de factura*/
            for ($i=0; $i < $totalRegistros ; $i++) {
                $detalle = new detalle_documento();
                $detalle->maestro_documento_id      = $maestro->id;
                $detalle->admision_cargo_detalle_id = $data[$i]['cargo_detalle_id'];
                $detalle->producto_id     = $data[$i]['producto_id'];
                $detalle->descripcion     = $data[$i]['descripcion'];
                $detalle->signo           = $tipo_documento->signo;
                $detalle->cantidad        = $data[$i]['cantidad'];
                $detalle->precio_unitario = $data[$i]['precio_unitario'];
                $detalle->precio_bruto    = $data[$i]['precio_total'];
                $detalle->descuento       = 0;
                $detalle->recargo         = 0;
                $detalle->precio_neto     = $data[$i]['precio_total'];
                $detalle->precio_base     = intval($data[$i]['precio_total'])/1.12;
                $detalle->precio_impuesto = $detalle->precio_neto - $detalle->precio_base;
                $detalle->estado = 'A';
                $detalle->save();

                $detalle_total += $detalle->precio_neto;

                /*Bitacora de admision*/
                $bitacora = new bitacora_admision();
                $bitacora->admision_id   = $admision_id;
                $bitacora->proceso       = 'FACTURA';
                $bitacora->observaciones = 'Factura '.$maestro->serie.' - '.$maestro->correlativo.' cargo '.$detalle->descripcion;
                $bitacora->save();
            }

            /*Pago de documento*/
            if ($condicion == 0) {
                /*Encabezado de Recibo*/
                $recibo = new maestro_pago();
                $recibo->empresa_id       = Auth::user()->empresa_id;
                $recibo->caja_id          = Auth::user()->caja_id;
                $recibo->paciente_id      = $paciente_id;
                $recibo->tipodocumento_id = 4;
                $recibo->resolucion_id    = $recibo_resolucion_id;
                $recibo->fecha_emision    = $hoy;
                $recibo->serie            = $recibo_resolucion_serie;
                $recibo->correlativo      = $recibo_resolucion_correlativo;
                $recibo->estado           = 'A';
                $recibo->save();

                /*Actualiza resolucion de recibo*/
                $resolucion_recibo = Resolucion::findOrFail($recibo->resolucion_id);
                $resolucion_recibo->ultimo_correlativo = $recibo_resolucion_correlativo;
                $resolucion_recibo->save();

                for ($i=0; $i < $totalRegistrosPago; $i++) { 
                    /*Detalle de recibo*/
                    $recibo_detalle = new detalle_pago();
                    $recibo_detalle->maestro_pago_id = $recibo->id;
                    $recibo_detalle->forma_pago   = $dataPago[$i]['forma_pago'];
                    $recibo_detalle->banco_id     = $dataPago[$i]['entidad_id'];
                    $recibo_detalle->cuenta_no    = $dataPago[$i]['cuenta_no'];
                    $recibo_detalle->documento_no = $dataPago[$i]['documento_no'];
                    $recibo_detalle->autoriza_no  = $dataPago[$i]['autoriza_no'];
                    $recibo_detalle->monto        = $dataPago[$i]['monto'];
                    $recibo_detalle->estado       = 'A';
                    $recibo_detalle->save();

                    $pago_total += $recibo_detalle->monto;

                    /*Pago de documento*/
                    $pago_documento = new pago_documento();
                    $pago_documento->maestro_documento_id = $maestro->id;
                    $pago_documento->maestro_pago_id      = $recibo->id;
                    if ($admision_id != 0) {
                        $pago_documento->admision_id      = $admision_id;
                    }else{
                        $pago_documento->admision_id      = null;
                    }
                    $pago_documento->saldo_documento      = $detalle_total;
                    $pago_documento->total_aplicado       = $recibo_detalle->monto;
                    $pago_documento->estado               = 'A';
                    $pago_documento->save();
                }
            }

            $respuesta1 = array('estado' => 'A','respuesta' => 'Factura '.$serie.'-'.$correlativo.' Grabada con exito !!!', 'factura_id' => $maestro->id);
            return Response::json($respuesta1);
        }
    }

    public function nd_store(){
        $tipo_documento_id = $_POST['tipo_documento_id'];
        $resolucion_id     = $_POST['resolucion_id'];
        $fecha_emision     = $_POST['fecha_emision'];
        $serie             = $_POST['serie'];
        $correlativo       = $_POST['correlativo'];
        $banco_id          = $_POST['banco_id'];
        $cheque_no         = $_POST['cheque_no'];
        $paciente_id       = $_POST['paciente_id'];
        $motivo_id         = $_POST['motivo_id'];
        $otros_cobros      = $_POST['otros_cobros'];
        $observaciones     = $_POST['observaciones'];
        $nit               = $_POST['nit'];
        $nombre            = $_POST['nombre'];
        $direccion         = $_POST['direccion'];
        $recibo_id         = $_POST['recibo_id'];

        /*======================================================================
        Creacion de encabezado de nota de debito
        ======================================================================*/
        $maestro = new maestro_documento();
        $maestro->empresa_id       = Auth::user()->empresa_id;
        $maestro->caja_id          = Auth::user()->caja_id;
        $maestro->tipodocumento_id = $tipo_documento_id;
        $maestro->resolucion_id    = $resolucion_id;
        $maestro->fecha_emision    = $fecha_emision;
        $maestro->serie            = $serie;
        $maestro->correlativo      = $correlativo;
        $maestro->paciente_id      = $paciente_id;
        $maestro->condicion        = 0;
        $maestro->nit              = $nit;
        $maestro->nombre           = $nombre;
        $maestro->direccion        = $direccion;
        $maestro->estado           = 'A';
        $maestro->save();

        /*======================================================================
        localiza detalle de documentos pagados con el recibo
        ======================================================================*/
        $detalle_original = DB::table('pago_documentos as pd')
                            ->join('maestro_documentos as md', 'pd.maestro_documento_id', 'md.id')
                            ->join('detalle_documentos as dd', 'md.id', 'dd.maestro_documento_id')
                            ->where('pd.maestro_pago_id', $recibo_id)
                            ->select('dd.admision_cargo_detalle_id', 'dd.producto_id', 'dd.descripcion', 'dd.cantidad', 'dd.precio_unitario', 'dd.precio_bruto', 'dd.descuento', 'dd.recargo', 'dd.precio_neto', 'dd.precio_base', 'dd.precio_impuesto')
                            ->get();

        /*======================================================================
        Creacion detalle de nota de debito
        ======================================================================*/
        foreach ($detalle_original as $do) {
            $detalle = new detalle_documento();
            $detalle->maestro_documento_id = $maestro->id;
            $detalle->admision_cargo_detalle_id = $do->admision_cargo_detalle_id;
            $detalle->producto_id          = $do->producto_id;
            $detalle->descripcion          = $do->descripcion;
            $detalle->cantidad             = $do->cantidad;
            $detalle->precio_unitario      = $do->precio_unitario;
            $detalle->precio_bruto         = $do->precio_bruto;
            $detalle->descuento            = $do->descuento;
            $detalle->recargo              = $do->recargo;
            $detalle->precio_neto          = $do->precio_neto;
            $detalle->precio_base          = $do->precio_base;
            $detalle->precio_impuesto      = $do->precio_impuesto;
            $detalle->estado               = 'A';
            $detalle->save();
        }

        /*======================================================================
        Si se cobro monto por cheque rechazado lo agrega a detalle de nota de debito
        ======================================================================*/
        if ($otros_cobros > 0) {
            $producto = Producto::findOrFail(1);
            $detalle = new detalle_documento();
            $detalle->maestro_documento_id = $maestro->id;
            $detalle->admision_cargo_detalle_id = null;
            $detalle->producto_id          = $producto->id;
            $detalle->descripcion          = $producto->descripcion;
            $detalle->cantidad             = 1;
            $detalle->precio_unitario      = $otros_cobros;
            $detalle->precio_bruto         = $otros_cobros;
            $detalle->descuento            = 0;
            $detalle->recargo              = 0;
            $detalle->precio_neto          = $otros_cobros;
            $detalle->precio_base          = $otros_cobros / 1.12;
            $detalle->precio_impuesto      = $detalle->precio_neto - $detalle->precio_base;
            $detalle->estado               = 'A';
            $detalle->save();
        }

        /*======================================================================
        Marcar cheque como rechazado
        ======================================================================*/
        $marcar_cheque = detalle_pago::where('maestro_pago_id', $recibo_id)->first();
        $marcar_cheque->estado = 'R';
        $marcar_cheque->save();

        $respuesta = array('parametro' => 0,'respuesta' => 'Nota de Debito '.$serie.'-'.$correlativo.' Grabada con exito !!!', 'nd_id' => $maestro->id);

        return Response::json($respuesta);
    }

    public function nc_store(){
        $resolucion_id    = $_POST['resolucion_id'];
        $paciente_id      = $_POST['paciente_id'];
        $tipodocumento_id = $_POST['tipo_documento_id'];
        $fecha            = $_POST['fecha_emision'];
        $serie            = strtoupper($_POST['serie']);
        $correlativo      = $_POST['correlativo'];
        $condicion        = $_POST['condicion'];
        $nit              = $_POST['nit'];
        $nombre           = $_POST['nombre'];
        $direccion        = $_POST['direccion'];
        $tipo_documento_afecto_id = $_POST['tipo_documento_afecto_id'];
        $serie_afecta     = strtoupper($_POST['serie_afecta']);
        $documento_afecto = $_POST['documento_afecto'];
        $data = (array) json_decode($_POST['arreglo'], true);
        $totalRegistros = count($data);
        $cadena_error = '';
        $hoy = Carbon::now()->format('Y-m-d');
        $tipo_documento = Tipo_documento::findOrFail($tipodocumento_id)->first();

        $existe = maestro_documento::where('empresa_id', Auth::user()->empresa_id)->where('tipodocumento_id', 1)->where('serie', $serie)->where('correlativo', $correlativo)->count();

        if ($totalRegistros == 0) {
            $cadena_error = $cadena_error.', No existe detalle de para nota de credito ';
        }
        if ($existe > 0) {
            $cadena_error = $cadena_error.', Nota de Crédito '.$serie.' - '.$correlativo.' Ya existe';
        }

        if ($cadena_error != '') {
            $respuesta1 = array('estado' => '0', 'mensaje' => $cadena_error);
        }
        else{
            $maestro = new maestro_documento();
            $maestro->empresa_id             = Auth::user()->empresa_id;
            $maestro->caja_id                = Auth::user()->caja_id;
            $maestro->tipodocumento_id       = $tipodocumento_id;
            $maestro->resolucion_id          = $resolucion_id;
            $maestro->fecha_emision          = $fecha;
            $maestro->serie                  = $serie;
            $maestro->correlativo            = $correlativo;
            $maestro->paciente_id            = $paciente_id;
            $maestro->condicion              = $condicion;
            $maestro->nit                    = $nit;
            $maestro->nombre                 = $nombre;
            $maestro->direccion              = $direccion;
            $maestro->tipodocumentoafecto_id = $tipo_documento_afecto_id;
            $maestro->serie_afecta           = $serie_afecta;
            $maestro->correlativo_afecto     = $documento_afecto;
            $maestro->estado                 = 'A';
            $maestro->save();

            $factura_resolucion = Resolucion::findOrFail($resolucion_id);
            $factura_resolucion->ultimo_correlativo = $correlativo;
            $factura_resolucion->save();

            for ($i=0; $i < $totalRegistros ; $i++) {
                $detalle = new detalle_documento();
                $detalle->maestro_documento_id = $maestro->id;
                $detalle->admision_cargo_detalle_id = $data[$i]['cargo_detalle_id'];
                $detalle->producto_id = $data[$i]['producto_id'];
                $detalle->descripcion = $data[$i]['descripcion'];
                $detalle->signo       = '-1';
                $detalle->cantidad    = $data[$i]['cantidad'];
                $detalle->precio_unitario = $data[$i]['precio_unitario'];
                $detalle->precio_bruto    = $data[$i]['precio_bruto'];
                $detalle->descuento       = $data[$i]['descuento'];
                $detalle->recargo         = $data[$i]['recargo'];
                $detalle->precio_neto     = $data[$i]['precio_neto'];
                $detalle->precio_base     = intval($data[$i]['precio_neto'])/1.12;
                $detalle->precio_impuesto = $detalle->precio_neto - $detalle->precio_base;
                $detalle->estado = 'A';
                $detalle->save();
            }

            $respuesta1 = array('estado' => '1','mensaje' => 'Nota de Crédito '.$serie.'-'.$correlativo.' Grabada con exito !!!', 'nota_id' => $maestro->id);
        }
        return Response::json($respuesta1);
    }

    public function documento_anular(){
        
        $id = $_POST['documento_id'];

        
        $maestro = maestro_documento::findOrFail($id);
        $tipo_documento = Tipo_documento::findOrFail($maestro->tipodocumento_id);

        //verifica que no exista nota de credito vigente aplicada a la factura
        $existe_nota = maestro_documento::where('empresa_id', Auth::user()->empresa_id)
                       ->where('tipodocumentoafecto_id', $maestro->tipodocumento_id)
                       ->where('serie_afecta', $maestro->serie)
                       ->where('correlativo_afecto', $maestro->correlativo)
                       ->where('estado', 'A')
                       ->count();

        if ($existe_nota == 0) {
            // anulacion encabezado de documento
            $maestro->motivoanulacion_id    = $_POST['motivo_id'];
            $maestro->observacion_anulacion = $_POST['observacion'];
            $maestro->anulacion_usuario_id  = Auth::user()->id;
            $maestro->fecha_anulacion       = Carbon::now();
            $maestro->estado                = 'I';
            $maestro->save();

            // anulacion detalle de documento
            $detalle = detalle_documento::where('maestro_documento_id', $id)->get();

            foreach ($detalle as $d) {
                $detalle_factura = detalle_documento::findOrFail($d->id);
                $detalle_factura->admision_cargo_detalle_id = null;
                $detalle_factura->estado = 'I';
                $detalle_factura->save();
            }

            //anulacion pago de documento

            $pago = pago_documento::where('maestro_documento_id', $id)->get();

            foreach ($pago as $p) {
                $pago_documento = pago_documento::findOrFail($p->id);
                $pago_documento->estado = 'I';
                $pago_documento->save();
            }

            $respuesta = array('parametro' => 0, 'respuesta' => $tipo_documento->descripcion.' Anulada con Exito !!!');
        }else{
            $nota = maestro_documento::where('empresa_id', Auth::user()->empresa_id)
                    ->where('tipodocumentoafecto_id', $maestro->tipodocumento_id)
                    ->where('serie_afecta', $maestro->serie)
                    ->where('correlativo_afecto', $maestro->correlativo)
                    ->where('estado', 'A')
                    ->select('serie', 'correlativo')
                    ->first();

            $respuesta = array('parametro' => 1, 'respuesta' => $tipo_documento->descripcion.' '.$maestro->serie.'-'.$maestro->correlativo.' asociada a nota de crédito vigente '.$nota->serie.'-'.$nota->correlativo.', por lo que no es posible realizar la anulación ');
        }

        return Response::json($respuesta);
    }

    public function documentos_con_saldo(){
        $paciente_id = $_POST['paciente_id'];

        $listado = DB::table('vw_venta_documentos as vvd')
                   ->leftjoin('vw_venta_pago_documentos as vvpd', 'vvd.id', 'vvpd.maestro_documento_id')
                   ->where('vvd.empresa_id', Auth::user()->empresa_id)
                   //->whereIn('vvd.tipodocumento_id', [1, 3])
                   ->where('vvd.total_documento','>',0)
                   ->where('vvd.paciente_id', $paciente_id)
                   ->select('vvd.id','vvd.tipodocumento_id','vvd.tipodocumento_descripcion', 'vvd.fecha_emision', 'vvd.serie', 'vvd.correlativo', 'vvd.nit', 'vvd.nombre', 'vvd.total_documento', 'vvpd.total_pagado', 'vvd.admision_id', 'vvd.admision')
                   ->get();

        return Response::json($listado);
    }

    public function nc_doctos_aplicar(){
        $tipodocumento_id = $_POST['tipodocumento_id'];
        $serie            = strtoupper($_POST['serie']);
        $correlativo      = $_POST['correlativo'];

        $existe = maestro_documento::where('empresa_id', Auth::user()->empresa_id)
                  ->where('serie', $serie)
                  ->where('correlativo', $correlativo)
                  ->where('estado', 'A')
                  ->count();

        if ($existe > 0) {
            $encabezado = DB::table('maestro_documentos as md')
                          ->where('md.empresa_id', Auth::user()->empresa_id)
                          ->where('md.tipodocumento_id', $tipodocumento_id)
                          ->where('md.serie', $serie)
                          ->where('md.correlativo', $correlativo)
                          ->select('md.id','md.paciente_id', 'md.nit', 'md.nombre', 'md.direccion')
                          ->first();

            $detalle = DB::table('detalle_documentos as dd')
                       ->where('dd.maestro_documento_id', $encabezado->id)
                       ->select('producto_id', 'descripcion', 'cantidad', 'precio_unitario', 'precio_bruto', 'descuento', 'recargo', 'precio_neto', 'precio_base', 'precio_impuesto', 'estado', 'dd.admision_cargo_detalle_id')
                       ->get();

            $respuesta = array('resultado' => 0, 'encabezado' => $encabezado, 'detalle' => $detalle, 'mensaje' => 'ok');
        }else{
            $respuesta = array('resultado' => 1, 'mensaje' => 'Documento '.$serie.'-'.$correlativo.' No encontrado');
        }
        return Response::json($respuesta);
    }

    public function factura_renumerar($id, Request $request){
        $validData = $request->validate([
            'refactura_serie' => 'required',
            'refactura_correlativo' => 'required',
            'refactura_admision_id' => 'required'
        ]);

        try{
            $pExiste = maestro_documento::where('serie', strtoupper($validData['refactura_serie']))->where('correlativo', $validData['refactura_correlativo'])->count();

            if ($pExiste > 0) {
                return Redirect::back()->withErrors('Documento ya existe');
            }else{
                $pCaja_id    = Auth::user()->caja_id;
                $pCaja       = Caja::findOrFail($pCaja_id);
                $pResolucion = Resolucion::where('tipo_documento', 1)->where('estado', 'A')->where('caja_id', $pCaja_id)->where('serie', strtoupper($validData['refactura_serie']))->where('correlativo_inicial' ,'<=', $validData['refactura_correlativo'])->where('correlativo_final' ,'>=', $validData['refactura_correlativo'])->first();

                if (!empty($pResolucion)) {
                    $pFactura = maestro_documento::findOrFail($id);
                    $pFactura->resolucion_id = $pResolucion->id;
                    $pFactura->serie = $validData['refactura_serie'];
                    $pFactura->correlativo = $validData['refactura_correlativo'];
                    $pFactura->save();
                    return Redirect::route('editar_factura', [$pFactura->id, $validData['refactura_admision_id']])->with('message','Factura grabada con exito');
                }else{
                    return Redirect::back()->withErrors('Caja no cuenta con una resolucion que permita realizar la factura');
                }
            }
        }catch(Exception $e){
            dd('error '. $e);
        }
    }

    public function documento_renumerar(){
        $documento_id      = $_POST['documento_id'];
        $tipodocumento_id  = $_POST['tipodocumento_id'];
        $nueva_serie       = $_POST['nueva_serie'];
        $nuevo_correlativo = $_POST['nuevo_correlativo'];

        $Caja_id    = Auth::user()->caja_id;
        $Caja       = Caja::findOrFail($Caja_id);
        $tipo_documento = tipo_documento::findOrFail($tipodocumento_id);

        $existe = maestro_documento::where('empresa_id', Auth::user()->empresa_id)
                                     ->where('tipodocumento_id', $tipodocumento_id)
                                     ->where('serie', $nueva_serie)
                                     ->where('correlativo', $nuevo_correlativo)
                                     ->count();

        if ($existe == 0) {
            $Resolucion = Resolucion::where('tipo_documento', $tipodocumento_id)
                                      ->where('estado', 'A')
                                      ->where('caja_id', $Caja_id)
                                      ->where('serie', strtoupper($nueva_serie))
                                      ->where('correlativo_inicial' ,'<=', $nuevo_correlativo)
                                      ->where('correlativo_final' ,'>=', $nuevo_correlativo)
                                      ->first();
            if (!empty($Resolucion)) {
                $Factura = maestro_documento::findOrFail($documento_id);
                $Factura->resolucion_id = $Resolucion->id;
                $Factura->serie = $nueva_serie;
                $Factura->correlativo = $nuevo_correlativo;
                $Factura->save();

                $respuesta = array('parametro' => 0, 'respuesta' => $tipo_documento->descripcon.' '.$Factura->serie.'-'.$Factura->correlativo.' Actualizada con exito');
            }else{
                $respuesta = array('parametro' => 1, 'respuesta' => $Caja->nombre_maquina.' No cuenta con una resolucion activa que permita emitir el documento, favor Verifique');
            }
        }else{
            $respuesta = array('parametro' => 1, 'respuesta' => $tipo_documento->descripcion.' '.$nueva_serie.'-'.$nuevo_correlativo.' Ya se encuentra grabado en nuestros registros, Favor verifique');
        }

        return Response::json($respuesta);
    }

    public function factura_refacturar($id, Request $request){
        $validData = $request->validate([
            'refactura_admision_id1' => 'required',
            'refactura_serie'        => 'required',
            'refactura_correlativo'  => 'required',
            'refactura_nit'          => 'required',
            'refactura_nombre'       => 'required',
            'refactura_direccion'    => 'required',
            'refactura_motivo_id'    => 'required',
            'observacion_refactura'  => 'required'

        ]);

        try{
            $pExiste = maestro_documento::where('serie', strtoupper($validData['refactura_serie']))->where('correlativo', $validData['refactura_correlativo'])->count();

            if ($pExiste > 0) {
                return Redirect::back()->withErrors('Documento ya existe');
            }else{
                $pCaja_id    = Auth::user()->caja_id;
                $pCaja       = Caja::findOrFail($pCaja_id);
                $pResolucion = Resolucion::where('tipo_documento', 1)->where('estado', 'A')->where('caja_id', $pCaja_id)->where('serie', strtoupper($validData['refactura_serie']))->where('correlativo_inicial' ,'<=', $validData['refactura_correlativo'])->where('correlativo_final' ,'>=', $validData['refactura_correlativo'])->first();

                if (!empty($pResolucion)) {
                    $Factura  = maestro_documento::findOrFail($id);
                    $pFactura = new maestro_documento();
                    $pFactura->empresa_id    = Auth::user()->empresa_id;
                    $pFactura->caja_id       = $pCaja_id;
                    $pFactura->tipodocumento_id = 1;
                    $pFactura->resolucion_id = $pResolucion->id;
                    $pFactura->fecha_emision = Carbon::now()->format('Y-m-d');
                    $pFactura->serie         = $validData['refactura_serie'];
                    $pFactura->correlativo   = $validData['refactura_correlativo'];
                    $pFactura->paciente_id   = $Factura->paciente_id;
                    $pFactura->condicion     = $Factura->condicion;
                    $pFactura->nit           = $validData['refactura_nit'];
                    $pFactura->nombre        = $validData['refactura_nombre'];
                    $pFactura->direccion     = $validData['refactura_direccion'];
                    $pFactura->motivoanulacion_id = $validData['refactura_motivo_id'];
                    $pFactura->observacion_anulacion = $validData['observacion_refactura'];
                    $pFactura->save();

                    $detalle_documento = detalle_documento::where('maestro_documento_id', $id)->get();
                    
                    foreach ($detalle_documento as $dd) {
                        $nuevo_detalle = new detalle_documento();
                        $nuevo_detalle->maestro_documento_id = $pFactura->id;
                        $nuevo_detalle->admision_cargo_detalle_id = $dd->admision_cargo_detalle_id;
                        $nuevo_detalle->producto_id     = $dd->producto_id;
                        $nuevo_detalle->descripcion     = $dd->descripcion;
                        $nuevo_detalle->signo           = $dd->signo;
                        $nuevo_detalle->cantidad        = $dd->cantidad;
                        $nuevo_detalle->precio_unitario = $dd->precio_unitario;
                        $nuevo_detalle->precio_bruto    = $dd->precio_bruto;
                        $nuevo_detalle->descuento       = $dd->descuento;
                        $nuevo_detalle->recargo         = $dd->recargo;
                        $nuevo_detalle->precio_neto     = $dd->precio_neto;
                        $nuevo_detalle->precio_base     = $dd->precio_base;
                        $nuevo_detalle->precio_impuesto = $dd->precio_impuesto;
                        $nuevo_detalle->estado          = 'A';
                        $nuevo_detalle->save();
                    }
                    return Redirect::route('editar_factura', [$pFactura->id, $validData['refactura_admision_id1']])->with('message','Factura grabada con exito');
                }else{
                    return Redirect::back()->withErrors('Caja no cuenta con una resolucion que permita realizar la factura');
                    
                }
            }
        }catch(Exception $e){
            dd('error '. $e);
        }

    }

    public function documento_refacturar(){
        $documento_id      = $_POST['documento_id'];

        $paciente_id       = $_POST['paciente_id'];
        $tipodocumento_id  = $_POST['tipodocumento_id'];
        $nueva_fecha       = $_POST['nueva_fecha'];
        $nueva_serie       = strtoupper($_POST['nueva_serie']);
        $nuevo_correlativo = $_POST['nuevo_correlativo'];
        $nueva_condicion   = $_POST['nueva_condicion'];
        $nuevo_nit         = $_POST['nuevo_nit'];
        $nuevo_nombre      = $_POST['nuevo_nombre'];
        $nueva_direccion   = $_POST['nueva_direccion'];
        $motivo_id         = $_POST['motivo_id'];
        $observaciones     = $_POST['observaciones'];

        $tipo_documento = Tipo_documento::findOrFail($tipodocumento_id);
        $caja_id        = Auth::user()->caja_id;
        $caja           = Caja::findOrFail($caja_id);
        $resolucion     = Resolucion::where('tipo_documento', $tipodocumento_id)
                          ->where('estado', 'A')
                          ->where('caja_id', $caja_id)
                          ->where('serie', $nueva_serie)
                          ->where('correlativo_inicial' ,'<=', $nuevo_correlativo)
                          ->where('correlativo_final' ,'>=', $nuevo_correlativo)
                          ->first();

        $Existe = maestro_documento::where('empresa_id', Auth::user()->empresa_id)
                  ->where('serie', $nueva_serie)
                  ->where('correlativo', $nuevo_correlativo)->count();

        $facturaAnterior = maestro_documento::findOrFail($documento_id);

        //$respuesta = array('parametro' => 0, 'respuesta' => $documento_id);
        if ($Existe == 0) {
            if (!empty($resolucion)) {
                $factura = new maestro_documento();
                $factura->empresa_id = Auth::user()->empresa_id;
                $factura->caja_id    = Auth::user()->caja_id;
                $factura->paciente_id      = $paciente_id;
                $factura->tipodocumento_id = $tipodocumento_id;
                $factura->resolucion_id    = $resolucion->id;
                $factura->fecha_emision    = $nueva_fecha;
                $factura->serie            = $nueva_serie;
                $factura->correlativo      = $nuevo_correlativo;
                $factura->condicion        = $nueva_condicion;
                $factura->nit              = $nuevo_nit;
                $factura->nombre           = $nuevo_nombre;
                $factura->direccion        = $nueva_direccion;
                $factura->estado           = 'A';
                $factura->tipodocumentoafecto_id = $facturaAnterior->tipodocumentoafecto_id;
                $factura->serie_afecta           = $facturaAnterior->serie_afecta;
                $factura->correlativo_afecto     = $facturaAnterior->correlativo_afecto;
                $factura->save();

                //Actualiza la resolucion
                $factura_resolucion = Resolucion::findOrFail($resolucion->id);
                $factura_resolucion->ultimo_correlativo = $nuevo_correlativo;
                $factura_resolucion->save();

                $detalles = detalle_documento::where('maestro_documento_id', $documento_id)->get();

                foreach ($detalles as $d) {
                    $detalle = new detalle_documento();
                    $detalle->maestro_documento_id      = $factura->id;
                    $detalle->admision_cargo_detalle_id = $d->admision_cargo_detalle_id;
                    $detalle->producto_id               = $d->producto_id;
                    $detalle->descripcion               = $d->descripcion;
                    $detalle->signo                     = $tipo_documento->signo;
                    $detalle->cantidad                  = $d->cantidad;
                    $detalle->precio_unitario           = $d->precio_unitario;
                    $detalle->precio_bruto              = $d->precio_bruto;
                    $detalle->descuento                 = $d->descuento;
                    $detalle->recargo                   = $d->recargo;
                    $detalle->precio_neto               = $d->precio_neto;
                    $detalle->precio_base               = $d->precio_base;
                    $detalle->precio_impuesto           = $d->precio_impuesto;
                    $detalle->estado                    = $d->estado;
                    $detalle->save();
                }

                $pagos = pago_documento::where('maestro_documento_id', $documento_id)->get();

                foreach ($pagos as $p) {
                    $pago = new pago_documento();
                    $pago->maestro_documento_id = $factura->id;
                    $pago->maestro_pago_id      = $p->maestro_pago_id;
                    $pago->saldo_documento      = $p->saldo_documento;
                    $pago->total_aplicado       = $p->total_aplicado;
                    $pago->estado               = $p->estado;
                    $pago->save();
                }


                $respuesta = array('parametro' => 0, 'respuesta' => $tipo_documento->descripcon.' '.$factura->serie.'-'.$factura->correlativo.' Guardada con exito', 'id' => $factura->id);
            }else{
                $respuesta = array('parametro' => 1, 'respuesta' => $caja->nombre_maquina.' No cuenta con una resolucion activa que permita emitir el documento, favor Verifique');
            }
        } else{
            $respuesta = array('parametro' => 1, 'respuesta' => $tipo_documento->descripcion.' '.$nueva_serie.'-'.$nuevo_correlativo.' Ya se encuentra guardada en nuestros registros, Favor verifique');
        }

        //$respuesta = array('parametro' => 0, 'respuesta' => $Existe);
        return Response::json($respuesta);
    }

    public function corte_idx(){
        $listado = DB::table('cortes as c')
                   ->join('maestro_documentos as md', 'c.id', 'md.corte_id')
                   ->join('detalle_documentos as dd', 'md.id', 'dd.maestro_documento_id')
                   ->join('cajas as c1', 'c.caja_id', 'c1.id')
                   ->join('users as u', 'c.created_by', 'u.id')
                   ->where('c.empresa_id', Auth::user()->empresa_id)
                   ->groupBy('c.id','c1.nombre_maquina', 'c.corte', 'c.fecha', 'u.name')
                   ->select('c.id','c1.nombre_maquina as caja_descripcion', 'c.corte', 'c.fecha', 'u.name as usuario_nombre', DB::raw('COUNT(md.id) as total_documentos'))
                   ->orderBy('c.fecha', 'DESC')
                   ->get();
        return view('ventas.cortes_idx', compact('listado'));
    }

    public function corte_create(){
        $hoy       = Carbon::now()->format('Y-m-d');
        $caja      = Caja::where('id', Auth::user()->caja_id)->first();
        $cajas     = Caja::where('empresa_id', Auth::user()->empresa_id)->get();

        return view('ventas.corte_create', compact('hoy', 'caja', 'cajas'));   
    }

    public function trae_resumen_documentos(){
        $fecha   = $_POST['fecha'];
        $caja_id = $_POST['caja_id'];

        $resumen = DB::table('tipo_documentos as td')
                   ->distinct()
                   ->leftjoin('vw_venta_documentos as vvd', function($join) use($fecha, $caja_id){
                        $join->on('td.id', 'vvd.tipodocumento_id')
                        ->where('vvd.empresa_id', Auth::user()->empresa_id)
                        ->where('vvd.caja_id', '=', $caja_id)
                        ->where('vvd.fecha_emision', '=', $fecha);
                   })
                   ->where('td.id', '!=', 4)
                   ->groupBy('td.id', 'td.descripcion', 'td.signo')
                   ->orderBy('td.descripcion')
                   ->select('td.id', 'td.descripcion', 'td.signo', DB::raw('SUM(IFNULL(vvd.total_documento,0))*td.signo as total_documento'))
                   ->get();
        return Response::json($resumen);
    }

    public function trae_detalle_documentos(){
        $fecha   = $_POST['fecha'];
        $caja_id = $_POST['caja_id'];

        $detalle = DB::table('vw_venta_documentos as vvd')
                   ->where('vvd.empresa_id', Auth::user()->empresa_id)
                   ->where('vvd.caja_id', $caja_id)
                   ->whereDate('vvd.fecha_emision', $fecha)
                   ->select('tipodocumento_descripcion','serie', 'correlativo', 'fecha_emision', 'nit', 'nombre', 'total_documento')
                   ->get();
        return Response::json($detalle);
    }

    public function trae_resumen_pagos(){
        $fecha   = $_POST['fecha'];
        $caja_id = $_POST['caja_id'];

        $resumen = DB::table('formas_pago as fp')
                   ->distinct()
                   ->leftjoin('vw_forma_pago_documentos as vfpd', function($join) use($fecha, $caja_id){
                        $join->on('fp.id', 'vfpd.forma_pago')
                        ->where('vfpd.empresa_id', Auth::user()->empresa_id)
                        ->where('vfpd.caja_id', '=', $caja_id)
                        ->where('vfpd.fecha_emision', '=', $fecha);
                   })
                    ->groupBy('fp.id', 'fp.descripcion')
                    ->select('fp.id', 'fp.descripcion', DB::raw('SUM(IFNULL(vfpd.total_forma_pago,0)) as total'))
                   ->get();
        return Response::json($resumen);
    }

    public function trae_detalle_pagos(){
        $fecha   = $_POST['fecha'];
        $caja_id = $_POST['caja_id'];

        $detalle = DB::table('vw_corte_caja_recibos as vccr')
                   ->where('vccr.empresa_id', Auth::user()->empresa_id)
                   ->where('vccr.caja_id', $caja_id)
                   ->whereDate('vccr.fecha_emision', $fecha)
                   ->select('serie', 'correlativo', 'tipo_admision', 'admision', 'nombre_completo', 'total_recibo', 'efectivo', 'cheque', 'tarjeta')
                   ->get();
        return Response::json($detalle);
    }

    public function corte_store(Request $request){
        //dd($request);
        $validData = $request->validate([
            'fecha'   => 'required',
            'caja_id' => 'required'
        ]);

        $count = Corte::where('empresa_id', Auth::user()->empresa_id)->count()+1;

        $corte = new Corte;
        $corte->empresa_id = Auth::user()->empresa_id;
        $corte->caja_id    = $validData['caja_id'];
        $corte->corte      = $count;
        $corte->fecha      = $validData['fecha'];
        $corte->estado     = 'A';
        $corte->save();

        maestro_documento::where('empresa_id', Auth::user()->empresa_id)
                           ->where('caja_id', $validData['caja_id'])
                           ->whereDate('fecha_emision', $validData['fecha'])
                           ->update(['corte_id' => $corte->id]);

        maestro_pago::where('empresa_id', Auth::user()->empresa_id)
                      ->where('caja_id', $validData['caja_id'])
                      ->whereDate('fecha_emision', $validData['fecha'])
                      ->update(['corte_id' => $corte->id]);

    }

    public function corte_edit($id){
        $corte = corte::findOrFail($id);
        $caja = caja::findOrFail($corte->caja_id);

        $resumend = DB::table('tipo_documentos as td')
                   ->distinct()
                   ->leftjoin('vw_venta_documentos as vvd', function($join) use($id){
                        $join->on('td.id', 'vvd.tipodocumento_id')
                        ->where('vvd.empresa_id', Auth::user()->empresa_id)
                        ->where('vvd.corte_id', $id);
                   })
                   ->where('td.id', '!=', 4)
                   ->groupBy('td.id', 'td.descripcion', 'td.signo')
                   ->orderBy('td.descripcion')
                   ->select('td.id', 'td.descripcion', 'td.signo', DB::raw('SUM(IFNULL(vvd.total_documento,0))*td.signo as total_documento'))
                   ->get();

        $totalresumend = DB::table('vw_venta_documentos as vvd')
                         ->where('vvd.empresa_id', Auth::user()->empresa_id)
                         ->where('vvd.corte_id', $id)
                         ->select(DB::raw('SUM(IFNULL(vvd.total_documento,0)) as total'))
                         ->first();

        $resumenp = DB::table('formas_pago as fp')
                    ->distinct()
                    ->leftjoin('vw_forma_pago_documentos as vfpd', function($join) use($id){
                         $join->on('fp.id', 'vfpd.forma_pago')
                         ->where('vfpd.empresa_id', Auth::user()->empresa_id)
                         ->where('vfpd.corte_id', '=', $id);
                    })
                    ->groupBy('fp.id', 'fp.descripcion')
                    ->select('fp.id', 'fp.descripcion', DB::raw('SUM(IFNULL(vfpd.total_forma_pago,0)) as total'))
                    ->get();
        //dd($resumenp);

        $documentos = DB::table('vw_venta_documentos as vvd')
                      ->where('vvd.empresa_id', Auth::user()->empresa_id)
                      ->where('vvd.corte_id', $id)
                      ->select('vvd.tipodocumento_descripcion', 'vvd.serie', 'vvd.correlativo', 'vvd.fecha_emision', 'vvd.nit', 'vvd.nombre',DB::raw('SUM(vvd.total_documento) as total_documento'))
                      ->groupBy('vvd.tipodocumento_descripcion', 'vvd.serie', 'vvd.correlativo', 'vvd.fecha_emision', 'vvd.nit', 'vvd.nombre')
                      ->get();

        $pagos = DB::table('vw_corte_caja_recibos as vvcr')
                 ->where('vvcr.empresa_id', Auth::user()->empresa_id)
                 ->where('vvcr.corte_id', $id)
                 ->get();

        //dd($documentos);

        return view('ventas.corte_edit', compact('corte', 'documentos', 'pagos', 'caja', 'resumend', 'resumenp', 'totalresumend'));
    }
}
