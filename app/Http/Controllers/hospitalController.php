<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use userStamps;
use App\Hospital;
use App\Hospital_Correlativo;

class hospitalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function hospital_Correlativos(){
        return $this->belongsTo(hospital_correlaivo::class);
    }

    public function index()
    {
        $pHospitales = \DB::table('hospitales')->OrderBy('nombre')->get();
        return view('hospitales.index', [
            'pHospitales' => $pHospitales
        ]);
    }

    public function create()
    {
        return view('hospitales.create');
    }

    public function store(Request $request)
    {
        
        $validData = $request->validate([
            'nombre' => 'required'
        ]);

        $hospital = new Hospital();
        $hospital->nombre = $validData['nombre'];
        $hospital->direccion = $request->direccion;
        $hospital->telefonos = $request->telefonos;
        $hospital->contacto = $request->contacto;
        $hospital->principal_agenda = $request->principal_agenda;
        if (isset($request->referencia)) {
            $hospital->referencia = 'S';
        }else{
            $hospital->referencia = 'N';
        }
        if (isset($request->estado)) {
            $hospital->estado = 'A';
        }else{
            $hospital->estado = 'I';
        }
        if (isset($request->principal_agenda)) {
            $hospital->principal_agenda = 'S';
        }else{
            $hospital->principal_agenda = 'N';
        }
        $hospital->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('hospitales')->with('message','Hospital grabado con exito');
        Session::flash('success', 'Hospital Guardado con exito !!!' );
        return redirect(route('hospitales'));
    }

    public function edit($id)
    {
        $pHospital = Hospital::findOrFail($id);
        return view('hospitales.edit',[
        	'pHospital' => $pHospital
        ]);
    }

    public function update(Request $request, $id)
    {
        $validData = $request->validate([
            'nombre' => 'required'
        ]);

        $hospital = Hospital::findOrFail($id);
        $hospital->nombre = $validData['nombre'];
        $hospital->direccion = $request->direccion;
        $hospital->telefonos = $request->telefonos;
        $hospital->contacto = $request->contacto;
        $hospital->principal_agenda = $request->principal_agenda;
        if (isset($request->referencia)) {
            $hospital->referencia = 'S';
        }else{
            $hospital->referencia = 'N';
        }
        if (isset($request->estado)) {
            $hospital->estado = 'A';
        }else{
            $hospital->estado = 'I';
        }
        $hospital->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('hospitales')->with('message','Hospital grabado con exito');
        Session::flash('success', 'Hospital Actualizado con exito !!!' );
        return redirect(route('hospitales'));
    }

    public function show($id){
        
        $pHospital = Hospital::findOrFail($id);
        $pHospitalCorr = \DB::table('hospital_Correlativos')->where('hospital_id', $pHospital->id)->get();
        //dd($pHospitalCorr);
        return view('hospitales.show', [
            'pHospital' => $pHospital,
            'pCorrelativos' => $pHospitalCorr
        ]);
    }
}
