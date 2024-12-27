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

    public function getAllProposeNotInRequest()
    {
        $propose_product = DB::table('proposed_products')
            ->whereNotIn('id', function ($query) {
                $query->select('proposed_product_id')
                    ->from('proposed_inventory_requests');
            })
            ->where('status', 'unregistered')
            ->paginate(10);


        return $propose_product;
    }
    public function searchProduct($search)
    {
        $propose_product = DB::table('proposed_products')
            ->where('name', 'like', '%' . $search . '%')
            ->whereNotIn('id', function ($query) {
                $query->select('proposed_product_id')
                    ->from('proposed_inventory_requests');
            })
            ->where('status', 'unregistered')
            ->paginate(10);

        return $propose_product;
    }
    public function getAllProposeProductEdit($request_code)
    {
        $propose_product = DB::table('proposed_products')
            ->whereIn('id', function ($query) {
                $query->select('proposed_product_id')
                    ->from('proposed_inventory_requests')
                    ->where('request_code',);
            })
            ->where('status', 'unregistered')
            ->paginate(10);


        return $propose_product;
    }

    public function searchProposeRequest($search)
    {
        $propose = ProposedRequest::where('request_code', 'like', '%' . $search . '%')
            ->select('request_code')
            ->distinct()
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

    public function update($id, $product_id, $quantity, $request_code, $notes = null)
    {
        try {
            foreach ($id as $key => $value) {
                $check = ProposedRequest::where('id', $value)->first();
                $existRecord = ProposedRequest::where('request_code', $request_code)->first();
                $dataUpdateOrInsert = [
                    'proposed_product_id' => $product_id[$key],
                    'quantity' => $quantity[$key],
                    'staff_id' => auth('web')->user()->id,
                    'notes' => strtolower($notes) ?? null,
                    'updated_at' => Carbon::now(),
                ];

                if ($check === null) {
                    $dataUpdateOrInsert['procurement_id'] = $existRecord->procurement_id;
                    $dataUpdateOrInsert['request_code'] = $existRecord->request_code;
                    $dataUpdateOrInsert['date_requested'] = $existRecord->date_requested;
                    if ($existRecord->status === 'resubmitted') {
                        $dataUpdateOrInsert['resubmit_count'] = $existRecord->resubmit_count;
                        $dataUpdateOrInsert['status'] = 'resubmitted';
                        $dataUpdateOrInsert['reason'] = $existRecord->reason;
                    }
                    ProposedRequest::create($dataUpdateOrInsert);
                }
                if ($check !== null && $check->status === 'resubmitted') {
                    $dataUpdateOrInsert['status'] = 'resubmitted';
                    $dataUpdateOrInsert['resubmit_count'] = $check->resubmit_count + 1;
                }

                if ($check) {
                    ProposedRequest::where('id', $value)->update($dataUpdateOrInsert);
                }
            }
        } catch (\Throwable $error) {
            throw $error;
        }
    }
    public function resubmit($request_code, $reason = null)
    {
        try {
            if (ProposedRequest::where('request_code', $request_code)->doesntExist()) {
                throw new \Exception('Propose request not found');
            }
            if ($reason !== null && $reason !== '') {
                ProposedRequest::where('request_code', $request_code)->update(
                    [
                        'reason' => $reason,
                        'status' => 'resubmitted',
                        'procurement_id' => auth('web')->user()->role === 'procurement' ? auth('web')->user()->id : null,
                    ]
                );
            } else {
                ProposedRequest::where('request_code', $request_code)->update(
                    [
                        'status' => 'resubmitted',
                        'procurement_id' => auth('web')->user()->role === 'procurement' ? auth('web')->user()->id : null,
                    ]
                );
            }
        } catch (\Throwable $error) {
            throw $error;
        }
    }
    public function reject($request_code, $reason = null)
    {
        try {
            if ($reason !== null && $reason !== '') {
                ProposedRequest::where('request_code', $request_code)->update(
                    [
                        'reason' => $reason,
                        'status' => 'rejected',
                        'procurement_id' => auth('web')->user()->id
                    ]
                );
            } else {
                ProposedRequest::where('request_code', $request_code)->update(
                    [
                        'status' => 'rejected',
                        'procurement_id' => auth('web')->user()->id
                    ]
                );
            }
        } catch (\Throwable $error) {
            throw $error;
        }
    }

    public function getByRequestCode($request_code)
    {
        $propose = DB::table('proposed_inventory_requests as pr')
            ->join('proposed_products as pp', 'pr.proposed_product_id', '=', 'pp.id')
            ->join('users as staff', 'staff.id', '=', 'pr.staff_id')
            ->leftJoin('users as procurement', 'procurement.id', '=', 'pr.procurement_id')
            ->select(
                'pp.id as product_id',
                'pp.name as product_name',
                'pp.price as product_price',
                'pp.sku as product_sku',
                'staff.name as staff_name',
                'staff.role as staff_role',
                'procurement.name as procurement_name',
                'procurement.role as procurement_role',
                'pr.*',

            )
            ->where('pr.request_code', $request_code)
            ->get();

        return $propose;
    }

    public function approve($request_code, $reason = null)
    {
        try {
            if ($reason !== null && $reason !== '') {
                ProposedRequest::where('request_code', $request_code)->update(
                    [
                        'reason' => $reason,
                        'status' => 'approved',
                        'procurement_id' => auth('web')->user()->id
                    ]
                );
            } else {
                ProposedRequest::where('request_code', $request_code)->update(
                    [
                        'status' => 'approved',
                        'procurement_id' => auth('web')->user()->id
                    ]
                );
            }
        } catch (\Throwable $error) {
            throw $error;
        }
    }

    public function delete($request_code)
    {
        try {
            ProposedRequest::where('request_code', $request_code)->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function deleteById($id)
    {
        try {
            $data = ProposedRequest::where('id', $id)->first();

            if ($data) {
                $data->delete();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
