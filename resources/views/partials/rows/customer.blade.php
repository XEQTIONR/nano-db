<div class="row">
  <div class="col-xs-12 col-md-4 invoice-col mx-3">
    <address>
      <b>{{$customer->name}}</b>
      <br>
      {{$customer->address}}
    </address>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-6">
    <small class="text-uppercase">Orders</small><br>
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <table class="table table-bordered">
      <thead>
      <tr>
        <th>Order#</th>
        <th>Order Date</th>
        <th>Discount %</th>
        <th class="text-right">Discount Amount</th>
        <th>Tax %</th>
        <th class="text-right">Tax Amount</th>
        <th class="text-right">Total</th>
        <th class="text-right">Payments Total</th>
        <th class="text-right">Commissions Total</th>
        <th class="text-right">Balance <br></th>
      </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
        <tr>
          <td>{{$order->Order_num}}</td>
          <td>{{$order->order_on}}</td>
          <td>{{$order->discount_percent}}</td>
          <td class="text-right">{{$order->discount_amount}}</td>
          <td>{{$order->tax_percentage}}</td>
          <td class="text-right">{{$order->tax_amount}}</td>
          <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),$order->grand_total)}}</td>
          <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),$order->payments_total)}}</td>
          <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),$order->commission)}}</td>
          <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),$order->balance)}}</td>
        </tr>
        @endforeach
        <tfoot>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td class="strong text-right">{{numfmt_format(resolve('CurrencyFormatter'),$totals->total)}}</td>
          <td class="strong text-right">{{numfmt_format(resolve('CurrencyFormatter'),$totals->payments)}}</td>
          <td class="strong text-right">{{numfmt_format(resolve('CurrencyFormatter'),$totals->commission)}}</td>
          <td class="strong text-right">{{numfmt_format(resolve('CurrencyFormatter'),$totals->balance)}}</td>
        </tr>
        </tfoot>
      </tbody>
    </table>
  </div>
</div>

<div class="row">
  <div class="col-xs-12 col-md-6">
    <small class="text-uppercase">Last 10 payments</small><br>
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Transaction ID</th>
          <th>Order #</th>
          <th>Payment Amount</th>
          <th>Refund Amount</th>
          <th>Payment On</th>
        </tr>
      </thead>
      <tbody>
        @foreach($payments as $payment)
          <tr>
            <td>{{$payment->transaction_id}}</td>
            <td>{{$payment->Order_num}}</td>
            <td>{{numfmt_format(resolve('CurrencyFormatter'),$payment->payment_amount)}}</td>
            <td>{{numfmt_format(resolve('CurrencyFormatter'),$payment->refund_amount)}}</td>
            <td>{{$payment->payment_on}}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>


