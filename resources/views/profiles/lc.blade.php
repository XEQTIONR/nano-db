@extends('layouts.app')

@section('body')

{{--<div class="container">--}}

<div class="row">
  <div class="col-xs-12">
    <div class="page-header">
      <h1>LC information</h1>
    </div>
  </div>
</div>

<div class="row justify-content-center d-block">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Letter of Credit</h3>
      </div>
      <div class="box-body">
        <dl class="dl-horizontal">
          <dt>LC#</dt>
          <dd class="mb-2">{{$lc->lc_num}}</dd>

          <dt>Date Issued</dt>
          <dd class="mb-2 date">{{$lc->date_issued}}</dd>

          <dt>Date Expiry</dt>
          <dd class="mb-2 date">{{$lc->date_expiry}}</dd>

          <dt>Invoice#</dt>
          <dd class="mb-2">{{$lc->invoice_no}}</dd>

          <dt></dt>
          <dd class="mb-2"></dd>

          <dt>Value(Foreign)</dt>
          <dd class="mb-2">{{numfmt_format(resolve('CurrencyFormatter'),$lc->foreign_amount)}}</dd>

          <dt>Currency code</dt>
          <dd class="mb-2">{{$lc->currency_code}}</dd>

          <dt>Exchange rate</dt>
          <dd class="mb-2">{{numfmt_format(resolve('CurrencyFormatter'),$lc->exchange_rate)}}</dd>

          <dt>Value(TK)</dt>
          <dd class="mb-2">{{numfmt_format(resolve('CurrencyFormatter'),($lc->foreign_amount * $lc->exchange_rate))}}</dd>

          <dt>Expenses Paid(Foreign)</dt>
          <dd>{{numfmt_format(resolve('CurrencyFormatter'),$lc->foreign_expense) }}</dd>

          <dt>Expenses Paid(Local)</dt>
          <dd class="mb-2">{{numfmt_format(resolve('CurrencyFormatter'),$lc->domestic_expense) }}</dd>

          <dt>Expenses Total(TK)</dt>
          <dd class="mb-2">{{numfmt_format(resolve('CurrencyFormatter'),($lc->foreign_expense * $lc->exchange_rate)+$lc->domestic_expense) }}</dd>

          <dt>Applicant</dt>
          <dd class="mb-2">{!! $lc->applicant !!}</dd>

          <dt>Beneficiary</dt>
          <dd class="mb-2">{!! $lc->beneficiary !!}</dd>

          <dt>Departing port</dt>
          <dd class="mb-2">{{$lc->port_depart}}</dd>

          <dt>Destination port</dt>
          <dd class="mb-2">{{$lc->port_arrive}}</dd>

          <dt>Notes</dt>
          <dd class="mb-2">{{$lc->notes}}</dd>

          <dt>created_at</dt>
          <dd class="mb-2">{{$lc->created_at}}</dd>

          <dt>updated_at</dt>
          <dd class="mb-2">{{$lc->updated_at}}</dd>
        </dl>
      </div>
    </div>
  </div> <!--col-->
  <div class="col-md-6 clearfix">
    <div class="box box-solid bg-light-blue">
      <div class="box-header bg-light-blue-active">
        <h3 class="box-title">Proforma Invoice</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" style="color: white"><i class="fa fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="box-body">
        <div>
          {{--SOMETHING RANDOM--}}
            <table class="table table-responsive table-bordered inner-white">
            <thead>
            <tr>
              <th class="text-teal-active">Tyre</th>
              <th class="text-teal-active">Qty</th>
              <th class="text-teal-active">Unit Price</th>
              <th class="text-teal-active text-right">Sub Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($performa as $record)
              <tr>
                <td><b>({{$record->tyre->tyre_id}})</b> {{$record->tyre->brand}} {{$record->tyre->size}} {{$record->tyre->pattern}} {{$record->tyre->lisi}}</td>
                <td>{{$record->qty}}</td>
                <td><span class="currency-symbol mr-1"></span>{{numfmt_format(resolve('CurrencyFormatter'),$record->unit_price)}}</td>

                <td class="text-right"><span class="currency-symbol mr-1"></span>{{numfmt_format(resolve('CurrencyFormatter'),$record->qty*$record->unit_price)}}</td>
              </tr>
            @endforeach
            @if($lc->total>0)
              <tr class="bg-teal-active">
                <td class="text-uppercase"><b> Total</b></td>
                <td class="text-uppercase"><b>{{ $lc->totalqty }}</b></td>
                <td></td>
                <td class="text-right"><b><span class="currency-symbol mr-1"></span>{{ numfmt_format(resolve('CurrencyFormatter'),$lc->total) }}</b></td>
              </tr>
            @endif
            </tbody>
          </table>
          {{--<a href="#" class="btn btn-primary">Edit</a>--}}
          {{--<a href="#" class="btn btn-danger">Delete</a>--}}

        </div>
      </div>
    </div>
    <div class="box box-solid bg-teal">
      <div class="box-header bg-teal-active">
        <h3 class="box-title">Consignments</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" style="color: white"><i class="fa fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="box-body">
        <div>
          <table class="table table-responsive inner-white" style="border: 1px solid white">
            <thead>
            <tr class="" >
              <th class="text-blue" >Bill Of Lading#</th>
              <th class="text-blue text-right">Total Value</th>
              <th class="text-blue text-right">Tax Paid</th>
              <th class="text-blue">Land Date</th>
              <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($consignments as $consignment)
              <tr>
                <td class=" strong" >{{$consignment->BOL}}</td>
                <td class="text-right"><span class="currency-symbol mr-1"></span> {{numfmt_format(resolve('CurrencyFormatter'),$consignment->value)}}</td>
                <td class="text-right">৳ {{numfmt_format(resolve('CurrencyFormatter'),$consignment->tax)}}</td>
                <td class="date">{{$consignment->land_date}}</td>
                <td class=""><a href="/consignments/{{$consignment->BOL}}"
                       class="btn btn-primary btn-sm">
                    <i class="icon-eye-r mr-1"  style="position: relative; top: 2px;"></i>
                    View</a>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
          {{--<a href="/consignments/create/{{$lc->lc_num}}" class="btn btn-primary">Add Consignment</a>--}}
        </div>
      </div>

    </div>
  </div>
</div> <!--row-->
{{--</div> <!--container-->--}}


@endsection


@section('footer-scripts')

  <script>

    $(document).ready(function(){

        $('.currency-symbol').html(currencies.{{$lc->currency_code}});
    });
  </script>

@endsection
