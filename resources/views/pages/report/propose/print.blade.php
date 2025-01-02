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
            /* background-color: #cfcfcf; */
            width: 82%;
        }

        .row-head {
            display: flex;
            flex-direction: row;
            margin: auto;
            width: 82%;
            /* background-color: #cfcfcf; */

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


        .col-6.text-end {
            text-align: left;
        }

        .col-6.text-start {
            text-align: start;
        }

        .col-8.text-end {
            text-align: left;
        }

        .col-8.text-start {
            text-align: start;
        }



        .text-sm {
            margin: 1%;
            font-size: 12px;

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

        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            body {
                font-size: 12px;
                margin: 10% auto;
                padding: 0;
            }

            table tr td {
                font-size: 11px;
            }
        }
    </style>

    <div class="title-head">
        <h5>{{ $title }}</h5>
    </div>

    <div class="row-head justify-content-center">
        <div class="col-md-12">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Supplier Name</th>
                        <th>Price</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // dd($data);
                    @endphp
                    @foreach ($data as $dt)
                        {{-- @php

                            $amount = $restock->product_price * $restock->quantity;
                            $total += $amount;
                        @endphp --}}
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dt->supplier_name }}</td>
                            <td>{{ formatNumber($dt->total_price) }}</td>
                            <td>{{ $dt->order_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    {{-- <tr>
                        <td colspan="4" class="text-end">Total:</td>
                        <td>{{ formatNumber($total) ?? 0 }}</td>
                    </tr> --}}
                </tfoot>
            </table>
            {{-- <p class="text-sm text-notes">Notes : {{ $notes }}</p> --}}
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.print();
        });
    </script>
@endsection
