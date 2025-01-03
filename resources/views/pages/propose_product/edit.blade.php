@extends('layouts.main.master')

@section('content')
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $title }}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="mb-3 alert alert-left alert-success alert-dismissible fade show" role="alert">
                                <span> {{ session('success') }}</span>
                            </div>
                        @elseif (session('error'))
                            <div class="mb-3 alert alert-left alert-danger alert-dismissible fade show" role="alert">
                                <span> {{ session('error') }}</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('propose.product.update', $propose->id) }}"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="name">Name:</label>
                                <input type="name" class="form-control" id="name" name="name"
                                    value="{{ old('name', $propose->name) }}">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="sku">SKU:</label>
                                {{-- <span class="text-danger text-sm">(optional)</span> --}}
                                <input type="text" class="form-control" id="sku" name="sku"
                                    value="{{ old('sku', $propose->sku) }}">
                                @error('sku')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="brand">Brand:</label>
                                <span class="text-muted text-sm">Apple</span>
                                <input type="text" class="form-control" id="brand" name="brand"
                                    value="{{ old('brand', $propose->brand) }}">
                                @error('brand')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="model">Model:</label>
                                <span class="text-muted text-sm">15 Promax</span>
                                <input type="text" class="form-control" id="model" name="model"
                                    value="{{ old('model', $propose->model) }}">
                                @error('model')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="size">Size:</label>
                                <span class="text-muted text-sm">(optional) </span>
                                <input type="text" class="form-control" id="size" name="size"
                                    value="{{ old('size', $propose->size) }}">
                                @error('size')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="unit_type">Unit Type:</label>
                                <span class="text-muted text-sm">(Unit,cm,kg,ml,l) </span>
                                <input type="text" class="form-control" id="unit_type" name="unit_type"
                                    value="{{ old('unit_type', $propose->unit_type) }}">
                                @error('unit_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="category_id">Category</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option selected="" disabled="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option
                                            {{ old('category_id', $propose->category_id) == $category->id ? 'selected' : '' }}
                                            value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                @error('category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="price">Price:</label>
                                <input type="number" min="0" class="form-control" id="price" name="price"
                                    value="{{ number_format(floor((int) $propose->price), 0, '.', '') }}">
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="image" class="form-label custom-file-input">Choose Image</label>
                                <input class="form-control" type="file" id="image" name="image">
                                <span class="text-dark text-sm">{{ $propose->image }}</span>
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="description">Description:</label>
                                <textarea class="form-control" id="description" rows="5" name="description">{{ old('description', $propose->description) }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('propose.product.index') }}" class="btn btn-danger">cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
