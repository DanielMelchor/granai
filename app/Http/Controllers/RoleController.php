<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\role_has_permission;
use DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$roles = Role::all();
    	
    	return view('roles.index', compact('roles'));
    }

    public function store(Request $request){
    	$validData = $request->validate([
	        'name' => 'required'
	    ]);

	    $role = new Role();
	    $role->name = $validData['name'];
	    $role->save();
	    return redirect(route('roles'))->with('success', 'Role grabado con exito!');
    }

    public function edit($id){
    	$role = Role::findOrFail($id);
    	$permisos = Permission::all();
    	$permisos_x_role = role_has_permission::where('role_id', $id)->select('permission_id')->get()->toArray();
        /*$array = [];
        foreach ($permisos_x_role  as $pr) {
            array_push($array, (string) $pr['permission_id']);
        }*/
    	return view('roles.edit', compact('role', 'permisos', 'permisos_x_role'));	
    }

    public function update(Request $request, $id){
    	//dd($request);
    	$validData = $request->validate([
	        'name' => 'required'
	    ]);

	    $role = Role::findOrFail($id);
	    $role->name = $validData['name'];
	    $role->save();

	    $permisos = $request->callbacks;

        DB::table('role_has_permissions')->where('role_id', $id)->delete();

	    if (isset($permisos)) {
            foreach ($permisos as $key => $permiso) {
                $role_permiso = new role_has_permission();
                $role_permiso->role_id = $role->id;
                $role_permiso->permission_id = $permiso;
                $role_permiso->save();
            }
        }
        
	    return redirect(route('roles'))->with('success', 'Role grabado con exito!');
    }

    public function show($id){

    }
}
