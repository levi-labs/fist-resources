<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposedProduct extends Model
{
    protected $table = 'proposed_products';

    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function getImageAttribute()
    {
        return asset('storage/' . $this->attributes['image']);
    }
}
