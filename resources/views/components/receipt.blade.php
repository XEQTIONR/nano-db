@extends('layouts.app')

@section('title')
  Orders
@endsection
@section('subtitle')
  View Receipt.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'Orders',
    'subcrumb' => 'View Receipt',
     'link' => route('orders.receipt', $order )])
  @endcomponent
@endsection

@section('body')
  <div class="row justify-content-center">
    <div class="col-xs-12 col-lg-8 col-xl-4">
      <section class="invoice" style="min-height: 95vh">
        <div class="row">
          <div class="col-xs-12 ">
            <h2 class="page-header">
              <img class="d-block mx-auto" src="/images/crosscountry.png" height="75" width="auto">
{{--              <small class="pull-right">Date : {{\Carbon\Carbon::createFromFormat('Y-m-d', $order->order_on)->format('d/m/Y')}}</small>--}}
            </h2>
            <div class="row">
              <div class="col-xs-4"></div>
              <div class="col-xs-4"><h2 class="text-center text-uppercase mb-4"><b>Invoice</b></h2></div>
              <div class="col-xs-4"><small class="pull-right"><strong>Date :</strong> {{\Carbon\Carbon::createFromFormat('Y-m-d', $order->order_on)->format('d/m/Y')}}</small></div>
            </div>
          </div>
          <!-- /.col -->
        </div>

        <div class="row invoice-info">

          <!-- /.col -->
          <div class="col-sm-4 invoice-col">
            <small class="text-uppercase">Bill To</small><br>
            <address v-if="customer">
              <b>{{ $order->customer->name }}</b> <br>
              <span> {!!  $order->customer->address  !!}</span> <br>
              {{$order->customer->phone}}
            </address>
          </div>
          <!-- /.col -->
          <div class="col-sm-4 invoice-col">
            <small class="text-uppercase">Beneficiary</small><br>
            <address>
              <b>Cross Country</b> <br>
              7/5 Ring Road, <br>
              Shyamoli, <br>
              Dhaka - 1207 <br>
              <b>Ph:</b> +8801742162518, +8801815440669, +8801716427861
            </address>
          </div>

          <!-- /.col -->
          <div class="col-sm-4 invoice-col">
            <b>Order # </b>{{ $order->Order_num }}<br>
          </div>

        </div>

        <div class="row mt-4">
          <div class="col-xs-12 ">
            <table class="table table-striped table-responsive">
              <thead>
              <tr>
                <th>#</th>
                <th>Tyre</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th class="text-right pr-5">Sub-total</th>
              </tr>
              </thead>
              <tbody>
              <?php $i= 0 ?>
              @foreach($order->orderContents as $item)
                <?php $i++ ?>
                <tr>
                  <td class="col-xs-1">{{$i}}</td>
                  <td class="col-xs-5"><b>({{ $item->tyre->tyre_id }})</b> {{ $item->tyre->brand }} {{ $item->tyre->size }} {{ $item->tyre->pattern }} {{ $item->tyre->lisi }}</td>
                  <td class="col-xs-2">{{ $item->qty }}</td>
                  <td class="col-xs-2">৳ {{ numfmt_format(resolve('CurrencyFormatter'),floatval($item->unit_price)) }}</td>
                  {{--<td class="col-xs-2"> @{{ parseFloat(item.unit_price)* parseInt(item.qty) / parseFloat(subTotal) |percentage_rounded}}</td>--}}
                  <td class="col-xs-2 text-right pr-5">৳ {{ numfmt_format(resolve('CurrencyFormatter'),floatval($item->unit_price)* intval($item->qty))}}</td>
                </tr>
              @endforeach
              <tr class="warning">
                <td></td>
                <td><b>Total</b></td>
                <td><b>{{$order->qtytotal}}</b></td>
                <td></td>
                <td class="text-right pr-5">৳ {{numfmt_format(resolve('CurrencyFormatter'),$order->subtotal)}}</td>
              </tr>

              </tbody>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-md-5">
            <div class="row">
              <p class="lead ml-5 ">Additional information</p>
            </div>
            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
              This receipt is a copy.
            </p>
          </div>

          <div class="col-xs-12 col-md-7">
            <div class="table-responsive mt-5 pt-3">
              <table class="table">
                <tbody>
                <tr>
                  <th style="width: 60%;" colspan="2">Total:</th>
                  <td class="text-right">৳ {{numfmt_format(resolve('CurrencyFormatter'),$order->subtotal)}}</td>
                  <td></td>
                </tr>
                <tr
                  @if(floatval($order->taxtotal) == 0)
                   class="no-print"
                  @endif
                >
                  <th>Tax
                    <br>
                    @if($order->tax_percentage>0)<small>({{$order->tax_percentage}} %)</small>@endif
                    @if($order->tax_amount>0)
                      <small class="ml-2">
                          <i class="fa fa-plus mr-2"></i> ৳ {{$order->tax_amount}}
                      </small>
                    @endif



                  </th>
                  <td><i class="fa fa-plus mr-2"></i></td>
                  <td class="text-right">৳ {{numfmt_format(resolve('CurrencyFormatter'),$order->taxtotal) }}</td>
                  <td></td>
                </tr>
                <tr>
                  <th>Discount
                    <br>

                    @if($order->discount_percent>0)<small>(-{{$order->discount_percent}} %)</small>@endif
                    @if($order->discount_amount>0)
                      <small class="ml-2">
                        <i class="fa fa-minus mr-2"></i> ৳ {{$order->discount_amount}}
                      </small>
                    @endif
                  </th>
                  <td><i class="fa fa-minus mr-2"></i></td>
                  <td class="text-right">৳ {{numfmt_format(resolve('CurrencyFormatter'),$order->discounttotal)}}</td>
                  <td></td>
                </tr>
                <tr>
                  <th colspan="2" style="border-top: 1px solid #bbb;">Grand Total:</th>
                  <td class="text-right" style="border-top: 1px solid #bbb;"><b>৳ {{numfmt_format(resolve('CurrencyFormatter'),$order->grandtotal)}}</b></td>
                  <td></td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="row no-print">
          <div class="col-xs-12">
            <button onclick="window.print()" class="btn btn-default">
              <i class="fa fa-print"></i> Print
            </button>
          </div>
        </div>
        <div class="print-footer">
          <div class="col-xs-12">
            <div class="col-xs-5">
              <div class="row" style="border-top: 1px solid rgb(187, 187, 187);">
                <h4 class="mx-auto">Received By</h4>
              </div>
            </div>
            <div class="col-xs-1"></div>
            <div class="col-xs-1"></div>
            <div class="col-xs-5">
              <div class="row" style="border-top: 1px solid rgb(187, 187, 187);">
                <h4 class="mx-auto">For Cross Country</h4>
              </div>
            </div>
          </div>

        </div>
      </section>
    </div>
  </div>


@endsection
