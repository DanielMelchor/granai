<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class receta_medico extends Model
{
    protected $table = 'receta_medicos';
    use Userstamps;
}
