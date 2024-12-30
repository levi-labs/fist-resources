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

                        <form method="POST" action="{{ route('user.updatePassword', Auth('web')->user()->id) }}">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label class="form-label" for="old_password">Old Password:</label>
                                <input type="password" class="form-control" id="old_password" name="old_password">
                                @error('old_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="new_password">New Password:</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                                @error('new_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="new_password_confirmation">Confirm Password:</label>
                                <input type="password" class="form-control" id="new_password_confirmation"
                                    name="new_password_confirmation">
                                @error('new_password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('category.index') }}" class="btn btn-danger">cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
