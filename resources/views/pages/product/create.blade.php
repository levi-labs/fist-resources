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

                        <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="name">Product Name:</label>
                                <input type="text" class="form-control" id="name" name="name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="sku">SKU:</label>
                                <span class="text-danger text-sm">(optional)</span>
                                <input type="text" class="form-control" id="sku" name="sku">
                                @error('sku')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="category_id">Category</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option selected="" disabled="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option {{ old('category_id') == $category->id ? 'selected' : '' }}
                                            value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                @error('category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="price">Price:</label>
                                <input type="number" min="0" class="form-control" id="price" name="price">
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="image" class="form-label custom-file-input">Choose Image</label>
                                <input class="form-control" type="file" id="image" name="image">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="description">Description:</label>
                                <textarea class="form-control" id="description" rows="5" name="description"></textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('product.index') }}" class="btn btn-danger">cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- 
@section('scripts')
    <!-- Pastikan jQuery dimuat terlebih dahulu -->


    <!-- Inisialisasi Select2 -->
    <script>
        // Inisialisasi Select2

        $(document).ready(function() {
            $('.my-select2').select2({});

            $('#category_id').on('select2:open', function(e) {
                // Mengubah style setelah dropdown terbuka
                $('.select2-selection').css({
                    'padding': '0.375rem 0.75rem',
                    'height': '40px',
                    'border': '1px solid rgb(62, 91, 232)',
                    'background-color': '#f8f9fa',
                    'color': '#495057'
                });
            });

            $('#category_id').on('select2:close', function(e) {
                // Mengubah style setelah dropdown tertutup
                $('.select2-selection').css({
                    'padding': '0.375rem 0.75rem',
                    'height': '40px',
                    'border': '1px solid #ced4da',
                    'background-color': '#fff',
                    'color': '#ced4da',
                });
            });

            $('.my-select2').on('change', function() {
                // Menampilkan nilai yang dipilih di console
                console.log('Nilai yang dipilih: ' + $(this).val());
            });
        });
    </script>
@endsection --}}
