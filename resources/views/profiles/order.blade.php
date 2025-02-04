@extends('layout.mainlayout')

@section('scripts')

<script>
  $( function() {
    $( "#accordion" ).accordion({
      collapsible: true,
      active: false,
      heightStyle: "content"

    });
  });
</script>
@endsection

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="page-header">
        <h1>Order information</h1>
      </div>
    </div>
  </div>

<div class="row">
<div class="col-md-6 col-md-push-3">
  <dl class="dl-horizontal">
    <dt>Order#</dt>
    <dd>{{$order->Order_num}}</dd>

    <dt>Customer ID</dt>
    <dd>{{$customer->id}}</dd>

    <dt>Customer Name</dt>
    <dd>{{$customer->name}}</dd>

    <dt>Total Order Value</dt>
    <dd>{{$order->subtotal}}</dd>

    <dt>Discount%</dt>
    <dd>{{$order->discount_percent}}</dd>

    <dt>Discount Amount</dt>
    <dd>{{$order->discount_amount}}</dd>

    <dt>Total Discount</dt>
    <dd>{{$order->totalDiscount}}</dd>

    <dt>Order Value(After Discount)</dt>
    <dd>{{$order->subtotal-$order->totalDiscount}}</dd>

    <dt>Tax %</dt>
    <dd>{{$order->tax_percentage}}</dd>

    <dt>Tax Amount</dt>
    <dd>{{$order->tax_amount}}</dd>

    <dt>Total Tax</dt>
    <dd>{{$order->totalTax}}</dd>

    <dt>Grand Total</dt>
    <dd>{{$order->subtotal-$order->totalDiscount+$order->totalTax}}</dd>

    <dt>Still payable</dt>
    <dd>{{$order->payable}}</dd>

    <dt>created_at</dt>
    <dd>{{$order->created_at}}</dd>

    <dt>upadated_at</dt>
    <dd>{{$order->updated_at}}</dd>

    <dt>Notes</dt>
    <dd></dd>
  </dl>
</div> <!--col-->
</div> <!--row-->

<!--"row">
<div class="col-md-1">
<a href="/consignments/create/" class="btn btn-danger">Delete Order</a>
</div>
</div>-->

</div> <!--container-->


<div id="accordion" class="container">

  <h3>Order Contents</h3>
  <div>
    {{--SOMETHING RANDOM--}}
    <table class="table table-hover table-bordered">
      <tr>
        <th>Tyre ID</th>
        <th>BOL#</th>
        <th>Container#</th>
        <th>Qty</th>
        <th>Unit Price</th>
      </tr>

      @foreach ($contents as $record)
      <tr>
        <td>{{$record->tyre_id}}</td>

        <td>{{$record->bol}}</td>
        <td>{{$record->container_num}}</td>

        <td>{{$record->qty}}</td>
        <td>{{$record->unit_price}}</td>

      </tr>
      @endforeach
    </table>

    <a href="#" class="btn btn-danger">Delete</a>

  </div>

  <h3>Payments</h3>
  <div>
    <table class="table table-hover">
      <tr>
        <th>Invoice#</th>
        <th>Amount Paid</th>
        <th>Payment Date</th>
        <th>created_at</th>
        <th>updated_at</th>
      </tr>

      @foreach ($payments as $payment)
        <tr>
          <td>{{$payment->Invoice_num}}</td>
          <td>{{$payment->payment_amount}}</td>
          <td>{{$payment->created_at}}</td>
          <td>{{$payment->created_at}}</td>
          <td>{{$payment->updated_at}}</td>
        </tr>
      @endforeach
    </table>
  </div>

</div> <!-- container accordion -->


@endsection
