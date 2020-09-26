<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class detalle_pago extends Model
{
    protected $table = 'detalle_pagos';
    use Userstamps;
}
