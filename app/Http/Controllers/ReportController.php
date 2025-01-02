<?php

namespace App\Http\Controllers;

use App\Models\ProposePurchaseOrder;
use App\Models\RestockPurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function reportRestockView()
    {
        $title = "Report Purchase Restock Order";

        return view('pages.report.restock.index', compact('title'));
    }

    public function reportRestockSearch(Request $request)
    {
        $title = "Report Purchase Restock Order";

        $restock_purchase  = new RestockPurchaseOrder();
        $data = $restock_purchase->getReport($request->from, $request->to);
        return view('pages.report.restock.print', compact(
            'title',
            'restock_purchase',
            'data'
        ));
    }
    public function reportProposeView()
    {
        $title = "Report Purchase Propose Order";
        return view('pages.report.propose.index', compact('title'));
    }

    public function reportProposeSearch(Request $request)
    {
        $title = "Report Purchase Propose Order";

        $restock_purchase  = new ProposePurchaseOrder();
        $data = $restock_purchase->getReport($request->from, $request->to);
        return view('pages.report.propose.print', compact(
            'title',
            'restock_purchase',
            'data'
        ));
    }
}
