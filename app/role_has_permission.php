<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class role_has_permission extends Model
{
    use Notifiable;

    protected $table = 'role_has_permissions';
}
