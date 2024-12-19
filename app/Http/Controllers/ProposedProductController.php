<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProposedProductRequest;
use App\Models\ProposedProduct;
use App\Models\ProposedRequest;
use App\Services\CategoryService;
use App\Services\ProposeProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProposedProductController extends Controller
{
    protected $proposedProduct;
    protected $category;
    public function __construct(ProposeProductService $proposedProduct, CategoryService $category)
    {
        $this->proposedProduct = $proposedProduct;
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sanitize = handleSanitize(request()->input('search', ''));
        if ($sanitize || $sanitize !== '') {
            $title = 'Proposed Product List';
            $propose_products =  $this->proposedProduct->search($sanitize);
            return view('pages.propose_product.index', compact('title', 'propose_products'));
        }
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
        $categories = $this->category->getAllCategories();
        return view('pages.propose_product.create', compact('title', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProposedProductRequest $request)
    {

        try {
            $data = $request->all();
            $this->proposedProduct->hanldeProductImageUpload($data);
            $this->proposedProduct->create($data);
            return redirect()->route('propose.product.index')->with('success', 'Proposed Product created successfully');
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
        $propose = $this->proposedProduct->getProposeProductById($id);
        $categories = $this->category->getAllCategories();

        return view('pages.propose_product.edit', compact('title', 'propose', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProposedProductRequest $request, $id)
    {
        try {

            if (ProposedProduct::where('sku', $request->sku)->where('id', '!=', $id)->exists()) {
                return redirect()->back()->with('error', 'Proposed Product SKU already exists');
            }

            $data = $request->all();
            $this->proposedProduct->hanldeProductImageUpload($data);
            $this->proposedProduct->update($data, $id);

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
            return redirect()->route('propose.product.index')->with('success', 'Proposed Product deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
