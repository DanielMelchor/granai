<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class maestro_documento extends Model
{
    protected $table = 'maestro_documentos';

    use Userstamps;
}