<?php

namespace App\Actions\Fortify;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticateUser
{

    public function authenticate($request)
    {
        $username = $request->post(config('fortify.username'));
        $password = $request->post('password');

        $admin = Admin::where('username', $username)
            ->orWhere('email', $username)
            ->orWhere('phone_number', $username)
            ->first();

        if ($admin && Hash::check($password, $admin->password)) {
            return $admin;
        }
        return false;
    }
}
