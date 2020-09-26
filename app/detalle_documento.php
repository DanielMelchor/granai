<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class detalle_documento extends Model
{
    protected $table = 'detalle_documentos';
    use Userstamps;
}
