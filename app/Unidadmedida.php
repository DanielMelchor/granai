<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Unidadmedida extends Model
{
    protected $table = 'unidad_medidas';

    use Userstamps;
}
