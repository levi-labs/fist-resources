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

    public function getAllProposePurchaseOrder()
    {
        $auth = auth('web')->user();
        if ($auth->role === 'supplier') {
            return ProposePurchaseOrder::where('status', 'awaiting shipment')
                ->where('supplier_id', $auth->supplier_id)
                ->paginate(10);
        }
        return ProposePurchaseOrder::where('status', 'awaiting shipment')->paginate(10);
    }
    public function getAllProposePurchaseOrderDelivered()
    {
        $auth = auth('web')->user();
        if ($auth->role === 'supplier') {
            return ProposePurchaseOrder::where('status', 'delivered')
                ->where('supplier_id', $auth->supplier_id)
                ->paginate(10);
        }
        return ProposePurchaseOrder::where('status', 'delivered')->paginate(10);
    }
    public function getAllProposePurchaseOrderShipped()
    {
        $auth = auth('web')->user();
        if ($auth->role === 'supplier') {
            return ProposePurchaseOrder::where('status', 'shipped')
                ->where('supplier_id', $auth->supplier_id)
                ->paginate(10);
        }
        return ProposePurchaseOrder::where('status', 'shipped')->paginate(10);
    }

    public function search($search, $status)
    {
        try {
            $auth = auth('web')->user();
            if ($auth->role === 'supplier') {
                $purchase = ProposePurchaseOrder::where('invoice_number', 'like', '%' . $search . '%')
                    ->where('status', $status)
                    ->where('supplier_id', auth('web')->user()->supplier_id)
                    ->paginate(10);
                return $purchase;
            }
            $purchase = ProposePurchaseOrder::where('invoice_number', 'like', '%' . $search . '%')
                ->where('status', $status)
                ->paginate(10);
            return $purchase;
        } catch (\Throwable $error) {
            throw $error;
        }
    }
    public function getProposePurchaseOrderById($id)
    {
        return ProposePurchaseOrder::find($id);
    }
    public function getProposePurchaseOrderDetailById($id)
    {
        return ProposePurchaseOrderDetail::where('proposed_order_id', $id)
            ->join('proposed_product_purchase_orders as propose_purchase_orders', 'propose_purchase_orders.id', '=', 'proposed_product_purchase_order_details.proposed_order_id')
            ->join('users as staff', 'staff.id', '=', 'propose_purchase_orders.staff_id')
            ->join('users as procurement', 'procurement.id', '=', 'propose_purchase_orders.procurement_id')
            ->join('suppliers', 'suppliers.id', '=', 'propose_purchase_orders.supplier_id')
            ->join('proposed_products as products', 'products.id', '=', 'proposed_product_purchase_order_details.proposed_product_id')
            ->select(
                'propose_purchase_orders.id as propose_purchase_order_id',
                'staff.name as staff_name',
                'procurement.name as procurement_name',
                'propose_purchase_orders.order_date',
                'propose_purchase_orders.delivery_date',
                'propose_purchase_orders.total_price',
                'propose_purchase_orders.status',
                'propose_purchase_orders.invoice_number',
                'propose_purchase_orders.request_code',
                'suppliers.name as supplier_name',
                'suppliers.address as supplier_address',
                'proposed_product_purchase_order_details.quantity as quantity',
                'proposed_product_purchase_order_details.price as product_price',
                'products.name as product_name',
                'products.sku as product_sku'

            )
            ->get();
    }

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
    public function updateStatus($id, $status)
    {
        try {
            $order = ProposePurchaseOrder::where('id', $id)->first();
            return ProposePurchaseOrder::where('request_code', $order->request_code)->update(['status' => $status]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
