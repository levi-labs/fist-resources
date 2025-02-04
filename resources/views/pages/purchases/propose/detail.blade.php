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
                                $type = 'propose';
                                $request_code = $purchases[0]->request_code;
                                $status = $purchases[0]->status;
                                $order_date = $purchases[0]->order_date;
                                $requested_by = $purchases[0]->staff_name;
                                // $role = $restocks[0]->staff_role;
                                $approved_by = $purchases[0]->procurement_name;
                                $supplier_name = $purchases[0]->supplier_name;
                                $supplier_address = $purchases[0]->supplier_address;
                                // $approved_role = $purchases[0]->procurement_role;
                                // dd($restocks);
                            @endphp
                            <h4 class="card-title">{{ $title }}</h4>
                            @if ($status == 'pending')
                                <a href="{{ route('restock.inventory.index') }}"
                                    class="btn btn-primary btn-sm mt-1">Back</a>
                            @elseif ($status == 'approved')
                                <a href="{{ route('restock.inventory.approved') }}"
                                    class="btn btn-primary btn-sm mt-1">Back</a>
                            @elseif ($status == 'resubmitted')
                                <a href="{{ route('restock.inventory.resubmitted') }}"
                                    class="btn btn-primary btn-sm mt-1">Back</a>
                            @elseif($status == 'rejected')
                                <a href="{{ route('restock.inventory.rejected') }}"
                                    class="btn btn-primary btn-sm mt-1">Back</a>
                            @endif
                        </div>
                        <div class="float-end">
                            {{-- @if ($status == 'pending') --}}
                            @if ($status == 'pending' || $status == 'resubmitted')
                                {{-- <a href="{{ route('restock.inventory.destroy', $request_code) }}"
                                    class="btn btn-danger btn-sm mt-4">Delete</a> --}}
                            @endif
                            @if ($status == 'approved' && auth('web')->user()->role == 'admin')
                                {{-- <a href="{{ route('restock.inventory.destroy', $request_code) }}"
                                    class="btn btn-danger btn-sm mt-4">Delete</a> --}}
                            @endif

                            <a href="{{ route('propose.purchase.print', $purchases[0]->propose_purchase_order_id) }}"
                                class="btn btn-secondary btn-sm mt-4" target="_blank">Print</a>


                            {{-- @endif --}}
                        </div>
                    </div>
                    <div class="row p-4">
                        <div class="col-md-9">
                            <h6 class="text-muted">Requested By: {{ $requested_by }}</h6>
                            {{-- <h6 class="text-muted">As: {{ ucfirst($role) }}</h6> --}}
                            <hr class="hr-horizontal dark my-2">
                            <h6 class="text-muted">Approved By: {{ $approved_by ?? '-' }}</h6>
                            {{-- <h6 class="text-muted">As: {{ ucfirst($approved_role ?? '-') }}</h6> --}}
                            <hr class="hr-horizontal dark">
                            <h6 class="text-muted">Supplier: {{ strtoupper($supplier_name) }}</h6>
                            <hr class="hr-horizontal dark">
                            <h6 class="text-muted">Address: {{ ucfirst($supplier_address) }}</h6>
                            {{-- <p class="text-muted text-wrap">Note: {{ $restocks[0]->note ?? '-' }}</p> --}}
                            {{-- <p class="text-muted text-wrap">Reason: {{ $restocks[0]->reason ?? '-' }}</p> --}}
                        </div>
                        <div class="col-md-3 text-start mx-auto align-items-center">
                            <h6 class="text-muted">Request Code: {{ $request_code }}</h6>
                            <h6 class="text-muted my-2">Order date : {{ $order_date }}</h6>
                            <hr class="hr-horizontal dark my-2">
                            <h6 class="text-muted align-items-center">Status: &nbsp;<span
                                    class="badge rounded-pill bg-dark p-2">{{ $status }}</span></h6>
                            {{-- <h6 class="text-muted my-2">Resubmit:
                            {{ $restocks[0]->resubmit_count > 0 ? $restocks[0]->resubmit_count : '-' }} </h6> --}}

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
                                        <th style="min-width: 100px">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total = 0;
                                    @endphp
                                    {{-- "restock_purchase_order_id" => 1
                                    "staff_name" => "kelvin goyette"
                                    "procument_name" => "prof. bethel harris"
                                    "order_date" => "2024-12-20"
                                    "delivery_date" => "2024-12-21"
                                    "total_price" => "50000000.00"
                                    "status" => "approved"
                                    "invoice_number" => "tLVsh/4/241220"
                                    "request_code" => "241220JmgGsBYuzjlv"
                                    "supplier_name" => "pt.abc-group"
                                    "supplier_address" => "jalan kebangsaan"
                                    "quantity" => 2
                                    "product_price" => "20000000.00"
                                    "product_name" => "macbook pro m4" --}}
                                    @forelse ($purchases as $purchase)
                                        @php
                                            $total += $purchase->product_price * $purchase->quantity;
                                        @endphp
                                        <tr>
                                            {{-- <td class="text-center"><img
                                                    class="bg-primary-subtle rounded img-fluid avatar-40 me-3"
                                                    src="../../assets/images/shapes/01.png" alt="profile"></td> --}}
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="font-weight-bold">
                                                <h6>{{ $purchase->product_name }}</h6>
                                                <span class="text-muted">SKU:{{ $purchase->product_sku }}</span>
                                            </td>
                                            <td>{{ $purchase->quantity }}</td>
                                            <td>{{ formatNumber($purchase->product_price) }}</td>
                                            <td>
                                                {{ formatNumber($purchase->product_price * $purchase->quantity) }}
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
                                        <td colspan="4" class="text-end">Total:</td>
                                        <td colspan="2">{{ formatNumber($total) ?? 0 }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-md-12 text-end">
                @if (auth('web')->user()->role == 'supplier')
                    @if ($status === 'pending' || $status === 'awaiting shipment')
                        <button type="button" class="btn btn-md btn-icon btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Process
                        </button>
                    @endif
                @endif

            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        @include('components.modal-procurement.shipment-restock', [
            'id' => $purchases[0]->propose_purchase_order_id,
            'type' => $type,
        ])
    </div>
@endsection
