<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use App\Caja;

class Resolucion extends Model
{
    protected $table = 'resoluciones';

    use Userstamps;

    public function caja(){
    	return $this->belongsTo(Caja::class);
    }
}
