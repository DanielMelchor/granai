<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use App\MotivoRechazo;

class MotivoRechazoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pMotivosRechazo = MotivoRechazo::all();
        return view('motivoRechazos.index', compact('pMotivosRechazo'));
    }

    public function create(){
    	return view('motivoRechazos.create');
    }

    public function store(Request $request){
    	$validData = $request->validate([
            'descripcion' => 'required'
        ]);

        $motivoRechazo = new MotivoRechazo();
        $motivoRechazo->descripcion = $validData['descripcion'];

        if(isset($request->estado)){
    		$motivoRechazo->estado = 'A';
    	}else{
    		$motivoRechazo->estado = 'I';
    	}

    	$motivoRechazo->save();

    	//return Redirect::route('motivoRechazos')->with('message','Motivo grabado con exito');
        Session::flash('success', 'Motivo de Rechazo Guardado con exito !!!' );
        return redirect(route('motivoRechazos'));
    }

    public function edit($id){
    	$pMotivoRechazo = MotivoRechazo::findOrFail($id);
    	return view('motivoRechazos.edit', compact('pMotivoRechazo'));
    }

    public function update(Request $request, $id){
    	$validData = $request->validate([
            'descripcion' => 'required'
        ]);

        $motivoRechazo = MotivoRechazo::findOrFail($id);
        $motivoRechazo->descripcion = $validData['descripcion'];

        if(isset($request->estado)){
    		$motivoRechazo->estado = 'A';
    	}else{
    		$motivoRechazo->estado = 'I';
    	}

    	$motivoRechazo->save();

    	//return Redirect::route('motivoRechazos')->with('message','Motivo grabado con exito');
        Session::flash('success', 'Motivo de Rechazo Actualizado con exito !!!' );
        return redirect(route('motivoRechazos'));
    }
}
