<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $request_restock = \App\Models\RestockInventory::where('status', 'approved')->count();
        $purchase_restock = \App\Models\RestockPurchaseOrder::count();
        $request_propose = \App\Models\ProposedRequest::where('status', 'approved')->count();
        $purchase_propose = \App\Models\ProposePurchaseOrder::count();

        return view('pages.dashboard.index', compact('request_restock', 'purchase_restock', 'request_propose', 'purchase_propose'));
    }
}
