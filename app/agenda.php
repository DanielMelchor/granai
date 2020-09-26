<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class agenda extends Model
{
    protected $table = 'agendas';
    use Userstamps;
}
