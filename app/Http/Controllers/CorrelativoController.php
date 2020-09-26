<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Correlativo;

class CorrelativoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$pCorrelativos = Correlativo::where('empresa_id', Auth::user()->empresa_id)->get();
    	return view('correlativos.index', compact('pCorrelativos'));
    }

    public function create(){
    	return view('correlativos.create');
    }

    public function store(Request $request){
    	$validData = $request->validate([
            'tipo_id' => 'required',
            'correlativo' => 'required'
        ]);

        $existe = Correlativo::where('empresa_id', Auth::user()->empresa_id)->where('tipo', $validData['tipo_id'])->count();

        if ($existe >= 1) {
        	return Redirect::back()->withErrors('Correlativo ya existe');
        }else{
        	$corr = new Correlativo;
	        $corr->empresa_id = Auth::user()->empresa_id;
	        $corr->tipo = $validData['tipo_id'];
	        $corr->correlativo = $validData['correlativo'];
	        $corr->save();

            Session::flash('success', 'Correlativo Guardado con exito !!!' );
            return redirect(route('correlativos'));

	        //return Redirect::route('correlativos')->with('message','Correlativo grabado con exito');    
        }
    }

    public function edit($id){
    	$correlativo = Correlativo::findOrFail($id);
    	return view('correlativos.edit', compact('correlativo'));
    }

    public function update(Request $request, $id){
        $validData = $request->validate([
            'tipo_id' => 'required',
            'correlativo' => 'required'
        ]);

    	$corr = Correlativo::findOrFail($id);
        $corr->empresa_id = Auth::user()->empresa_id;
        $corr->tipo = $validData['tipo_id'];
        $corr->correlativo = $validData['correlativo'];
        $corr->save();

        //return Redirect::route('correlativos')->with('message','Correlativo grabado con exito');
        Session::flash('success', 'Correlativo Actualizado con exito !!!' );
        return redirect(route('correlativos'));
    }
}
