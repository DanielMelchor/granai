<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class dosis extends Model
{
    protected $table = 'Dosis';

    use Userstamps;
}
