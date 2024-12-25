<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RestockPurchaseOrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseRestockController extends Controller
{
    protected $restockPurchaseOrderService;
    public function __construct(RestockPurchaseOrderService $restockPurchaseOrderService)
    {
        $this->restockPurchaseOrderService = $restockPurchaseOrderService;
    }
    public function index()
    {
        $title = 'Purchase Restock List';
        $data  = $this->restockPurchaseOrderService->getAllRestockPurchaseOrder();
        return view('pages.purchases.restock.index', compact('title', 'data'));
    }

    public function show($id)
    {
        $title = 'Purchase Restock Detail';
        $purchases  = $this->restockPurchaseOrderService->getRestockPurchaseOrderDetailById($id);
        // dd($purchases);
        return view('pages.purchases.restock.detail', compact('title', 'purchases'));
    }
}
