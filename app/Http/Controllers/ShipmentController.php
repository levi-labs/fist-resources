<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Services\RestockPurchaseOrderService;
use App\Services\ShipmentService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller
{
    protected $restockPurchaseOrderService;
    protected $shipmentService;

    public function __construct(
        RestockPurchaseOrderService $restockPurchaseOrderService,
        ShipmentService $shipmentService
    ) {
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
            'delivery_date' => $request->delivery_date ?? null,
            'courier' => $request->courier,
            'tracking_number' => $request->tracking_number,
            'notes' => $request->notes ?? null,
            'restock_purchase_order_id' => $request->id,
            'status' => 'shipped',
        ];
        // dd($data);
        try {
            DB::transaction(function () use ($data, $request) {
                $this->shipmentService->create($data);
                $this->restockPurchaseOrderService->updateStatus($request->id, 'shipped');
            });

            return response()->json(['success' => true, 'message' => 'Shipment created successfully!'], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
