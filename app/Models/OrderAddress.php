<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['order_id', 'first_name', 'last_name', 'type', 'email', 'phone_number', 'street_address', 'city', 'postal_code', 'state', 'country'];
}
