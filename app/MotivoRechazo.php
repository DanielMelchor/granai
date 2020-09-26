<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class MotivoRechazo extends Model
{
    protected $table = 'motivo_rechazos';

    use Userstamps;
}
