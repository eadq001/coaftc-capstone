<?php

namespace App\Models;

use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Model;

class UnverifiedUser extends Model
{
    protected $casts = [
        'user_role' => UserRoles::class
    ];
}
