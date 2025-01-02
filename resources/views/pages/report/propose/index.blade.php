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

                        <form id="reportForm" method="POST" action="{{ route('report.propose.purchase.search') }}"
                            target="_blank">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="from">From:</label>
                                <input type="date" class="form-control" id="from" name="from">
                                @error('from')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="to">To:</label>
                                <input type="date" class="form-control" id="to" name="to">
                                @error('to')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="button" id="submit" class="btn btn-primary">Submit</button>
                            {{-- <a href="{{ route('category.index') }}" class="btn btn-danger">cancel</a> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let from = document.getElementById('from');
            let to = document.getElementById('to');
            let submit = document.getElementById('submit');

            from.addEventListener('change', function() {
                if (from.value === '' && to.value === '') {
                    submit.setAttribute('disabled', true)
                } else if (from.value !== '' || to.value !== '') {

                    submit.setAttribute('type', 'submit');
                    submit.removeAttribute('disabled')
                }
            });

            to.addEventListener('change', function() {
                if (from.value === '' && to.value === '') {
                    submit.setAttribute('disabled', true)
                } else if (from.value !== '' || to.value !== '') {
                    submit.setAttribute('type', 'submit');
                    submit.removeAttribute('disabled')
                }
            });
        });
    </script>
@endsection
