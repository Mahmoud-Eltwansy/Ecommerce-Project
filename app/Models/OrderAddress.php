<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Intl\Countries;

class OrderAddress extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['order_id', 'first_name', 'last_name', 'type', 'email', 'phone_number', 'street_address', 'city', 'postal_code', 'state', 'country'];

    /**
     * Accessor that returns the full name of the user who addressed the order.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    /**
     * Accessor that returns the full name of the country corresponding to the country code stored in the
     * `country` attribute of the model.
     *
     * @return string
     */
    public function getCountryNameAttribute()
    {
        return Countries::getName($this->country);
    }
}
