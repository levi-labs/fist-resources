<?php

namespace App\Services;

use App\Models\RestockInventory;
use App\Models\RestockPurchaseOrder;
use App\Models\RestockPurchaseOrderDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use  Illuminate\Support\Str;


class RestockPurchaseOrderService
{

    public function create($request_code, $supplier, $delivery_date = null)
    {
        // dd($request_code, $supplier, $notes, $delivery_date);
        try {
            $data_order = [];
            $data_order_detail = [];
            $request_inventory = RestockInventory::where('request_code', $request_code)
                ->join('products', 'products.id', '=', 'restock_inventory_requests.product_id')
                ->select(
                    'restock_inventory_requests.*',
                    'products.name as product_name',
                    'products.price as product_price',
                    DB::raw('restock_inventory_requests.quantity * products.price as total_price')
                )
                ->get();


            DB::transaction(function () use ($request_inventory, $data_order, $data_order_detail, $supplier, $delivery_date) {
                $invoice =  Str::random(5) . '/' . auth('web')->user()->id . '/'  . date('ymd');
                $data_order = [
                    'supplier_id' => $supplier,
                    'staff_id' => $request_inventory->first()->staff_id,
                    'procurement_id' => auth('web')->user()->id,
                    'request_code' => $request_inventory->first()->request_code,
                    'invoice_number' => $invoice,
                    'order_date' => date('Y-m-d'),
                    'delivery_date' => $delivery_date,
                    'status' => 'approved',
                    'total_price' => $request_inventory->sum('total_price'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                foreach ($request_inventory as $key => $value) {

                    $data_order_detail[] = [
                        'product_id' => $value->product_id,
                        'quantity' => $value->quantity,
                        'price' => $value->product_price,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }

                $purchase_order = RestockPurchaseOrder::create($data_order);
                $purchase_order_id = $purchase_order->id;
                for ($i = 0; $i < count($data_order_detail); $i++) {
                    $data_order_detail[$i]['restock_purchase_order_id'] = $purchase_order_id;
                }
                // dd($data_order_detail);

                RestockPurchaseOrderDetail::insert($data_order_detail);
            });
        } catch (\Throwable $error) {
            throw $error;
        }
    }
}
