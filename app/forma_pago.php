<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class forma_pago extends Model
{
    protected $table = 'formas_pago';

    use userStamps;
}
