@extends('layout.mainlayout')

@section('content')

<table class="DBinfo">
  <tr>
    <th>Container#</th>
    <th>BOL#</th>
    <th>Tyre ID</th>
    <th>Quantity</th>
    <th>Unit Price</th>
    <th>Total Weight</th>
    <th>Total Tax Paid</th>
    <th>Created</th>
    <th>Updated</th>
  </tr>


  @foreach ($contents as $content)
    <tr>
      <td>{{$content->Container_num}}</td>
      <td>{{$content->BOL}}</td>
      <td>{{$content->tyre_id}}</td>
      <td>{{$content->qty}}</td>
      <td>{{numfmt_format(resolve('CurrencyFormatter'),$content->unit_price}})</td>
      <td>{{$content->total_weight}}</td>
      <td>numfmt_format(resolve('CurrencyFormatter'),{{$content->total_tax}})</td>
      <td>{{$content->created_at}}</td>
      <td>{{$content->updated_at}}</td>
    </tr>
  @endforeach



</table>

@endsection
