<?php


namespace App\Services;

use App\Models\ProposedProduct;

class ProposeProductService
{
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
        try {
            return ProposedProduct::where('id', $id)->update($data);
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
