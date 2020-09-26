<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class MotivoAnulacion extends Model
{
    protected $table = 'motivo_anulaciones';

    use Userstamps;
}
