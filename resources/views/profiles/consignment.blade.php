@extends('layouts.app')


@section('body')

{{--<div class="container">--}}

  <div class="row">
    <div class="col-xs-12">
      <div class="page-header">
        <h1>Consignment information</h1>
      </div>
    </div>
  </div>

  <div class="row justify-content-center d-block">
    <div class="col-lg-6">
      <div class="box box-teal">
        <div class="box-header with-border">
          <h3 class="box-title">Consignment</h3>
        </div>
        <div class="box-body">
          <dl class="dl-horizontal">

            <dt class="mb-2">Bill of Lading#</dt>
            <dd>{{$consignment->BOL}}</dd>

            <dt class="mb-2">LC#</dt>
            <dd><a href="/lcs/{{$consignment->lc}}">{{$consignment->lc}}</a></dd>

            <dt class="mb-2">Landed On</dt>
            <dd class="date">{{$consignment->land_date}}</dd>

            <dt class="mb-2">Exchange_rate</dt>
            <dd>{{$consignment->exchange_rate}}</dd> {{--Change 60 to exchange_rate--}}

            <dt class="mb-2">Total Value (Foreign)</dt>
            <dd><span class="currency-symbol"></span> {{$consignment->value}}</dd>

            <dt class="mb-2">Total Value (Local)</dt>
            <dd>৳ {{$consignment->value * $consignment->exchange_rate}}</dd> {{--change 60 to excahnge rate--}}

            <dt class="mb-2">Total Tax Charged (TK)</dt>
            <dd>৳ {{$consignment->tax}}</dd>
          </dl>
        </div>
      </div>

      <div class="box box-solid bg-orange">
        <div class="box-header bg-orange-active">
          <h3 class="box-title ">Sold / Waste</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" style="color: white"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body">
          <div>

            @foreach ($containers as $container)
            @if(isset($container->sold) || isset($container->waste))
              <?php $total = 0; $total_qty = 0; $total_tax = 0; $total_waste = 0; $total_stock = 0; $total_remain = 0 ?>
              <table class="table table-bordered table-condensed inner-white">
                <thead>
                <tr>
                  <th colspan="6" class="bg-orange-active">Container# {{$container->Container_num}}</th>
                </tr>
                <tr>
                  <th class="col-xs-3 bg-orange-active">Tyre</th>
                  <th class="col-xs-1 bg-orange-active">Supply</th>
                  <th class="col-xs-1 bg-orange-active">Sold</th>
                  <th class="col-xs-1 bg-orange-active">Waste</th>
                  <th class="col-xs-1 bg-teal-active">Remain</th>
                </tr>

                </thead>
                <tbody>


                @foreach($container->contents as $listing)
                <tr>
                  <td class="col-xs-3"><b>({{$listing->tyre_id}})</b> {{$listing->tyre->brand}} {{$listing->tyre->size}} {{$listing->tyre->pattern}} {{$listing->tyre->lisi}}</td>
                  <td class="col-xs-1">{{$listing->qty}}</td>
                  <td class="col-xs-1">{{$listing->sold}}</td>
                  <td class="col-xs-1">{{$listing->qty_minus_sold - $listing->remain}}</td>
                  <td class="col-xs-1 bg-teal">{{$listing->remain}}</td>
                </tr>
                <?php
                  $total_qty += intval($listing->sold);
                  $total_stock += intval($listing->qty);
                  $total_waste += (intval($listing->qty_minus_sold) - intval($listing->remain));
                  $total_remain += $listing->remain;
                ?>
                @endforeach

                <tr>
                  <th class="col-xs-3 text-uppercase bg-orange-active">Total</th>
                  <th class="col-xs-1 bg-orange-active">{{$total_stock}}</th>
                  <th class="col-xs-1 bg-orange-active">{{$total_qty}}</th>
                  <th class="col-xs-1 bg-orange-active">{{$total_waste}}</th>
                  <th class="col-xs-1 bg-teal-active">{{$total_remain}}</th>
                </tr>
                </tbody>
              </table>

                <table class="table table-bordered table-condensed inner-white bg-orange-active">
                  <thead>

                  </thead>
                </table>
            @endif
            @endforeach
          </div>
        </div>
      </div>
    </div> <!--col-->
    <div class="col-lg-6 clearfix">
      <div class="box box-solid bg-purple">
        <div class="box-header bg-purple-active">
          <h3 class="box-title ">Containers</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" style="color: white"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body">
          <div>
            <?php $total = 0; $total_qty = 0; $total_tax = 0; $total_weight = 0 ?>
            @foreach ($containers as $container)
              <table class="table table-bordered table-condensed inner-white">
                <thead>
                <tr>
                  <th colspan="6">Container# {{$container->Container_num}}</th>
                </tr>
                <tr>
                  <th class="col-xs-3">Tyre</th>
                  <th class="col-xs-1">Qty</th>
                  <th class="col-xs-2">Unit Price</th>
                  <th class="col-xs-2">Sub-total</th>
                  <th class="col-xs-2">Total Tax</th>
                  <th class="col-xs-2">Total Weight</th>
                </tr>

                </thead>
                <tbody>
                  @foreach($container->contents as $listing) {{---each tyre qty price etc--}}
                  <tr>
                  <td class="col-xs-3"><b>({{$listing->tyre->tyre_id}})</b> {{$listing->tyre->brand}} {{$listing->tyre->size}} {{$listing->tyre->pattern}} {{$listing->tyre->lisi}}</td>
                  <td class="col-xs-1">{{$listing->qty}}</td>
                  <td class="col-xs-2 text-right"><span class="currency-symbol"></span>{{numfmt_format(resolve('CurrencyFormatter'),$listing->unit_price)}}</td>
                  <td class="col-xs-2 text-right"><span class="currency-symbol"></span>{{numfmt_format(resolve('CurrencyFormatter'),$listing->qty * $listing->unit_price)}}</td>
                  <td class="col-xs-2 text-right">৳{{numfmt_format(resolve('CurrencyFormatter'),$listing->total_tax)}}</td>
                  <td class="col-xs-2 text-right">{{numfmt_format(resolve('CurrencyFormatter'),$listing->total_weight)}} kg</td>
                  </tr>
                    <?php
                      $total+= (floatval($listing->unit_price) * floatval($listing->qty));
                      $total_qty += intval($listing->qty);
                      $total_tax += floatval($listing->total_tax);
                      $total_weight += floatval($listing->total_weight);
                    ?>
                  @endforeach
                </tbody>
              </table>
            @endforeach
              <table class="table table-bordered table-condensed inner-white bg-purple-active">
                <thead>
                  <tr>
                    <th class="col-xs-3 text-uppercase">Total</th>
                    <th class="col-xs-1">{{$total_qty}}</th>
                    <th class="col-xs-2"></th>
                    <th class="col-xs-2 text-right"><span class="currency-symbol"></span>{{ numfmt_format(resolve('CurrencyFormatter'),$total, 2) }}</th>
                    <th class="col-xs-2 text-right">৳{{ numfmt_format(resolve('CurrencyFormatter'),$total_tax, 2) }}</th>
                    <th class="col-xs-2 text-right">{{ numfmt_format(resolve('CurrencyFormatter'),$total_weight, 2) }} kg</th>
                  </tr>
                </thead>
              </table>

            {{--<a href="/container_contents/create/{{$consignment->BOL}}" class="btn btn-primary">Add a container</a>--}}

          </div>
        </div>
      </div> <!--box-->
      <div class="box box-solid bg-maroon">
        <div class="box-header bg-maroon-active">
          <h3 class="box-title">Expenses</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" style="color: white"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body">
          <div>
            <table class="table table-condensed inner-white">
              <tr>
                <th class="col-xs-1">Expense ID</th>
                <th class="col-xs-3">Expense Date</th>
                <th class="col-xs-3">Expense Note</th>
                <th class="col-xs-2">Expense Local</th>
                <th class="col-xs-2 text-right">Expense Foreign</th>
                <th class="col-xs-4 text-right">Total</th>
              </tr>

            @foreach($expenses as $expense)
              <tr>
              <td class="col-xs-1">{{$expense->expense_id}}</td>
              <td class="col-xs-1">{{$expense->created_at}}</td>
              <td class="col-xs-3">{{$expense->expense_notes}}</td>
              <td class="col-xs-2">৳{{$expense->expense_local}}</td>
              <td class="col-xs-1 text-right"><span class="currency-symbol"></span>{{$expense->expense_foreign}}</td>
              <td class="col-xs-4 text-right">৳{{numfmt_format(resolve('CurrencyFormatter'),$expense->expense_foreign*$consignment->exchange_rate+ $expense->expense_local)}}</td>
              </tr>
            @endforeach
              <tr class="strong">
                <td colspan="3" class="col-xs-4 text-uppercase">Total</td>
                <td class="col-xs-2">৳{{numfmt_format(resolve('CurrencyFormatter'),$consignment->expense_local_total)}}</td>
                <td class="col-xs-1 text-right"><span class="currency-symbol"></span>{{numfmt_format(resolve('CurrencyFormatter'),$consignment->expense_foreign_total)}}</td>
                <td class="col-xs-4 text-right">৳{{numfmt_format(resolve('CurrencyFormatter'),$consignment->expense_grand_total)}}</td>
              </tr>
            </table>
            {{--<a href="/consignment_expenses/create/{{$consignment->BOL}}" class="btn btn-primary">Add an expense</a>--}}
          </div>
        </div>
      </div>
    </div>
  </div> <!--row-->

@endsection

@section('footer-scripts')
  <script>
      if (typeof currencies['{{$currency}}'] !== 'undefined')
          $('.currency-symbol').html(currencies['{{$currency}}']);
          //console.log(currencies['{{$currency}}']);
  </script>
@endsection
