<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
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

    public function restockInventory()
    {
        return $this->hasMany(RestockInventory::class, 'product_id', 'id');
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
    // public function price(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn($value) => number_format(floor($value), 0, ',', '.'),
    //     );
    // }
}
