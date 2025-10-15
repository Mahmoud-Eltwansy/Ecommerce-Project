<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Store extends Model
{
    use HasFactory;
    protected $table = 'stores';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($store) {
            if (empty($store->slug))
                $store->slug = Str::slug($store->name);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
