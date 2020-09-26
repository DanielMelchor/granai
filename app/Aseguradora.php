<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Aseguradora extends Model
{
    protected $table = 'aseguradoras';

    use Userstamps;
}
