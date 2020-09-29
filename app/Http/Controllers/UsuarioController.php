<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Session;
use Redirect;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\user;
use App\Empresa;
use App\Caja;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $usuarios = DB::table('users as u')
                    ->leftjoin('empresas as e', 'u.empresa_id', 'e.id')
                    ->leftjoin('cajas as c', 'u.caja_id', 'c.id')
                    ->select('u.id', 'u.username', 'u.name', 'e.nombre_comercial', 'c.nombre_maquina', 'u.estado')
                    ->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create(){
        $empresas = Empresa::all();
        return view('usuarios.create', compact('empresas'));
    }

    public function store(Request $request){
        $validData = $request->validate([
            'username' => 'required',
            'name'     => 'required'
        ]);

        $usuario = new User();
        $usuario->username   = $validData['username'];
        $usuario->name       = $validData['name'];
        $usuario->empresa_id = $request->empresa_id;
        $usuario->caja_id    = $request->caja_id;
        $usuario->password   = Hash::make('amad');
        if (isset($request->estado)) {
            $usuario->estado = 'A';
        }else{
            $usuario->estado = 'i';
        }
        $usuario->save();

        Session::flash('success', 'Usuario Guardado con exito !!!' );
        return redirect(route('usuarios'));
    }

    public function edit($id){
        $empresas = Empresa::all();
        $usuario  = User::findOrFail($id);
        $cajas    = Caja::where('empresa_id', $usuario->empresa_id)->where('estado', 'A')->get();

        return view('usuarios.edit', compact('empresas', 'usuario', 'cajas'));
    }

    public function update($id, Request $request){
        $validData = $request->validate([
            'username' => 'required',
            'name'     => 'required'
        ]);

        $usuario = User::findOrFail($id);
        $usuario->username   = $validData['username'];
        $usuario->name       = $validData['name'];
        $usuario->empresa_id = $request->empresa_id;
        $usuario->caja_id    = $request->caja_id;
        //$usuario->password   = Hash::make('amad');
        if (isset($request->estado)) {
            $usuario->estado = 'A';
        }else{
            $usuario->estado = 'i';
        }
        $usuario->save();

        Session::flash('success', 'Usuario Guardado con exito !!!' );
        return redirect(route('usuarios'));
    }

    public function edit_clave(){
        return view('usuarios.cambiar_contrasena');
    }

    public function update_contrasena(Request $request){
        $validData = $request->validate([
            'oldpass'       => 'required',
            'nueva_clave'   => 'required|min:8',
            'confirmacion'  => 'required|min:8'
        ]);

        $clave = $validData['nueva_clave'];
        $confirma = $validData['confirmacion'];
        //dd($clave.' '.$confirma);

    	if (Hash::check($request->oldpass, Auth::user()->password)){
    		if ($clave === $confirma) {
    			if (strlen($clave) < 8) {
    				//$respuesta = array('parametro' => 1,'respuesta' => 'El minimo de caracteres para la contraseña es de Ocho caracteres');
                    return Redirect::back()->withErrors('nueva clave debe tener un minimo de 8 caracteres');
    			}else{
    				//$respuesta = array('parametro' => 1,'respuesta' => Auth::user()->id);
    				$usuario = user::findOrFail(Auth::user()->id);
    				$usuario->password = Hash::make($clave);
    				$usuario->save();
    			
    				//$respuesta = array('parametro' => 0,'respuesta' => 'Clave actualizada con exito !!!');
                    return redirect::back()->with('success', 'clave Actualizada con exito!!!');
                    //return Redirect::back()->withErrors('medico no cuenta con la configuracion necesaria para imprimir la receta');
    			}
    		}else{
    			//$respuesta = array('parametro' => 1,'respuesta' => 'Nueva contraseña no concuerda con la confirmación, Favor verifique');	
                return Redirect::back()->withErrors('Nueva contraseña no concuerda con la confirmación, Favor verifique');
    		}
		}else{
			//$respuesta = array('parametro' => 1,'respuesta' => 'Contraseña Actual no concuerda con nuestro registro, Favor verifique');
            return Redirect::back()->withErrors('Contraseña Actual no concuerda con nuestro registro, Favor verifique');
		}

		//return Response::json($respuesta);
    }

    public function reset($id){
        $usuario = User::findOrFail($id);
        $usuario->password = Hash::make('amad');
        $usuario->save();
        Session::flash('success', 'Contraseña Inicializada con exito !!!' );
        return redirect(route('usuarios'));
    }
}
