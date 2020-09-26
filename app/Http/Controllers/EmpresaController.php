<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\user;
use App\Empresa;
use App\userStamps;

class EmpresaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pEmpresas = Empresa::all();
        return view('empresas.index', [
            'pEmpresas' => $pEmpresas
        ]);
    }

    public function create()
    {
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        //dd($request);
        $validData = $request->validate([
            'razon_social' => 'required',
            'nombre_comercial' => 'required',
            'direccion' => 'required',
            'telefonos' => 'required',
            'porcentaje_impuesto' => 'required'
        ]);

        $empresa = new Empresa();
        $empresa->razon_social = $validData['razon_social'];
        $empresa->nombre_comercial = $validData['nombre_comercial'];
        $empresa->direccion = $validData['direccion'];
        $empresa->telefonos = $validData['telefonos'];
        $empresa->nit_empresa = $request->nit_empresa;
        $empresa->igss_empresa = $request->igss_empresa;
        $empresa->fecha_constitucion = $request->fecha_constitucion;
        $empresa->porcentaje_impuesto = $request->porcentaje_impuesto;

        if (!empty($request->logo_empresa))
        //if ($request->hasFile('logo_empresa')) 
        {
        	$logo = $request->file('logo_empresa')->getClientOriginalName();
        	$request->file('logo_empresa')->move('logos', $logo);
        	$empresa->ruta_logo = 'logos/' . $logo;
        }
        
        if (isset($request->estado)) {
            $empresa->estado = 'A';
        }else{
            $empresa->estado = 'I';
        }
        $empresa->save();

        return Redirect::route('empresas')->with('message','Empresa grabada con exito');
    }

    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('empresas.edit',[
            'pEmpresa' => $empresa
            ]);
    }

    public function update(REQUEST $request, $id)
    {
    	$validData = $request->validate([
            'razon_social' => 'required',
            'nombre_comercial' => 'required',
            'direccion' => 'required',
            'telefonos' => 'required',
            'porcentaje_impuesto' => 'required'
        ]);

        $empresa = Empresa::findOrFail($id);
        $empresa->razon_social = $validData['razon_social'];
        $empresa->nombre_comercial = $validData['nombre_comercial'];
        $empresa->direccion = $validData['direccion'];
        $empresa->telefonos = $validData['telefonos'];
        $empresa->nit_empresa = $request->nit_empresa;
        $empresa->igss_empresa = $request->igss_empresa;
        $empresa->fecha_constitucion = $request->fecha_constitucion;
        $empresa->porcentaje_impuesto = $request->porcentaje_impuesto;

        if (!empty($request->logo_empresa))
        //if ($request->hasFile('ruta_logo')) 
        {
        	$logo = $request->file('logo_empresa')->getClientOriginalName();
        	$request->file('logo_empresa')->move('logos', $logo);
        	$empresa->ruta_logo = 'logos/' . $logo;
        }
        
        if (isset($request->estado)) {
            $empresa->estado = 'A';
        }else{
            $empresa->estado = 'I';
        }
        $empresa->save();


        return Redirect::route('empresas')->with('message','Empresa grabada con exito');
    }

    public function borrar_logo($id)
    {
        $empresa = Empresa::findOrFail($id);
        if (!empty($empresa->ruta_logo))
        {
            unlink($empresa->ruta_logo);
        }
        
        $empresa->ruta_logo = '';
        $empresa->save();
        return view('empresas.edit',[
            'pEmpresa' => $empresa
            ]);
    }
}
