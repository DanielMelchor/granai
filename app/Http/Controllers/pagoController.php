<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Redirect;
use Response;
use Carbon\carbon;
use Illuminate\Http\Request;
use App\maestro_pago;
use App\detalle_pago;
use App\pago_documento;
use App\Tipo_documento;
use App\Banco;
use App\Caja;
use App\Resolucion;
use App\Paciente;
use App\MotivoAnulacion;


class pagoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$listado = DB::table('maestro_pagos as mp')
    	           ->join('tipo_documentos as td', 'mp.tipodocumento_id', 'td.id')
    	           ->leftjoin('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
    	           ->where('empresa_id', Auth::user()->empresa_id)
    	           ->where('tipodocumento_id', 4)
    	           ->select('mp.id','mp.tipodocumento_id', 'td.descripcion as tipodocumento_descripcion','mp.fecha_emision', 'mp.serie', 'mp.correlativo','mp.estado', DB::raw('(CASE WHEN mp.estado = "A" THEN "Vigente" ELSE "Anulado" END) AS estado_descripcion'), DB::raw('SUM(dp.monto) as monto'))
    	           ->groupBy('mp.id','mp.tipodocumento_id', 'td.descripcion','mp.fecha_emision', 'mp.serie', 'mp.correlativo', 'mp.estado')
    	           ->get();
    	return view('pagos.index', compact('listado'));
    }

    public function create(){
        $hoy       = Carbon::now()->format('Y-m-d');
        $documento = Tipo_documento::findOrFail(4);
        $pacientes = Paciente::all();
        $caja      = Caja::where('id', Auth::user()->caja_id)->first();
        $bancos    = Banco::where('tipo_referencia', 'B')->where('estado', 'A')->get();
        $tarjetas  = Banco::where('tipo_referencia', 'T')->where('estado', 'A')->get();

        if (empty($caja)) {
            return Redirect::back()->withErrors('Usuario no permitido para emitir Recibos');
        } else{
            $resolucion = Resolucion::where('caja_id', Auth::user()->caja_id)->where('tipo_documento', 4)->where('estado', 'A')->count();
            if ($resolucion == 0) {
                return Redirect::back()->withErrors('Caja no cuenta con una resolucion Activa que permita emitir Recibos');
            } else{
                return view('pagos.create', compact('hoy', 'documento', 'pacientes', 'caja', 'bancos', 'tarjetas'));
            }
        }
    }

    public function edit($id){
        $documento = Tipo_documento::findOrFail(4);
        $caja      = Caja::where('id', Auth::user()->caja_id)->first();
        $bancos    = Banco::where('tipo_referencia', 'B')->where('estado', 'A')->get();
        $tarjetas  = Banco::where('tipo_referencia', 'T')->where('estado', 'A')->get();
        $encabezado = maestro_pago::findOrFail($id);
        $paciente   = paciente::findOrFail($encabezado->paciente_id);
        $listado    = MotivoAnulacion::where('estado', 'A')->get();

        return view('pagos.edit', compact('encabezado', 'documento', 'paciente', 'caja', 'bancos', 'tarjetas', 'listado'));
    }

    public function recibo_store(){
        $tipodocumento_id = $_POST['tipo_documento_id'];
        $resolucion_id    = $_POST['resolucion_id'];
        $fecha_emision    = $_POST['fecha_emision'];
        $serie            = $_POST['serie'];
        $correlativo      = $_POST['correlativo'];
        $paciente_id      = $_POST['paciente_id'];
        $data      = (array) json_decode($_POST['documentos'], true);
        $dataPagos = (array) json_decode($_POST['pagos'], true);
        $totaldataPagos = count($dataPagos);
        $totaldata = count($data);

        $resolucion = Resolucion::where('caja_id', Auth::user()->caja_id)->where('tipo_documento', 4)->where('estado', 'A')->count();

        if ($resolucion != 0) {
            /*=================================================================
            crea encabezado de recibo
            =================================================================*/
            $recibo = new maestro_pago;
            $recibo->empresa_id       = Auth::user()->empresa_id;
            $recibo->caja_id          = Auth::user()->caja_id;
            $recibo->paciente_id      = $paciente_id;
            $recibo->tipodocumento_id = $tipodocumento_id;
            $recibo->resolucion_id    = $resolucion_id;
            $recibo->fecha_emision    = $fecha_emision;
            $recibo->serie            = $serie;
            $recibo->correlativo      = $correlativo;
            $recibo->estado           = 'A';
            $recibo->save();

            /*=================================================================
            crea detalle de recibo
            =================================================================*/

            for ($i=0; $i < $totaldataPagos; $i++) { 
                $recibo_detalle = new detalle_pago();
                $recibo_detalle->maestro_pago_id = $recibo->id;
                $recibo_detalle->forma_pago      = $dataPagos[$i]['forma_pago'];
                $recibo_detalle->banco_id        = $dataPagos[$i]['entidad_id'];
                $recibo_detalle->cuenta_no       = $dataPagos[$i]['cuenta_no'];
                $recibo_detalle->documento_no    = $dataPagos[$i]['documento_no'];
                $recibo_detalle->autoriza_no     = $dataPagos[$i]['autoriza_no'];
                $recibo_detalle->monto           = $dataPagos[$i]['monto'];
                $recibo_detalle->estado          = 'A';
                $recibo_detalle->save();
            }

            /*=================================================================
            crea pago de documentos
            =================================================================*/

            for ($i=0; $i < $totaldata; $i++) { 
                $documento_pago = new pago_documento();
                $documento_pago->maestro_documento_id = $data[$i]['id'];
                $documento_pago->maestro_pago_id      = $recibo->id;
                $documento_pago->saldo_documento      = $data[$i]['saldo'];
                $documento_pago->total_aplicado       = $data[$i]['pago'];
                if ($data[$i]['admision_id'] == 0) {
                    $documento_pago->admision_id          = null;
                }else{
                    $documento_pago->admision_id          = $data[$i]['admision_id'];
                }
                
                $documento_pago->estado               = 'A';
                $documento_pago->save();
            }

            /*=================================================================
            actualiza correlativo de recibo
            =================================================================*/

            $resolucion_recibo = Resolucion::findOrFail($recibo->resolucion_id);
            $resolucion_recibo->ultimo_correlativo = $recibo->correlativo;
            $resolucion_recibo->save();

            $respuesta = array('estado' => 'A','respuesta' => 'Recibo '.$serie.'-'.$correlativo.' Grabado con exito !!!', 'recibo_id' => $recibo->id);
        } else {
            $respuesta = array('estado' => 'I','respuesta' => 'Caja no cuenta con una resolucion activa que permita generar recibos');
        }

        return Response::json($respuesta);
    }

    public function trae_recibo(){
        $banco_id = $_POST['banco_id'];
        $cheque   = $_POST['cheque'];
        $banco    = Banco::findOrFail($banco_id);

        $existe = detalle_pago::where('banco_id', $banco_id)->where('documento_no', $cheque)->where('estado','A')->count();

        if ($existe > 0) {
            $encabezado = DB::table('maestro_pagos as mp')
                          ->join('detalle_pagos as dp', 'mp.id', 'dp.maestro_pago_id')
                          ->join('pacientes as p', 'mp.paciente_id', 'p.id')
                          ->where('mp.empresa_id', Auth::user()->empresa_id)
                          ->where('dp.banco_id', $banco_id)
                          ->where('dp.documento_no', $cheque)
                          ->select('mp.id', 'mp.serie', 'mp.correlativo', 'mp.fecha_emision', 'p.nombre_completo', DB::raw('SUM(dp.monto) as total'), 'p.id as paciente_id')
                          ->groupBy('mp.id', 'mp.serie', 'mp.correlativo', 'mp.fecha_emision', 'p.nombre_completo', 'p.id')
                          ->first();

            $detalle = DB::table('pago_documentos as pd')
                       ->join('maestro_documentos as md', 'pd.maestro_documento_id', 'md.id')
                       ->join('detalle_documentos as dd', 'md.id', 'dd.maestro_documento_id')
                       ->join('tipo_documentos as td', 'md.tipodocumento_id', 'td.id')
                       ->where('pd.maestro_pago_id', $encabezado->id)
                       ->groupBy('md.id','td.descripcion', 'md.serie', 'md.correlativo', 'md.fecha_emision', 'md.nit', 'md.nombre', 'md.direccion')
                       ->select('md.id as factura_id', 'td.descripcion', 'md.serie', 'md.correlativo', 'md.fecha_emision', 'md.nit', 'md.nombre', 'md.direccion')
                       ->get();

            $respuesta = array('estado' => '1', 'encabezado' => $encabezado, 'detalle' => $detalle);
        }else {
            $respuesta = array('estado' => '0','mensaje' => $banco->nombre.' Cheque No. '.$cheque.' no encontrado en los registros');
        }
        return Response::json($respuesta);
    }

    public function trae_detalle_recibo(){
        $recibo_id = $_POST['recibo_id'];
        
        $detalle = DB::table('pago_documentos as pd')
                   ->join('maestro_documentos as md', 'pd.maestro_documento_id', 'md.id')
                   ->join('tipo_documentos as td', 'md.tipodocumento_id', 'td.id')
                   ->where('pd.maestro_pago_id', $recibo_id)
                   ->orderBy('pd.id')
                   ->select('md.id', 'md.tipodocumento_id', 'td.descripcion', DB::raw('DATE_FORMAT(md.fecha_emision, "%d/%m/%Y") as fecha_emision'), 'md.serie', 'md.correlativo', 'md.nit', 'md.nombre', 'pd.saldo_documento', 'pd.total_aplicado')
                   ->get();
        return Response::json($detalle);
    }

    public function trae_pago_recibo(){
        $recibo_id = $_POST['recibo_id'];

        $pago = DB::table('detalle_pagos as dp')
                ->leftjoin('bancos as b', 'dp.banco_id', 'b.id')
                ->where('dp.maestro_pago_id', $recibo_id)
                ->select('dp.id', 'dp.forma_pago', DB::raw('CASE WHEN dp.forma_pago = "E" THEN "Efectivo" WHEN dp.forma_pago = "B" THEN "Cheque" ELSE "Tarjeta" END as forma_pago_descripcion'), 'dp.banco_id', DB::raw('CASE WHEN IFNULL(b.nombre,0) = 0 then "" ELSE b.nombre END as emisor_nombre'), 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                ->orderBy('dp.id')
                ->get();
        return Response::json($pago);

    }

    public function recibo_anular(Request $request, $id){
        $recibo = maestro_pago::findOrFail($id);
        $recibo->motivo_anulacion_id    = $request->motivo_id;
        $recibo->observacion_anulacion  = $request->observacion_anulacion;
        $recibo->anulacion_usuario_id   = Auth::user()->id;
        $recibo->fecha_anulacion        = Carbon::now();
        $recibo->estado                 = 'I';
        $recibo->save();

        $pago_recibo = detalle_pago::where('maestro_pago_id', $id)->get();

        foreach ($pago_recibo as $pr) {
            $detalle = detalle_pago::findOrFail($pr->id);
            $detalle->estado = 'I';
            $detalle->save();
        }

        $pago_documento = pago_documento::where('maestro_pago_id', $id)->get();

        foreach ($pago_documento as $pd) {
            $pago = pago_documento::findOrFail($pd->id);
            $pago->estado = 'I';
            $pago->save();
        }

        return Redirect::route('editar_recibo',[$id])->with('message','Recibo anulado con exito !!!');
    }
}
