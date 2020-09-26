<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use user;
use App\dosis;
use userStamps;


class dosisController extends Controller
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
        $pDosis = dosis::all();
        return view('dosis.index', [
            'pDosis' => $pDosis
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dosis.create');
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
            'descripcion' => 'required'
        ]);

        $dosis = new dosis();
        $dosis->descripcion = $validData['descripcion'];
        //$medico->firma = $file_name;
        if (isset($request->estado)) {
            $dosis->estado = 'A';
        }else{
            $dosis->estado = 'I';
        }
        $dosis->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('dosis')->with('message','Especialidad grabado con exito');
        Session::flash('success', 'Dosis Guardada con exito !!!' );
        return redirect(route('dosis'));
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
        $dosis = dosis::findOrFail($id);
        return view('dosis.edit',[
            'pDosis' => $dosis]);
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
            'descripcion' => 'required'
        ]);


        $dosis = dosis::findOrFail($id);
        $dosis->descripcion = $validData['descripcion'];
        //$medico->firma = $file_name;
        if (isset($request->estado)) {
            $dosis->estado = 'A';
        }else{
            $dosis->estado = 'I';
        }
        $dosis->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('dosis')->with('message','Especialidad grabado con exito');
        Session::flash('success', 'Dosis Actualizada con exito !!!' );
        return redirect(route('dosis'));
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
