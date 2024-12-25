<?php

namespace App\Services;

use App\Models\ProposedRequest;
use App\Models\ProposePurchaseOrder;
use App\Models\ProposePurchaseOrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProposePurchaseOrderService
{

    public function create($request_Code, $supplier, $delivery_date = null)
    {
        // dd($request_Code, $supplier, $delivery_date);

        // dd($propose_inventory);
        try {
            $data_order = [];
            $data_order_detail = [];
            $propose_inventory = ProposedRequest::where('request_code', $request_Code)
                ->join(
                    'proposed_products as pp',
                    'pp.id',
                    '=',
                    'proposed_inventory_requests.proposed_product_id',
                )
                ->select(
                    'proposed_inventory_requests.*',
                    'pp.name as product_name',
                    'pp.price as product_price',
                    DB::raw('proposed_inventory_requests.quantity * pp.price as total_price')

                )
                ->get();
            DB::transaction(function () use ($propose_inventory, $data_order, $data_order_detail, $supplier, $delivery_date) {
                $invoice =  Str::random(5) . '/' . auth('web')->user()->id . '/'  . date('ymd') . 'PR';
                $data_order = [
                    'supplier_id' => $supplier,
                    'staff_id' => $propose_inventory->first()->staff_id,
                    'procurement_id' => auth('web')->user()->id,
                    'request_code' => $propose_inventory->first()->request_code,
                    'invoice_number' => $invoice,
                    'order_date' => date('Y-m-d'),
                    'delivery_date' => $delivery_date,
                    'status' => 'awaiting shipment',
                    'total_price' => $propose_inventory->sum('total_price'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                // dd($propose_inventory);
                foreach ($propose_inventory as $key => $value) {
                    $data_order_detail[] = [
                        'proposed_product_id' => $value->proposed_product_id,
                        'quantity' => $value->quantity,
                        'price' => $value->product_price,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
                // dd($data_order, $data_order_detail);

                $propose_purchase = ProposePurchaseOrder::create($data_order);
                $propose_purchase_id = $propose_purchase->id;

                for ($i = 0; $i < count($data_order_detail); $i++) {
                    $data_order_detail[$i]['proposed_order_id'] = $propose_purchase_id;
                }
                // dd($data_order, $data_order_detail);
                ProposePurchaseOrderDetail::insert($data_order_detail);
            });
        } catch (\Throwable $error) {
            throw $error;
        }
    }
}
