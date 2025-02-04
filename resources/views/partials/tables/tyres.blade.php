<table class="table table-hover table-condensed table-responsive">
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
    <tr style="cursor: pointer" onclick="location.href='/tyres/{{$tyre->tyre_id}}'">
      <td class="text-center">{{$tyre->tyre_id}}</td>
      <td>{{$tyre->brand}}</td>
      <td>{{$tyre->size}}</td>
      <td>{{$tyre->lisi}}</td>
      <td>{{$tyre->pattern}}</td>
    </tr>
  @endforeach
</tbody>
</table>

{{--@if ($tyres->links()!=null)--}}
@if (method_exists($tyres, 'links'))
  {{$tyres->links()}}
@endif
