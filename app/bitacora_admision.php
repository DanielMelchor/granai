<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class bitacora_admision extends Model
{
    protected $table = 'bitacora_admisiones';

    use Userstamps;
}
