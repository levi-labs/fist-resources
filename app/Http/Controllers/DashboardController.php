<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $auth = auth('web')->user();
        if ($auth->role === 'supplier') {
            $purchase_restock_awaiting = \App\Models\RestockPurchaseOrder::where('status', 'awaiting shipment')
                ->where('supplier_id', auth('web')->user()->supplier_id)
                ->count();
            $purchase_propose_awaiting = \App\Models\ProposePurchaseOrder::where('status', 'awaiting shipment')
                ->where('supplier_id', auth('web')->user()->supplier_id)
                ->count();
            $purchase_restock_delivered = \App\Models\RestockPurchaseOrder::where('status', 'delivered')
                ->where('supplier_id', auth('web')->user()->supplier_id)
                ->count();
            $purchase_propose_delivered = \App\Models\ProposePurchaseOrder::where('status', 'delivered')
                ->where('supplier_id', auth('web')->user()->supplier_id)
                ->count();
            $purchase_restock_shipped = \App\Models\RestockPurchaseOrder::where('status', 'shipped')
                ->where('supplier_id', auth('web')->user()->supplier_id)
                ->count();
            $purchase_propose_shipped = \App\Models\ProposePurchaseOrder::where('status', 'shipped')
                ->where('supplier_id', auth('web')->user()->supplier_id)
                ->count();
            return view('pages.dashboard.index', compact(
                'purchase_restock_awaiting',
                'purchase_propose_awaiting',
                'purchase_restock_delivered',
                'purchase_propose_delivered',
                'purchase_restock_shipped',
                'purchase_propose_shipped',
            ));
        }
        $request_restock = \App\Models\RestockInventory::where('status', 'approved')->count();
        $purchase_restock = \App\Models\RestockPurchaseOrder::count();
        $request_propose = \App\Models\ProposedRequest::where('status', 'approved')->count();
        $purchase_propose = \App\Models\ProposePurchaseOrder::count();

        return view('pages.dashboard.index', compact('request_restock', 'purchase_restock', 'request_propose', 'purchase_propose'));
    }

    public function readNotification($id)
    {

        try {
            $notif = \App\Models\Notification::find($id);
            $notif->status = 'read';
            $notif->update();
            $type = $notif->order_type; // Tambahkan titik koma setelah penugasan

            if ($type == 'purchase restock') {
                $link = route('restock.purchase.show', $notif->related_order_id);
            } elseif ($type == 'purchase proposed product') {
                $link = route('propose.purchase.show', $notif->related_order_id);
            } elseif ($type == 'request restock') {
                $link = route('restock.inventory.show', $notif->request_related);
            } elseif ($type == 'request propose') {
                $link = route('propose.inventory.show', $notif->request_related);
            } elseif ($type == 'shipment restock shipped') {
                $link = route('shipment.restockShipped');
            } elseif ($type == 'shipment restock delivered') {
                $link = route('shipment.restockDelivered');
            } elseif ($type == 'shipment propose shipped') {
                $link = route('shipment.proposeShipped');
            } elseif ($type == 'shipment propose delivered') {
                $link = route('shipment.proposeDelivered');
            }
            return redirect($link);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
