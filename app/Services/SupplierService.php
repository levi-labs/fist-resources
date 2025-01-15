<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\User;
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
        DB::beginTransaction();
        try {

            $supplier = Supplier::create($data);
            $user = [
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'role' => 'supplier',
                'password' => bcrypt('password'),
                'supplier_id' => $supplier->id,
            ];
            User::create($user);
            DB::commit();
        } catch (\Throwable $error) {
            DB::rollBack();
            throw $error;
        }
    }

    public function updateSupplier($id, $data)
    {
        DB::beginTransaction();
        try {
            $supplier = Supplier::findOrFail($id);
            $user = [
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'role' => 'supplier',
                'password' => bcrypt('password'),
                'supplier_id' => $supplier->id,
            ];
            $supplier->update($data);
            User::where('supplier_id', $supplier->id)->update($user);
            DB::commit();
        } catch (\Throwable $error) {
            DB::rollBack();
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
