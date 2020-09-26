<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Response;
use App\Producto;
use App\Unidadmedida;
use userStamps;

class productoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pProductos = Producto::all();
        return view('productos.index', [
            'pProductos' => $pProductos
        ]);
    }

    public function create()
    {
        $pUnidades = Unidadmedida::where('estado', 'A')->get();
        return view('productos.create', compact('pUnidades'));
    }

    public function store(Request $request){
        $validData = $request->validate([
            'descripcion' => 'required',
            'clasificacion_producto' => 'required',
            'descripcion_a_mostrar' => 'required'
        ]);

        $producto = new Producto();
        $producto->empresa_id          = Auth::user()->empresa_id;
        $producto->descripcion        = $validData['descripcion'];
        $producto->clasificacion      = $validData['clasificacion_producto'];
        $producto->descripcion_a_mostrar = $validData['descripcion_a_mostrar'];
        if ($validData['clasificacion_producto'] == 'PROC') {
            if (isset($request->siglas)) {
                $producto->siglas = $request->siglas;
            }else{
                return Redirect::back()->withErrors('Debe ingresar siglas del procedimiento');
            }
        }
        if ($validData['clasificacion_producto'] == 'PROD') {
            if (isset($request->medida_id)) {
                $producto->medida_id = $request->medida_id;
            }else{
                return Redirect::back()->withErrors('Debe ingresar unidad de medida minima para el producto');
            }
        }

        if (isset($request->estado)) {
            $producto->estado = 'A';
        }else{
            $producto->estado = 'I';
        }
        $producto->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('productos')->with('message','Producto grabado con exito');
        Session::flash('success', 'Producto Guardado con exito !!!' );
        return redirect(route('productos'));
    }

    public function edit($id)
    {
        $pProducto = Producto::findOrFail($id);
        $pUnidades = Unidadmedida::where('estado', 'A')->get();
        return view('productos.edit', compact('pProducto', 'pUnidades'));
    }

    public function update(Request $request, $id){
        $validData = $request->validate([
            'descripcion' => 'required',
            'clasificacion_producto' => 'required',
            'descripcion_a_mostrar' => 'required'
        ]);

        $producto = Producto::findOrFail($id);
        $producto->descripcion           = $validData['descripcion'];
        $producto->clasificacion         = $validData['clasificacion_producto'];
        $producto->descripcion_a_mostrar = $validData['descripcion_a_mostrar'];
        $producto->siglas = $request->siglas;
        //$medico->firma = $file_name;
        if (isset($request->estado)) {
            $producto->estado = 'A';
        }else{
            $producto->estado = 'I';
        }
        $producto->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('productos')->with('message','Producto grabado con exito');
        Session::flash('success', 'Producto Actualizado con exito !!!' );
        return redirect(route('productos'));
    }

    public function descripcion(){
        
        $producto = Producto::findOrFail($_POST['cod']);
        $descripcion = $producto->descripcion_a_mostrar;
        //echo json_encode($descripcion, JSON_UNESCAPED_UNICODE);
        return Response::json($descripcion);
        //print_r($producto);
        //exit;
    }
}
