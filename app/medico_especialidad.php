<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class medico_especialidad extends Model
{
    protected $table = 'medico_especialidades';

    use Userstamps;
}
