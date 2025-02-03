@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">

                    Report generated on:{{$date}}<br>
                    Report for {{$time_frame}} year {{$year}}<br>
                    1.No of order: {{$count}}<br>
                    {{--We have {{$count}} orders on the month of {{$date->month}} year of {{$date->year}} <br>--}}
                    2.Total tyres sold: {{$count_tyres}}<br>
                    3.Total Value: {{$total_value}}<br>
                    4.Avg no of tyres in each order: {{$avg_tyre}}<br>
                    5.Avg Value per order: {{$avg_value}}<br>
                    6.No of order with payment: {{$orders_with_payments}}<br>
                    7.Orders fully paid: {{$orders_full_paid}}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
