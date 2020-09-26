<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
//use File;
use Session;
use validator;
use PDF;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Empresa;
use App\Admision;
use App\bitacora_admision;
use App\User;
use App\Paciente;
use App\Medico;
use App\Hospital;
use App\Aseguradora;
use App\admision_consulta;
use App\admision_procedimiento;
use App\admision_cargo;
use App\admision_cargo_detalle;
use App\observacion_admision;
use App\Especialidad;
use App\Producto;
use App\AdmisionCompleta;
use App\Admision_fotos;
use App\Correlativo;
use App\Medicamento;
use App\Medicamento_dosis;
use App\receta_config;
use App\receta_medico;
use Intervention\Image\ImageManagerStatic as Image;
use App\maestro_documento;
use App\detalle_documento;

class AdmisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        //phpinfo();
        //$pAdmisiones   = vw_admision::Nombre($request->busqueda)->orderBy('fecha_creacion','desc')->paginate(10);
        $hoy = Carbon::now()->format('Y-m-d');
        $pMedicos      = Medico::where('estado','=','A')->get();
        $pHospitales   = Hospital::where('estado','=','A')->get();
        $pAseguradoras = Aseguradora::where('estado','=','A')->get();
        $pPacientes    = Paciente::orderBy('nombre_completo')->get();
        $pAdmisiones   = DB::table('admisiones as a')
                         ->join('pacientes as p', 'a.paciente_id', 'p.id')
                         ->select('a.id', 'a.admision', 'a.fecha', 'p.expediente_no', 'p.nombre_completo', 'a.estado')
                         ->orderBy('a.admision', 'desc')
                         ->get();
        return view('admisiones.index', compact('pMedicos','pHospitales', 'pAseguradoras','pPacientes', 'pAdmisiones', 'hoy'));
    }

    public function index_cargos(Request $request)
    {
        if (!empty($request->busqueda)){
            $pAdmisiones = vw_admision::Nombre($request->busqueda)->orderBy('fecha_creacion','desc')->paginate();
        }else
        {
            $pAdmisiones   = vw_admision::orderBy('fecha_creacion','desc')->paginate();
        }

        return view('admisiones.index_cargos', [
            'pAdmisiones'   => $pAdmisiones
        ]);
    }

    public function index_cerradas(Request $request){
        if (!empty($request->busqueda)){
            $pAdmisiones = vw_admision::Nombre($request->busqueda)->where('estado','C')->orderBy('fecha_creacion','desc')->paginate();
        }else
        {
            $pAdmisiones   = vw_admision::where('estado','C')->orderBy('fecha_creacion','desc')->paginate();
        }

        $pObservaciones = observacion_admision::where('proceso','REAPERTURA')->where('estado','A')->get();
        return view('admisiones.index_cerradas', compact('pAdmisiones', 'pObservaciones'));
    }

    public function index_sin_factura(Request $request){
        if (!empty($request->busqueda)){
            $pAdmisiones = vw_admisiones_sin_factura::Nombre($request->busqueda)->where('factura_id','0')->orderBy('fecha_creacion','desc')->paginate();
        }else
        {
            $pAdmisiones   = vw_admisiones_sin_factura::where('factura_id','0')->orderBy('fecha_creacion','desc')->paginate();
        }

        return view('admisiones.index_sin_factura', compact('pAdmisiones'));
    }


    public function create()
    {
        $pMedicos      = Medico::where('estado','=','A')->get();
        $pHospitales   = Hospital::where('estado','=','A')->get();
        $pAseguradoras = Aseguradora::where('estado','=','A')->get();
        $pPacientes    = Paciente::all()->orderBy('nombre_completo');
        return view('admisiones.create',[
            'pMedicos'      => $pMedicos,
            'pHospitales'   => $pHospitales,
            'pAseguradoras' => $pAseguradoras,
            'pPacientes'    => $pPacientes
        ]);
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'tipo_admision' => 'required',
            'paciente_id' => 'required',
            'hospital_id' => 'required',
            'medico_id'   => 'required',
            'fecha'       => 'required'
        ]);


        //$admision = new AdmisionCompleta();
        $admision = new Admision();
        $admision->empresa_id       = Auth::user()->empresa_id;
        $admision->tipo_admision    = $validData['tipo_admision'];
        $admision->fecha            = $validData['fecha'];
        $id_admision = Correlativo::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'A')->max('correlativo');
        $id_admision += 1;
        $admision->paciente_id      = $validData['paciente_id'];
        $paciente = Paciente::where('id', $request->paciente_id)->first();
        $admision->edad = Carbon::parse($paciente->fecha_nacimiento)->age;
        $admision->admision         = $id_admision;
        $admision->medico_id        = $validData['medico_id'];
        $admision->hospital_id      = $validData['hospital_id'];
        $admision->admision_tercero = $request->admision_tercero;
        $admision->aseguradora_id   = $request->aseguradora_id;
        $admision->poliza_no        = $request->poliza_no;
        if (isset($request->deducible)) {
            $admision->deducible        = $request->deducible;
        }else{
            $admision->deducible        = 0;
        }
        if (isset($request->copago)) {
            $admision->copago           = $request->copago;
        }else{
            $admision->copago           = 0;
        }

        $admision->estado           = 'P';

        $admision->save();

        $actualizar_correlativo = Correlativo::where('empresa_id',Auth::user()->empresa_id)->where('tipo', 'A')->first();
        $actualizar_correlativo->correlativo = $id_admision;
        $actualizar_correlativo->save();

        $bitacora = new bitacora_admision();
        $bitacora->admision_id = $admision->id;
        $bitacora->proceso     = 'APERTURA';
        $bitacora->observaciones = 'Creacion de admision por usuario '.Auth::user()->name;
        $bitacora->save();

        //Session::flash('success', 'Se editó el medico con éxito.');
        /*if ($request->tipo_proceso == 'A'){
            return Redirect::route('admisiones')->with('message','Admisión grabada con exito');    
        }else
        {
            return Redirect::route('pacientes')->with('message','Admisión grabada con exito');    
        }*/
        //return back()->with('message','Admision grabada con exito');
        Session::flash('success', 'Admisión Guardada con exito !!!' );
        return redirect::route('admisiones');
    }

    public function store_nueva(Request $request){
        $validData = $request->validate([
            'paciente_id' => 'required',
            'hospital_id' => 'required',
            'medico_id'   => 'required',
            'admision_id' => 'required'
        ]);

        $admision = new AdmisionCompleta();
        $admision->empresa_id       = Auth::user()->empresa_id;
        $admision->paciente_id      = $validData['paciente_id'];
        $admision->medico_id        = $validData['medico_id'];
        $admision->admision_id      = $validData['admision_id'];
        $admision->hospital_id      = $validData['hospital_id'];
        $admision->admision_tercero = $request->admision_tercero;
        $admision->aseguradora_id   = $request->aseguradora_id;
        $admision->poliza_no        = $request->poliza_no;
        if(is_null($request->deducible)){

            $admision->deducible    = 0;
        }
        else{
            $admision->deducible    = $request->deducible;
        }
        if(is_null($request->copago)){

            $admision->copago    = 0;
        }
        else{
            $admision->copago    = $request->copago;
        }
        $admision->estado           = 'P';

        $admision->save();

        return Redirect::route('nueva_admision', $admision->paciente_id)->with('message','Admisión cerrada con exito');
    }

    public function edit($id)
    {
        $pMedicos       = Medico::all();
        $pHospitales    = Hospital::all();
        $pAseguradoras  = Aseguradora::all();
        $pPacientes     = Paciente::all();
        $pAdmision      = Admision::findOrFail($id);
        $facturas = DB::table('maestro_documentos as md')
                    ->join('tipo_documentos as td', 'md.tipodocumento_id', 'td.id')
                    ->join('detalle_documentos as dd', 'md.id', 'dd.maestro_documento_id')
                    ->join('admision_cargo_detalles as acd', 'dd.admision_cargo_detalle_id', 'acd.id')
                    ->join('admision_cargos as ac', 'acd.admision_cargo_id', 'ac.id')
                    ->where('ac.admision_id', $id)
                    ->select('td.descripcion as tipodocumento_descripcion', 'md.id as factura_id', 'md.serie', 'md.correlativo', 'md.fecha_emision', 'md.nombre as factura_nombre', DB::raw('(CASE WHEN md.estado = "A" THEN "Vigente" ELSE "Anulada" END) AS estado_descripcion'), DB::raw('SUM(dd.precio_neto) as total'))
                    ->groupBy('td.descripcion', 'md.id', 'md.serie', 'md.correlativo', 'md.fecha_emision', 'md.nombre', 'md.estado')
                    ->get();
        //$pBitacora      = bitacora_admision::where('admision_id', $id)->get();
        $pBitacora      = DB::table('bitacora_admisiones as ba')
                          ->join('users as u', 'ba.created_by', 'u.id')
                          ->where('ba.admision_id', $id)
                          ->select('u.name', 'ba.created_at', 'ba.observaciones')
                          ->orderBy('ba.created_at', 'desc')
                          ->paginate(10);
        $pObservaciones = observacion_admision::where('proceso','REAPERTURA')->where('estado','A')->get();
        $pProductos     = producto::where('empresa_id', Auth::user()->empresa_id)
                          ->where('estado', 'A')->get();

        return view('admisiones.edit', compact('pMedicos', 'pHospitales', 'pAseguradoras', 'pPacientes', 'pAdmision', 'pBitacora', 'pObservaciones', 'pProductos', 'facturas'));
    }

    public function update_ajax(){
        $admision_id = $_POST['admision_id'];
        $paciente_id = $_POST['paciente_id'];
        $hospital_id = $_POST['hospital_id'];
        $medico_id   = $_POST['medico_id'];
        $tipo_admision    = $_POST['tipo_admision'];
        $fecha            = $_POST['fecha'];
        $admision_tercero = (int)$_POST['admision_tercero'];
        $aseguradora_id   = $_POST['aseguradora_id'];
        $poliza_no   = (int)$_POST['poliza_no'];
        $deducible   = (int)$_POST['deducible'];
        $copago      = (int)$_POST['copago'];
        /*$data = (array) json_decode($_POST['arreglo'], true);
        $totalRegistros = count($data);*/
        $dataEliminar = (array) json_decode($_POST['eliminar'], true);
        $totalEliminar = count($dataEliminar);
        $dataAgregar = (array) json_decode($_POST['agregar'], true);
        $totalAgregar = count($dataAgregar);

        $admision = Admision::findOrFail($admision_id);
        $admision->empresa_id       = Auth::user()->empresa_id;
        $admision->tipo_admision    = $tipo_admision;
        $admision->fecha            = $fecha;
        if ($paciente_id != $admision->paciente_id) {
            $paciente = Paciente::where('id', $request->paciente_id)->first();
            $admision->edad = Carbon::parse($paciente->fecha_nacimiento)->age;
        }
        $admision->paciente_id      = $paciente_id;
        $admision->medico_id        = $medico_id;
        $admision->hospital_id      = $hospital_id;
        if (isset($admision_tercero)) {
            $admision->admision_tercero = $admision_tercero;
        } else {
            $admision->admision_tercero = 0;
        }
        if (strlen(trim($aseguradora_id)) > 0)  {
            $admision->aseguradora_id   = trim($aseguradora_id);
        }
        $admision->poliza_no        = $poliza_no;
        $admision->deducible        = $deducible;
        $admision->copago           = $copago;
        $admision->save();

        if ($totalEliminar > 0) {
            for ($i=0; $i < $totalEliminar ; $i++) { 
                $bitacora = new bitacora_admision();
                $bitacora->admision_id   = $admision->id;
                $bitacora->proceso       = 'CARGO';
                $bitacora->observaciones = 'Eliminar cargo '. $dataEliminar[$i]['producto_descripcion'];
                $bitacora->save();

                $cargos = admision_cargo::where('admision_id', $admision_id)->where('producto_id', intval($dataEliminar[$i]['producto_id']))->delete();
            }
        }
        
        /*$cargo_detalles = admision_cargo_detalle::where('admision_id', $admision_id)->delete();*/

        for($i=0; $i < $totalAgregar; $i++) {
            $cargo = new admision_cargo();
            $cargo->admision_id = $admision_id;
            $cargo->producto_id = intval($dataAgregar[$i]['producto_id']);
            $cargo->descripcion = $dataAgregar[$i]['producto_descripcion'];
            $cargo->cantidad    = floatval($dataAgregar[$i]['cantidad']);
            $cargo->precio_unitario = floatval($dataAgregar[$i]['precio_unitario']);
            $cargo->precio_total    = floatval($dataAgregar[$i]['precio_total']);
            $cargo->total_cliente   = floatval($dataAgregar[$i]['total_cliente']);
            $cargo->total_aseguradora = floatval($dataAgregar[$i]['total_aseguradora']);
            $cargo->save();

            $bitacora = new bitacora_admision();
            $bitacora->admision_id   = $admision->id;
            $bitacora->proceso       = 'CARGO';
            $bitacora->observaciones = 'Agregar cargo '.$cargo->descripcion;
            $bitacora->save();

            if ($cargo->total_cliente > 0) {
                $cargo_detalle = new admision_cargo_detalle();
                $cargo_detalle->admision_id       = $admision_id;
                $cargo_detalle->admision_cargo_id = $cargo->id;
                $cargo_detalle->facturar_a        = 'C';
                if ($admision->deducible > 0) {
                    $cargo_detalle->porcentaje        = $admision->deducible;
                }else{
                    $cargo_detalle->porcentaje        = 0;
                }
                $cargo_detalle->valor             = $cargo->total_cliente;
                $cargo_detalle->estado            = 'A';
                $cargo_detalle->save();
            }

            if ($cargo->total_aseguradora > 0) {
                $cargo_detalle = new admision_cargo_detalle();
                $cargo_detalle->admision_id       = $admision_id;
                $cargo_detalle->admision_cargo_id = $cargo->id;
                $cargo_detalle->facturar_a        = 'A';
                if ($admision->deducible > 0) {
                    $cargo_detalle->porcentaje        = 100 - $admision->deducible;
                }else{
                    $cargo_detalle->porcentaje        = 0;
                }
                $cargo_detalle->valor             = $cargo->total_aseguradora;
                $cargo_detalle->estado            = 'A';
                $cargo_detalle->save();
            }
        }

        $respuesta = 'Admision Grabada con Exito !!!!';

        return response::json($respuesta);
        exit;
    }

    public function update(Request $request, $id)
    {
        $validData = $request->validate([
            'paciente_id' => 'required',
            'hospital_id' => 'required',
            'medico_id'   => 'required',
            'fecha'       => 'required'
        ]);


        $admision = Admision::findOrFail($id);
        $admision->empresa_id       = Auth::user()->empresa_id;
        //$admision->tipo_admision    = $validData['tipo_admision'];
        $admision->fecha             = $validData['fecha'];
        if ($validData['paciente_id'] != $admision->paciente_id) {
            $paciente = Paciente::where('id', $request->paciente_id)->first();
            $admision->edad = Carbon::parse($paciente->fecha_nacimiento)->age;
        }
        
        $admision->paciente_id      = $validData['paciente_id'];
        $admision->medico_id        = $validData['medico_id'];
        $admision->hospital_id      = $validData['hospital_id'];
        $admision->admision_tercero = $request->admision_tercero;
        $admision->aseguradora_id   = $request->aseguradora_id;
        $admision->poliza_no        = $request->poliza_no;
        $admision->deducible        = $request->deducible;
        $admision->copago           = $request->copago;
        $admision->estado           = $admision->estado;

        $admision->save();

        $bitacora = new bitacora_admision();
        $bitacora->admision_id   = $admision->id;
        $bitacora->proceso       = 'ACTUALIZACION';
        $bitacora->observaciones = 'Actualizacion de admision';

        return back()->with('message','Admisión actualizada con exito !!!!');

    }

    public function cerrar_admision($id){

        $admision = Admision::findOrFail($id);
        $admision->estado = 'C';
        $admision->save();

        $bitacora = new bitacora_admision();
        $bitacora->admision_id   = $admision->id;
        $bitacora->proceso       = 'CIERRE';
        $bitacora->observaciones = 'Cierre de admision';
        $bitacora->save();

         /*return Redirect::route('admisiones')->with('message','Admisión cerrada con exito');    */
         return back()->with('message','Admisión cerrada con exito !!!!');
    }

    public function cerrar_admision_ajax(){

        $admision_id = $_POST['admision_id'];

        $admision = Admision::findOrFail($admision_id);
        $admision->estado = 'C';
        $admision->save();

        $bitacora = new bitacora_admision();
        $bitacora->admision_id   = $admision->id;
        $bitacora->proceso       = 'CIERRE';
        $bitacora->observaciones = 'Cierre de admision';
        $bitacora->save();

         //return back()->with('message','Admisión cerrada con exito !!!!');
        return response::json('Admision Cerrada con Exito !!!!');

    }

    public function reapertura(Request $request, $id){
        $admision = Admision::findOrFail($id);
        $admision->estado = 'P';
        $admision->save();

        $bitacora = new bitacora_admision();
        $bitacora->admision_id    = $admision->id;
        $bitacora->proceso        = 'REAPERTURA';
        $bitacora->observacion_id = $request->observacion_id;
        $bitacora->observaciones  = $request->observaciones;
        $bitacora->save();

        return back()->with('message','Admisión aperturada con exito !!!!');

    }

    public function nueva_admision($id, $origen){
        /*
        Datos Generales
        */
        $pPaciente       = paciente::where('id',$id)->first();
        if ($pPaciente->genero == 'M') {
            $genero = 'Masculino';
        }else{
            $genero = 'Femenino';
        }
        $totalAdmisiones = \DB::table('admisiones')
                           ->where('empresa_id', Auth::user()->empresa_id)
                           ->where('paciente_id', $id)
                           ->count();
        $pListaC = \DB::table('admisiones as a')
                   ->leftjoin('admision_consultas as ac', 'a.id', 'ac.admision_id')
                   ->where('a.empresa_id', Auth::user()->empresa_id)
                   ->where('a.paciente_id', $id)
                   ->where('a.tipo_admision', 'C')
                   ->select('a.admision', 'ac.id as detalle_id', 'a.id', 'a.created_at as fecha')
                   ->orderBy('a.admision', 'DESC')
                   ->paginate(10);
        $pListaP = \DB::table('admisiones as a')
                   ->leftjoin('admision_procedimientos as ap', 'a.id', 'ap.admision_id')
                   ->where('a.empresa_id', Auth::user()->empresa_id)
                   ->where('a.paciente_id', $id)
                   ->where('a.tipo_admision', 'P')
                   ->select('a.admision', 'ap.id as detalle_id', 'a.id', 'a.created_at as fecha', 'ap.procedimiento_id', 'ap.tolerancia', 'ap.premedicacion', 'ap.patologo', 'ap.anestesiologo', 'ap.indicacion', 'ap.hallazgos', 'ap.diagnostico', 'ap.recomendaciones')
                   ->orderBy('a.admision', 'DESC')
                   ->paginate(10);
        $pListaH = \DB::table('admisiones as a')
                   ->where('a.empresa_id', Auth::user()->empresa_id)
                   ->where('a.paciente_id', $id)
                   ->where('a.tipo_admision', 'H')
                   ->select('a.admision', 'a.id as detalle_id', 'a.id', 'a.created_at as fecha')
                   ->orderBy('a.admision', 'DESC')
                   ->paginate(10);
        $pMedicamentos = medicamento::where('estado','A')->get();
        $pDosis = Medicamento_dosis::all()->load('dosis');
        $pProcedimientos = Producto::where('estado', 'A')->where('clasificacion', 'PROC')->get();

        return view('admisiones.nueva_admision', compact('pPaciente', 'genero', 'pListaC', 'pListaP', 'pListaH', 'pMedicamentos', 'pDosis', 'pProcedimientos', 'origen'));

    }

    public function update_nueva_consulta(Request $request){
        if ($request->detalle_id == 0) {
            $consulta = new admision_consulta();
        }else{
            $consulta = admision_consulta::findOrFail($request->detalle_id);
        }
        
        $consulta->admision_id         = $request->admision_id;
        $consulta->paciente_id         = $request->paciente_id;
        $consulta->peso                = $request->peso;
        $consulta->talla               = $request->talla;
        $consulta->pulso               = $request->pulso;
        $consulta->temperatura         = $request->temperatura;
        $consulta->respiracion         = $request->respiracion;
        $consulta->presion_sistolica   = substr($request->presion_sistolica, 0,3);
        $consulta->presion_diastolica  = substr($request->presion_sistolica, 4,3);
        $consulta->subjetivo           = $request->consulta_subjetivo;
        $consulta->objetivo            = $request->consulta_objetivo;
        $consulta->impresion_clinica   = $request->consulta_impresion_clinica;
        $consulta->plan                = $request->consulta_plan;
        $consulta->tratamiento         = $request->consulta_tratamiento;
        $consulta->save();
        return back()->with('message','Consulta grabada con exito');
    }

    public function update_nuevo_procedimiento(Request $request){
        if (isset($request->admision_id) or $request->admision_id == 0) {
            $procedimiento = admision_procedimiento::where('admision_id', $request->padmision_id)->first();
        }else{
            $procedimiento = new admision_procedimiento();
        }
        $procedimiento->admision_id  = $request->padmision_id;
        $procedimiento->paciente_id  = $request->ppaciente_id;
        $procedimiento->producto_id  = $request->procedimiento_producto_id;
        $procedimiento->tolerancia   = $request->procedimiento_tolerancia;
        $procedimiento->premedicacion = $request->procedimiento_premedicacion;
        $procedimiento->indicacion   = $request->procedimiento_indicacion;
        $procedimiento->hallazgos    = $request->procedimiento_hallazgos;
        $procedimiento->diagnostico  = $request->procedimiento_diagnostico;
        $procedimiento->recomendaciones = $request->procedimiento_recomendacion;

        $procedimiento->save();
        
        return back()->with('message','Procedimiento grabado con exito');
    }

    public function update_nuevo_egreso(Request $request){

        $validData = $request->validate([
            'fecha_inicio'   => 'required',
            'fecha_fin'      => 'required',
            'resumen_egreso' => 'required'
        ]);

        $egreso = admision::findOrFail($request->admision_egreso_id);
        $egreso->fecha_inicio   = $validData['fecha_inicio'];
        $egreso->fecha_fin      = $validData['fecha_fin'];
        $egreso->resumen_egreso = $validData['resumen_egreso'];
        $egreso->save();

        return back()->with('message','Hospitalización grabado con exito');

    }

    public function update_nuevo($id, Request $request){
        $admision = AdmisionCompleta::findOrFail($request->admision_id);

        $admision->hospitalizacion_fecha_inicio = $request->hospitalizacion_fecha_inicio;
        $admision->hospitalizacion_fecha_final  = $request->hospitalizacion_fecha_final;
        $admision->hospitalizacion_resumen      = $request->hospitalizacion_resumen;
        $admision->consulta_subjetivo           = $request->consulta_subjetivo;
        $admision->consulta_objetivo            = $request->consulta_objetivo;
        $admision->consulta_impresion_clinica   = $request->consulta_impresion_clinica;
        $admision->consulta_tratamiento         = $request->consulta_tratamiento;
        $admision->consulta_plan                = $request->consulta_plan;
        $admision->consulta_peso                = $request->consulta_peso;
        $admision->consulta_pulso               = $request->consulta_pulso;
        $admision->consulta_talla               = $request->consulta_talla;
        $admision->consulta_temperatura         = $request->consulta_temperatura;
        $admision->consulta_respiracion         = $request->consulta_respiracion;
        $admision->consulta_presion_sistolica   = $request->consulta_presion_sistolica;
        $admision->consulta_presion_diastolica  = $request->consulta_presion_diastolica;
        $admision->procedimiento_producto_id    = $request->procedimiento_producto_id;
        $admision->procedimiento_tolerancia     = $request->procedimiento_tolerancia;
        $admision->procedimiento_premedicacion  = $request->procedimiento_premedicacion;
        $admision->referido_por                 = $request->referido_por;
        $admision->procedimiento_indicacion     = $request->procedimiento_indicacion;
        $admision->procedimiento_hallazgos      = $request->procedimiento_hallazgos;
        $admision->procedimiento_diagnostico    = $request->procedimiento_diagnostico;
        $admision->procedimiento_recomendacion  = $request->procediminto_recomendacion;
        $admision->procedimiento_anestesiologo_id = $request->procedimiento_anestesiologo_id;
        $admision->procedimiento_patologo_id    = $request->procedimiento_patologo_id;

        $admision->save();

        return Redirect::route('nueva_admision', $admision->paciente_id)->with('message','Admisión actualizada con exito');    
    }

    public function SubirImagen(request $request){

        //dd($request->all());
        //var_dump($request);
        $validData = $request->validate([
            'file' => 'required|image|max:5120'
        ]);

        $correlativo = \DB::table('admision_fotos')->where('admision_id', $request->imagen_admision_id)->count();
        $correlativo +=  1;
        $correlativo = str_pad($correlativo + 1,4,'0', STR_PAD_LEFT);
        $file_name   = pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $request->file->getClientOriginalExtension();
        $file_name = $file_name.'_'.$correlativo.'.'.$extension;
        Storage::disk('public')->putFileAs("$request->imagen_admision_id/originales/", $request->file, $file_name);

        $requestImage = $request->file;
        $requestImagePath = $requestImage->getRealPath() .'.'.$extension;
        $interventionImage = Image::make($request->file)->resize(530, 470)->encode('jpg');
        $interventionImage->save($requestImagePath);
        Storage::disk('public')->putFileAs("$request->imagen_admision_id/mini/", new File($requestImagePath), $file_name);

        $admision_foto = new admision_fotos();
        $admision_foto->admision_id = $request->imagen_admision_id;
        $admision_foto->nombre_imagen = $file_name;
        $admision_foto->nombre_imagen_mini = $file_name;
        $admision_foto->informe = 'N';
        $admision_foto->save();

        //return response()->json(['success' => true, 'payload' => 'Imagenes Cargadas con Exito !!!']);


        //dd($correlativo.' '.$file_name);

        //$imagenes = $request->file('file')->store("public/$request->imagen_admision_id/originales");
        //$file_name = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
        //dd($file_name);
        //Storage::disk('public')->putFileAs("originales/", $request->file, $file_name);

        //return Response::json($request);

        //$correlativo = \DB::table('admision_fotos')->where('admision_id', $request->imagen_admision_id)->count();
        //$files = \Input::file('file');
        //dma$files = \Input::file('fileList');
        //dd($files);
        //print_r(count((array) $files));
        /*if (count((array) $files) > 0 && !is_null($files[0])) {
            foreach($files as $file){
                $correlativo +=  1;
                $correlativo = str_pad($correlativo + 1,4,'0', STR_PAD_LEFT);
                $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $file_name = $file_name.'_'.$correlativo.'.'.$extension;
                $requestImage = $file;
                $requestImagePath = $requestImage->getRealPath() .'.'.$extension;
                $interventionImage = Image::make($requestImage)->resize(530, 470)->encode('jpg');
                $interventionImage->save($requestImagePath);
                Storage::disk('public')->putFileAs("$request->imagen_admision_id/originales/", $file, $file_name);
                Storage::disk('public')->putFileAs("$request->imagen_admision_id/mini/", new File($requestImagePath), $file_name);

                $admision_foto = new admision_fotos();
                $admision_foto->admision_id = $request->imagen_admision_id;
                $admision_foto->nombre_imagen = $file_name;
                $admision_foto->nombre_imagen_mini = $file_name;
                $admision_foto->informe = 'N';
                $admision_foto->save();
            }
        }*/
        //return back();
    }

    public function MarcarImagen(){
        //print_r($_POST['id_foto']);
        $imagen = Admision_Completa_foto::findOrFail($_POST['id_foto']);
        $imagen->informe = $_POST['action'];
        $imagen->save();
        exit;
    }

    public function trae_consulta(){
        $admision_id = $_POST['id'];
        //$data = admision_consulta::where('id',$admision_id)->first();
        $data = DB::table('admisiones as a')
                ->join('medicos as m', 'a.medico_id', 'm.id')
                ->join('hospitales as h', 'a.hospital_id', 'h.id')
                ->leftjoin('aseguradoras as s', 'a.aseguradora_id', 's.id')
                ->leftjoin('admision_consultas as ac', 'a.id', 'ac.admision_id')
                ->where('a.id', $admision_id)
                ->where('a.tipo_admision', 'C')
                ->select('a.id', 'a.admision', 'm.nombre_completo as medico_nombre', 'h.nombre as hospital_nombre', 'a.created_at as fecha', 'a.edad as edad', 's.nombre as aseguradora_nombre', 'a.poliza_no', 'a.deducible', 'a.copago', 'ac.id as detalle_id', 'ac.subjetivo', 'ac.objetivo', 'ac.impresion_clinica', 'ac.plan', 'ac.tratamiento', 'ac.peso', 'ac.talla', 'ac.pulso', 'ac.temperatura', 'ac.respiracion', 'ac.presion_sistolica', 'ac.presion_diastolica', 'ac.bmi')
                ->first();
        //print_r($data);
        //var_dump($data);
        return Response::json($data);
        //echo json_encode($data, JSON_UNESCAPED_UNICODE);
        //exit;
    }

    public function trae_procedimiento(){
        $admision_id = $_POST['id'];
        $data = DB::table('admisiones as a')
                ->join('medicos as m', 'a.medico_id', 'm.id')
                ->join('hospitales as h', 'a.hospital_id', 'h.id')
                ->leftjoin('aseguradoras as s', 'a.aseguradora_id', 's.id')
                ->leftjoin('admision_procedimientos as ap', 'a.id', 'ap.admision_id')
                ->where('a.id', $admision_id)
                ->where('a.tipo_admision', 'P')
                ->select('a.id', 'a.admision', 'm.nombre_completo as medico_nombre', 'h.nombre as hospital_nombre', 'a.created_at as fecha', 'a.edad as edad', 's.nombre as aseguradora_nombre', 'a.poliza_no', 'a.deducible', 'a.copago', 'ap.id as detalle_id', 'ap.procedimiento_id', 'ap.tolerancia', 'ap.premedicacion', 'ap.patologo', 'ap.anestesiologo', 'ap.indicacion', 'ap.hallazgos', 'ap.diagnostico', 'ap.recomendaciones')
                ->first();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function trae_imagenes_procedimiento(){
        $admision_id = $_POST['admision_id'];
        $totalFotos = admision_fotos::where('admision_id', $admision_id)->count();
        if ($totalFotos > 0) {
            $data = DB::table('admision_fotos as af')
                    ->where('admision_id', $admision_id)
                    ->select('af.id', 'af.nombre_imagen_mini', 'af.admision_id', 'af.informe')
                    ->get();
        } else {
            $data = [];
        }
        return response::json($data);
    }

    public function trae_egreso(){
        $admision_id = $_POST['id'];
        //$data = admision::where('id',$admision_id)->first();
        $data = DB::table('admisiones as a')
                ->join('medicos as m', 'a.medico_id', 'm.id')
                ->join('hospitales as h', 'a.hospital_id', 'h.id')
                ->leftjoin('aseguradoras as s', 'a.aseguradora_id', 's.id')
                ->where('a.id', $admision_id)
                ->select('a.id', 'a.admision', 'm.nombre_completo as medico_nombre', 'h.nombre as hospital_nombre', 'a.created_at as fecha', 'a.edad as edad', 's.nombre as aseguradora_nombre', 'a.poliza_no', 'a.deducible', 'a.copago', 'a.fecha_inicio', 'a.fecha_fin', 'a.resumen_egreso', 'a.id as detalle_id')
                ->first();
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }    

    public function update_consulta_ajax(){
        if ($_POST['detalle_id'] == 0) {
            $consulta = new admision_consulta();
        }else{
            $consulta = admision_consulta::findOrFail($_POST['detalle_id']);
        }

        $consulta->admision_id          = $_POST['admision_id'];
        $consulta->paciente_id          = $_POST['paciente_id'];
        $consulta->peso                 = $_POST['peso'];
        $consulta->talla                = $_POST['talla'];
        $consulta->pulso                = $_POST['pulso'];
        $consulta->bmi                  = $_POST['bmi'];
        $consulta->temperatura          = $_POST['temperatura'];
        $consulta->respiracion          = $_POST['respiracion'];
        $consulta->presion_sistolica    = substr($_POST['presion'], 0,3);
        $consulta->presion_diastolica   = substr($_POST['presion'], 4,3);
        $consulta->subjetivo   = $_POST['consulta_subjetivo'];
        $consulta->objetivo    = $_POST['consulta_objetivo'];
        $consulta->impresion_clinica = $_POST['consulta_impresion_clinica'];
        $consulta->plan        = $_POST['consulta_plan'];
        $consulta->tratamiento = $_POST['consulta_tratamiento'];
        $consulta->save();

        $respuesta = array('id' => $consulta->id , 'respuesta' => 'Consulta grabada con exito');

        return response::json($respuesta);
        exit;

    }

    public function update_procedimiento_ajax(){
        if ($_POST['detalle_id'] == 0) {
            $procedimiento = new admision_procedimiento();
        }else{
            $procedimiento = admision_procedimiento::findOrFail($_POST['detalle_id']);
        }

        $procedimiento->admision_id      = $_POST['admision_id'];
        $procedimiento->paciente_id      = $_POST['paciente_id'];
        $procedimiento->procedimiento_id = $_POST['procedimiento_id'];
        $procedimiento->tolerancia       = $_POST['tolerancia'];
        $procedimiento->premedicacion    = $_POST['premedicacion'];
        $procedimiento->anestesiologo    = $_POST['anestesiologo'];
        $procedimiento->patologo         = $_POST['patologo'];
        $procedimiento->indicacion       = $_POST['indicacion'];
        $procedimiento->hallazgos        = $_POST['hallazgos'];
        $procedimiento->diagnostico      = $_POST['diagnostico'];
        $procedimiento->recomendaciones  = $_POST['recomendaciones'];
        $procedimiento->save();

        $respuesta = array('id' => $procedimiento->id , 'respuesta' => 'Procedimiento grabado con exito');

        return response::json($respuesta);
        exit;

    }

    public function update_hospitalizacion_ajax(){
        if ($_POST['detalle_id'] == 0) {
            $hospitalizacion = new admision();
        }else{
            $hospitalizacion = admision::findOrFail($_POST['detalle_id']);
        }

        $hospitalizacion->fecha_inicio    = $_POST['fecha_inicio'];
        $hospitalizacion->fecha_fin       = $_POST['fecha_fin'];
        $hospitalizacion->resumen_egreso  = $_POST['resumen_egreso'];
        $hospitalizacion->save();

        $respuesta = array('id' => $hospitalizacion->id , 'respuesta' => 'Hospitalización grabada con exito');

        return response::json($respuesta);
    }

    public function ultimaconsulta_ajax(){
        $paciente_id = $_POST['paciente_id'];

        $ultima = admision::where('tipo_admision', 'C')->where('paciente_id', $paciente_id)->max('id');

        return response::json($ultima);
        exit;
    }

    public function ultimoegreso_ajax(){
        $paciente_id = $_POST['paciente_id'];

        $ultima = admision::where('empresa_id', Auth::user()->empresa_id)->where('tipo_admision', 'H')->where('paciente_id', $paciente_id)->max('id');

        return response::json($ultima);
        exit;
    }

    public function ultimoprocedimiento_ajax(){
        $paciente_id = $_POST['paciente_id'];

        $ultima = admision::where('empresa_id', Auth::user()->empresa_id)->where('tipo_admision', 'P')->where('paciente_id', $paciente_id)->max('id');

        return response::json($ultima);
        exit;
    }

    public function impresion_receta($id){
        $constante = 2.83465;
        setlocale(LC_ALL,"es_ES");
        \Carbon\Carbon::setLocale('es'); 

        $pEmpresa = Empresa::findOrFail(Auth::user()->empresa_id);
        $pRecetaC = receta_config::first();
        dd($pRecetaC);
        //$pConsulta = admision_consulta::where('id', 46)->first();
        $pConsulta = DB::table('admision_consultas as ac')
                     ->join('pacientes as p', 'ac.paciente_id', 'p.id')
                     ->where('ac.admision_id', $id)
                     ->select('ac.created_at as created_at','ac.tratamiento', 'p.nombre_completo as paciente_nombre')
                     ->first();

        $fecha = \Carbon\Carbon::parse($pConsulta->created_at);
        $dia = $fecha->format('d');
        $mes = $fecha->format('m');
        switch ($mes) {
            case '01': $nombre_mes = 'Enero'; break;
            case '02': $nombre_mes = 'Febrero'; break;
            case '03': $nombre_mes = 'Marzo'; break;
            case '04': $nombre_mes = 'Abril'; break;
            case '05': $nombre_mes = 'Mayo'; break;
            case '06': $nombre_mes = 'Junio'; break;
            case '07': $nombre_mes = 'Julio'; break;
            case '08': $nombre_mes = 'Agosto'; break;
            case '09': $nombre_mes = 'Septiembre'; break;
            case '10': $nombre_mes = 'Octubre'; break;
            case '11': $nombre_mes = 'Noviembre'; break;
            case '12': $nombre_mes = 'Diciembre'; break;
            default: $nombre_mes = 'no definido';  break;
        }
        $anio = $fecha->format('Y');

        ini_set('memory_limit', '-1');
        $pdf = PDF::loadView('informes.receta', compact('pEmpresa', 'dia', 'nombre_mes', 'anio', 'pRecetaC', 'pConsulta'));
        $pdf->setPaper('letter','portrait');
        $paper_size = array([0,0,500,1000], 'landscape');
        $nombre_informe = 'receta.pdf';
        //$pdf = PDF::loadView('informes.receta', compact('pEmpresa'));
        return $pdf->stream($nombre_informe);

        //return view('informes.receta', compact('pRecetaC', 'dia','nombre_mes', 'anio','pConsulta'));
    }

    public function trae_cargos(){
        $admision_id = $_POST['admision_id'];

        $pCargos = DB::table('admision_cargos as ac')
                   ->leftjoin('admision_cargo_detalles as acd', 'ac.id', 'acd.admision_cargo_id')
                   ->leftjoin('detalle_documentos as dd', 'acd.id', 'dd.admision_cargo_detalle_id')
                   ->where('ac.admision_id', $admision_id)
                   ->where('acd.facturar_a', 'C')
                   ->where('acd.estado', 'A')
                   ->groupBy('ac.admision_id', 'ac.producto_id', 'ac.descripcion', 'ac.cantidad', 'ac.precio_unitario', 'ac.precio_total', 'ac.total_cliente', 'ac.total_aseguradora', 'dd.estado')
                   ->select('ac.admision_id', 'ac.producto_id', 'ac.descripcion', 'ac.cantidad', 'ac.precio_unitario', 'ac.precio_total', 'ac.total_cliente', 'ac.total_aseguradora', 'dd.estado as facturado')
                   ->orderBy('acd.id', 'ASC')
                   ->get();
        return response::json($pCargos);

    }

    public function trae_datos_para_factura(){
        $admision_id = $_POST['admision_id'];
        $encabezado = DB::table('pacientes as p')
                      ->join('admisiones as a', 'p.id', 'a.paciente_id')
                      ->where('empresa_id', Auth::user()->empresa_id)
                      ->where('a.id', $admision_id)
                      ->select('p.factura_nit', 'p.factura_nombre', 'p.factura_direccion')->first();
        $cargos = DB::table('admision_cargos as ac')
                  ->join('admision_cargo_detalles as acd', 'ac.id', 'acd.admision_cargo_id')
                  ->join('productos as p', 'ac.producto_id', 'p.id')
                  ->where('ac.admision_id', $admision_id)
                  ->where('acd.facturar_a', 'C')
                  ->whereNotExists(function ($query)
                  {
                    $query->select(DB::raw(1))
                            ->from('detalle_documentos as dd')
                            ->whereRaw('dd.admision_cargo_detalle_id = acd.id');
                  })
                  ->select('ac.producto_id','p.descripcion as producto_descripcion','ac.cantidad', 'ac.descripcion', 'acd.valor', 'acd.id as cargo_detalle_id')
                  ->get();
        $respuesta = array('encabezado' => $encabezado, 'cargos' => $cargos);
        return response::json($respuesta);
    }

    public function receta($admision_id){
        $medico = admision::findOrFail($admision_id)->select('medico_id')->first();
        $pRecetaC = receta_medico::where('medico_id', $medico->medico_id)->first();

        if (empty($pRecetaC->pagina_alto) || empty($pRecetaC->pagina_ancho)) {
            dd('sin receta');
            //return Redirect::back()->withErrors('medico no cuenta con la configuracion necesaria para imprimir la receta');
        } else{
            setlocale(LC_ALL,"es_ES");
            \Carbon\Carbon::setLocale('es'); 
            $pEmpresa = Empresa::findOrFail(Auth::user()->empresa_id);
            $pConsulta = DB::table('admision_consultas as ac')
                         ->join('admisiones as a', 'ac.admision_id', 'a.id')
                         ->join('pacientes as p', 'ac.paciente_id', 'p.id')
                         ->where('ac.admision_id', $admision_id)
                         ->select('a.fecha','ac.tratamiento', 'p.nombre_completo as paciente_nombre')
                         ->first();
            $fecha = \Carbon\Carbon::parse($pConsulta->fecha);
            $dia = $fecha->format('d');
            $mes = $fecha->format('m');
            switch ($mes) {
                case '01': $nombre_mes = 'Enero'; break;
                case '02': $nombre_mes = 'Febrero'; break;
                case '03': $nombre_mes = 'Marzo'; break;
                case '04': $nombre_mes = 'Abril'; break;
                case '05': $nombre_mes = 'Mayo'; break;
                case '06': $nombre_mes = 'Junio'; break;
                case '07': $nombre_mes = 'Julio'; break;
                case '08': $nombre_mes = 'Agosto'; break;
                case '09': $nombre_mes = 'Septiembre'; break;
                case '10': $nombre_mes = 'Octubre'; break;
                case '11': $nombre_mes = 'Noviembre'; break;
                case '12': $nombre_mes = 'Diciembre'; break;
                default: $nombre_mes = 'no definido';  break;
            }
            $anio = $fecha->format('Y');
            $orientacion = 'portrait';
            if ($pRecetaC->orientacion == 'L') {
                $orientacion = 'landscape';
            }
            
            ini_set('memory_limit', '-1');
            $pdf = PDF::loadView('admisiones.receta', compact('pEmpresa', 'dia', 'nombre_mes', 'anio', 'pRecetaC', 'pConsulta'));

            
            $pdf->setPaper( [0, 0, $pRecetaC->pagina_alto, $pRecetaC->pagina_ancho], $orientacion);
            //$paper_size = array([0,0,500,1000], 'landscape');
            $nombre_informe = 'receta.pdf';
            //$pdf = PDF::loadView('informes.receta', compact('pEmpresa'));
            return $pdf->stream($nombre_informe);
            //return Redirect::back()->withMessage('si va a generar pdf');
        }

    }

    public function informe($admision_id){
        $empresa = Empresa::findOrFail(Auth::user()->empresa_id);
        $admision = DB::table('Admisiones as a')
                    ->join('Pacientes as p', 'a.paciente_id', 'p.id')
                    ->join('Medicos as m', 'a.medico_id', 'm.id')
                    ->join('Hospitales as h', 'a.hospital_id', 'h.id')
                    ->join('admision_procedimientos as ap', 'a.id', 'ap.admision_id')
                    ->join('productos as p1', 'ap.procedimiento_id', 'p1.id')
                    ->leftjoin('Aseguradoras as a1', 'a.aseguradora_id', 'a1.id')
                    ->where('a.empresa_id', Auth::user()->empresa_id)
                    ->where('a.id', $admision_id)
                    ->select('a.id','p.codigo_id as paciente_codigo', 'p.nombre_completo as paciente_nombre', 'a.edad as paciente_edad',
                             'p1.descripcion as procedimiento_descripcion', 'a.fecha', 'a.referido_por', 'h.nombre as hospital_nombre',
                             'ap.premedicacion', DB::raw('CASE WHEN ap.tolerancia = "B" THEN "Buena" WHEN ap.tolerancia = "R" THEN "Regular" ELSE "Mala" END as tolerancia_descripcion'), 'ap.indicacion', 'ap.hallazgos', 'ap.recomendaciones', 'ap.diagnostico', 'm.firma')
                    ->first();

        $fotos = admision_fotos::where('admision_id', $admision_id)->where('informe', 'S')->get();
        
        ini_set('memory_limit', '-1');
        $pdf = PDF::loadView('admisiones.informe', compact('empresa', 'admision', 'fotos'));
        $pdf->setPaper('letter','portrait');
        $nombre_informe = 'informe.pdf';
        return $pdf->stream($nombre_informe);

        return view('admisiones.informe', compact('empresa'));
    }

    public function imagen_informe(){
        $id = $_POST['id'];
        $foto = admision_fotos::findOrFail($id);
        var_dump($foto);
        if ($foto->informe == 'S') {
            $foto->informe = 'N';
        }else{
            $foto->informe = 'S';
        }
        $foto->save();
    }
}