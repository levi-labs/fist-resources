<?php


namespace App\Services;

use App\Http\Requests\RestockInventoryRequest;
use App\Models\Notification;
use App\Models\RestockInventory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RestockInventoryService
{

    public function searchRestockInventory($search)
    {

        return RestockInventory::where('request_code', 'like', '%' . $search . '%')
            ->select('request_code')
            ->distinct()
            ->get();
    }
    public function getAllRestockInventoryPending()
    {

        $restocks = DB::table('restock_inventory_requests as restock')
            ->select('restock.request_code', DB::raw('MAX(restock.created_at) as latest_created_at'))
            ->where('restock.status', 'pending')
            ->groupBy('restock.request_code')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        // dd($restocks->toSql(), $restocks->get());
        return $restocks;
    }
    public function getAllRestockInventoryApproved()
    {

        $restocks = DB::table('restock_inventory_requests as restock')
            ->select('restock.request_code', DB::raw('MAX(restock.created_at) as latest_created_at'))
            ->where('restock.status', 'approved')
            ->groupBy('restock.request_code')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        // dd($restocks->toSql(), $restocks->get());
        return $restocks;
    }
    public function getAllRestockInventoryResubmitted()
    {
        $restocks = DB::table('restock_inventory_requests as restock')
            ->select('restock.request_code', DB::raw('MAX(restock.created_at) as latest_created_at'))
            ->where('restock.status', 'resubmitted')
            ->groupBy('restock.request_code')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        // dd($restocks->toSql(), $restocks->get());
        return $restocks;
    }

    public function getAllRestockInventoryRejected()
    {
        $restocks = DB::table('restock_inventory_requests as restock')
            ->select('restock.request_code', DB::raw('MAX(restock.created_at) as latest_created_at'))
            ->where('restock.status', 'rejected')
            ->groupBy('restock.request_code')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        return $restocks;
    }

    public function create($id, $quantity, $notes = null)
    {
        DB::beginTransaction();
        try {
            // DB::table('restock_inventory_requests')->truncate();
            $data = [];
            $request_code = date('ymd') . Str::random(12);
            foreach ($id as $key => $value) {
                $data[] = [
                    'product_id' => $value,
                    'quantity' => $quantity[$key],
                    'staff_id' => auth('web')->user()->id,
                    'request_code' => $request_code,
                    'date_requested' => date('Y-m-d'),
                    'notes' => strtolower($notes),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
            RestockInventory::insert($data);
            Notification::create([
                'user_role' => 'procurement',
                'request_related' => $request_code,
                'notification_type' => 'request',
                'order_type' => 'request restock',
                'message' => 'New Request Item',
            ]);
            DB::commit();
            // dd(session()->get('cart'));
        } catch (\Throwable $error) {
            DB::rollBack();
            throw $error;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  array  $id
     * @param  array  $idProduct
     * @param  array  $quantity
     * @param  string  $request_code
     * @param  string|null  $notes
     * @return void
     */
    public function update($id, $idProduct, $quantity, $request_code, $notes)
    {
        // dd($id, $idProduct, $quantity, $request_code, $notes);
        try {
            // $dataUpdateOrInsert = [];
            foreach ($id as $key => $value) {
                $check = RestockInventory::where('id', $value)->first();
                $existRecord = RestockInventory::where('request_code', $request_code)->first();
                $dataUpdateOrInsert = [
                    'product_id' => $idProduct[$key],
                    'quantity' => $quantity[$key],
                    'staff_id' => auth('web')->user()->id,
                    // 'status' =>  $check->status === 'resubmitted' ? 'resubmitted' : 'pending',
                    'notes' => strtolower($notes) ?? null,
                    'updated_at' => Carbon::now(),
                ];

                if ($check === null) {

                    // dd($existRecord);
                    $dataUpdateOrInsert['procurement_id'] = $existRecord->procurement_id;
                    $dataUpdateOrInsert['request_code'] = $existRecord->request_code;
                    $dataUpdateOrInsert['reason'] = $existRecord->reason;
                    $dataUpdateOrInsert['date_requested'] = $existRecord->date_requested;
                    if ($existRecord->status === 'resubmitted') {
                        $dataUpdateOrInsert['resubmit_count'] = $existRecord->resubmit_count;
                        $dataUpdateOrInsert['status'] = 'resubmitted';
                    }
                    RestockInventory::create($dataUpdateOrInsert);
                }
                if ($check !== null && $check->status === 'resubmitted') {

                    $dataUpdateOrInsert['status'] = 'resubmitted';
                    $dataUpdateOrInsert['resubmit_count'] = $check->resubmit_count + 1;
                }

                if ($check) {
                    RestockInventory::where('id', $value)->update($dataUpdateOrInsert);
                }

                // RestockInventory::updateOrInsert(
                //     [
                //         'id' => $value,
                //     ],
                //     $dataUpdateOrInsert
                // );
            }
            // dd($dataUpdateOrInsert);
        } catch (\Throwable $error) {
            throw $error;
        }
    }

    public function getRestockInventoryByRequestCode($request_code)
    {
        $restock = DB::table('restock_inventory_requests as restock')
            ->join('products as product', 'product.id', '=', 'restock.product_id')
            ->join('users as staff', 'staff.id', '=', 'restock.staff_id')
            ->leftJoin('users as procurement', 'procurement.id', '=', 'restock.procurement_id')
            ->select(
                'restock.*',
                'product.name as product_name',
                'product.sku as product_sku',
                'product.price as product_price',
                'staff.name as staff_name',
                'staff.role as staff_role',
                'procurement.name as procurement_name',
                'procurement.role as procurement_role'
            )
            ->where('restock.request_code', $request_code)
            ->get();
        return $restock;
    }

    public function approve($request_code, $reason = null)
    {
        try {
            if ($reason !== null && $reason !== '') {
                RestockInventory::where('request_code', $request_code)->update(
                    [
                        'reason' => $reason,
                        'status' => 'approved',
                        'procurement_id' => auth('web')->user()->id
                    ]
                );
            } else {
                RestockInventory::where('request_code', $request_code)->update(
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

    public function resubmit($request_code): void
    {
        try {

            if (RestockInventory::where('request_code', $request_code)->doesntExist()) {
                throw new \Exception('Restock inventory request does not exist');
            }

            RestockInventory::where('request_code', $request_code)->update(
                [
                    'status' => 'resubmitted',
                    'procurement_id' => auth('web')->user()->role === 'procurement' ? auth('web')->user()->id : null,
                    // 'resubmit_count' => DB::raw('resubmit_count + 1')
                ]
            );
        } catch (\Throwable $error) {
            throw $error;
        }
    }

    public function reject($request_code, $reason = null)
    {
        try {
            if ($reason !== null && $reason !== '') {
                RestockInventory::where('request_code', $request_code)->update(
                    [
                        'reason' => $reason,
                        'status' => 'rejected',
                        'procurement_id' => auth('web')->user()->id
                    ]
                );
            } else {
                RestockInventory::where('request_code', $request_code)->update(
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


    public function delete($request_code)
    {
        try {
            RestockInventory::where('request_code', $request_code)->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function deleteById($id)
    {
        try {
            $data = RestockInventory::where('id', $id)->first();
            if ($data) {
                $data->delete();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
