<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class medicamento_dosis extends Model
{
    protected $table = 'medicamento_dosis';

    use Userstamps;

    public function medicamento(){
    	return $this->belongsTo(Medicamento::class);
    }

    public function dosis(){
    	return $this->belongsTo(dosis::class);
    }

    /*public static function dosis($id){
    	return medicamento_dosis::where('medicamento_id','=', $id)->get();
    }*/
}
