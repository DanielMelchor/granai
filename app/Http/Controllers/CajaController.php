<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use DB;
use App\Caja;
use App\Resolucion;
use App\vw_resoluciones;
use App\Tipo_documento;
use App\maestro_documento;
use App\maestro_pago;

class CajaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$pCajas = Caja::all();
    	return view('cajas.index', compact('pCajas'));
    }

    public function create(){
    	$tipo_documentos = Tipo_documento::all();
        return view('cajas.create', compact('tipo_documentos'));
    }

    public function store(Request $request){
        $caja_nombre      = $_POST['caja_nombre'];
        $editar_documento = $_POST['editar_documento'];
        $caja_estado      = $_POST['caja_estado'];
        $data             = (array) json_decode($_POST['arreglo'], true);
        $totalRegistros   = count($data);

        $caja = new Caja();
        $caja->empresa_id       = Auth::user()->empresa_id;
        $caja->nombre_maquina   = $caja_nombre;
        $caja->editar_documento = $editar_documento;
        $caja->estado           = $caja_estado;
        $caja->save();

        if ($totalRegistros > 0) {
            for ($i=0; $i < $totalRegistros; $i++) { 
                $resolucion = new Resolucion();
                $resolucion->caja_id             = $caja->id;
                $resolucion->tipo_documento      = $data[$i]['tipo_documento_id'];
                $resolucion->serie               = $data[$i]['serie'];
                $resolucion->correlativo_inicial = $data[$i]['correlativo_inicial'];
                $resolucion->correlativo_final   = $data[$i]['correlativo_final'];
                $resolucion->ultimo_correlativo  = $data[$i]['ultimo_correlativo'];
                $resolucion->estado              = $data[$i]['resolucion_estado'];
                $resolucion->save();
            }
        }

        $respuesta = array('caja_id' => $caja->id,'respuesta' => 'Caja '.$caja_nombre.' Guardada con exito !!!');
        return Response::json($respuesta);

    }

    public function edit($id){
        $pCaja = Caja::findOrFail($id);
        $pResoluciones = Resolucion::where('caja_id', $id)->get();
        $tipo_documentos = Tipo_documento::all();
        return view('cajas.edit', compact('pCaja', 'pResoluciones', 'tipo_documentos'));
    }

    public function update(){
        $caja_id          = $_POST['caja_id'];
        $caja_nombre      = $_POST['caja_nombre'];
        $editar_documento = $_POST['editar_documento'];
        $caja_estado      = $_POST['caja_estado'];
        $data             = (array) json_decode($_POST['arreglo'], true);
        $totalRegistros   = count($data);

        $caja = Caja::findOrFail($caja_id);
        $caja->nombre_maquina   = $caja_nombre;
        $caja->editar_documento = $editar_documento;
        $caja->estado           = $caja_estado;
        $caja->save();

        if ($totalRegistros > 0) {
            for ($i=0; $i < $totalRegistros; $i++) { 
                switch ($data[$i]['proceso']) {
                    case 'A':
                        $resolucion = new Resolucion();
                        $resolucion->caja_id             = $caja->id;
                        $resolucion->tipo_documento      = $data[$i]['tipo_documento_id'];
                        $resolucion->serie               = $data[$i]['serie'];
                        $resolucion->correlativo_inicial = $data[$i]['correlativo_inicial'];
                        $resolucion->correlativo_final   = $data[$i]['correlativo_final'];
                        $resolucion->ultimo_correlativo  = $data[$i]['ultimo_correlativo'];
                        $resolucion->estado              = $data[$i]['resolucion_estado'];
                        $resolucion->save();
                        break;
                    case 'U':
                        $resolucion = Resolucion::findOrFail($data[$i]['resolucion_id']);
                        //$resolucion->caja_id             = $caja->id;
                        //$resolucion->tipo_documento      = $data[$i]['tipo_documento_id'];
                        $resolucion->serie               = $data[$i]['serie'];
                        $resolucion->correlativo_inicial = $data[$i]['correlativo_inicial'];
                        $resolucion->correlativo_final   = $data[$i]['correlativo_final'];
                        $resolucion->ultimo_correlativo  = $data[$i]['ultimo_correlativo'];
                        $resolucion->estado              = $data[$i]['resolucion_estado'];
                        $resolucion->save();
                        break;
                    case 'D':
                        $resolucion = Resolucion::findOrFail($data[$i]['resolucion_id']);
                        $resolucion->delete();
                        break;
                    default:
                        # code...
                        break;
                }
            }
        }
        $respuesta = array('caja_id' => $caja->id,'respuesta' => 'Caja '.$caja_nombre.' Actualizada con exito !!!');
        return Response::json($respuesta);

        //return Redirect::route('cajas')->with('message','Caja grabada con exito');
    }

    public function show($id)
    {
        $pCaja = Caja::findOrFail($id);
        $pResoluciones = vw_resoluciones::where('caja_id', $id)->get();
        return view('cajas.show', compact('pCaja','pResoluciones'));
        /*$medicamento = medicamento::findOrFail($id);
        $pMedicamentoDosis = vw_medicamento_dosis::WHERE('medicamento_id', $id)->get();
        $pDosis = dosis::all();
        return view('medicamentos.show',[
            'pMedicamento' => $medicamento,
            'pMedicamentoDosis' => $pMedicamentoDosis,
            'pDosis' => $pDosis
        ]);*/
    }

    public function resolucion_x_serie(){
        $caja_id = $_POST['caja_id'];
        $tipo_documento_id = $_POST['tipo_documento_id'];
        $serie   = strtoupper($_POST['serie']);

        $resolucion = Resolucion::where('caja_id', $caja_id)->where('tipo_documento', $tipo_documento_id)->where('serie', $serie)->where('estado', 'A')->first();

        if (isset($resolucion)) {
            $correlativo = $resolucion->ultimo_correlativo + 1;
            $resolucion_id = $resolucion->id;
        }else{
            $correlativo = 0;
            $resolucion_id = 0;
        }

        
        $respuesta = array('resolucion_id' => $resolucion_id, 'correlativo' => $correlativo);

        return response::json($respuesta);
    }

    public function resolucion_factura_x_caja(){
        $caja_id = $_POST['caja_id'];
        $tipo_documento_id = $_POST['tipo_documento_id'];
        $resolucion = Resolucion::where('caja_id', $caja_id)->where('tipo_documento', $tipo_documento_id)->where('estado', 'A')->first();

        $correlativo = $resolucion->ultimo_correlativo + 1;

        $respuesta = array('resolucion_id' => $resolucion->id, 'serie' => $resolucion->serie, 'correlativo' => $correlativo);

        return response::json($respuesta);
    }

    public function resolucion_recibo_x_caja(){
        $caja_id = $_POST['caja_id'];
        $resolucion = Resolucion::where('caja_id', $caja_id)->where('tipo_documento', 4)->where('estado', 'A')->first();

        $correlativo = $resolucion->ultimo_correlativo + 1;

        $respuesta = array('resolucion_id' => $resolucion->id, 'serie' => $resolucion->serie, 'correlativo' => $correlativo);

        return response::json($respuesta);   
    }

    public function caja_resoluciones(){
        $caja_id = $_POST['caja_id'];
        $resoluciones = DB::table('Resoluciones as r')
                        ->join('tipo_documentos as td', 'r.tipo_documento', 'td.id')
                        ->where('r.caja_id', $caja_id)
                        ->select('r.id','r.tipo_documento as tipo_documento_id', 'td.descripcion as tipo_documento_descripcion', 'r.serie', 'r.correlativo_inicial', 'r.correlativo_final', 'r.ultimo_correlativo', 'r.estado')
                        ->groupBy('r.id','r.tipo_documento', 'td.descripcion', 'r.serie', 'r.correlativo_inicial', 'r.correlativo_final', 'r.ultimo_correlativo', 'r.estado')
                        ->orderBy('r.id', 'asc')
                        ->get();

        return response::json($resoluciones);
    }

    public function resolucion_registros_utilizados(){
        $resolucion_id = $_POST['resolucion_id'];
        $totalRegistros = maestro_documento::where('resolucion_id', $resolucion_id)->count();
        if ($totalRegistros == 0) {
            $totalRegistros = maestro_pago::where('resolucion_id', $resolucion_id)->count();
        }
        return response::json($totalRegistros);
    }

    public function cajas_x_empresa(){
        $empresa_id = $_POST['empresa_id'];

        $cajas = Caja::where('empresa_id', $empresa_id)
                 ->where('estado', 'A')
                 ->get();

        return response::json($cajas);
    }
}
