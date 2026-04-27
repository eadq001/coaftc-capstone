<?php

namespace App\Models;

use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Model;

class UnverifiedUser extends Model
{
    protected $casts = [
        'user_role' => UserRoles::class
    ];

    protected $guarded = [];
//    protected $fillable = ['username', 'email', 'user_role', 'password', 'verification_token'];
}
