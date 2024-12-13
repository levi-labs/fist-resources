<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RestockInventory;
use App\Services\ProductService;
use App\Services\RestockInventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $restocks = $this->restockInventoryService->getAllRestockInventoryRequest();
        return view('pages.restock_inventory.index', compact('title', 'restocks'));
    }

    public function search()
    {

        $sanitize = handleSanitize(request()->input('search', ''));

        if ($sanitize) {
            $title = 'Restock Inventory List';
            $restocks = $this->restockInventoryService->searchRestockInventory($sanitize);
            return view('pages.restock_inventory.index', compact('title', 'restocks'));
        } else {
            return redirect()->route('restock.inventory.index');
        }
    }

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
    public function show($request_code)
    {
        $title = 'Restock Inventory Details';
        $restocks = $this->restockInventoryService->getRestockInventoryByRequestCode($request_code);

        return view('pages.restock_inventory.detail', compact('title', 'restocks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($request_code)
    {
        $title = 'Edit Restock Inventory';
        $products = $this->productService->getAllProducts(10);
        $restocks = $this->restockInventoryService->getRestockInventoryByRequestCode($request_code);
        $cart = session()->get('cart', []);
        // dd($restocks);
        foreach ($restocks as $key => $value) {
            $cart[$value->product_id] = [
                'id' => $value->id,
                'product_id' => $value->product_id,
                'staff_id' => $value->staff_id,
                'name' => $value->product_name,
                'quantity' => $value->quantity,
                'price' => $value->product_price,
                'request_code' => $value->request_code
            ];
        }

        session()->put('cart', $cart);
        return view('pages.restock_inventory.edit', compact('title', 'restocks', 'products'));
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
            return redirect()->route('restock.inventory.index')->with('success', 'Restock inventory request deleted successfully!');
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
}
