<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use App\MotivoAnulacion;

class MotivoAnulacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pMotivosAnulacion = MotivoAnulacion::all();
        return view('motivosAnulacion.index', compact('pMotivosAnulacion'));
    }

    public function create(){
    	return view('motivosAnulacion.create');
    }

    public function store(Request $request){
    	$validData = $request->validate([
            'descripcion' => 'required'
        ]);

        $motivoAnulacion = new MotivoAnulacion();
        $motivoAnulacion->descripcion = $validData['descripcion'];

        if(isset($request->estado)){
    		$motivoAnulacion->estado = 'A';
    	}else{
    		$motivoAnulacion->estado = 'I';
    	}

    	$motivoAnulacion->save();

    	//return Redirect::route('motivosAnulacion')->with('message','Motivo grabado con exito');
        Session::flash('success', 'Motivo de Anulación Guardado con exito !!!' );
        return redirect(route('motivosAnulacion'));
    }

    public function edit($id){
    	$pMotivoAnulacion = MotivoAnulacion::findOrFail($id);
    	return view('motivosAnulacion.edit', compact('pMotivoAnulacion'));
    }

    public function update(Request $request, $id){
    	$validData = $request->validate([
            'descripcion' => 'required'
        ]);

        $motivoAnulacion = MotivoAnulacion::findOrFail($id);
        $motivoAnulacion->descripcion = $validData['descripcion'];

        if(isset($request->estado)){
    		$motivoAnulacion->estado = 'A';
    	}else{
    		$motivoAnulacion->estado = 'I';
    	}

    	$motivoAnulacion->save();

    	//return Redirect::route('motivosAnulacion')->with('message','Motivo grabado con exito');
        Session::flash('success', 'Motivo de Anulación Actualizado con exito !!!' );
        return redirect(route('motivosAnulacion'));
    }
}
