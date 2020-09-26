<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use DB;
use DateTime;
use Session;
use App\evento;
use App\Agenda;
use App\Medico;
use App\Hospital;
use App\Paciente;
use App\Aseguradora;
use App\admision_correlativo;
use App\Admision;
use App\Correlativo;
use App\bitacora_admision;
use Redirect;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        //$resultado = evento::all()->toJson();
		$eventosDB = evento::where('estado', 'P')->get();
        $pAseguradoras = Aseguradora::where('estado','=','A')->get();
		foreach($eventosDB as $evento){
            $e['id'] = $evento->id;
            $e['title'] = $evento->title;
		    $e['start'] = $evento->start;
            $e['end'] = $evento->end;
		    $e['descripcion'] = $evento->descripcion;
		    $eventos[] = $e;
		}
        $resultado = json_encode($eventosDB);

        //dd($resultado);

        return view('agenda.index', compact('resultado','pAseguradoras')
        );
    }

    public function nuevo_index(){
        //dd($medico . " ". $estado . " ". $fecha);
        $medicos = Medico::where('empresa_id', Auth::user()->empresa_id)->where('estado', 'A')->get();
        $hospitales = hospital::where('estado', 'A')->get();
        $aseguradoras = Aseguradora::where('estado','A')->get();
        $pacientes = Paciente::all();
        $today   = Carbon::now()->format('Y-m-d');
        $medico  = Medico::where('empresa_id', Auth::user()->empresa_id)->where('estado', 'A')->first();


        /*if ($estado == 'T') {
            $agendas = \DB::table('agendas')
                    ->where('agendas.medico_id', $medico)
                    ->whereDate('agendas.fecha_inicio', $fecha)
                    ->join('hospitales', 'hospitales.id', 'agendas.hospital_id')
                    ->leftJoin('pacientes', 'pacientes.id', 'agendas.paciente_id')
                    ->select('agendas.id', 'agendas.empresa_id', 'agendas.medico_id', 'agendas.hospital_id',
                             'agendas.paciente_id', 'agendas.fecha_inicio', 'agendas.nombre_completo', 'agendas.telefonos',
                             'agendas.observaciones', 'agendas.estado', 'pacientes.expediente_no as expediente_no', 'hospitales.nombre')
                    ->OrderBy('fecha_inicio')
                    ->get();
        }else{
            $agendas = \DB::table('agendas')
                    ->where('agendas.medico_id', $medico)
                    ->where('agendas.estado', $estado)
                    ->whereDate('agendas.fecha_inicio', $fecha)
                    ->join('hospitales', 'hospitales.id', 'agendas.hospital_id')
                    ->leftJoin('pacientes', 'pacientes.id', 'agendas.paciente_id')
                    ->select('agendas.id', 'agendas.empresa_id', 'agendas.medico_id', 'agendas.hospital_id',
                             'agendas.paciente_id', 'agendas.fecha_inicio', 'agendas.nombre_completo', 'agendas.telefonos',
                             'agendas.observaciones', 'agendas.estado', 'pacientes.expediente_no as expediente_no', 'hospitales.nombre')
                    ->OrderBy('fecha_inicio')
                    ->get();
        }*/

        return view('agenda.nuevo_index', compact('medicos', 'medico', 'today', 'hospitales', 'pacientes', 'aseguradoras'));
        //vw_consultas_resumen::whereBetween('fecha', [$fechaInicial, $fechaFinal])->paginate(15);
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'AEtxtFecha' => 'required',
            'AEFecha' => 'required',
            'AEtxtStart' => 'required',
            'AEtxtEnd' => 'required',
            'AEtxtTitle' => 'required',
            'AEtxtDescripcion' => 'required'
        ]);

        //dd(date("Y/m/d",$validData['AEFecha'])."T". $validData['AEtxtStart']);

        $evento              = new evento();
        $evento->title       = $validData['AEtxtTitle'];
        $evento->descripcion = $validData['AEtxtDescripcion'];
        $evento->color       = '#222d32'; //$request->AEtxtColor;//'#FFFFFF';
        $evento->textcolor   = '##b8c7ce'; //$request->AEtxtTextColor;//'#000000';
        $evento->start       = $validData['AEFecha']."T". $validData['AEtxtStart'];
        $evento->end         = $validData['AEFecha']."T". $validData['AEtxtEnd'];
        $evento->estado      = 'P';

        $evento->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('agenda')->with('message','Cita grabada con exito');
        Session::flash('success', 'Cita grabada con exito !!!' );
        return redirect(route('agenda'));
    }

    public function nuevo_store(Request $request){
        $validData = $request->validate([
            'fecha_cita'  => 'required',
            'hora_inicio' => 'required',
            'hora_final'  => 'required', 
            'nombre_completo' => 'required',
            'telefonos'   => 'required',
            'hospital_id' => 'required',
            'medico_id'   => 'required'
        ]);

        //$fechahora = DateTime($validData['fecha']. " ".$validData['hora'], "Y-m-d H:i:s");
        //$fechahora = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $validData['fecha']. " ".$validData['hora']);
        $fechahora_inicio = Carbon::createFromFormat('Y-m-d H:i', $validData['fecha_cita']. " ".$validData['hora_inicio'])->toDateTimeString();
        $fechahora_final = Carbon::createFromFormat('Y-m-d H:i', $validData['fecha_cita']. " ".$validData['hora_final'])->toDateTimeString();

        $agenda = new Agenda;
        $agenda->empresa_id       = Auth::user()->empresa_id;
        $agenda->medico_id        = $validData['medico_id'];
        $agenda->hospital_id      = $validData['hospital_id'];
        $agenda->fecha_inicio     = $fechahora_inicio;
        $agenda->fecha_final      = $fechahora_final;
        $agenda->nombre_completo  = $validData['nombre_completo'];
        $agenda->telefonos        = $validData['telefonos'];
        $agenda->observaciones    = $request->observaciones;
        $agenda->estado           = 'A';
        $agenda->save();

        //return Redirect::route('nueva_agenda', [$agenda->medico_id, 'T', $validData['fecha_cita']])->with('message','Cita grabada con exito');
        Session::flash('success', 'Cita grabada con exito !!!' );
        return redirect(route('nueva_agenda', [$agenda->medico_id, 'T', $validData['fecha_cita']]));
    }

    public function nuevo_edit($id){
        $cita       = Agenda::findOrFail($id);
        $admision_id = Admision::where('agenda_id', $cita->id)->select('id')->first();
        if (!isset($admision_id)) {
            $admision_id = 0;
        }else{
            $admision_id = $admision_id->id;
        }
        $medicos    = Medico::where('estado', 'A')->get();
        $hospitales = hospital::where('estado', 'A')->get();
        $aseguradoras = Aseguradora::where('estado','=','A')->get();
        $pacientes  = Paciente::all();
        $today      = Carbon::now()->format('Y-m-d');
        $fecha      = date('Y-m-d',strtotime($cita->fecha_inicio)); 
        $hora_inicio = date('H:i',strtotime($cita->fecha_inicio)); 
        $hora_final = date('H:i',strtotime($cita->fecha_final)); 
        //$fecha_cita  = $cita->fecha_inicio->format('Y-m-d');
        //dd($fecha_cita);

        return view('agenda.nuevo_edicion', compact('cita', 'medicos', 'hospitales', 'pacientes', 'fecha','hora_inicio', 'hora_final', 'aseguradoras', 'admision_id'));
    }

    public function update(Request $request)
    {
        $validData = $request->validate([
            'EVFecha'          => 'required',
            'EVtxtFecha'       => 'required',
            'EVtxtStart'       => 'required',
            'EVtxtEnd'         => 'required',
            'EVtxtTitle'       => 'required',
            'EVtxtDescripcion' => 'required'
        ]);

        $fecha         = date("Y-d-m",strtotime($validData['EVFecha']));
        $evento        = evento::findOrFail($request->EVid);
        $evento->title = $validData['EVtxtTitle'];
        $evento->descripcion = $validData['EVtxtDescripcion'];
        $evento->start = $fecha."T". $validData['EVtxtStart'];
        $evento->end   = $fecha."T". $validData['EVtxtEnd'];

        $evento->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        return Redirect::route('agenda')->with('message','hospital grabado con exito');
    }

    public function update_nuevo(Request $request, $id)
    {
        $validData = $request->validate([
            'fecha_cita'  => 'required',
            'hora_inicio' => 'required',
            'hora_final'  => 'required', 
            'nombre_completo' => 'required',
            'telefonos'   => 'required',
            'hospital_id' => 'required',
            'medico_id'   => 'required'
        ]);

        $fechahora_inicio = Carbon::createFromFormat('Y-m-d H:i', $validData['fecha_cita']. " ".$validData['hora_inicio'])->toDateTimeString();
        $fechahora_final = Carbon::createFromFormat('Y-m-d H:i', $validData['fecha_cita']. " ".$validData['hora_final'])->toDateTimeString();
        
        $agenda = Agenda::findOrFail($id);
        $agenda->empresa_id       = Auth::user()->empresa_id;
        $agenda->medico_id        = $validData['medico_id'];
        $agenda->hospital_id      = $validData['hospital_id'];
        $agenda->fecha_inicio     = $fechahora_inicio;
        $agenda->fecha_final      = $fechahora_final;
        $agenda->nombre_completo  = $validData['nombre_completo'];
        $agenda->telefonos        = $validData['telefonos'];
        $agenda->observaciones    = $request->observaciones;
        $agenda->paciente_id      = $request->paciente_id;
        $agenda->estado           = 'A';
        $agenda->save();

         /*return Redirect::route('nueva_agenda', [$agenda->medico_id, 'T', $validData['fecha_cita']])->with('message','Cita actualizada con exito');*/
         //return Redirect::route('nueva_edicion', [$id])->with('message','Cita actualizada con exito');
         Session::flash('success', 'Cita Actualizada con exito !!!' );
        return redirect(route('nueva_edicion', [$id]));
    }

    public function destroy(Request $request){
        $evento         = evento::findOrFail($request->EVid);
        $evento->estado = 'I';

        $evento->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        return Redirect::route('agenda')->with('message','Cita anulada con exito');
    }

    public function marcar_cancelada($id){
        $cita = Agenda::findOrFail($id);
        $fecha         = date("Y-m-d",strtotime($cita->fecha_inicio));
        $cita->estado = 'C';
        $cita->save();
        return Redirect::route('nueva_agenda',[$cita->medico_id, 'T', $fecha])->with('message','Cita cancelada con exito');   
    }

    public function marcar_cancelada_ajax(){
        $cita_id = $_POST['cita_id'];
        $cita = Agenda::findOrFail($cita_id);
        //$fecha         = date("Y-m-d",strtotime($cita->fecha_inicio));
        $cita->estado = 'C';
        $cita->save();

        return Response::json('Cita Anulada con Exito !!!');
    }

    public function marcar_realizada($id){
        $cita = Agenda::findOrFail($id);
        $fecha         = date("Y-m-d",strtotime($cita->fecha_inicio));
        $cita->estado = 'R';
        $cita->save();
        return Redirect::route('nueva_agenda',[$cita->medico_id, 'T', $fecha])->with('message','Cita finalizada con exito');   
    }

    public function marcar_realizada_ajax(){
        $cita_id = $_POST['cita_id'];
        $cita = Agenda::findOrFail($cita_id);
        //$fecha         = date("Y-m-d",strtotime($cita->fecha_inicio));
        $cita->estado = 'R';
        $cita->save();
        return Response::json('Cita Finalizada con Exito !!!');
    }

    public function trae_citas(){
        $medico_id = $_POST['medico_id'];
        $fecha     = $_POST['fecha'];
        $estado    = $_POST['estado'];

        if ($estado == 'T') {
            $listado = DB::table('agendas as a')
                       ->leftjoin('pacientes as p', 'a.paciente_id', 'p.id')
                       ->where('a.empresa_id', Auth::user()->empresa_id)
                       ->where('a.medico_id', $medico_id)
                       ->whereDate('a.fecha_inicio', $fecha)
                       ->select('a.fecha_inicio', 'a.estado', 'a.observaciones', 'a.paciente_id', 'a.nombre_completo', 'a.telefonos', 'p.expediente_no', 'a.id')
                       ->orderBy('a.fecha_inicio')
                       ->get();
        } else {
            $listado = DB::table('agendas as a')
                       ->leftjoin('pacientes as p', 'a.paciente_id', 'p.id')
                       ->where('a.empresa_id', Auth::user()->empresa_id)
                       ->where('a.medico_id', $medico_id)
                       ->whereDate('a.fecha_inicio', $fecha)
                       ->where('a.estado', $estado)
                       ->select('a.fecha_inicio', 'a.estado', 'a.observaciones', 'a.paciente_id', 'a.nombre_completo', 'a.telefonos', 'p.expediente_no', 'a.id')
                       ->orderBy('a.fecha_inicio')
                       ->get();
        }
        return response::json($listado);
    }

    public function store_admision_x_cita(){
        $cita_id          = $_POST['cita_id'];
        $tipo_admision    = $_POST['tipo_admision'];
        if (isset($_POST['admision_tercero'])) {
            $admision_tercero = $_POST['admision_tercero'];
        }else{
            $admision_tercero = '';
        }

        if (isset($_POST['aseguradora_id'])) {
            $aseguradora_id   = $_POST['aseguradora_id'];
        }else{
            $aseguradora_id   = '';
        }

        if (isset($_POST['poliza_no'])) {
            $poliza_no        = $_POST['poliza_no'];
        }else{
            $poliza_no        = '';
        }

        if (isset($_POST['deducible'])) {
            $deducible        = $_POST['deducible'];
        }else{
            $deducible        = 0;
        }

        if (isset($_POST['copago'])) {
            $copago           = $_POST['copago'];
        }else{
            $copago           = 0;
        }
        
        $cita = Agenda::findOrFail($cita_id);
        /*$cita->estado  = 'R';
        $cita->save();*/
        
        $admision = new Admision();
        $admision->empresa_id       = Auth::user()->empresa_id;
        $admision->agenda_id        = $cita->id;
        $admision->fecha            = $cita->fecha_inicio;
        $admision->tipo_admision    = $tipo_admision;
        $admision->serie            = '';
        $id_admision = Correlativo::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'A')->max('correlativo');
        $id_admision += 1;
        $admision->admision         = $id_admision;
        $admision->paciente_id      = $cita->paciente_id;
        $paciente = Paciente::where('id', $cita->paciente_id)->first();
        $admision->edad = Carbon::parse($paciente->fecha_nacimiento)->age;
        $admision->medico_id        = $cita->medico_id;
        $admision->hospital_id      = $cita->hospital_id;
        $admision->admision_tercero = $admision_tercero;
        $admision->aseguradora_id   = $aseguradora_id;
        $admision->poliza_no        = $poliza_no;
        $admision->deducible        = $deducible;
        $admision->copago           = $copago;
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

        //$respuesta = array('parametro' => 0,'respuesta' => 'Admisión '.$admision->admision.' Creada con exito'); 
        $respuesta = 'Admisión '.$admision->admision.' Creada con exito'; 
        //print_r($respuesta);
        //var_dump($respuesta);

        return response::json($respuesta);

    }
}
