<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\carbon;

use App\Admision;
use App\Hospital;
use App\Paciente;
use App\Paciente_antecedente;
use App\Medico;
use App\Aseguradora;
use App\admision_consulta;
use App\admision_procedimiento;
use App\userStamps;
use App\Especialidad;
use App\vw_admision_listado;
use App\Producto;
use App\admision_procedimiento_fotos;
use App\AdmisionCompleta;
use App\Correlativo;
use App\vw_admision;
use App\Medicamento;
use App\medicamento_dosis;

class pacienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        //$pPacientes = Paciente::Nombre($request->busqueda)->get();
        $pPacientes = DB::table('pacientes as p')
                      ->select('p.id as id', 'p.codigo_id as codigo_id', 'p.expediente_No as expediente_no', 'p.nombre_completo as nombre_completo', 'p.telefonos as telefonos', 'p.celular as celular', 'p.fecha_nacimiento as fecha_nacimiento')
                      ->get();
        $pMedicos   = Medico::all();
        $pHospitales = Hospital::all();
        $pAseguradoras = Aseguradora::all();
        return view('pacientes.index', [
            'pPacientes' => $pPacientes,
            'pMedicos' => $pMedicos,
            'pHospitales' => $pHospitales,
            'pAseguradoras' => $pAseguradoras
        ]);
    }

    public function create($origen, $cita)
    {
        $pAseguradoras = aseguradora::all();
        $paciente = Correlativo::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'P')->max('correlativo') + 1;
        
        return view('pacientes.create', compact('pAseguradoras', 'paciente', 'origen', 'cita'));
    }

    public function store(Request $request, $origen, $cita)
    {
        $validData = $request->validate([
            'expediente_no'    => 'required|numeric',
            'codigo_id'        => 'required|numeric',
            'nombres'          => 'required|min:3',
            'apellidos'        => 'required|min:3',
            'fecha_nacimiento' => 'required|date'

        ]);

        $paciente = new Paciente();
        $paciente->expediente_no    = $validData['expediente_no'];
        $paciente->codigo_id        = $validData['codigo_id'];
        $paciente->nombres          = $validData['nombres'];
        $paciente->apellidos        = $validData['apellidos'];
        $paciente->fecha_nacimiento = $validData['fecha_nacimiento'];
        $paciente->expediente_anterior_no = $request->expediente_anterior_no;
        $paciente->apellido_casada  = $request->apellido_casada;
        $paciente->nombre_completo  = $paciente->nombres .' '.$paciente->apellidos.' '.$paciente->apellido_casada;
        $paciente->direccion        = $request->direccion;
        $paciente->ciudad           = $request->ciudad;
        $paciente->telefonos        = $request->telefonos;
        $paciente->fax              = $request->fax;
        $paciente->celular          = $request->celular;
        $paciente->correo_electronico       = $request->correo_electronico;
        $paciente->profesion        = $request->profesion;
        $paciente->trabajo_nombre   = $request->trabajo_nombre;
        $paciente->trabajo_telefono = $request->trabajo_telefono;
        $paciente->estado_civil     = $request->estado_civil;
        $paciente->conyugue_nombre  = $request->conyugue_nombre;
        $paciente->conyugue_ocupacion       = $request->conyugue_ocupacion;
        $paciente->emergencia_parentesco_id = $request->emergencia_parentesco_id;
        $paciente->emergencia_nombre        = $request->emergencia_nombre;
        $paciente->emergencia_telefonos     = $request->emergencia_telefonos;
        $paciente->referido_por     = $request->referido_por;
        $paciente->religion         = $request->religion;
        $paciente->aseguradora_id   = $request->aseguradora_id;
        $paciente->seguro_no        = $request->seguro_no;
        $paciente->recordar_cita    = $request->recordar_cita;
        $paciente->antmedico_descripcion      = $request->antmedico_descripcion;
        $paciente->antquirurgico_descripcion  = $request->antquirurgico_descripcion;
        $paciente->antalergia_descripcion     = $request->antalergia_descripcion;
        $paciente->antgineco_descripcion      = $request->antgineco_descripcion;
        $paciente->antfamiliar_descripcion    = $request->antfamiliar_descripcion;
        $paciente->antmedicamento_descripcion = $request->antmedicamento_descripcion;
        $paciente->tabaco_cnt       = $request->tabaco_cnt;
        $paciente->tabaco_tiempo    = $request->tabaco_tiempo;
        $paciente->alcohol_cnt      = $request->alcohol_cnt;
        $paciente->alcohol_tiempo   = $request->alcohol_tiempo;
        $paciente->factura_nit      = $request->factura_nit;
        $paciente->factura_nombre   = $request->factura_nombre;
        $paciente->factura_direccion = $request->factura_direccion;
        $paciente->cadena = $paciente->nombres .' '.$paciente->apellidos.' '.$paciente->apellido_casada.' '.$paciente->codigo_id.' '.$paciente->expediente_no.' '.$paciente->expediente_anterior_no.' '.$paciente->telefonos.' '.$paciente->celular;

        if (isset($request->genero)){
        	$paciente->genero       = $request->genero;
        }

        if (isset($request->recordar_cita)){
        	$paciente->recordar_cita       = 'S';
        }else
        {
        	$paciente->recordar_cita       = 'N';
        }

        if (isset($request->antecedente_importante)){
        	$paciente->antecedente_importante       = 'S';
        }else
        {
        	$paciente->antecedente_importante       = 'N';
        }


        
        $paciente->save();

        $corr = Correlativo::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'P')->first();
        $corr->correlativo = $validData['codigo_id'];
        $corr->save();

        //Session::flash('success', 'Se editó el medico con éxito.');
        Session::flash('success', 'Paciente grabado con exito !!!' );

        if ($origen == 'P') {
            return Redirect::route('pacientes');
        } else {
            return Redirect::route('nueva_edicion', $cita);
        }
    }

    public function consultas($id){
        $pTotalConsultas = vw_admision_consulta::where('paciente_id', $id)->count();
        $pConsultas    = vw_admision_consulta::where('paciente_id', $id)->orderByRaw('fecha_creacion DESC')->paginate(1);
        $json["lista"] = array_values($pConsultas);
        $json["cantidad"] = array_values($pTotalConsultas);

        header("Content-type:application/json; charset = utf-8");
        echo json_encode($json);
        exit();
    }

    public function edit($id)
    {
        $pPaciente = Paciente::findOrFail($id);
        $pAseguradoras = aseguradora::all();
        //$pAdmisiones   = vw_admision::where('paciente_id', $id)->orderByRaw('fecha_creacion DESC')->get();

        return view('pacientes.edit', compact('pPaciente', 'pAseguradoras'));

    }

    public function update(Request $request, $id)
    {
        $validData = $request->validate([
            'expediente_no'    => 'required|numeric',
            'codigo_id'        => 'required|numeric',
            'nombres'          => 'required|min:3',
            'apellidos'        => 'required|min:3',
            'fecha_nacimiento' => 'required|date'

        ]);

        $paciente = Paciente::findOrFail($id);
        $paciente->expediente_no    = $validData['expediente_no'];
        $paciente->codigo_id        = $validData['codigo_id'];
        $paciente->nombres          = $validData['nombres'];
        $paciente->apellidos        = $validData['apellidos'];
        $paciente->fecha_nacimiento = $validData['fecha_nacimiento'];
        $paciente->expediente_anterior_no = $request->expediente_anterior_no;
        $paciente->apellido_casada  = $request->apellido_casada;
        $paciente->nombre_completo  = $paciente->nombres .' '.$paciente->apellidos.' '.$paciente->apellido_casada;
        $paciente->direccion        = $request->direccion;
        $paciente->ciudad           = $request->ciudad;
        $paciente->telefonos        = $request->telefonos;
        $paciente->fax              = $request->fax;
        $paciente->celular          = $request->celular;
        $paciente->correo_electronico       = $request->correo_electronico;
        $paciente->profesion        = $request->profesion;
        $paciente->trabajo_nombre   = $request->trabajo_nombre;
        $paciente->trabajo_telefono = $request->trabajo_telefono;
        $paciente->estado_civil     = $request->estado_civil;
        $paciente->conyugue_nombre  = $request->conyugue_nombre;
        $paciente->conyugue_ocupacion       = $request->conyugue_ocupacion;
        $paciente->emergencia_parentesco_id = $request->emergencia_parentesco_id;
        $paciente->emergencia_nombre        = $request->emergencia_nombre;
        $paciente->emergencia_telefonos     = $request->emergencia_telefonos;
        $paciente->referido_por     = $request->referido_por;
        $paciente->religion         = $request->religion;
        $paciente->aseguradora_id   = $request->aseguradora_id;
        $paciente->seguro_no        = $request->seguro_no;
        $paciente->recordar_cita    = $request->recordar_cita;
        $paciente->antmedico_descripcion      = $request->antmedico_descripcion;
        $paciente->antquirurgico_descripcion  = $request->antquirurgico_descripcion;
        $paciente->antalergia_descripcion     = $request->antalergia_descripcion;
        $paciente->antgineco_descripcion      = $request->antgineco_descripcion;
        $paciente->antfamiliar_descripcion    = $request->antfamiliar_descripcion;
        $paciente->antmedicamento_descripcion = $request->antmedicamento_descripcion;
        $paciente->tabaco_cnt       = $request->tabaco_cnt;
        $paciente->tabaco_tiempo    = $request->tabaco_tiempo;
        $paciente->alcohol_cnt      = $request->alcohol_cnt;
        $paciente->alcohol_tiempo   = $request->alcohol_tiempo;
        $paciente->factura_nit      = $request->factura_nit;
        $paciente->factura_nombre   = $request->factura_nombre;
        $paciente->factura_direccion = $request->factura_direccion;
        $paciente->cadena = $paciente->nombres .' '.$paciente->apellidos.' '.$paciente->apellido_casada.' '.$paciente->codigo_id.' '.$paciente->expediente_no.' '.$paciente->expediente_anterior_no.' '.$paciente->telefonos;

        if (isset($request->masculino)){
            $paciente->genero       = $request->masculino;
        }
        if (isset($request->femenino)){
            $paciente->genero       = $request->femenino;
        }

        if (isset($request->recordar_cita)){
            $paciente->recordar_cita       = 'S';
        }else
        {
            $paciente->recordar_cita       = 'N';
        }

        if (isset($request->antecedente_importante)){
            $paciente->antecedente_importante       = 'S';
        }else
        {
            $paciente->antecedente_importante       = 'N';
        }


        
        $paciente->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        return Redirect::route('pacientes')->with('message','Paciente grabado con exito');
    }

    public function show($id){        
        $pPaciente = Paciente::findOrFail($id);
        $pMedicos = Medico::where('estado', 'A')->get();
        $pHospitales = Hospital::where('estado', 'A')->get();
        $pAseguradoras = Aseguradora::where('estado', 'A')->get();
        $pAdmisiones = vw_admision_listado::where('paciente_id', $id)->OrderBy('fecha_creacion', 'DESC')->get();
        $pProcedimientos = Producto::where('estado','A')->where('clasificacion_producto', 'PROC')->get();
        $ultimaAdmision = vw_admision_listado::where('paciente_id', $id)->max('id');
        $ultimaAdmisionC = vw_admision_listado::where('id', $ultimaAdmision)->first();
        $tipoAdmision = vw_admision_listado::where('id', $ultimaAdmision)->first();
        $pGeneralesAdmision = Admision::where('id', $ultimaAdmision)->first();
        $pHospital = Hospital::where('id', $pGeneralesAdmision->hospital_id)->first();
        $pMedico   = Medico::where('id', $pGeneralesAdmision->medico_id)->first();
        if($ultimaAdmisionC->tipo == 'C'){
            $pDetalle = admision_consulta::where('admision_id', $ultimaAdmisionC->id)->first();
            $pProcedimiento = null;
            $pImagenes      = null;
            $totalImagenes  = null;
        }elseif($ultimaAdmisionC->tipo == 'C'){
            $pDetalle = admision_procedimiento::where('admision_id', $admision_id)->first();
            $pProcedimiento = admision_procedimiento::where('admision_id', $ultimaAdmision)->first();
            $pImagenes    = admision_procedimiento_fotos::where('procedimiento_id', $pProcedimiento->id)->get();
            $totalImagenes = admision_procedimiento_fotos::where('procedimiento_id', $pProcedimiento->id)->get()->count();
        }else{
            $pDetalle       = null;
            $pProcedimiento = null;
            $pImagenes      = null;
            $totalImagenes  = null;
        }
        return view('admisiones.admisiones_listado', compact('pPaciente', 'pAdmisiones', 'pDetalle','tipoAdmision', 'pProcedimientos', 'pGeneralesAdmision', 'pHospital', 'pMedico', 'pImagenes', 'totalImagenes', 'pMedicos', 'pHospitales', 'pAseguradoras'));
    }
    public function show1($id, $admision_id, $admision_tipo){        
        
        $pPaciente = Paciente::findOrFail($id);
        $pMedicos = Medico::where('estado', 'A')->get();
        $pHospitales = Hospital::where('estado', 'A')->get();
        $pAseguradoras = Aseguradora::where('estado', 'A')->get();
        $pAdmisiones = vw_admision_listado::where('paciente_id', $id)->OrderBy('fecha_creacion', 'DESC')->get();
        $pProcedimientos = Producto::where('estado','A')->where('clasificacion_producto', 'PROC')->get();
        $ultimaAdmisionC = vw_admision_listado::where('id', $admision_id)->first();
        $tipoAdmision = vw_admision_listado::where('id', $admision_id)->first();
        $pGeneralesAdmision = Admision::where('id', $admision_id)->first();
        $pHospital = Hospital::where('id', $pGeneralesAdmision->hospital_id)->first();
        $pMedico   = Medico::where('id', $pGeneralesAdmision->medico_id)->first();
        if($tipoAdmision->tipo == 'C'){
            $pDetalle       = admision_consulta::where('admision_id', $admision_id)->first();
            $pProcedimiento = null;
            $pImagenes      = null;
            $totalImagenes  = null;
        }elseif($tipoAdmision->tipo == 'P'){
            $pDetalle       = admision_procedimiento::where('admision_id', $admision_id)->first();
            $pProcedimiento = admision_procedimiento::where('admision_id', $admision_id)->first();
            $pImagenes      = admision_procedimiento_fotos::where('procedimiento_id', $pProcedimiento->id)->get();
            $totalImagenes  = admision_procedimiento_fotos::where('procedimiento_id', $pProcedimiento->id)->get()->count();
        }else{
            $pDetalle       = null;
            $pProcedimiento = null;
            $pImagenes      = null;
            $totalImagenes  = null;
        }

        return view('admisiones.admisiones_listado', compact('pPaciente', 'pAdmisiones', 'pDetalle', 'tipoAdmision', 'pProcedimientos', 'pGeneralesAdmision', 'pHospital', 'pMedico', 'pImagenes','totalImagenes', 'pMedicos', 'pHospitales', 'pAseguradoras'));
    }

    public function lista_pacientes(){
        $data = Paciente::all();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function trae_datos_facturacion(){
        $datos = DB::table('pacientes as p')
                 ->where('p.id', $_POST['paciente_id'])
                 ->select('p.factura_nit', 'p.factura_nombre', 'p.factura_direccion')
                 ->first();
        return Response::json($datos);
    }

    public function verifica_expediente(){
        $expediente_no = $_POST['expediente'];
        $existe = Paciente::where('expediente_no', $expediente_no)->count();
        return Response::json($existe);
    }

    public function get_telefono_x_paciente(){
        $paciente_id = $_POST['paciente_id'];
        $complemento = Paciente::findOrFail($paciente_id);
        return Response::json($complemento);
    }
}
