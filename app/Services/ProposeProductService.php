<?php


namespace App\Services;

use App\Models\Product;
use App\Models\ProposedProduct;
use Illuminate\Support\Facades\DB;

class ProposeProductService
{
    public function hanldeProductImageUpload(&$data)
    {
        if (array_key_exists('image', $data)) {
            $file = $data['image']->store('images', 'public');
            $data['image'] = $file;
        }
    }

    public function search($name)
    {
        return DB::table('proposed_products')->where('name', 'like', '%' . $name . '%')
            ->get();
    }
    public function getAllProposedProduct()
    {
        return ProposedProduct::all();
    }

    public function getAllPaginateProposedProduct()
    {
        return ProposedProduct::paginate(10);
    }

    public function getProposeProductById($id)
    {
        return ProposedProduct::findOrFail($id);
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
            if (isset($data['image'])) {

                if ($propose->image !== null) {
                    handle_delete_file($propose->image);
                }
            }
            return $propose->update($data);
        } catch (\Throwable $error) {
            throw $error;
        }
    }
    public function delete($id)
    {
        try {
            $propose =  ProposedProduct::where('id', $id)->first();
            if ($propose->image !== null) {
                handle_delete_file($propose->image);
            }
            return $propose->delete();
        } catch (\Throwable $error) {
            throw $error;
        }
    }
}
