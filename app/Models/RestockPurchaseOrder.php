<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RestockPurchaseOrder extends Model
{

    protected $table = 'restock_purchase_orders';
    protected $guarded = ['id'];


    public function getReport($from, $to)
    {
        try {
            if ($from !== null && $to !== null) {
                $restock_purchase = DB::table('restock_purchase_orders')
                    ->select(
                        'restock_purchase_orders.id',
                        'restock_purchase_orders.order_date',
                        'suppliers.name as supplier_name',
                        'restock_purchase_orders.invoice_number',
                        'restock_purchase_orders.delivery_date',
                        'restock_purchase_orders.total_price',
                    )
                    ->whereBetween('restock_purchase_orders.order_date', [$from, $to])
                    ->join('suppliers', 'restock_purchase_orders.supplier_id', 'suppliers.id')
                    ->get();
                return $restock_purchase;
            } elseif ($from !== null && $to === null) {
                $restock_purchase = DB::table('restock_purchase_orders')
                    ->select(
                        'restock_purchase_orders.id',
                        'restock_purchase_orders.order_date',
                        'suppliers.name as supplier_name',
                        'restock_purchase_orders.invoice_number',
                        'restock_purchase_orders.delivery_date',
                        'restock_purchase_orders.total_price',
                    )
                    ->where('restock_purchase_orders.order_date', '>=', $from)
                    ->join('suppliers', 'restock_purchase_orders.supplier_id', 'suppliers.id')
                    ->get();
                return $restock_purchase;
            } elseif ($from === null && $to !== null) {
                $restock_purchase = DB::table('restock_purchase_orders')
                    ->select(
                        'restock_purchase_orders.id',
                        'restock_purchase_orders.order_date',
                        'suppliers.name as supplier_name',
                        'restock_purchase_orders.invoice_number',
                        'restock_purchase_orders.delivery_date',
                        'restock_purchase_orders.total_price',
                    )
                    ->where('restock_purchase_orders.order_date', '<=', $to)
                    ->join('suppliers', 'restock_purchase_orders.supplier_id', 'suppliers.id')
                    ->get();
                return $restock_purchase;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
