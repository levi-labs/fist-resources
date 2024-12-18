<?php

namespace App\Services;

use \App\Models\ProposedRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProposeRequestService
{
    public function getAllProposeRequestPending()
    {
        $propose = DB::table('proposed_inventory_requests as proposed_requests')
            ->join('proposed_products', 'proposed_requests.proposed_product_id', '=', 'proposed_products.id')
            ->select('proposed_requests.request_code', DB::raw('MAX(proposed_requests.created_at) as latest_created_at'))
            ->where('proposed_requests.status', 'pending')
            ->groupBy('proposed_requests.request_code')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        return $propose;
    }

    public function searchProposeRequest($search)
    {
        $propose = DB::table('proposed_inventory_requests as proposed_requests')
            ->join('proposed_products', 'proposed_requests.proposed_product_id', '=', 'proposed_products.id')
            ->select('proposed_requests.request_code', DB::raw('MAX(proposed_requests.created_at) as latest_created_at'))
            ->where('proposed_products.name', 'like', '%' . $search . '%')
            ->groupBy('proposed_requests.request_code')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        return $propose;
    }

    public function getAllProposeRequestApproved()
    {
        $propose = DB::table('proposed_inventory_requests as proposed_requests')
            ->join('proposed_products', 'proposed_requests.proposed_product_id', '=', 'proposed_products.id')
            ->select('proposed_requests.request_code', DB::raw('MAX(proposed_requests.created_at) as latest_created_at'))
            ->where('proposed_requests.status', 'approved')
            ->groupBy('proposed_requests.request_code')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        return $propose;
    }

    public function getAllProposeRequestResubmitted()
    {
        $propose = DB::table('proposed_inventory_requests as proposed_requests')
            ->join('proposed_products', 'proposed_requests.proposed_product_id', '=', 'proposed_products.id')
            ->select('proposed_requests.request_code', DB::raw('MAX(proposed_requests.created_at) as latest_created_at'))
            ->where('proposed_requests.status', 'resubmitted')
            ->groupBy('proposed_requests.request_code')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        return $propose;
    }
    public function getALlProposeRequestRejected()
    {
        $propose = DB::table('proposed_inventory_requests as proposed_requests')
            ->join('proposed_products', 'proposed_requests.proposed_product_id', '=', 'proposed_products.id')
            ->select('proposed_requests.request_code', DB::raw('MAX(proposed_requests.created_at) as latest_created_at'))
            ->where('proposed_requests.status', 'rejected')
            ->groupBy('proposed_requests.request_code')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        return $propose;
    }

    public function create($id, $quantity, $notes = null)
    {
        try {
            $data = [];
            $request_code = date('ymd') . Str::random(12) . 'PR';
            foreach ($id as $key => $value) {
                $data[] = [
                    'proposed_product_id' => $value,
                    'quantity' => $quantity[$key],
                    'staff_id' => auth('web')->user()->id,
                    'request_code' => $request_code,
                    'date_requested' => date('Y-m-d'),
                    'notes' => strtolower($notes),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
            ProposedRequest::insert($data);
            // dd(session()->get('cart'));
        } catch (\Throwable $error) {
            throw $error;
        }
    }
}
