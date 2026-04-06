<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterUsersController extends Controller
{
    public function show() {
        return view('auth.login');
    }
}
