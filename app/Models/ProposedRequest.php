<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposedRequest extends Model
{
    protected $table = 'proposed_inventory_requests';

    protected $guarded = ['id'];

    public function proposedProduct()
    {
        return $this->belongsTo(ProposedProduct::class, 'proposed_product_id', 'id');
    }
}
