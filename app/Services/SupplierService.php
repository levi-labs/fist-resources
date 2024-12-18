<?php

namespace App\Services;

use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class SupplierService
{
    public function searchSuppliers($name)
    {
        $suppliers = DB::table('suppliers')->where('name', 'like', '%' . $name . '%')->get();
        $suppliers = Supplier::hydrate($suppliers->toArray());
        return $suppliers;
    }

    public function getAllSuppliers()
    {
        $suppliers = DB::table('suppliers')->get();
        $suppliers = Supplier::hydrate($suppliers->toArray());
        return $suppliers;
    }

    public function getSupplierById($id)
    {
        $supplier = DB::table('suppliers')->where('id', $id)->first();
        return $supplier;
    }

    public function createSupplier($data)
    {
        try {
            return Supplier::create($data);
        } catch (\Throwable $error) {
            throw $error;
        }
    }

    public function updateSupplier($id, $data)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            return $supplier->update($data);
        } catch (\Throwable $error) {
            throw $error;
        }
    }

    public function deleteSupplier($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            return $supplier->delete();
        } catch (\Throwable $error) {
            throw $error;
        }
    }
}
