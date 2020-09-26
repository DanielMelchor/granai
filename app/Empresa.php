<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Empresa extends Model
{
    protected $table = 'Empresas';

    use Userstamps;
}
