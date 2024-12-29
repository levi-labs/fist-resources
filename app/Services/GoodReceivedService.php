<?php

namespace App\Services;

use App\Models\GoodReceived;
use App\Models\GoodReceivedDetail;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProposedProduct;
use App\Models\ProposePurchaseOrder;
use App\Models\ProposePurchaseOrderDetail;
use App\Models\RestockPurchaseOrder;
use App\Models\RestockPurchaseOrderDetail;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;

class GoodReceivedService
{

    public function getAllGoodReceived()
    {
        return GoodReceived::join('shipments', 'shipments.id', 'goods_received.shipment_id')
            ->leftjoin('restock_purchase_orders', 'restock_purchase_orders.id', 'goods_received.restock_purchase_order_id')
            ->leftjoin(
                'proposed_product_purchase_orders',
                'proposed_product_purchase_orders.id',
                'goods_received.proposed_product_purchase_order_id'
            )
            ->select(
                'goods_received.*',
                'shipments.tracking_number',
                'shipments.status',
                'restock_purchase_orders.id as restock_po_id',
                'proposed_product_purchase_orders.id as proposed_po_id'
            )
            ->paginate(10);
    }

    public function checkTrackingNumber($tracking_number)
    {
        $data = Shipment::where('tracking_number', $tracking_number)->first();
        if ($data) {
            $check = GoodReceived::where('shipment_id', $data->id)->first();
            if ($check) {
                return response()->json(['success' => false, 'error' => 'Tracking number already used in good received'], 400);
            }
            return response()->json(['success' => true, 'data' => $data], 200);
        } else {
            return response()->json(['success' => false, 'error' => 'Tracking number not found'], 404);
        }
    }

    public function getById($id)
    {
        return GoodReceived::join('shipments', 'shipments.id', 'goods_received.shipment_id')
            ->join('users', 'users.id', 'goods_received.received_by')
            ->leftjoin('restock_purchase_orders', 'restock_purchase_orders.id', 'goods_received.restock_purchase_order_id')
            ->leftjoin(
                'proposed_product_purchase_orders',
                'proposed_product_purchase_orders.id',
                'goods_received.proposed_product_purchase_order_id'
            )
            ->select(
                'goods_received.*',
                'shipments.tracking_number',
                'shipments.status',
                'restock_purchase_orders.id as restock_po_id',
                'proposed_product_purchase_orders.id as proposed_po_id',
                'users.name as received_name'
            )
            ->where('goods_received.id', $id)
            ->first();
    }
    public function handleProductImageUpload(&$data)
    {
        if (array_key_exists('image', $data)) {
            if ($data['image'] instanceof \Illuminate\Http\UploadedFile) {
                // Menyimpan gambar di folder 'images' di disk 'public'
                $file = $data['image']->store('images', 'public');
                $data['image'] = $file;  // Update data dengan path file
            }
        }
    }

    public function create($data)
    {
        try {

            DB::transaction(function () use ($data) {
                $datas = Shipment::where('id', $data['shipment_id'])->first();
                $new_product = [];
                $goodreceive_detail = [];
                if ($datas['restock_purchase_order_id'] !== null) {
                    $getSupplierId = RestockPurchaseOrder::where('id', $datas['restock_purchase_order_id'])->first();
                    $orderItem = RestockPurchaseOrderDetail::join('products', 'products.id', 'restock_purchase_order_details.product_id')
                        ->select(
                            'restock_purchase_order_details.*',
                            'products.id as product_id',
                            'products.name',
                            'products.category_id',
                            'products.brand',
                            'products.price',
                            'products.image'
                        )
                        ->where('restock_purchase_order_id', $datas['restock_purchase_order_id'])
                        ->get();
                    $image = request()->file('image');
                    $path = $image->store('images', 'public');


                    $resetarray = array_values($data['quantity']);
                    // dd($resetarray);
                    $GoodsReceived = GoodReceived::create([
                        'shipment_id' => $data['shipment_id'],
                        'restock_purchase_order_id' => $data['restock_order'],
                        'received_by' => auth('web')->user()->id,
                        'received_date' => date('Y-m-d'),
                        'request_type' => 'restock',
                        'notes' => $data['notes'] ?? null,
                        'image' => $path

                    ]);
                    // dd($orderItem);

                    $data_inventory = [];
                    $goodreceive_detail = [];
                    foreach ($orderItem as $key => $value) {
                        $data_inventory[$key] = [
                            'supplier_id' => $getSupplierId->supplier_id,
                            'product_id' => $value->product_id,
                        ];
                        $goodreceive_detail[$key] = [
                            'good_received_id' => $GoodsReceived->id,
                            'product_id' => $value->product_id,
                        ];
                    }
                    foreach ($resetarray as $key => $value) {
                        $data_inventory[$key]['stock'] = $value;
                        $goodreceive_detail[$key]['quantity'] = $value;
                    }
                    foreach ($resetarray as $key => $value) {
                        Inventory::create($data_inventory[$key]);
                        GoodReceivedDetail::create($goodreceive_detail[$key]);
                    }
                    // dd($data_inventory, $goodreceive_detail);
                    $datas->update([
                        'status' => 'delivered',
                    ]);
                    $getSupplierId->update([
                        'status' => 'delivered',
                    ]);
                } else {
                    $resetarray = array_values($data['quantity']);
                    $getSupplierId = ProposePurchaseOrder::where('id', $data['propose_order'])->first();
                    $orderItem = ProposePurchaseOrderDetail::join('proposed_products', 'proposed_products.id', 'proposed_product_purchase_order_details.proposed_product_id')
                        ->where('proposed_order_id', $datas['proposed_product_purchase_order_id'])
                        ->select(
                            'proposed_product_purchase_order_details.*',
                            'proposed_products.id as product_id',
                            'proposed_products.name',
                            'proposed_products.category_id',
                            'proposed_products.brand',
                            'proposed_products.model',
                            'proposed_products.size',
                            'proposed_products.unit_type',
                            'proposed_products.sku',
                            'proposed_products.price as product_price',
                            'proposed_products.image',
                            'proposed_products.description',
                        )
                        ->get();
                    $image = request()->file('image');
                    $path = $image->store('images', 'public');
                    $GoodsReceived = GoodReceived::create([
                        'shipment_id' => $data['shipment_id'],
                        'proposed_product_purchase_order_id' => $data['propose_order'],
                        'received_by' => auth('web')->user()->id,
                        'received_date' => date('Y-m-d'),
                        'request_type' => 'proposed',
                        'notes' => $data['notes'] ?? null,
                        'image' => $path
                    ]);
                    foreach ($orderItem as $key => $value) {
                        $new_product[] = [
                            'name' => $value->name,
                            'category_id' => $value->category_id,
                            'brand' => $value->brand,
                            'model' => $value->model,
                            'size' => $value->size,
                            'unit_type' => $value->unit_type,
                            'sku' => $value->sku,
                            'price' => $value->product_price,
                            'description' => $value->description,
                            'image' => $value->image,
                        ];
                        $goodreceive_detail[] = [
                            'good_received_id' => $GoodsReceived->id,
                            'quantity' => $data['quantity'][$key],
                        ];
                        ProposedProduct::where('id', $value->product_id)->update(['status' => 'registered']);
                    }
                    for ($i = 0; $i < count($new_product); $i++) {
                        $product =  Product::create($new_product[$i]);
                        $goodreceive_detail[$i]['product_id'] = $product->id;
                    }
                    for ($i = 0; $i < count($goodreceive_detail); $i++) {
                        // $checkProductId = Product::where('id', $goodreceive_detail[$i]['product_id'])->first();
                        // if ($checkProductId) {
                        //     $checkProductId->update(['quantity' => $checkProductId->quantity + $goodreceive_detail[$i]['quantity']]);
                        // }
                        Inventory::create([
                            'product_id' => $goodreceive_detail[$i]['product_id'],
                            'supplier_id' => $getSupplierId->supplier_id,
                            'stock' => $goodreceive_detail[$i]['quantity']
                        ]);
                        GoodReceivedDetail::create($goodreceive_detail[$i]);
                    }
                    $datas->update([
                        'status' => 'delivered'
                    ]);
                    $getSupplierId->update([
                        'status' => 'delivered'
                    ]);
                }
            });
        } catch (\Throwable $error) {
            throw $error;
        }
    }
}
