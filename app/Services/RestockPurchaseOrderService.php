<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\RestockInventory;
use App\Models\RestockPurchaseOrder;
use App\Models\RestockPurchaseOrderDetail;
use App\Models\User;
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
                    'status' => 'awaiting shipment',
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
                $getUser = User::where('supplier_id', $supplier)->first();
                Notification::create([
                    'user_id' => $getUser->id,
                    'user_role' => 'supplier',
                    'notification_type' => 'purchase',
                    'message' => 'New Purchase Order',
                    'order_type' => 'purchase restock',
                    'related_order_id' => $purchase_order_id,
                ]);
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

    public function search($search, $status)
    {
        try {
            $auth = auth('web')->user();
            if ($auth->role === 'supplier') {
                $purchase = RestockPurchaseOrder::where('invoice_number', 'like', '%' . $search . '%')
                    ->where('status', $status)
                    ->where('supplier_id', auth('web')->user()->supplier_id)
                    ->paginate(10);
                return $purchase;
            }
            $purchase = RestockPurchaseOrder::where('invoice_number', 'like', '%' . $search . '%')
                ->where('status', $status)
                ->paginate(10);
            return $purchase;
        } catch (\Throwable $error) {
            throw $error;
        }
    }
    public function getAllRestockPurchaseOrder()
    {
        $auth = auth('web')->user();
        if ($auth->role === 'supplier') {
            return RestockPurchaseOrder::where('status', 'awaiting shipment')
                ->where('supplier_id', auth('web')->user()->supplier_id)
                ->paginate(10);
        }
        return RestockPurchaseOrder::where('status', 'awaiting shipment')
            ->paginate(10);
    }
    public function getAllRestockPurchaseOrderShipped()
    {
        $auth = auth('web')->user();
        if ($auth->role === 'supplier') {
            return RestockPurchaseOrder::where('status', 'shipped')
                ->where('supplier_id', auth('web')->user()->supplier_id)
                ->paginate(10);
        }
        return RestockPurchaseOrder::where('status', 'shipped')
            ->paginate(10);
    }
    public function getAllRestockPurchaseOrderDelivered()
    {
        $auth = auth('web')->user();
        if ($auth->role === 'supplier') {
            return RestockPurchaseOrder::where('status', 'delivered')
                ->where('supplier_id', auth('web')->user()->supplier_id)
                ->paginate(10);
        }
        return RestockPurchaseOrder::where('status', 'delivered')
            ->paginate(10);
    }
    public function getRestockPurchaseOrderById($id)
    {
        return RestockPurchaseOrder::find($id);
    }
    public function getRestockPurchaseOrderDetailById($id)
    {
        return RestockPurchaseOrderDetail::where('restock_purchase_order_id', $id)
            ->join('restock_purchase_orders', 'restock_purchase_orders.id', '=', 'restock_purchase_order_details.restock_purchase_order_id')
            ->join('users as staff', 'staff.id', '=', 'restock_purchase_orders.staff_id')
            ->join('users as procument', 'procument.id', '=', 'restock_purchase_orders.procurement_id')
            ->join('suppliers', 'suppliers.id', '=', 'restock_purchase_orders.supplier_id')
            ->join('products', 'products.id', '=', 'restock_purchase_order_details.product_id')
            ->select(
                'restock_purchase_orders.id as restock_purchase_order_id',
                'staff.name as staff_name',
                'procument.name as procurement_name',
                'restock_purchase_orders.order_date',
                'restock_purchase_orders.delivery_date',
                'restock_purchase_orders.total_price',
                'restock_purchase_orders.status',
                'restock_purchase_orders.invoice_number',
                'restock_purchase_orders.request_code',
                'suppliers.name as supplier_name',
                'suppliers.address as supplier_address',
                'restock_purchase_order_details.quantity as quantity',
                'restock_purchase_order_details.price as product_price',
                'products.name as product_name',
                'products.sku as product_sku'

            )
            ->get();
    }

    public function updateStatus($id, $status)
    {
        try {
            $order = RestockPurchaseOrder::where('id', $id)->first();
            return RestockPurchaseOrder::where('request_code', $order->request_code)->update(['status' => $status]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
