<?php

namespace App\Models;

use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes, FilterTrait;

    protected static function boot()
    {
        parent::boot();

        // Global Scope to show the products that belongs to the Authintacted User
        static::addGlobalScope('store', function (Builder $builder) {
            $user = Auth::user();
            // To check if the user is admin or not
            if ($user->store_id) {
                $builder->where('store_id', '=', $user->store_id);
            }
        });

        // Event that fills the slug column automatically whenever there's a new record
        static::creating(function ($product) {
            if (empty($product->slug))
                $product->slug = Str::slug($product->name);
        });
    }
}
