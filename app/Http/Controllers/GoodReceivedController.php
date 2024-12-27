<?php

namespace App\Http\Controllers;

use App\Models\ProposePurchaseOrderDetail;
use App\Models\RestockPurchaseOrderDetail;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Services\GoodReceivedService;

use function Laravel\Prompts\select;

class GoodReceivedController extends Controller
{
    protected $goodReceivedService;
    public function __construct(
        GoodReceivedService $goodReceivedService
    ) {
        $this->goodReceivedService = $goodReceivedService;
    }
    public function index()
    {
        $title = 'Good Received';
        $goods_received = $this->goodReceivedService->getAllGoodReceived();
        return view('pages.goods.index', compact('title', 'goods_received'));
    }

    public function trackingNumber()
    {
        try {
            $check = $this->goodReceivedService->checkTrackingNumber(request()->tracking_number);
            $checkData = $check->getData();
            if ($checkData->success === true) {
                $shipment = Shipment::where('tracking_number', request()->tracking_number)->first();

                if ($shipment->restock_purchase_order_id !== null) {
                    $orderItem = RestockPurchaseOrderDetail::join('products', 'products.id', 'restock_purchase_order_details.product_id')
                        ->where('restock_purchase_order_id', $shipment->restock_purchase_order_id)
                        ->select('restock_purchase_order_details.*', 'products.name')
                        ->get();
                } elseif ($shipment->proposed_product_purchase_order_id !== null) {
                    $orderItem = ProposePurchaseOrderDetail::join('proposed_products', 'proposed_products.id', 'proposed_product_purchase_order_details.proposed_product_id')
                        ->where('proposed_order_id', $shipment->proposed_product_purchase_order_id)
                        ->select('proposed_product_purchase_order_details.*', 'proposed_products.name')
                        ->get();
                }
                return response()->json(['success' => true, 'data' => $checkData->data, 'items' => $orderItem], 200);
            } else {
                return response()->json(['success' => false, 'error' => $checkData->error], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // dd($request->all());
            // return response()->json($request->quantities);
            $this->goodReceivedService->create($request->all());
            session()->flash('success', 'Good received created successfully!');
            return response()->json(['success' => true, 'message' => 'Good received created successfully!'], 201);
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
