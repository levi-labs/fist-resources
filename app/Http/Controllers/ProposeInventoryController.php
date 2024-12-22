<?php

namespace App\Http\Controllers;

use App\Models\ProposedProduct;
use App\Models\ProposedRequest;
use App\Services\ProposeProductService;
use App\Services\ProposeRequestService;
use Illuminate\Http\Request;

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
        $title = 'Approved Propose Inventory List';
        $proposes = $this->proposeRequestService->getAllProposeRequestApproved();
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
    public function show($request_code)
    {
        $title = 'Propose Inventory Details';
        $proposed = $this->proposeRequestService->getByRequestCode($request_code);
        return view('pages.propose_inventory.detail', compact('title', 'proposed'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProposedRequest $proposedRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProposedRequest $proposedRequest)
    {
        //
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
