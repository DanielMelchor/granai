<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Paciente extends Model
{
    protected $table = 'pacientes';

    use Userstamps;

    public function scopeNombre($query, $cadena){
        $arreglo = explode(" ", $cadena);
     	for ($i=0; $i < count($arreglo) ; $i++) 
        { 
        	return $query->where(strtoupper('cadena'), 'LIKE', strtoupper("%$arreglo[$i]%"));
        }
    }
}
