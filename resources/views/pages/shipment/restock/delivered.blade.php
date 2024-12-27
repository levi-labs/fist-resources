@extends('layouts.main.master')
<style>
    .my-active {
        background-color: #001F4D !important;
        color: #ffffff !important;
    }
</style>
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
                            <h4 class="card-title">{{ $title }}</h4>
                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <a href="{{ route('shipment.restockShipped') }}"
                                        class="btn btn-outline-secondary btn-sm {{ request()->routeIs('shipment.restockShipped*') ? 'my-active' : '' }}">Awaiting
                                        Shipment</a>
                                    <a href="{{ route('shipment.restockDelivered') }}"
                                        class="btn btn-outline-secondary btn-sm {{ request()->routeIs('shipment.restockDelivered*') ? 'my-active' : '' }}">Delivered</a>
                                    {{-- <a href="{{ route('restock.purchase.delivered') }}"
                                        class="btn btn-outline-secondary btn-sm {{ request()->routeIs('restock.purchase.delivered*') ? 'my-active' : '' }}">Shipped</a> --}}
                                    {{-- <a href="#" class="btn btn-primary btn-sm">Rejected</a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="float-end">
                            <form action="{{ route('shipment.restockDeliveredsearch') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" name="search"
                                        placeholder="Search..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-sm text-sm" type="submit">
                                            <svg class="icon-18" width="18" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                </circle>
                                                <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- <div class="row p-4">
                        <div class="col-sm-12">
                            <a href="{{ route('restock.purchase.create') }}" class="btn btn-primary btn-sm">Add
                                New</a>
                        </div>
                    </div> --}}
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table id="user-list-table" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr class="ligth">
                                        <th>#</th>
                                        <th>Invoice</th>
                                        <th>Tracking Number</th>
                                        {{-- <th style="min-width: 100px">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($shipments as $dt)
                                        <tr>
                                            {{-- <td class="text-center"><img
                                                    class="bg-primary-subtle rounded img-fluid avatar-40 me-3"
                                                    src="../../assets/images/shapes/01.png" alt="profile"></td> --}}
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $dt->invoice_number }}</td>
                                            <td>{{ $dt->tracking_number }}</td>
                                            {{-- <td>
                                                <div class="flex align-items-center list-user-action">
                                                    <a class="btn btn-sm btn-icon btn-success" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        href="{{ route('restock.purchase.show', $dt->id) }}"
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
                                                </div>
                                            </td> --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No Data Found</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                            <div class="px-2">
                                {{ $shipments->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
