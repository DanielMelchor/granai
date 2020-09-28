<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use DB;
use Session;
use Response;
use App\banco;

class BancoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pBancos = banco::paginate(15);
        return view('bancos.index', compact('pBancos'));
    }

    public function create(){
    	return view('bancos.create');
    }

    public function store(Request $request){
        $validData = $request->validate([
            'nombre' => 'required',
            'tipo_referencia' => 'required'
        ]);
    	$banco = new banco();
    	$banco->nombre = $validData['nombre'];
        $banco->tipo_referencia = $validData['tipo_referencia'];

        if(isset($request->estado)){
    		$banco->estado = 'A';
    	}else{
    		$banco->estado = 'I';
    	}

    	$banco->save();

    	//return Redirect::route('bancos')->with('message','Banco grabado con exito');
        if ($banco->tipo_referencia == 'B') {
            Session::flash('success', 'Banco Guardado con exito !!!' );
        }else{
            Session::flash('success', 'Casa Emisora Guardada con exito !!!' );
        }

        return redirect(route('bancos'));
    }

    public function edit($id){    	
    	$pBanco = banco::findOrFail($id);
    	return view('bancos.edit',[
    		'pBanco' => $pBanco
    	]);
    }

    public function update(Request $request, $id){
        $validData = $request->validate([
            'nombre' => 'required',
            'tipo_referencia' => 'required'
        ]);

    	$banco = banco::findOrFail($id);
    	
    	$banco->nombre = $validData['nombre'];
        $banco->tipo_referencia = $validData['tipo_referencia'];
    	if(isset($request->estado)){
    		$banco->estado = 'A';
    	}else{
    		$banco->estado = 'I';
    	}

    	$banco->save();

    	//return Redirect::route('bancos')->with('message','Banco grabado con exito');
        if ($banco->tipo_referencia == 'B') {
            Session::flash('success', 'Banco Actualizado con exito !!!' );
        }else{
            Session::flash('success', 'Casa Emisora Actualizada con exito !!!' );
        }
        
        return redirect(route('bancos'));
    }

    public function trae_formas_pago(){
        //print_r($_POST['parametro']);

        $resultado = DB::table('bancos')
                     ->where('estado', 'A')
                     ->where('tipo_referencia', $_POST['parametro'])
                     ->select('id', 'nombre')
                     ->get();
        return Response::json($resultado);
    }
}
