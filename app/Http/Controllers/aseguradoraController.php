<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use user;
use App\Aseguradora;
use userStamps;

class aseguradoraController extends Controller
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
        $pAseguradoras = Aseguradora::all();
        return view('aseguradoras.index', [
            'pAseguradoras' => $pAseguradoras
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('aseguradoras.create');
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
            'nombre' => 'required'
        ]);

        $aseguradora = new Aseguradora();
        $aseguradora->nombre = $validData['nombre'];
        $aseguradora->direccion = $request->direccion;
        $aseguradora->telefonos = $request->telefonos;
        $aseguradora->contacto = $request->contacto;
        $aseguradora->facturacion_nit = $request->facturacion_nit;
        $aseguradora->facturacion_nombre = $request->facturacion_nombre;
        $aseguradora->facturacion_direccion = $request->facturacion_direccion;
        $aseguradora->copago = $request->copago;
        $aseguradora->coaseguro = $request->coaseguro;
        if (isset($request->estado)) {
            $aseguradora->estado = 'A';
        }else{
            $aseguradora->estado = 'I';
        }
        $aseguradora->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        Session::flash('success', 'Aseguradora grabada con exito !!!' );
        return redirect(route('aseguradoras'));

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
        $pAseguradora = Aseguradora::findorfail($id);
        return view('aseguradoras.edit',[
            'pAseguradora' => $pAseguradora
            ]);
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
            'nombre' => 'required'
        ]);

        $aseguradora = Aseguradora::findorfail($id);
        $aseguradora->nombre = $validData['nombre'];
        $aseguradora->direccion = $request->direccion;
        $aseguradora->telefonos = $request->telefonos;
        $aseguradora->contacto = $request->contacto;
        $aseguradora->facturacion_nit = $request->facturacion_nit;
        $aseguradora->facturacion_nombre = $request->facturacion_nombre;
        $aseguradora->facturacion_direccion = $request->facturacion_direccion;
        $aseguradora->copago = $request->copago;
        $aseguradora->coaseguro = $request->coaseguro;
        if (isset($request->estado)) {
            $aseguradora->estado = 'A';
        }else{
            $aseguradora->estado = 'I';
        }
        $aseguradora->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('aseguradoras')->with('message','Medico grabado con exito');
        Session::flash('success', 'Aseguradora Actualizada con exito !!!' );
        return redirect(route('aseguradoras'));

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