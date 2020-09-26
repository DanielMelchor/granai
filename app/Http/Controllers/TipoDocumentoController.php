<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Tipo_Documento;
use App\userStamps;


class TipoDocumentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pTipoDocumentos = Tipo_Documento::all();
        return view('tipo_documentos.index', compact('pTipoDocumentos'));
    }

    public function create(){
    	return view('tipo_documentos.create');
    }

    public function store(Request $request){
    	$validData = $request->validate([
            'descripcion' => 'required',
            'signo'       => 'required'
        ]);

        $Tipo_Documento = new Tipo_Documento();
        $Tipo_Documento->descripcion = $validData['descripcion'];
        $Tipo_Documento->signo       = $validData['signo'];

        if(isset($request->estado)){
            $Tipo_Documento->estado = 'A';
        }else{
            $Tipo_Documento->estado = 'I';
        }

        $Tipo_Documento->save();

        //return Redirect::route('tipodocumentos')->with('message','Tipo de documento grabado con exito');
        Session::flash('success', 'Tipo de documento Guardado con exito !!!' );
        return redirect(route('tipodocumentos'));
    }

    public function edit($id){
    	$pTipoDocumento = Tipo_Documento::findOrFail($id);
        return view('tipo_documentos.edit', compact('pTipoDocumento'));	
    }

    public function update(Request $request, $id){
    	$validData = $request->validate([
            'descripcion' => 'required',
            'signo'       => 'required'
        ]);

        $Tipo_Documento = Tipo_Documento::findOrFail($id);
        $Tipo_Documento->descripcion = $validData['descripcion'];
        $Tipo_Documento->signo       = $validData['signo'];

        if(isset($request->estado)){
            $Tipo_Documento->estado = 'A';
        }else{
            $Tipo_Documento->estado = 'I';
        }

        $Tipo_Documento->save();

        //return Redirect::route('tipodocumentos')->with('message','Tipo de documento grabado con exito');
        Session::flash('success', 'Tipo de documento Actualizado con exito !!!' );
        return redirect(route('tipodocumentos'));
    }
}
