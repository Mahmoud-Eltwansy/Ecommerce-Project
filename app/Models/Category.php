<?php

namespace App\Models;

use App\Rules\Filter;
use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class Category extends Model
{
    use HasFactory, SoftDeletes, FilterTrait;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'parent_id'
    ];

    /**
     * Boot the model.
     *
     * Listen for the creating event to auto set slug if empty.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug))
                $category->slug = Str::slug($category->name);
        });
    }

    /**
     * Validation rules for categories
     *
     * @param int $id Optional ID to ignore in the unique name rule
     * @return array Validation rules
     */
    public static function rules($id = 0)
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                Rule::unique('categories', 'name')->ignore($id),
                'filter:php,laravel',
            ],
            'parent_id' => [
                'nullable',
                'int',
                'exists:categories,id'
            ],
            'image' => [
                File::image()
                    ->max('1mb')
                    ->dimensions(Rule::dimensions()->minHeight(100)->minWidth(100))
            ],
            'status' => [
                'required',
                'in:active,archived'
            ]
        ];
    }
}
