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
        $data  = $this->proposedPurchaseOrderService->getAllProposePurchaseOrder();
        return view('pages.purchases.propose.index', compact('title', 'data'));
    }

    public function show($id)
    {
        $title = 'Purchase Propose Detail';
        $purchases  = $this->proposedPurchaseOrderService->getProposePurchaseOrderDetailById($id);
        // dd($purchases);
        return view('pages.purchases.propose.detail', compact('title', 'purchases'));
    }
}
