<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'sku',
        'price',
        'category_id',
        'image',
        'description',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn($value) => strtolower($value),
        );
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            set: fn($value) => strtolower($value),
        );
    }
    protected function sku(): Attribute
    {
        return Attribute::make(
            set: fn($value) => strtolower($value),
        );
    }
    public function getImageAttribute()
    {
        return asset('storage/' . $this->attributes['image']);
    }
}
