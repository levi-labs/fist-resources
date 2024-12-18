<?php

namespace App\Http\Controllers;

use App\Models\ProposedProduct;
use App\Services\ProposeProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProposedProductController extends Controller
{
    protected $proposedProduct;
    public function __construct(ProposeProductService $proposedProduct)
    {
        $this->proposedProduct = $proposedProduct;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Proposed Product List';
        $propose_products = ProposedProduct::all();
        return view('pages.propose_product.index', compact('title', 'propose_products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add New Proposed Product';
        return view('pages.propose_product.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'sku' => 'nullable',
            'description' => 'required',

        ]);
        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated->errors())->withInput();
        }
        try {
            $this->proposedProduct->create($validated);
            return redirect()->route('proposed-product.index')->with('success', 'Proposed Product created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $title = 'Edit Proposed Product';
        $proposedProduct = $this->proposedProduct->getProposeProductById($id);

        return view('pages.propose_product.edit', compact('title', 'proposedProduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'sku' => 'nullable',
            'description' => 'required',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated->errors())->withInput();
        }

        try {
            $this->proposedProduct->update($validated, $id);
            return redirect()->route('propose.product.index')->with('success', 'Proposed Product updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->proposedProduct->delete($id);
            return redirect()->route('proposed-product.index')->with('success', 'Proposed Product deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
