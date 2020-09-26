<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Especialidad extends Model
{
    protected $table = 'especialidades';

    use userStamps;
}
