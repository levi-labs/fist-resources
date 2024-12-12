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

                        <form method="POST" action="{{ route('user.store') }}">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username">
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="role">Role</label>
                                <select class="form-select" id="role" name="role">
                                    <option selected="" disabled="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option {{ old('role') == $role ? 'selected' : '' }} value="{{ $role }}">
                                            {{ $role }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="password">Password:</label>
                                <input type="text" class="form-control" id="password" name="password">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('user.index') }}" class="btn btn-danger">cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
