<table id="table_id" class="table table-hover table-bordered">
<thead>
  <tr>
    <th class="text-center">Transaction ID</th>
    <th class="text-center">Order #</th>
    <th class="text-center">Amount Paid (&#2547)</th>
    <th class="text-center">Refund (&#2547)</th>
    <th class="text-center">Payment Type</th>
    <th class="text-center">Bank</th>
    <th class="text-center">Created</th>
  </tr>
</thead>
<tbody>
  @foreach ($payments as $payment)
    <tr>
      <td class="text-center strong">{{ str_pad($payment->transaction_id, 10, "0", STR_PAD_LEFT) }}</td>
      <td class="text-center">{{$payment->Order_num}}</td>
      <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),$payment->payment_amount)}}</td>
      <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),$payment->refund_amount)}}</td>
      <td class="text-center">{{$paymentTypes[$payment->type]}}</td>
      <td class="text-center">{{$payment->bankAccount ? $payment->bankAccount->bank_name : null }}</td>
      <td class="text-center">{{$payment->created_at}}</td>
    </tr>
  @endforeach
</tbody>
  <tfoot>
  <tr>

    <th class="text-center"></th>
    <th class="text-center"></th>
    <th class="text-right"></th>
    <th class="text-right"></th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  </tfoot>
</table>

@if (method_exists($payments, 'links'))
  {{$payments->links()}}
@endif
