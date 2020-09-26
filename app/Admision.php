<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Admision extends Model
{
    use Userstamps;
    
    protected $table = 'admisiones';    

    public function scopeNombre($query, $cadena){
        $arreglo = explode(" ", $cadena);
     	for ($i=0; $i < count($arreglo) ; $i++) 
        { 
        	return $query->where(strtoupper('cadena'), 'LIKE', strtoupper("%$arreglo[$i]%"));
        }
    }
}
