<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockInventory extends Model
{
    protected $table = 'restock_inventory_requests';

    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
