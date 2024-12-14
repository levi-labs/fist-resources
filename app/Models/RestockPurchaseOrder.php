<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockPurchaseOrder extends Model
{

    protected $table = 'restock_purchase_orders';
    protected $guarded = ['id'];
}
