<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Medico extends Model
{
    protected $table = 'medicos';

    use Userstamps;
}
