<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use App\Resolucion;

class caja extends Model
{
    protected $table = 'cajas';

    use Userstamps;

    public function caja_resoluciones(){
    	return $this->hasMany(Resolucion::class);
    }
}
