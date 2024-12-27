<?php

namespace App\Http\Controllers;

use App\Models\ProposePurchaseOrder;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Services\RestockPurchaseOrderService;
use App\Services\ShipmentService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Services\ProposePurchaseOrderService;

class ShipmentController extends Controller
{
    protected $restockPurchaseOrderService;
    protected $shipmentService;
    protected $proposePurchaseOrderService;

    public function __construct(
        ProposePurchaseOrderService $proposePurchaseOrderService,
        RestockPurchaseOrderService $restockPurchaseOrderService,
        ShipmentService $shipmentService
    ) {
        $this->proposePurchaseOrderService = $proposePurchaseOrderService;
        $this->restockPurchaseOrderService = $restockPurchaseOrderService;
        $this->shipmentService = $shipmentService;
    }
    public function store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'shipment_date' => 'required',
            'courier' => 'required',
            'tracking_number' => 'required',
            'notes' => 'nullable',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()->toArray()], 422);
        }
        $data = [
            'shipment_date' => $request->shipment_date,
            'courier' => $request->courier,
            'tracking_number' => $request->tracking_number,
            'notes' => $request->notes ?? null,
            'status' => 'shipped',
        ];

        if ($request->type == 'restock') {
            $data['restock_purchase_order_id'] = $request->id;
        } else if ($request->type == 'propose') {
            $data['proposed_product_purchase_order_id'] = $request->id;
        }
        try {
            DB::transaction(function () use ($data, $request) {

                $this->shipmentService->create($data);
                if ($request->type == 'restock') {
                    $this->restockPurchaseOrderService->updateStatus($request->id, 'shipped');
                }
                if ($request->type == 'propose') {
                    $this->proposePurchaseOrderService->updateStatus($request->id, 'shipped');
                }
            });

            return response()->json(['success' => true, 'message' => 'Shipment created successfully!'], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function reject($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $this->shipmentService->reject($id);
            });

            return response()->json(['success' => true, 'message' => 'Shipment rejected successfully!'], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function restockShipped()
    {
        $title = 'Shipment Restock Shipped';
        $sanitize = handleSanitize(request()->input('search', ''));
        if ($sanitize) {
            $shipments = $this->shipmentService->searchRestock($sanitize, 'shipped');
            return view('pages.shipment.restock.restock', compact('title', 'shipments'));
        } else {
            $shipments = $this->shipmentService->getRestockShipmentShipped();
            return view('pages.shipment.restock.restock', compact('title', 'shipments'));
        }
    }
    public function restockDelivered()
    {
        $title = 'Shipment Restock Delivered';
        $sanitize = handleSanitize(request()->input('search', ''));
        if ($sanitize) {
            $shipments = $this->shipmentService->searchRestock($sanitize, 'delivered');
            return view('pages.shipment.restock.delivered', compact('title', 'shipments'));
        } else {
            $shipments = $this->shipmentService->getRestockShipmentDelivered();
            return view('pages.shipment.restock.delivered', compact('title', 'shipments'));
        }
    }
    public function proposeShipped()
    {
        $title = 'Shipment Propose Shipped';
        $sanitize = handleSanitize(request()->input('search', ''));
        if ($sanitize) {
            $shipments = $this->shipmentService->searchPropose($sanitize, 'shipped');
            return view('pages.shipment.propose.propose', compact('title', 'shipments'));
        } else {
            $shipments = $this->shipmentService->getProposeShipmentShipped();
            return view('pages.shipment.propose.propose', compact('title', 'shipments'));
        }
    }
    public function proposeDelivered()
    {
        $title = 'Shipment Propose Delivered';
        $sanitize = handleSanitize(request()->input('search', ''));
        if ($sanitize) {
            $shipments = $this->shipmentService->searchPropose($sanitize, 'delivered');
            return view('pages.shipment.propose.delivered', compact('title', 'shipments'));
        } else {
            $shipments = $this->shipmentService->getProposeShipmentDelivered();
            return view('pages.shipment.propose.delivered', compact('title', 'shipments'));
        }
    }
}
