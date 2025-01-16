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
                                    <a href="{{ route('restock.purchase.index') }}"
                                        class="btn btn-outline-secondary btn-sm {{ request()->routeIs('restock.purchase.index*') ? 'my-active' : '' }}">Awaiting
                                        Shipment</a>
                                    <a href="{{ route('restock.purchase.shipped') }}"
                                        class="btn btn-outline-secondary btn-sm {{ request()->routeIs('restock.purchase.shipped*') ? 'my-active' : '' }}">Shipped</a>
                                    <a href="{{ route('restock.purchase.delivered') }}"
                                        class="btn btn-outline-secondary btn-sm {{ request()->routeIs('restock.purchase.delivered*') ? 'my-active' : '' }}">Delivered</a>
                                    {{-- <a href="#" class="btn btn-primary btn-sm">Rejected</a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="float-end">
                            <form action="{{ route('restock.purchase.search') }}" method="POST">
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
                                        <th>Status</th>
                                        <th style="min-width: 100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $dt)
                                        <tr>
                                            {{-- <td class="text-center"><img
                                                    class="bg-primary-subtle rounded img-fluid avatar-40 me-3"
                                                    src="../../assets/images/shapes/01.png" alt="profile"></td> --}}
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $dt->invoice_number }}</td>
                                            <td>{{ $dt->status }}</td>
                                            <td>
                                                <div class="flex align-items-center list-user-action">
                                                    <a class="btn btn-sm btn-icon btn-success" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        href="{{ route('restock.purchase.show', $dt->id) }}"
                                                        aria-label="Detail" data-bs-original-title="">
                                                        <span class="btn-inner">
                                                            <svg class="icon-18" width="18" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <circle cx="11.7669" cy="11.7666" r="8.98856"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round"></circle>
                                                                <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor"
                                                                    stroke-width="1.5" stroke-linecap="round"
                                                                    stroke-linejoin="round"></path>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No Data Found</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                            <div class="px-2">
                                {{ $data->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
