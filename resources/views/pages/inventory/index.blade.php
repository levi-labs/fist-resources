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
                            <h4 class="card-title">{{ $title }}</h4>
                        </div>
                        <div class="float-end">
                            {{-- <form action="{{ route('product.search') }}" method="POST">
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
                            </form> --}}
                        </div>
                    </div>
                    <div class="row p-4">
                        <div class="col-sm-12">
                            {{-- <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm">Add
                                New</a> --}}
                        </div>
                    </div>
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table id="user-list-table" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr class="ligth">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Stock</th>
                                        <th style="min-width: 100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($inventories as $product)
                                        <tr>
                                            {{-- <td class="text-center"><img
                                                    class="bg-primary-subtle rounded img-fluid avatar-40 me-3"
                                                    src="../../assets/images/shapes/01.png" alt="profile"></td> --}}
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $product->name }} <br>
                                                <span>SKU : {{ $product->sku }}</span>
                                            </td>
                                            <td>{{ $product->total_stock }}</td>
                                            <td>
                                                <div class="flex align-items-center list-user-action">
                                                    <a class="btn btn-sm btn-icon btn-success" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        href="{{ route('product.show', $product->product_id) }}"
                                                        aria-label="Detail" data-bs-original-title="Detail">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
