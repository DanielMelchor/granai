<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Producto extends Model
{
    protected $table = 'productos';

    use Userstamps;
}
