<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Corte extends Model
{
    protected $table = 'cortes';

    use Userstamps;
}
