@extends('layouts.app')

@section('title')
  Home
@endsection

@section('body')
  <div class="row">
    <div class="col-xs-12">
      <h2>Welcome to <b>nanoDB</b></h2>
      <p>Stock and accounting tool for Intertrac Nano</p>
      <p>Current version : <b>{{ config('app.version') }}</b></p>

      <p>
        <span>Server Time : <b>{{$now->toDayDateTimeString()}}</b></span> <br>
        <span>Local Time : <b v-cloak id="app">@{{ date_string }}</b></span>
      </p>
    </div>
  </div>
  <div class="row flex-wrap">
    <div class="col-xs-12 col-lg-4">
      <div class="box box-widget widget-user-2">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header bg-orange">
          <span class="info-box-icon bg-transparent mt-3"><i class="fa fa-exclamation"></i></span>
          <!-- /.widget-user-image -->
          <h4 class="">Most owing customers</h4>
          <h5 class="">Avoid selling to these people</h5>
        </div>
        <div class="box-footer no-padding">
          <ul class="nav nav-stacked">
            @foreach($highest_owing_customers as $customer)
            <li class="p-4">{{$customer->name}} <span class="pull-right text-red">৳ {{numfmt_format( resolve('CurrencyFormatter'), $customer->balance_total)}}</span></li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>

    <div class="col-xs-12 col-lg-4">
      <div class="box box-widget widget-user-2">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header bg-red">
          <span class="info-box-icon bg-transparent mt-3"><i class="fa fa-times"></i></span>
          <!-- /.widget-user-image -->
          <h4 class="">Biggest Zero Payment Orders</h4>
          <h5 class="">Largest orders with no payments</h5>
        </div>
        <div class="box-footer no-padding">
          <ul class="nav nav-stacked">
            @foreach($highest_no_payments as $order)
              <li class="p-4">
                <span class="badge bg-red mr-2" date-toggle="tooltip" title="{{$order->order_on}}">{{$order->Order_num}}</span>
                {{$order->name}}
                <span class="pull-right text-red">৳ {{numfmt_format( resolve('CurrencyFormatter'), $order->grand_total)}}</span></li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>

    <div class="col-xs-12 col-lg-4">
      <div class="box box-widget widget-user-2">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header bg-yellow">
          <span class="info-box-icon bg-transparent mt-3"><i class="fa fa-star"></i></span>
          <!-- /.widget-user-image -->
          <h4 class="">Newest Unpaid Orders</h4>
          <h5 class="">Most recent unpaid orders</h5>
        </div>
        <div class="box-footer no-padding">
          <ul class="nav nav-stacked">
            @foreach($newest_owing_orders as $order)
              <li class="p-4">
                <span class="badge bg-red mr-2" date-toggle="tooltip" title="{{$order->order_on}}">{{$order->Order_num}}</span>
                {{$order->name}}
                <span class="pull-right text-red">৳ {{numfmt_format( resolve('CurrencyFormatter'), $order->balance)}}</span></li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection


@section('footer-scripts')
  <script>

    var app = new Vue({

        el: '#app',
        data : {
          date_string : ''
        },

        created : function(){
            this.tickOn();
        },
        methods : {

            tickOn : function() {
                var timerID = setInterval(function(){

                    var local = new Date();
                    var hours = local.getHours() % 12;
                    var minutes = local.getMinutes();
                    var seconds = local.getSeconds();
                    var ampm = hours > 0 ? 'PM' : 'AM';

                    hours = hours < 10 ? ('0'+hours) : hours;
                    minutes = minutes < 10 ? ('0'+minutes) : minutes;
                    seconds = seconds < 10 ? ('0'+seconds) : seconds;

                    app.date_string = hours+':'+minutes+':'+seconds+' '+ampm;
                }, 1000)

            }
        },
        computed : {
            date : function(){
                 return Date();
            }
        }
    })

  </script>
@endsection