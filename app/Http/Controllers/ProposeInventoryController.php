<?php

namespace App\Http\Controllers;

use App\Models\ProposedProduct;
use App\Models\ProposedRequest;
use App\Services\ProposeProductService;
use App\Services\ProposePurchaseOrderService;
use App\Services\ProposeRequestService;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProposeInventoryController extends Controller
{
    protected $proposeRequestService;
    protected $proposedProductService;

    public function __construct(
        ProposeRequestService $proposeRequestService,
        ProposeProductService $proposedProductService
    ) {
        $this->proposeRequestService = $proposeRequestService;
        $this->proposedProductService = $proposedProductService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        session()->forget('cart');
        $title = 'Propose Inventory List';
        $proposes = $this->proposeRequestService->getAllProposeRequestPending();
        return view('pages.propose_inventory.index', compact('title', 'proposes'));
    }
    public function search(Request $request)
    {

        $sanitize = handleSanitize(request()->input('search', ''));
        if ($sanitize) {
            $title = 'Propose Inventory List';
            $proposes = $this->proposeRequestService->searchProposeRequest($sanitize);
            switch ($proposes->status) {
                case 'pending':
                    return view('pages.propose_inventory.index', compact('title', 'proposes'));
                    break;
                case 'approved':
                    return view('pages.propose_inventory.approved', compact('title', 'proposes'));
                    break;
                case 'resubmitted':
                    return view('pages.propose_inventory.resubmitted', compact('title', 'proposes'));
                    break;
                case 'rejected':
                    return view('pages.propose_inventory.rejected', compact('title', 'proposes'));
                    break;
                default:
                    return view('pages.propose_inventory.index', compact('title', 'proposes'));
                    break;
            }
        }
    }

    public function approved()
    {
        session()->forget('cart');
        $title = 'Approved Propose Inventory List';
        $proposes = $this->proposeRequestService->getAllProposeRequestApproved();
        return view('pages.propose_inventory.approved', compact('title', 'proposes'));
    }

    public function resubmitted()
    {
        session()->forget('cart');
        $title = 'Resubmitted Propose Inventory List';
        $proposes = $this->proposeRequestService->getAllProposeRequestResubmitted();
        return view('pages.propose_inventory.resubmitted', compact('title', 'proposes'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Propose Inventory';
        $propose_products = $this->proposeRequestService->getAllProposeNotInRequest();
        // dd($propose_products);
        return view('pages.propose_inventory.create', compact('title', 'propose_products'));
    }

    public function approve(ProposePurchaseOrderService $proposePurchaseOrderService, Request $request, $request_code)
    {
        $validator = Validator::make(request()->all(), [
            'delivery_date' => 'required',
            'supplier' => 'required',
            'reason' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toArray()], 422);
        }
        try {
            DB::transaction(function () use ($proposePurchaseOrderService, $request, $request_code) {
                $this->proposeRequestService->approve($request_code, $request->reason);
                $proposePurchaseOrderService->create(
                    $request_code,
                    $request->supplier,
                    $request->delivery_date
                );
            });

            session()->flash('success', 'Propose inventory request, approved successfully!');
            return response()->json([
                'success' => true,
                'message' => 'Propose inventory request, approved successfully!'
            ], 201);
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return back()->with('error', $th->getMessage());
        }
    }

    public function resubmit(Request $request, $request_code)
    {
        try {
            $this->proposeRequestService->resubmit($request_code, $request->reason);
            session()->flash('success', 'Propose inventory request, resubmitted successfully!');
            return response()->json([
                'success' => true,
                'message' => 'Propose inventory request, resubmitted successfully!'
            ], 201);
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return back()->with('error', $th->getMessage());
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
            $this->proposeRequestService->create($request->id, $request->quantity, $request->notes);
            return redirect()->route('propose.inventory.index')->with('success', 'Propose inventory request created successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(SupplierService $supplierService, $request_code)
    {
        $title = 'Propose Inventory Details';
        $proposed = $this->proposeRequestService->getByRequestCode($request_code);
        $suppliers = $supplierService->getAllSuppliers();
        return view('pages.propose_inventory.detail', compact('title', 'proposed', 'suppliers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($request_code)
    {
        $title = 'Edit Propose Inventory';
        $propose_products = $this->proposedProductService->getAllPaginateProposedProduct();
        // dd($propose_products);
        $proposed = $this->proposeRequestService->getByRequestCode($request_code);

        $cart = session()->get('cart', []);

        foreach ($proposed as $key => $value) {
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
        // dd($cart);
        session()->put('cart', $cart);


        return view('pages.propose_inventory.edit', compact('title', 'proposed', 'propose_products'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $request_code)
    {
        try {
            $id = $request->id;
            $product_id = $request->product_id;
            $quantity = $request->quantity;
            $notes = $request->notes;

            $this->proposeRequestService->update($id, $product_id, $quantity, $request_code, $notes);
            return redirect()->route('propose.inventory.index')->with('success', 'Propose inventory request updated successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProposedRequest $proposedRequest)
    {
        //
    }

    public function addItem($id)
    {
        try {

            $product = $this->proposedProductService->getProposeProductById($id);

            // $product = (array)$product;
            // dd($product);
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

            session()->put('cart', $cart);

            return redirect()->back()->with('success', 'Item added to cart successfully!');
        } catch (\Throwable $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function updateAddItem($id, $request_code)
    {
        try {
            $title = 'Edit Propose Inventory';
            $propose_products = $this->proposedProductService->getAllPaginateProposedProduct();
            $product = $this->proposedProductService->getProposeProductById($id);
            $proposed = $this->proposeRequestService->getByRequestCode($request_code);

            $cart = session()->get('cart', []);
            // dd($product);
            if (isset($cart[$id])) {
                $cart[$id]['quantity']++;
                // ProposedRequest::where('id', $id)->update(['quantity' => $cart[$id]['quantity']]);
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
                ];
            }
            session()->put('cart', $cart);
            // session()->flash('success', 'Item added to cart successfully!');
            return view('pages.propose_inventory.edit', [
                'cart' => session('cart'),
                'title' => $title,
                'propose_products' => $propose_products,
                'proposed' => $proposed
            ]);
        } catch (\Throwable $error) {
            return back()->with('error', $error->getMessage());
        }
    }

    public function removeItem($id)
    {
        try {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Item removed from cart successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
