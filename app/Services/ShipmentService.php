<?php

namespace App\Services;

use App\Models\Shipment;

class ShipmentService
{

    public function create($data)
    {
        try {
            Shipment::create($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function reject($id)
    {
        try {
            Shipment::where('id', $id)->update(['status' => 'rejected']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
