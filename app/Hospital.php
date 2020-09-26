<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Hospital extends Model
{
    protected $table = 'hospitales';

    use Userstamps;
}
