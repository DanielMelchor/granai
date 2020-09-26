<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class pago_documento extends Model
{
    protected $table = 'pago_documentos';
    use Userstamps;
}
