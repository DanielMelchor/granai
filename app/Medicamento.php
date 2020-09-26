<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Medicamento extends Model
{
    protected $table = 'medicamentos';

    use Userstamps;

    public function medicamento_dosis(){
    	return $this->hasMany(Medicamento_dosis::class);
    }
}
