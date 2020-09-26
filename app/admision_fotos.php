<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class admision_fotos extends Model
{
    protected $table = 'admision_fotos';
    protected $fillable = ['nombre_imagen', 'nombre_imagen_mini', 'informe'];

    use Userstamps;

    public function getUrlPath(){
    	return \Storage::url($this->nombre_imagen_mini);
    }

}
