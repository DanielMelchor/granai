<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\forma_pago;

class FormaPagoController extends Controller
{
    public function campos_requeridos(){
    	$id = $_POST['fpago_id'];

    	if (isset($id)) {
    		$forma_pago = forma_pago::findOrFail($id);
    		return Response::json($forma_pago);
    	}
    }
}
