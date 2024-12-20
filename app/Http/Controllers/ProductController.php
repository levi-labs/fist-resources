<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productservice;
    protected $categoryservice;
    public function __construct(ProductService $productservice, CategoryService $categoryservice)
    {
        $this->productservice = $productservice;
        $this->categoryservice = $categoryservice;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Product List';
        $products = $this->productservice->getAllProducts();

        return view('pages.product.index', compact('title', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add New Product';
        $categories = $this->categoryservice->getAllCategories();
        return view('pages.product.create', compact('title', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $data = $request->all();

            $this->productservice->hanldeProductImageUpload($data);
            // dd($data);
            $this->productservice->createProduct($data);
            return redirect()->route('product.index')->with('success', 'Product created successfully');
        } catch (\Throwable $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $title = 'Product Details';
        $product = $this->productservice->getProductById($product->id);

        return view('pages.product.detail', compact('title', 'product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $title = 'Edit Product';
        $product = $this->productservice->getProductById($product->id);
        $categories = $this->categoryservice->getAllCategories();
        return view('pages.product.edit', compact('title', 'product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $data = $request->all();
            $this->productservice->hanldeProductImageUpload($data);
            $this->productservice->updateProduct($product->id, $data);
            return redirect()->route('product.index')->with('success', 'Product updated successfully');
        } catch (\Throwable $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $this->productservice->deleteProduct($product->id);
            return redirect()->route('product.index')->with('success', 'Product deleted successfully');
        } catch (\Throwable $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }
}
