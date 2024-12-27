<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RestockInventory;
use App\Services\ProductService;
use App\Services\RestockInventoryService;
use App\Services\RestockPurchaseOrderService;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RestockInventoryController extends Controller
{
    protected $productService;
    protected $restockInventoryService;
    public function __construct(RestockInventoryService $restockInventoryService, ProductService $productService)
    {
        $this->productService = $productService;
        $this->restockInventoryService = $restockInventoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        session()->forget('cart');

        $title = 'Restock Inventory List';
        $restocks = $this->restockInventoryService->getAllRestockInventoryPending();
        return view('pages.restock_inventory.index', compact('title', 'restocks'));
    }

    public function approved()
    {
        session()->forget('cart');
        $title = 'Approved Restock Inventory List';
        $restocks = $this->restockInventoryService->getAllRestockInventoryApproved();
        return view('pages.restock_inventory.approved', compact('title', 'restocks'));
    }

    public function resubmitted()
    {
        session()->forget('cart');
        $title = 'Resubmited Restock Inventory List';
        $restocks = $this->restockInventoryService->getAllRestockInventoryResubmitted();
        return view('pages.restock_inventory.resubmited', compact('title', 'restocks'));
    }

    public function rejected()
    {
        session()->forget('cart');
        $title = 'Rejected Restock Inventory List';
        $restocks = $this->restockInventoryService->getAllRestockInventoryRejected();
        return view('pages.restock_inventory.rejected', compact('title', 'restocks'));
    }



    public function search()
    {

        $sanitize = handleSanitize(request()->input('search', ''));

        if ($sanitize || $sanitize !== '') {
            // $title = 'Restock Inventory List';
            $restocks = $this->restockInventoryService->searchRestockInventory($sanitize);
            $check = RestockInventory::where('request_code', $sanitize)->first();
            switch ($check->status) {
                case 'pending':
                    $title = 'Restock Inventory List';
                    return view('pages.restock_inventory.index', compact('title', 'restocks'));
                    break;
                case 'approved':
                    $title = 'Approved Restock Inventory List';
                    return view('pages.restock_inventory.approved', compact('title', 'restocks'));
                    break;
                case 'resubmitted':
                    $title = 'Resubmited Restock Inventory List';
                    return view('pages.restock_inventory.resubmited', compact('title', 'restocks'));
                    break;
                case 'rejected':
                    $title = 'Rejected Restock Inventory List';
                    return view('pages.restock_inventory.rejected', compact('title', 'restocks'));
                    break;
                default:
                    $title = 'Restock Inventory List';
                    return view('pages.restock_inventory.index', compact('title', 'restocks'));
            }
            // return view('pages.restock_inventory.index', compact('title', 'restocks'));
        } else {
            return redirect()->route('restock.inventory.index');
        }
    }
    public function searchEdit() {}

    public function addItem($id)
    {
        try {
            $product = $this->productService->getProductById($id);

            $product = (array)$product;

            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                $cart[$id]['quantity']++;
            } else {
                $cart[$id] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'quantity' => 1,
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'sku' => $product['sku'],
                    'category' => $product['category_name'],
                    'product_id' => $product['id'],
                ];
            }
            // dd($cart);
            // dd(session()->get('cart'));
            // dd(session()->forget('cart'));
            session()->put('cart', $cart);
            // dd(session()->get('cart'));
            return redirect()->back()->with('success', 'Item added to cart successfully!');
            // if (isset($cart[$id])) {
            //     return redirect()->route('restock.inventory.add', ['id' => $id])->with('success', 'Item added to cart successfully!');
            // }

        } catch (\Throwable $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function updateAddItem($id)
    {
        try {
            $product = $this->productService->getProductById($id);

            $product = (array)$product;

            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                $cart[$id]['quantity']++;
            } else {
                $cart[$id] = [
                    'id' => Str::random(12),
                    'product_id' => $product['id'],
                    'name' => $product['name'],
                    'quantity' => 1,
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'sku' => $product['sku'],
                    'category' => $product['category_name'],
                    'product_id' => $product['id'],
                ];
            }
            // dd($cart);
            // dd(session()->get('cart'));
            // dd(session()->forget('cart'));
            session()->put('cart', $cart);
            // dd(session()->get('cart'));
            return redirect()->back()->with('success', 'Item added to cart successfully!');
            // if (isset($cart[$id])) {
            //     return redirect()->route('restock.inventory.add', ['id' => $id])->with('success', 'Item added to cart successfully!');
            // }

        } catch (\Throwable $error) {
            return back()->with('error', $error->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // session()->flush();
        // dd(session()->get('cart'));
        // dd(session()->forget('cart'));
        $sanitize = handleSanitize(request()->input('search', ''));
        if ($sanitize) {
            $title = 'Add New Restock Inventory';
            $products = $this->productService->searchProducts($sanitize);
            return view('pages.restock_inventory.create', compact('title', 'products'));
        } else {
            $title = 'Add New Restock Inventory';
            $products = $this->productService->getAllProducts(10);
            return view('pages.restock_inventory.create', compact('title', 'products'));
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if (session('cart') === null || empty(session('cart'))) {
                return redirect()->back()->with('error', 'Cart is empty!');
            }
            $this->restockInventoryService->create($request->id, $request->quantity, $request->notes);

            return redirect()->route('restock.inventory.index')->with('success', 'Restock inventory request created successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SupplierService $supplierService, $request_code)
    {
        $title = 'Restock Inventory Details';
        $restocks = $this->restockInventoryService->getRestockInventoryByRequestCode($request_code);
        $suppliers = $supplierService->getAllSuppliers();

        return view('pages.restock_inventory.detail', compact('title', 'restocks', 'suppliers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($request_code)
    {
        $title = 'Edit Restock Inventory';
        // $products = $this->productService->getAllProducts(10);
        $restocks = $this->restockInventoryService->getRestockInventoryByRequestCode($request_code);

        $sanitize = handleSanitize(request()->input('search', ''));
        if ($sanitize) {
            $products = $this->productService->searchProducts($sanitize);
            return view('pages.restock_inventory.edit', compact('title', 'restocks', 'products'));
        } else {
            $products = $this->productService->getAllProducts(10);
            return view('pages.restock_inventory.edit', compact('title', 'restocks', 'products'));
        }
        /** 
         *$cart = session()->get('cart', []);
         *foreach ($restocks as $key => $value) {
            $cart[$value->product_id] = [
                'id' => $value->id,
                'product_id' => $value->product_id,
                'staff_id' => $value->staff_id,
                'name' => $value->product_name,
                'quantity' => $value->quantity,
                'price' => $value->product_price,
                'request_code' => $value->request_code
            ];
         *}
        session()->put('cart', $cart);
         */

        // return view('pages.restock_inventory.edit', compact('title', 'restocks', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $request_code = null)
    {
        try {
            $id = $request->id;
            $product_id = $request->product_id;
            $quantity = $request->quantity;
            $notes = $request->notes;

            $this->restockInventoryService->update($id, $product_id, $quantity, $request_code, $notes);
            return redirect()->route('restock.inventory.index')->with('success', 'Restock inventory request updated successfully!');
        } catch (\Throwable $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($request_code)
    {
        try {
            $this->restockInventoryService->delete($request_code);
            return redirect()
                ->route('restock.inventory.index')
                ->with('success', 'Restock inventory request deleted successfully!');
        } catch (\Throwable $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }
    public function removeItem($id)
    {
        $cart = session()->get('cart', []);

        $cart = array_filter($cart, function ($item) use ($id) {
            return $item['id'] != $id;
        });

        session()->put('cart', array_values($cart)); // Reindex array setelah dihapus

        return redirect()->back()->with('success', 'Item removed from cart successfully!');
    }

    public function removeUpdateItem($id)
    {
        // $cart = session()->get('cart', []);

        // $cart = array_filter($cart, function ($item) use ($id) {
        //     return $item['id'] != $id;
        // });

        $this->restockInventoryService->deleteById($id);

        // session()->put('cart', array_values($cart)); // Reindex array setelah dihapus

        return redirect()->back()->with('success', 'Item removed from cart successfully!');
    }
    public function updateAndCreate($id, $request_code)
    {
        try {
            $product = $this->productService->getProductById($id);
            $restock_request = $this->restockInventoryService->getRestockInventoryByRequestCode($request_code);
            $check = RestockInventory::where('request_code', $request_code)->where('product_id', $product->id)->first();
            if ($check !== null) {
                RestockInventory::where('id', $check->id)->update([
                    'quantity' => $check->quantity + 1
                ]);

                return redirect()->back()->with('success', 'Item added to cart successfully!');
            }
            $data = [
                'staff_id' => auth('web')->user()->id,
                'procurement_id' => $restock_request[0]->procurement_id ?? null,
                'product_id' => $product->id,
                'quantity' => 1,
                'notes' => $restock_request[0]->notes ?? null,
                'request_code' => $restock_request[0]->request_code,
                'date_requested' => $restock_request[0]->date_requested,
                'status' => $restock_request[0]->status,
                'resubmit_count' => $restock_request[0]->resubmit_count,
                'reason' => $restock_request[0]->reason ?? null
            ];
            RestockInventory::create($data);
            return redirect()->back()->with('success', 'Item added to cart successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function approve(RestockPurchaseOrderService $restockPurchaseOrderService, Request $request, $request_code)
    {

        $validate = Validator::make($request->all(), [
            'delivery_date' => 'required',
            'supplier' => 'required',
            'reason' => 'nullable',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()->toArray()], 422);
        }
        try {
            DB::transaction(function () use ($restockPurchaseOrderService, $request, $request_code) {
                $this->restockInventoryService->approve($request_code, $request->reason);
                $restockPurchaseOrderService->create(
                    $request_code,
                    $request->supplier,
                    $request->delivery_date
                );
            });
            session()->flash('success', 'Restock inventory request, approved successfully!');

            // return redirect()->route('restock.inventory.index')->with('success', 'Restock inventory request, approved successfully!');
            return response()->json(['success' => 'Restock inventory request, approved successfully!'], 201);
        } catch (\Throwable $th) {
            // return redirect()->back()->with('error', $th->getMessage());
            session()->flash('error', $th->getMessage());
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function resubmit(Request $request, $request_code)
    {
        try {
            $this->restockInventoryService->resubmit($request_code);

            session()->flash('success', 'Restock inventory request, resubmitted successfully!');

            return response()->json(['success' => 'Restock inventory request, resubmitted successfully!'], 201);
        } catch (\Throwable $error) {
            session()->flash('error', $error->getMessage());
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function reject(Request $request, $request_code)
    {
        // dd($request, $request_code);
        try {
            $this->restockInventoryService->reject($request_code, $request->reason);

            // session()->flash('success', 'Restock inventory request, rejected successfully!');

            // return response()->json(['success' => 'Restock inventory request, rejected successfully!'], 201);
            return redirect()->route('restock.inventory.rejected')->with('success', 'Restock inventory request, rejected successfully!');
        } catch (\Throwable $error) {
            // session()->flash('error', $error->getMessage());
            // return response()->json(['error' => $error->getMessage()], 500);
            return redirect()->back()->with('error', $error->getMessage());
        }
    }
    public function print($request_code)
    {
        $title = 'Restock Request';
        $restocks = $this->restockInventoryService->getRestockInventoryByRequestCode($request_code);
        return view('pages.restock_inventory.print', compact('restocks', 'title'));
    }
}
