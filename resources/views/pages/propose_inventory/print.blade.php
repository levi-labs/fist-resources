@extends('layouts.print.body')
@section('content')
    <style>
        .title-head {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 10px auto;
            font-size: 24px;
            width: 81%;
        }

        .row-head {
            display: flex;
            flex-direction: row;
            margin: auto;
            width: 81%;

        }

        .row-head.justify-content-space-between {
            justify-content: space-between;
        }

        .row-head.justify-content-center {
            justify-content: center;
        }

        .col-md-12 {
            text-align: center;
            width: 100%;
            margin: auto;
            box-sizing: border-box;
            padding: 5%;
        }

        .col-6 {
            flex: 0 0 50%;
            margin-top: 1%;
            padding: 0 5%;
            box-sizing: border-box;
        }

        .col-6.text-end {
            text-align: left;
        }

        .col-6.text-start {
            text-align: start;
        }



        .text-sm {
            margin: 1%;
            font-size: 12px;

        }

        /*
                                                                                                                                                                                                                                            .text-sm.text-group .text-sm.text-wrap {
                                                                                                                                                                                                                                                overflow-wrap: break-word;
                                                                                                                                                                                                                                                word-wrap: break-word;
                                                                                                                                                                                                                                            } */

        .text-to {
            margin: 1%;
            font-size: 14px;
        }

        .text-sm.text-notes {
            text-align: start;
            margin-top: 20px;
        }

        .col-6.text-end.group-data {
            display: flex;
            justify-content: end;

        }

        .col-6.text-end.group-data>.info {
            width: 40%;
            text-align: start;
            padding: 1%;
            box-sizing: border-box;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .col-6 h5 {
            margin: 1%;
            font-size: 12px;
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin: auto;
            border: 1px solid black;
        }

        .table-custom th,
        .table-custom td {
            border: 1px solid black;
            padding: 5px;
        }

        .col-8 {
            flex: 0 0 60%;
            margin-top: 1%;
            padding: 0 5%;
            box-sizing: border-box;
        }

        .col-4 {
            flex: 0 0 20%;
            margin-top: 1%;
            padding: 0 5%;
            box-sizing: border-box;
        }
    </style>
    @php
        $notes = $proposed[0]->note ?? '-';
    @endphp
    <div class="title-head">
        <h4>{{ $title }}</h4>

    </div>
    <div class="row-head justify-content-space-between">
        <div class="col-8 text-start">
            <button onclick="window.print()" class="btn">Print</button>
            <h5>TO</h5>
            <p class="text-to">Procurement Team</p>
            <p class="text-to">Jakarta, Indonesia</p>
        </div>
        <div class="col-4 text-end group-data">
            <div class="info">
                <table>
                    <tr>
                        <td>Request Code</td>
                        <td>:</td>
                        <td>{{ $proposed[0]->request_code ?? '-' }}</td>
                    </tr>
                    <tr></tr>
                    <td>Request Date</td>
                    <td>:</td>
                    <td>{{ $proposed[0]->date_requested ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Requested By</td>
                        <td>:</td>
                        <td>{{ $proposed[0]->staff_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Approved_by</td>
                        <td>:</td>
                        <td>{{ $proposed[0]->procurement_name ?? '-' }}</td>
                    </tr>
                </table>
                {{-- <p class="text-sm text-wrap">Request Code : {{ $restocks[0]->request_code ?? '-' }}</p>
                <p class="text-sm text-wrap">Request Date : {{ $restocks[0]->request_date ?? '-' }}</p>
                <p class="text-sm text-wrap">Requested By : {{ $restocks[0]->staff_name ?? '-' }}</p>
                <p class="text-sm text-wrap">Approved_by : {{ $restocks[0]->procurement_name ?? '-' }}</p> --}}
            </div>

        </div>
    </div>
    <div class="row-head justify-content-center">
        <div class="col-md-12">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($proposed as $restock)
                        @php

                            $amount = $restock->product_price * $restock->quantity;
                            $total += $amount;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $restock->product_name }}</td>
                            <td>{{ formatNumber($restock->product_price) }}</td>
                            <td>{{ $restock->quantity }}</td>
                            <td>{{ formatNumber($amount) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end">Total:</td>
                        <td>{{ formatNumber($total) ?? 0 }}</td>
                    </tr>
                </tfoot>
            </table>
            <p class="text-sm text-notes">Notes : {{ $notes }}</p>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.print();
        });
    </script>
@endsection
