<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Caja;
use App\Resolucion;
use App\Tipo_documento;

class ResolucionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create($id){
    	$pCaja = Caja::findOrFail($id);
        $pTipoDocumentos = Tipo_documento::where('estado', 'A')->get();

    	return view('resoluciones.create', compact('pCaja', 'pTipoDocumentos'));
    }

    public function store(Request $request){
    	$validData = $request->validate([
            'caja_id'             => 'required',
            'tipo_documento'      => 'required',
            'serie'               => 'required',
            'correlativo_inicial' => 'required',
            'correlativo_final'   => 'required',
            'ultimo_correlativo'  => 'required'
        ]);

        $resolucion = new Resolucion();
        $resolucion->caja_id = $validData['caja_id'];
        $resolucion->tipo_documento = $validData['tipo_documento'];
        $resolucion->serie   = strtoupper($validData['serie']);
        $resolucion->correlativo_inicial = $validData['correlativo_inicial'];
        $resolucion->correlativo_final   = $validData['correlativo_final'];
        $resolucion->ultimo_correlativo   = $validData['ultimo_correlativo'];

        if(isset($request->estado)){
            $resolucion->estado = 'A';
        }else{
            $resolucion->estado = 'I';
        }

        $resolucion->save();

        return Redirect::route('resolucion_caja', $request->caja_id)->with('message','Resolucion grabada con exito');
    }

    public function edit($id){
    	$pResolucion = Resolucion::findOrFail($id);
    	$pCaja = Caja::findOrFail($pResolucion->caja_id);
        $pTipoDocumentos = Tipo_documento::where('estado', 'A')->get();

    	return view('resoluciones.edit', compact('pCaja', 'pResolucion','pTipoDocumentos'));
    }

    public function update(Request $request, $id){
    	$validData = $request->validate([
            'caja_id'             => 'required',
            'serie'               => 'required',
            'correlativo_inicial' => 'required',
            'correlativo_final'   => 'required',
            'ultimo_correlativo'  => 'required'
        ]);

        $resolucion = Resolucion::findOrFail($id);
        $resolucion->caja_id = $validData['caja_id'];
        $resolucion->serie   = strtoupper($validData['serie']);
        $resolucion->correlativo_inicial = $validData['correlativo_inicial'];
        $resolucion->correlativo_final   = $validData['correlativo_final'];
        $resolucion->ultimo_correlativo   = $validData['ultimo_correlativo'];
        $resolucion->estado = $request->estado;

        /*
        if(isset($request->estado)){
            $resolucion->estado = 'A';
        }else{
            $resolucion->estado = 'I';
        }*/

        $resolucion->save();

        return Redirect::route('resolucion_caja', $request->caja_id)->with('message','Resolucion grabada con exito');
    }
}
