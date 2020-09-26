<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class admision_cargo extends Model
{
    protected $table = 'admision_cargos';

    use Userstamps;
}
