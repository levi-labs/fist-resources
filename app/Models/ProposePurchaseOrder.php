<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProposePurchaseOrder extends Model
{
    protected $table = 'proposed_product_purchase_orders';

    protected $guarded = ['id'];

    public function getReport($from, $to)
    {
        try {
            if ($from !== null && $to !== null) {
                $propose_purchase = DB::table('proposed_product_purchase_orders')
                    ->select(
                        'proposed_product_purchase_orders.*',
                        'suppliers.name as supplier_name',
                        'suppliers.address as supplier_address',
                    )
                    ->whereBetween('proposed_product_purchase_orders.order_date', [$from, $to])
                    ->join('suppliers', 'proposed_product_purchase_orders.supplier_id', 'suppliers.id')
                    ->get();
            } elseif ($from !== null && $to === null) {
                $propose_purchase = DB::table('proposed_product_purchase_orders')
                    ->select(
                        'proposed_product_purchase_orders.*',
                        'suppliers.name as supplier_name',
                        'suppliers.address as supplier_address',
                    )
                    ->where('proposed_product_purchase_orders.order_date', '>=', $from)
                    ->join('suppliers', 'proposed_product_purchase_orders.supplier_id', 'suppliers.id')
                    ->get();
            } elseif ($from === null && $to !== null) {
                $propose_purchase = DB::table('proposed_product_purchase_orders')
                    ->select(
                        'proposed_product_purchase_orders.*',
                        'suppliers.name as supplier_name',
                        'suppliers.address as supplier_address',
                    )
                    ->where('proposed_product_purchase_orders.order_date', '<=', $to)
                    ->join('suppliers', 'proposed_product_purchase_orders.supplier_id', 'suppliers.id')
                    ->get();
            }
            return $propose_purchase;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
