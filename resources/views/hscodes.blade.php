@extends('layout.mainlayout')

@section('content')

<table class="DBinfo">
  <tr>
    <th>HSCODE</th>
    <th>Created</th>
    <th>Updated</th>
  </tr>


  @foreach ($hscodes as $hscode)
    <tr>
      <td>{{$hscode->id}}</td>
      <td>{{$hscode->created_at}}</td>
      <td>{{$hscode->updated_at}}</td>
    </tr>
  @endforeach



</table>

@endsection
