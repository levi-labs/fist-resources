<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $title = 'Inventory List';
        $inventories = Inventory::join('products', 'inventories.product_id', '=', 'products.id')
            ->select('product_id', 'products.name', 'products.sku', DB::raw('SUM(stock) as total_stock'))
            ->groupBy('product_id', 'products.name', 'products.sku')
            ->get();
        return view('pages.inventory.index', compact('title', 'inventories'));
    }
}
