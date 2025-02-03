@extends('layout.mainlayout')

@section('content')


<span>{{$lc_num}}</span>
<table>
  <tr>
    <th>Tyre ID</th>
    <th>Qty</th>
    <th>Unit Price</th>

    <th>created_at</th>
    <th>updated_at</th>
  </tr>


  @foreach ($records as $record)
  <tr>
    <td>{{$record->tyre_id}}</td>
    <td>{{$record->qty}}</td>
    <td>{{$record->unit_price}}</td>

    <td>{{$record->created_at}}</td>
    <td>{{$record->updated_at}}</td>
  </tr>
  @endforeach

</table>

@endsection
