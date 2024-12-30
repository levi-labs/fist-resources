<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function restock()
    {
        $title = "Report Purchase Restock Order";

        return view('pages.report.restock', compact('title'));
    }

    public function printRestock(Request $request)
    {
        $title = "Report Purchase Restock Order";

        $restocks = DB::table('restocks')
            ->select('restocks.id', 'restocks.order_number', 'restocks.date', 'products.name as product_name', 'units.name as unit_name', 'restocks.quantity')
            ->join('products', 'restocks.product_id', 'products.id')
            ->join('units', 'products.unit_id', 'units.id')
            ->orderBy('restocks.id', 'desc')
            ->paginate(10);

        return view('pages.report.restock', compact('title', 'restocks'));
    }
    public function propose()
    {
        $title = "Report Purchase Propose Order";
        return view('pages.report.propose', compact('title'));
    }
}
