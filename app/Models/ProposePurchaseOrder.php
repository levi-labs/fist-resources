<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposePurchaseOrder extends Model
{
    protected $table = 'proposed_product_purchase_orders';

    protected $guarded = ['id'];
}
