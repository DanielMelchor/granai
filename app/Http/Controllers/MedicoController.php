<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use DB;
use Response;
use App\Medico;
use App\receta_medico;
use App\userStamps;
use App\Especialidad;
use App\medico_especialidad;

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pMedicos = Medico::where('empresa_id', Auth::user()->empresa_id)->get();
        return view('medicos.index', [
            'pMedicos' => $pMedicos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pEspecialidades = Especialidad::orderBy('descripcion')->get();
        return view('medicos.create', compact('pEspecialidades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validData = $request->validate([
            'nombres'   => 'required',
            'apellidos' => 'required',
            'titulo'    => 'required'
        ]);

        $medico = new Medico();
        $medico->empresa_id = Auth::user()->empresa_id;
        $medico->nombres   = $validData['nombres'];
        $medico->apellidos = $validData['apellidos'];
        $medico->direccion = $request->direccion;
        $medico->titulo    = $request->titulo;
        $medico->nit       = $request->nit;
        $medico->telefono  = $request->telefono;
        $medico->celular   = $request->celular;
        if ($request->hasFile('medico_firma')){
            $firma             = $request->file('medico_firma')->getClientOriginalName();
            $request->file('medico_firma')->move('firmas', $firma);
            $medico->firma = 'firmas/' . $firma;    
        }
        
        if (isset($request->estado)) {
            $medico->estado = 'A';
        }else{
            $medico->estado = 'I';
        }

        if (isset($request->principal)) {
            $medico->principal = 'S';
        }else{
            $medico->principal = 'N';
        }

        $medico->save();

        // especialidades
        $check = $request->get('check');
        if(!is_null($request->get('check'))){
            for ($i=0; $i < count($check) ; $i++) { 
                $medico_especialidad = new medico_especialidad();
                $medico_especialidad->medico_id = $medico->id;
                $medico_especialidad->especialidad_id = $check[$i];
                $medico_especialidad->estado = 'A';
                $medico_especialidad->save();
            }
        }

        $receta = new receta_medico();
        $receta->medico_id      = $medico->id;
        $receta->pagina_alto    = $request->pagina_x;
        $receta->pagina_ancho   = $request->pagina_y;
        $receta->orientacion    = $request->orientacion;
        $receta->unidad_medida  = $request->unidad_medida;
        $receta->dia_x          = $request->dia_x;
        $receta->dia_y          = $request->dia_y;
        $receta->mes_x          = $request->mes_x;
        $receta->mes_y          = $request->mes_y;
        $receta->anio_x         = $request->anio_x;
        $receta->anio_y         = $request->anio_y;
        $receta->paciente_x     = $request->paciente_x;
        $receta->paciente_y     = $request->paciente_y;
        $receta->tratamiento_x  = $request->tratamiento_x;
        $receta->tratamiento_y  = $request->tratamiento_y;
        $receta->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('editar_medico', $medico->id)->with('message','Medico grabado con exito');
        Session::flash('success', 'Médico Guardado con exito !!!' );
        //return redirect(route('editar_medico', $medico->id));
        return redirect::route('medicos');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $medico = Medico::findOrFail($id);
        $receta = receta_medico::where('medico_id', $id)->first();
        /*$pEspecialidades = DB::table('Especialidades as e')
                           ->leftjoin('medico_especialidades as me', 'e.id', 'me.especialidad_id', 'me.medico_id', $id)
                           ->select('e.id', 'e.descripcion', DB::raw('IFNULL(me.estado, "I") as estado'))
                           ->groupBy('e.id', 'e.descripcion')
                           ->orderBy('e.descripcion')
                           ->get();*/
        $pEspecialidades = especialidad::addSelect(['detestado' => medico_especialidad::where('medico_id', $id)->select(DB::raw('IFNULL(estado, "I")'))->whereColumn('especialidad_id', 'especialidades.id')->limit(1)
                            ])->get();

                           //dd($pEspecialidades);

        return view('medicos.edit', compact('medico', 'receta', 'pEspecialidades'));
    }

 	 /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validData = $request->validate([
            'nombres'   => 'required',
            'apellidos' => 'required',
            'titulo'    => 'required'
        ]);

        $medico = Medico::findOrFail($id);
        $medico->nombres   = $validData['nombres'];
        $medico->apellidos = $validData['apellidos'];
        $medico->direccion = $request->direccion;
        $medico->titulo    = $request->titulo;
        $medico->nit       = $request->nit;
        $medico->telefono  = $request->telefono;
        $medico->celular   = $request->celular;
        $name              = $request->file('firma');

        if ($request->hasFile('medico_firma')){
            $firma         = $request->file('medico_firma')->getClientOriginalName();
            $request->file('medico_firma')->move('firmas', $firma);
            $medico->firma = 'firmas/' . $firma;    
        }

        if (isset($request->estado)) {
            $medico->estado = 'A';
        }else{
            $medico->estado = 'I';
        }

        if (isset($request->principal)) {
            $medico->principal = 'S';
        }else{
            $medico->principal = 'N';
        }

        $medico->save();

        // especialidades
        medico_especialidad::where('medico_id', $id)->delete();
        $check = $request->get('check');
        if(!is_null($request->get('check'))){
            for ($i=0; $i < count($check) ; $i++) { 
                $medico_especialidad = new medico_especialidad();
                $medico_especialidad->medico_id       = $medico->id;
                $medico_especialidad->especialidad_id = $check[$i];
                $medico_especialidad->estado          = 'A';
                $medico_especialidad->save();
            }
        }

        $existe = receta_medico::where('medico_id', $id)->count();

        if ($existe == 0) {
            $receta = new receta_medico();
        }else{
            $receta = receta_medico::where('medico_id', $id)->first();
        }
        
        $receta->medico_id      = $medico->id;
        $receta->pagina_alto    = $request->pagina_x;
        $receta->pagina_ancho   = $request->pagina_y;
        $receta->orientacion    = $request->orientacion;
        $receta->unidad_medida  = $request->unidad_medida;
        $receta->dia_x          = $request->dia_x;
        $receta->dia_y          = $request->dia_y;
        $receta->mes_x          = $request->mes_x;
        $receta->mes_y          = $request->mes_y;
        $receta->anio_x         = $request->anio_x;
        $receta->anio_y         = $request->anio_y;
        $receta->paciente_x     = $request->paciente_x;
        $receta->paciente_y     = $request->paciente_y;
        $receta->tratamiento_x  = $request->tratamiento_x;
        $receta->tratamiento_y  = $request->tratamiento_y;
        $receta->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return back()->with('message','Medico grabado con exito');
        Session::flash('success', 'Médico Actualizado con exito !!!' );
        //return redirect(route('editar_medico', $medico->id));
        return redirect::route('medicos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function borrar_firma($id)
    {
        $medico = Medico::findOrFail($id);
        $receta = receta_medico::where('medico_id', $id)->first();
        $pEspecialidades = especialidad::addSelect(['detestado' => medico_especialidad::where('medico_id', $id)->select(DB::raw('IFNULL(estado, "I")'))->whereColumn('especialidad_id', 'especialidades.id')->limit(1)
                            ])->get();
        if (!empty($medico->firma))
        {
            unlink($medico->firma);
        }
        
        $medico->firma = '';
        $medico->save();
        return view('medicos.edit', compact('medico', 'receta', 'pEspecialidades'));
    }

    public function existe_config_receta_ajax(){
        $medico_id = $_POST['medico_id'];
        $total_registros = receta_config::where('medico_id', $medico_id)->count();
        return response::json($total_registros);
    }

    public function store_config_receta_ajax(){
        $receta = new receta_config();
    }

    public function update_config_receta_ajax(){
        $receta = receta_config::where('medico_id')->first();
    }
}