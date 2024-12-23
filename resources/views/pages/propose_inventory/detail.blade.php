@extends('layouts.main.master')
@section('content')
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            @if (session('success'))
                                <div class="mx-3 my-3 alert alert-left alert-success" role="alert">
                                    <span> {{ session('success') }}</span>
                                </div>
                            @elseif (session('error'))
                                <div class="mx-3 my-3 alert alert-left alert-danger" role="alert">
                                    <span> {{ session('error') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="header-title">
                            @php
                                $request_code = $proposed[0]->request_code;
                                $status = $proposed[0]->status;
                                $date_requested = $proposed[0]->date_requested;
                                $requested_by = $proposed[0]->staff_name;
                                $role = $proposed[0]->staff_role;
                                $approved_by = $proposed[0]->procurement_name;
                                $approved_role = $proposed[0]->procurement_role;
                                // dd($restocks);
                            @endphp
                            <h4 class="card-title">{{ $title }}</h4>
                            @if ($status == 'pending')
                                <a href="{{ route('propose.inventory.index') }}"
                                    class="btn btn-primary btn-sm mt-1">Back</a>
                            @elseif ($status == 'approved')
                                <a href="{{ route('propose.inventory.approved') }}"
                                    class="btn btn-primary btn-sm mt-1">Back</a>
                            @elseif ($status == 'resubmitted')
                                <a href="{{ route('propose.inventory.resubmitted') }}"
                                    class="btn btn-primary btn-sm mt-1">Back</a>
                            @elseif($status == 'rejected')
                                <a href="{{ route('propose.inventory.rejected') }}"
                                    class="btn btn-primary btn-sm mt-1">Back</a>
                            @endif
                        </div>
                        <div class="float-end">
                            {{-- @if ($status == 'pending') --}}
                            @if ($status == 'pending' || $status == 'resubmitted')
                                <a href="{{ route('propose.inventory.destroy', $request_code) }}"
                                    class="btn btn-danger btn-sm mt-4">Delete</a>
                            @endif
                            @if ($status == 'approved' && auth('web')->user()->role == 'admin')
                                <a href="{{ route('propose.inventory.destroy', $request_code) }}"
                                    class="btn btn-danger btn-sm mt-4">Delete</a>
                            @endif
                            <a href="{{ route('propose.inventory.print', $request_code) }}"
                                class="btn btn-secondary btn-sm mt-4" target="_blank">Print</a>
                            {{-- @endif --}}
                        </div>
                    </div>
                    <div class="row p-4 justify-content-between">
                        <div class="col-md-9">
                            <h6 class="text-muted">Requested By: {{ $requested_by }}</h6>
                            <h6 class="text-muted">As: {{ ucfirst($role) }}</h6>
                            <hr class="hr-horizontal dark my-2">
                            <h6 class="text-muted">Approved By: {{ $approved_by ?? '-' }}</h6>
                            <h6 class="text-muted">As: {{ ucfirst($approved_role ?? '-') }}</h6>
                            <hr class="hr-horizontal dark">
                            <p class="text-muted text-wrap">Note: {{ $proposed[0]->note ?? '-' }}</p>
                            <p class="text-muted text-wrap">Reason: {{ $proposed[0]->reason ?? '-' }}</p>
                        </div>
                        <div class="col-md-2 text-start mx-auto align-items-center">

                            <h6 class="text-muted text-sm ">Request Code: {{ $request_code }}</h6>
                            <h6 class="text-muted my-2">Date Requested: {{ $date_requested }}</h6>
                            <hr class="hr-horizontal dark my-2">
                            <h6 class="text-muted align-items-center">Status: &nbsp;<span
                                    class="badge rounded-pill bg-dark p-2">{{ $status }}</span></h6>
                            <h6 class="text-muted my-2">Resubmit:
                                {{ $proposed[0]->resubmit_count > 0 ? $proposed[0]->resubmit_count : '-' }} </h6>



                        </div>
                    </div>
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table id="user-list-table" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr class="ligth">
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th style="min-width: 100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total = 0;
                                    @endphp

                                    @forelse ($proposed as $propose)
                                        @php
                                            $total += $propose->product_price * $propose->quantity;
                                        @endphp
                                        <tr>
                                            {{-- <td class="text-center"><img
                                                    class="bg-primary-subtle rounded img-fluid avatar-40 me-3"
                                                    src="../../assets/images/shapes/01.png" alt="profile"></td> --}}
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="font-weight-bold">
                                                <h6>{{ $propose->product_name }}</h6>
                                                <span class="text-muted">SKU:{{ $propose->product_sku }}</span>
                                            </td>
                                            <td>{{ $propose->quantity }}</td>
                                            <td>{{ formatNumber($propose->product_price) }}</td>
                                            <td>
                                                <div class="flex align-items-center list-user-action">
                                                    <a class="btn btn-sm btn-icon btn-success" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        href="{{ route('propose.product.show', $propose->proposed_product_id) }}"
                                                        aria-label="Detail" data-bs-original-title="Detail">
                                                        <span class="btn-inner">
                                                            <svg class="icon-20" width="20" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                                                <path d="M19.2036 8.66919V12.6792" stroke="currentColor"
                                                                    stroke-width="1.5" stroke-linecap="round"
                                                                    stroke-linejoin="round"></path>
                                                                <path d="M21.2497 10.6741H17.1597" stroke="currentColor"
                                                                    stroke-width="1.5" stroke-linecap="round"
                                                                    stroke-linejoin="round"></path>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                    {{-- 
                                                    <a class="btn btn-sm btn-icon btn-danger" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        href="{{ route('restock.inventory.deleteItem', $restock->id) }}"
                                                        aria-label="Delete" data-bs-original-title="Delete"
                                                        onclick="return confirm('Are you sure?')">
                                                        <span class="btn-inner">
                                                            <svg class="icon-20" width="20" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg"
                                                                stroke="currentColor">
                                                                <path
                                                                    d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                                                <path d="M20.708 6.23975H3.75" stroke="currentColor"
                                                                    stroke-width="1.5" stroke-linecap="round"
                                                                    stroke-linejoin="round"></path>
                                                                <path
                                                                    d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                                            </svg>
                                                        </span>
                                                    </a> --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No Data Found</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end">Total:</td>
                                        <td colspan="2">{{ formatNumber($total) ?? 0 }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (auth('web')->user()->role === 'procurement')
            @include('components.action-in-detail-propose.action-procurement', [
                'params' => $request_code,
                'status' => $status,
            ])
        @endif
    </div>
@endsection
