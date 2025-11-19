<?php

namespace App\Models;

use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class Product extends Model
{
    use HasFactory, SoftDeletes, FilterTrait;

    protected $fillable = ['name', 'slug', 'description', 'image', 'price', 'compare_price', 'options', 'rating', 'featured', 'status', 'category_id', 'store_id'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'image'];

    protected $appends = ['image_url'];

    protected static function boot()
    {
        parent::boot();

        // Global Scope to show the products that belongs to the Authintacted User
        static::addGlobalScope('store', function (Builder $builder) {
            $user = Auth::user();
            // To check if the user is admin or not
            if ($user?->store_id) {
                // $builder->where('store_id', '=', $user->store_id);
            }
        });

        // Event that fills the slug column automatically whenever there's a new record
        static::creating(function ($product) {
            if (empty($product->slug))
                $product->slug = Str::slug($product->name);
        });
    }

    public static function rules($id = 0)
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                Rule::unique('products', 'name')->ignore($id),
                'filter:php,laravel',
            ],
            'image' => [
                File::image()
                    ->max('1mb')
                    ->dimensions(Rule::dimensions()->minHeight(100)->minWidth(100))
            ],
            'status' => [
                'required',
                'in:active,archived,draft'
            ]
        ];
    }
    /**
     * Get the category that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the tags that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        $query->where('status', '=', 'active');
    }

    // Accessor
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('storage\default-product-image.png');
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        return asset('storage' . $this->image);
    }

    public function getDiscountAttribute()
    {
        if (!$this->compare_price)
            return 0;
        return floor(($this->compare_price - $this->price) / $this->compare_price * 100);
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $options = array_merge([
            'category_id' => null,
            'store_id' => null,
            'tag_id' => null,
            'status' => 'active'
        ], $filters);

        $builder->when($options['status'], function ($builder, $value) {
            $builder->where('status', $value);
        });
        $builder->when($options['store_id'], function ($builder, $value) {
            $builder->where('store_id', $value);
        });

        $builder->when($options['category_id'], function ($builder, $value) {
            $builder->where('category_id', $value);
        });

        $builder->when($options['tag_id'], function ($builder, $value) {
            $builder->whereExists(function ($builder) use ($value) {
                $builder->select(1)
                    ->from('product_tag')
                    ->whereRaw('product_id = products.id')
                    ->where('tag_id', $value);
            });
        });
    }
}
