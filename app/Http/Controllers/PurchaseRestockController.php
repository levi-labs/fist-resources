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
        $title = 'Purchase Restock Awaiting Shipment';
        $sanitize = handleSanitize(request()->input('search', ''));
        if ($sanitize) {
            $data  = $this->restockPurchaseOrderService->search($sanitize, 'awaiting shipment');
            return view('pages.purchases.restock.index', compact('title', 'data'));
        } else {
            $data  = $this->restockPurchaseOrderService->getAllRestockPurchaseOrder();
            return view('pages.purchases.restock.index', compact('title', 'data'));
        }
    }

    public function shipped()
    {
        $title = 'Purchase Restock Shipped';
        $sanitize = handleSanitize(request()->input('search', ''));
        if ($sanitize) {
            $data  = $this->restockPurchaseOrderService->search($sanitize, 'shipped');
            return view('pages.purchases.restock.shipped', compact('title', 'data'));
        } else {
            $data  = $this->restockPurchaseOrderService->getAllRestockPurchaseOrderShipped();
            return view('pages.purchases.restock.shipped', compact('title', 'data'));
        }
    }

    public function delivered()
    {
        $title = 'Purchase Restock Delivered';
        $sanitize = handleSanitize(request()->input('search', ''));
        if ($sanitize) {
            $data  = $this->restockPurchaseOrderService->search($sanitize, 'delivered');
            return view('pages.purchases.restock.delivered', compact('title', 'data'));
        } else {
            $data  = $this->restockPurchaseOrderService->getAllRestockPurchaseOrderDelivered();
            return view('pages.purchases.restock.delivered', compact('title', 'data'));
        }
    }

    public function show($id)
    {
        $title = 'Purchase Restock Detail';
        $purchases  = $this->restockPurchaseOrderService->getRestockPurchaseOrderDetailById($id);
        // dd($purchases);
        return view('pages.purchases.restock.detail', compact('title', 'purchases'));
    }
    public function print($id)
    {
        $title = 'Purchase Restock';
        $purchases  = $this->restockPurchaseOrderService->getRestockPurchaseOrderDetailById($id);
        return view('pages.purchases.restock.print', compact('title', 'purchases'));
    }
}
