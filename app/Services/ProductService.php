<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function hanldeProductImageUpload(&$data)
    {
        if (array_key_exists('image', $data)) {
            $file = $data['image']->store('images', 'public');
            $data['image'] = $file;
        }
    }

    public function searchProducts($search)
    {
        return Product::with('category')->where('name', 'like', '%' . $search . '%')->paginate();
    }
    public function getAllProducts($paginate = null)
    {
        if ($paginate === null) {
            $products =  DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.*', 'categories.name as category_name')
                ->get();

            return $products;
        } else {
            $products =  DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.*', 'categories.name as category_name')
                ->paginate(10);

            return $products;
        }
    }


    public function getProductById($id)
    {
        $product = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->where('products.id', $id)
            ->first();

        // $productModel = new Product((array)$product);
        if ($product) {
            $product->image_url = asset('storage/' . $product->image);
        }
        return $product;
    }
    public function createProduct($data)
    {
        try {

            return Product::create($data);
        } catch (\Throwable $error) {
            throw $error;
        }
    }

    public function updateProduct($id, $data)
    {
        try {
            $product = Product::findOrFail($id);
            if (isset($data['image'])) {

                if ($product->image !== null) {
                    handle_delete_file($product->image);
                }
            }

            return $product->update($data);
        } catch (\Throwable $error) {
            throw $error;
        }
    }

    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->image !== null) {
                handle_delete_file($product->image);
            }
            return $product->delete();
        } catch (\Throwable $error) {
            throw $error;
        }
    }
}
