<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodReceived extends Model
{
    protected $table = 'goods_received';

    protected $guarded = ['id'];

    public function getImageAttribute()
    {
        return asset('storage/' . $this->attributes['image']);
    }
}
