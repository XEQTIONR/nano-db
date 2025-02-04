<div id="load" style="position: relative;">
<table class="table table-hover table-condensed">
<thead>
  <tr>
    <th>Tyre ID</th>
    <th>Brand</th>
    <th>Size</th>
    <th>Li/Si</th>
    <th>Pattern</th>
  </tr>
</thead>
<tbody>
  @foreach ($tyres as $tyre)
    <tr>
      <td>{{$tyre->tyre_id}}</td>
      <td>{{$tyre->brand}}</td>
      <td>{{$tyre->size}}</td>
      <td>{{$tyre->lisi}}</td>
      <td>{{$tyre->pattern}}</td>
    </tr>
  @endforeach
</tbody>
</table>

@if (method_exists($tyres, 'links'))
  {{$tyres->links()}}
@endif

</div>
