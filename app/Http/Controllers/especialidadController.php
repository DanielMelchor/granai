<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Especialidad;
use userStamps;

class especialidadController extends Controller
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
        $pEspecialidades = Especialidad::all();
        return view('especialidades.index', [
            'pEspecialidades' => $pEspecialidades
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('especialidades.create');
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
            'iniciales' => 'required',
            'descripcion' => 'required'
        ]);

        $especialidad = new Especialidad();
        $especialidad->iniciales = $validData['iniciales'];
        $especialidad->descripcion = $validData['descripcion'];
        //$medico->firma = $file_name;
        if (isset($request->estado)) {
            $especialidad->estado = 'A';
        }else{
            $especialidad->estado = 'I';
        }
        $especialidad->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('especialidades')->with('message','Especialidad grabado con exito');
        Session::flash('success', 'Especialidad Guardada con exito !!!' );
        return redirect(route('especialidades'));
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
        $especialidad = Especialidad::findOrFail($id);
        return view('especialidades.edit',[
            'pEspecialidad' => $especialidad]);
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
            'iniciales' => 'required',
            'descripcion' => 'required'
        ]);

        $especialidad = Especialidad::findOrFail($id);
        $especialidad->iniciales = $validData['iniciales'];
        $especialidad->descripcion = $validData['descripcion'];
        //$medico->firma = $file_name;
        if (isset($request->estado)) {
            $especialidad->estado = 'A';
        }else{
            $especialidad->estado = 'I';
        }
        $especialidad->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('especialidades')->with('message','Especialidad grabado con exito');
        Session::flash('success', 'Especialidad Actualizada con exito !!!' );
        return redirect(route('especialidades'));
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
}
