<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Correlativo extends Model
{
    protected $table = 'correlativos';

    use Userstamps;
}
