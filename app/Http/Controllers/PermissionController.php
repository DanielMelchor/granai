<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$permisos = Permission::all();
    	
    	return view('permissions.index', compact('permisos'));
    }

    public function store(Request $request){
    	$validData = $request->validate([
	        'name' => 'required'
	    ]);

	    $permiso = new Permission();
	    $permiso->name = $validData['name'];
	    $permiso->save();
	    return redirect(route('permisos'))->with('success', 'Permiso grabado con exito!');
    }

    public function edit($id){
    	$permiso = Permission::findOrFail($id);
    	return view('permissions.edit', compact('permiso'));	
    }

    public function update(Request $request, $id){
    	$validData = $request->validate([
	        'name' => 'required'
	    ]);

	    $permiso = Permission::findOrFail($id);
	    $permiso->name = $validData['name'];
	    $permiso->save();
	    return redirect(route('permisos'))->with('success', 'Permiso grabado con exito!');
    }
}
