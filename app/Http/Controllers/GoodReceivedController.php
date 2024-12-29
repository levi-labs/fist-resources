<?php

namespace App\Http\Controllers;

use App\Http\Requests\RestockInventoryRequest;
use App\Models\GoodReceivedDetail;
use App\Models\ProposePurchaseOrder;
use App\Models\ProposePurchaseOrderDetail;
use App\Models\RestockInventory;
use App\Models\RestockPurchaseOrder;
use App\Models\RestockPurchaseOrderDetail;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Services\GoodReceivedService;
use Illuminate\Support\Facades\DB;

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
        // dd($goods_received);
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
    public function show($id)
    {
        try {
            $title = 'Good Received Detail';
            $good_received = $this->goodReceivedService->getById($id);
            if ($good_received->restock_po_id !== null) {
                $orderItem = RestockPurchaseOrderDetail::join('products', 'products.id', 'restock_purchase_order_details.product_id')
                    ->where('restock_purchase_order_id', $good_received->restock_po_id)
                    ->select('restock_purchase_order_details.*', 'products.name')
                    ->get();

                $goodDetails = GoodReceivedDetail::join('goods_received', 'goods_received.id', 'good_received_details.good_received_id')
                    ->join('products', 'products.id', 'good_received_details.product_id')
                    ->where('goods_received.id', $id)
                    ->select(
                        'good_received_details.*',
                        'goods_received.shipment_id',
                        'products.name',
                        'products.sku',
                        'products.price'
                    )
                    ->get();

                $requestItem = RestockPurchaseOrder::where('restock_purchase_orders.id', $good_received->restock_po_id)
                    ->join('restock_inventory_requests', 'restock_inventory_requests.request_code', 'restock_purchase_orders.request_code')
                    ->join('products', 'products.id', 'restock_inventory_requests.product_id')
                    ->select(
                        'restock_purchase_orders.id as purchase_order_id',
                        'restock_purchase_orders.request_code as pruchase_request_code',
                        'restock_inventory_requests.id as request_id',
                        'restock_inventory_requests.status as request_status',
                        'restock_inventory_requests.quantity as request_quantity',
                        'products.id as product_id',
                        'products.name',
                        'products.sku',
                        'products.price',
                        DB::raw('restock_inventory_requests.quantity * products.price as total_price')
                    )
                    ->get();
            } elseif ($good_received->proposed_product_purchase_order_id !== null) {
                $requestItem = ProposePurchaseOrder::where('proposed_product_purchase_orders.id', $good_received->proposed_product_purchase_order_id)
                    ->join('proposed_inventory_requests', 'proposed_inventory_requests.request_code', 'proposed_product_purchase_orders.request_code')
                    ->join('proposed_products', 'proposed_products.id', 'proposed_inventory_requests.proposed_product_id')
                    ->select(
                        'proposed_product_purchase_orders.id as purchase_order_id',
                        'proposed_product_purchase_orders.request_code as pruchase_request_code',
                        'proposed_inventory_requests.id as request_id',
                        'proposed_inventory_requests.status as request_status',
                        'proposed_inventory_requests.quantity as request_quantity',
                        'proposed_products.id as product_id',
                        'proposed_products.name',
                        'proposed_products.sku',
                        'proposed_products.price',
                        DB::raw('proposed_inventory_requests.quantity * proposed_products.price as total_price')
                    )
                    ->get();

                $goodDetails = GoodReceivedDetail::join('goods_received', 'goods_received.id', 'good_received_details.good_received_id')
                    ->join('products', 'products.id', 'good_received_details.product_id')
                    ->where('good_received_id', $id)
                    ->select('good_received_details.*', 'products.name', 'products.sku')
                    ->get();

                $orderItem = ProposePurchaseOrderDetail::join('proposed_products', 'proposed_products.id', 'proposed_product_purchase_order_details.proposed_product_id')
                    ->where('proposed_order_id', $good_received->proposed_product_purchase_order_id)
                    ->select(
                        'proposed_product_purchase_order_details.*',
                        'proposed_products.name',
                        'proposed_products.sku',
                        DB::raw('proposed_product_purchase_order_details.quantity * proposed_products.price as total_price')
                    )
                    ->get();
            }
            // dd($good_received, $orderItem, $goodDetails, $requestItem);

            return view('pages.goods.detail', compact(
                'title',
                'good_received',
                'orderItem',
                'goodDetails',
                'requestItem'
            ));
            // return response()->json(['success' => true, 'data' => $good_received], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
