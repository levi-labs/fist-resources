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
                            <form action="{{ route('restock.inventory.createsearch') }}" method="POST">
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
                    <div class="row p-4">
                        <div class="col-sm-12">
                            <a href="{{ route('restock.inventory.index') }}" class="btn btn-primary btn-sm">Back</a>
                        </div>
                    </div>
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table id="user-list-table" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr class="ligth">
                                        <th>#</th>
                                        <th>name</th>
                                        <th>price</th>
                                        <th style="max-width: 100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($propose_products as $pproduct)
                                        <tr>
                                            {{-- <td class="text-center"><img
                                                    class="bg-primary-subtle rounded img-fluid avatar-40 me-3"
                                                    src="../../assets/images/shapes/01.png" alt="profile"></td> --}}
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pproduct->name }}</td>
                                            <td>{{ formatNumber($pproduct->price) }}</td>
                                            <td>

                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('propose.inventory.add', $pproduct->id) }}">Add</a>

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
                                {{ $propose_products->links('pagination::bootstrap-5') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
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
                            <h4 class="card-title">Request Product List</h4>
                        </div>
                    </div>
                    <form action="{{ route('restock.inventory.store') }}" method="POST">
                        @csrf
                        <div class="row p-4">
                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            </div>
                        </div>

                        <div class="card-body px-0">
                            <div class="table-responsive">
                                <table id="user-list-table" class="table table-striped" role="grid"
                                    data-bs-toggle="data-table">
                                    <thead>
                                        <tr class="ligth">
                                            <th>#</th>
                                            <th>name</th>
                                            <th>quantity</th>
                                            <th>price</th>
                                            <th style="max-width: 100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @forelse (session('cart', []) as $item)
                                            @php
                                                $total += $item['price'] * $item['quantity'];
                                            @endphp
                                            <tr>
                                                {{-- <td class="text-center"><img
                                                    class="bg-primary-subtle rounded img-fluid avatar-40 me-3"
                                                    src="../../assets/images/shapes/01.png" alt="profile"></td> --}}
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item['name'] }}</td>
                                                <td>
                                                    <input type="hidden" name="id[]" id=""
                                                        value="{{ $item['id'] }}">
                                                    <input class="text-center" type="number" name="quantity[]"
                                                        id="" value="{{ $item['quantity'] }}" min="1">
                                                </td>
                                                <td>{{ formatNumber($item['price']) }}</td>
                                                <td>

                                                    <a class="btn btn-sm btn-danger"
                                                        href="{{ route('propose.inventory.remove', $item['id']) }}"
                                                        onclick="return confirm('Are you sure?')">Delete</a>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No Data Found</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end">Total:</td>
                                            <td>{{ formatNumber($total) ?? 0 }}</td>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="notes">Note: <span
                                            class="text-danger">(optional)</span></label>
                                    <textarea class="form-control" id="notes" rows="2" name="notes"></textarea>
                                    @error('notes')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- <div class="float-end">
    <form action="{{ route('restock.inventory.createsearch') }}" method="POST">
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
</div> --}}
