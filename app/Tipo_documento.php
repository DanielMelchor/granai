<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Tipo_documento extends Model
{
    protected $table = 'Tipo_documentos';

    use Userstamps;
}
