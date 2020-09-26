<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Unidadmedida;
use userStamps;

class unidadmedidaController extends Controller
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
        $pUnidadmedidas = Unidadmedida::all();
        return view('unidadmedidas.index', [
            'pUnidadmedidas' => $pUnidadmedidas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('unidadmedidas.create');
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
            'descripcion' => 'required',
            'siglas' => 'required'
        ]);

        $unidadmedida = new Unidadmedida();
        $unidadmedida->descripcion = $validData['descripcion'];
        $unidadmedida->siglas = $validData['siglas'];
        if (isset($request->estado)) {
            $unidadmedida->estado = 'A';
        }else{
            $unidadmedida->estado = 'I';
        }
        $unidadmedida->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('unidadmedidas')->with('message','Unidad de medida grabada con exito');
        Session::flash('success', 'Unidad de Médida Guardada con exito !!!' );
        return redirect(route('unidadmedidas'));
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
        $unidadmedida = Unidadmedida::findOrFail($id);
        return view('unidadmedidas.edit',[
            'pUnidadmedida' => $unidadmedida]);
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
            'descripcion' => 'required',
            'siglas' => 'required'
        ]);

        $unidadmedida = Unidadmedida::findOrFail($id);
        $unidadmedida->descripcion = $validData['descripcion'];
        $unidadmedida->siglas = $validData['siglas'];
        if (isset($request->estado)) {
            $unidadmedida->estado = 'A';
        }else{
            $unidadmedida->estado = 'I';
        }
        $unidadmedida->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('unidadmedidas')->with('message','Unidad de medida grabada con exito');
        Session::flash('success', 'Unidad de Médida Actualizada con exito !!!' );
        return redirect(route('unidadmedidas'));
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
