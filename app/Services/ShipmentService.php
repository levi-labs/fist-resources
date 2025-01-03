<?php

namespace App\Services;

use App\Models\Shipment;

class ShipmentService
{
    public function searchRestock($search, $status)
    {
        try {
            return Shipment::join('restock_purchase_orders', 'restock_purchase_orders.id', 'shipments.restock_purchase_order_id')
                ->select(
                    'shipments.*',
                    'restock_purchase_orders.invoice_number as invoice_number',
                )
                ->where('tracking_number', 'like', '%' . $search . '%')
                ->orWhere('invoice_number', 'like', '%' . $search . '%')
                ->where('shipments.status', $status)
                ->paginate(10);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function searchPropose($search, $status)
    {
        try {
            return Shipment::join('proposed_product_purchase_orders', 'proposed_product_purchase_orders.id', 'shipments.proposed_product_purchase_order_id')
                ->select(
                    'shipments.*',
                    'proposed_product_purchase_orders.invoice_number as invoice_number',
                )
                ->where('tracking_number', 'like', '%' . $search . '%')
                ->orWhere('invoice_number', 'like', '%' . $search . '%')
                ->where('shipments.status', $status)
                ->paginate(10);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getRestockShipmentShipped()
    {
        try {
            return Shipment::join('restock_purchase_orders', 'restock_purchase_orders.id', 'shipments.restock_purchase_order_id')
                ->select(
                    'shipments.*',
                    'restock_purchase_orders.invoice_number as invoice_number',
                )
                ->where('shipments.status', 'shipped')
                ->whereNotNull('restock_purchase_order_id')
                ->paginate(10);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getRestockShipmentDelivered()
    {
        try {
            return Shipment::join('restock_purchase_orders', 'restock_purchase_orders.id', 'shipments.restock_purchase_order_id')
                ->select(
                    'shipments.*',
                    'restock_purchase_orders.invoice_number as invoice_number',
                )
                ->where('shipments.status', 'delivered')
                ->whereNotNull('restock_purchase_order_id')
                ->paginate(10);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getProposeShipmentShipped()
    {
        try {
            return Shipment::join('proposed_product_purchase_orders', 'proposed_product_purchase_orders.id', 'shipments.proposed_product_purchase_order_id')
                ->select(
                    'shipments.*',
                    'proposed_product_purchase_orders.invoice_number as invoice_number',
                )
                ->where('shipments.status', 'shipped')
                ->whereNotNull('proposed_product_purchase_order_id')
                ->paginate(10);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getProposeShipmentDelivered()
    {
        try {
            return Shipment::join('proposed_product_purchase_orders', 'proposed_product_purchase_orders.id', 'shipments.proposed_product_purchase_order_id')
                ->select(
                    'shipments.*',
                    'proposed_product_purchase_orders.invoice_number as invoice_number',
                )
                ->where('shipments.status', 'delivered')
                ->whereNotNull('proposed_product_purchase_order_id')
                ->paginate(10);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function create($data)
    {
        try {
            Shipment::create($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function reject($id)
    {
        try {
            Shipment::where('id', $id)->update(['status' => 'rejected']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
