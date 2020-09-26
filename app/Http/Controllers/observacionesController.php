<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\observacion_admision;
use userStamps;
use App\Especialidad;

class observacionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pObservaciones = observacion_admision::all();
        return view('observaciones.index', [
            'pObservaciones' => $pObservaciones
        ]);
    }

    public function create()
    {
        return view('observaciones.create');
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'proceso' => 'required',
            'descripcion' => 'required'
        ]);

        $observacion = new observacion_admision();
        $observacion->proceso = $validData['proceso'];
        $observacion->descripcion = $validData['descripcion'];
        if (isset($request->estado)) {
            $observacion->estado = 'A';
        }else{
            $observacion->estado = 'I';
        }
        $observacion->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('observaciones')->with('message','Observación grabada con exito');
        Session::flash('success', 'Observación Guardada con exito !!!' );
        return redirect(route('observaciones'));
    }

    public function edit($id)
    {
        $pObservacion = observacion_admision::findOrFail($id);
        return view('observaciones.edit',[
        	'pObservacion' => $pObservacion
        ]);
    }

    public function update(Request $request, $id)
    {
        $validData = $request->validate([
            'proceso' => 'required',
            'descripcion' => 'required'
        ]);

        $observacion = observacion_admision::findOrFail($id);
        $observacion->proceso = $validData['proceso'];
        $observacion->descripcion = $validData['descripcion'];
        if (isset($request->estado)) {
            $observacion->estado = 'A';
        }else{
            $observacion->estado = 'I';
        }
        $observacion->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('observaciones')->with('message','Observación grabada con exito');
        Session::flash('success', 'Observacion Actualizada con exito !!!' );
        return redirect(route('observaciones'));
    }
}
