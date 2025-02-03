<table id="table_id" class="table table-condensed table-bordered table-hover">
<thead>
  <tr>
    <th class="">Order #</th>
    <th class="">Order On</th>
    <th class="">Customer ID</th>
    <th>Customer Name</th>
    <th class="">Order Total (&#2547)</th>
    <th class="">Payments Total (&#2547)</th>
    <th class="">Commission (&#2547)</th>
    <th class="">Balance (&#2547)</th>
    <th class="">Status</th>
  </tr>
</thead>
<tbody>
  @foreach ($orders as $order)
    <tr style="cursor: pointer;">
      <td class="text-center details-control strong">{{$order->Order_num}}</td>
      <td class="text-center">{{$order->order_on}}</td>
      <td class="text-center">{{$order->customer_id}}</td>
      <td class="">{{$order->name}}</td>
      <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),floatval($order->grand_total))}}</td>
      <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),floatval($order->payments_total))}}</td>
      <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),floatval($order->commission))}}</td>
      <td class="text-right strong @if(floatval($order->balance)>0) text-red @else text-green @endif">{{numfmt_format(resolve('CurrencyFormatter'),$order->balance)}}</td>
      <td class="">
        @if($order->payments_total == 0)
          <span class="label label-danger"><i class="fa fa-times mr-1"></i> No payments</span>
        @elseif($order->balance == 0)
          <span class="label label-success">  <i class="fa fa-check mr-1"></i> Paid Off</span>
        @else
          <?php $percentage = intval(($order->payments_total*100)/($order->grand_total - floatval($order->commission))); ?>

          <div class="progress progress-xs" data-toggle="tooltip" title="{{$percentage}}% Paid">
            <div class="progress-bar progress-bar-<?php if($percentage<33) echo "danger"; else {  echo $percentage<66 ?  "warning" :  "success"; } ?>"
                 style="width: {{$percentage}}%"></div>
          </div>

        @endif
      </td>
    </tr>
  @endforeach
</tbody>
<tfoot>
  <tr>
    <th class="text-center" colspan="3"></th>
    <th></th>
    <th class="text-right"></th>
    <th class="text-right"></th>
    <th class="text-right"></th>
    <th class="text-right text-red"></th>
    <th></th>
  </tr>
</tfoot>
</table>

@if (method_exists($orders, 'links'))
  {{$orders->links()}}
@endif
