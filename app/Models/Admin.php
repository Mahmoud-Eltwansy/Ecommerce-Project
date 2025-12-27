<?php

namespace App\Models;

use App\Traits\HasRolesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends User
{
    use HasFactory, Notifiable, HasApiTokens, HasRolesTrait;
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'status',
        'phone_number',
        'super_admin'
    ];
}
