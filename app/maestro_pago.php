<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class maestro_pago extends Model
{
    protected $table = 'maestro_pagos';

    use Userstamps;
}