<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class observacion_admision extends Model
{
    protected $table = 'observacion_admisiones';

    use Userstamps;
}
