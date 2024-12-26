<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProposePurchaseOrderService;

class PurchaseProposeController extends Controller
{
    protected $proposedPurchaseOrderService;
    public function __construct(ProposePurchaseOrderService $proposedPurchaseOrderService)
    {
        $this->proposedPurchaseOrderService = $proposedPurchaseOrderService;
    }
    public function index()
    {

        $title = 'Purchase Propose List';
        $sanitze = handleSanitize(request()->input('search', ''));
        if ($sanitze) {
            $data  = $this->proposedPurchaseOrderService->search($sanitze, 'awaiting shipment');
            return view('pages.purchases.propose.index', compact('title', 'data'));
        } else {
            $data  = $this->proposedPurchaseOrderService->getAllProposePurchaseOrder();
            return view('pages.purchases.propose.index', compact('title', 'data'));
        }
    }
    public function shipped()
    {
        $title = 'Purchase Propose Shipped';
        $sanitze = handleSanitize(request()->input('search', ''));
        if ($sanitze) {
            $data  = $this->proposedPurchaseOrderService->search($sanitze, 'shipped');
            return view('pages.purchases.propose.shipped', compact('title', 'data'));
        } else {
            $data  = $this->proposedPurchaseOrderService->getAllProposePurchaseOrderShipped();
            return view('pages.purchases.propose.shipped', compact('title', 'data'));
        }
    }

    public function delivered()
    {
        $title = 'Purchase Propose Delivered';
        $sanitze = handleSanitize(request()->input('search', ''));
        if ($sanitze) {
            $data  = $this->proposedPurchaseOrderService->search($sanitze, 'delivered');
            return view('pages.purchases.propose.delivered', compact('title', 'data'));
        } else {
            $data  = $this->proposedPurchaseOrderService->getAllProposePurchaseOrderDelivered();
            return view('pages.purchases.propose.delivered', compact('title', 'data'));
        }
    }

    public function show($id)
    {
        $title = 'Purchase Propose Detail';
        $purchases  = $this->proposedPurchaseOrderService->getProposePurchaseOrderDetailById($id);
        // dd($purchases);
        return view('pages.purchases.propose.detail', compact('title', 'purchases'));
    }
}
