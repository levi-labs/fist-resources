<?php


namespace App\Services;

use App\Models\ProposedProduct;
use Illuminate\Support\Facades\DB;

class ProposeProductService
{
    public function search($name)
    {
        return DB::table('proposed_products')->where('name', 'like', '%' . $name . '%')
            ->get();
    }
    public function gellAllProposedProduct()
    {
        return ProposedProduct::all();
    }

    public function getProposeProductById($id)
    {
        return ProposedProduct::where('id', $id)->first();
    }

    public function create($data)
    {
        try {
            return ProposedProduct::create($data);
        } catch (\Throwable $error) {
            throw $error;
        }
    }
    public function update($data, $id)
    {
        // dd($data, $id);
        try {
            $propose =  ProposedProduct::where('id', $id)->first();
            return $propose->update($data);
        } catch (\Throwable $error) {
            throw $error;
        }
    }
    public function delete($id)
    {
        try {
            return ProposedProduct::where('id', $id)->delete();
        } catch (\Throwable $error) {
            throw $error;
        }
    }
}
