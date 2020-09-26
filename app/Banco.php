<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Banco extends Model
{
    protected $table = 'bancos';

    use Userstamps;

}
