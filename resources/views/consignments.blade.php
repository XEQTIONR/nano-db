@extends('layouts.app')

@section('title')
  Consignments
@endsection
@section('subtitle')
  All Consignemnts arrived.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'Consignments',
    'subcrumb' => 'All Consignments',
    'link'  =>  route('consignments.index')])
  @endcomponent
@endsection

@section('header-scripts')
  <style>
    .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
      background-color: #30bbbb !important;
      border-color: #30bbbb !important;
    }
  </style>
@endsection

@section('body')
  <div class="box box-teal">
    <div class="box-body">
      <table id="table_id" class="table table-hover table-bordered">
        <thead>
        <tr>
          <th>BOL#</th>
          <th>LC#</th>
          <th>Rate</th>
          <th>Value($)</th>
          <th>Value(&#2547)</th>
          <th>Tax(&#2547)</th>
          <th>Land Date</th>
          <th>Created</th>
          {{--<th>Updated</th>--}}
          <th>Stock</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($consignments as $consignment)
          <tr style="cursor: pointer;" onclick="location.href='/consignments/{{$consignment->BOL}}'">
            <td class="text-center strong">{{$consignment->BOL}}</td>
            <td class="">{{$consignment->lc}}</td>
            <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),$consignment->exchange_rate)}}</td>
            <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),$consignment->value)}}</td>
            <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),$consignment->value * $consignment->exchange_rate)}}</td>
            <td class="text-right text-red">{{numfmt_format(resolve('CurrencyFormatter'),$consignment->tax)}}</td>
            <td class="text-center">{{$consignment->land_date}}</td>
            <td class="text-center">{{$consignment->created_at}}</td>
            {{--<td class="text-center">{{$consignment->updated_at}}</td>--}}
            <td>

              @if($consignment->percentage == 0)
                <small class="label bg-gray" data-toggle="tooltip" title="All {{$consignment->total_sold}} sold">empty</small>
              @elseif($consignment->percentage == 100)
                <small class="label bg-primary" data-toggle="tooltip" title="{{$consignment->total_bought}} total">full</small>
              @else
                <div class="progress progress-xs" data-toggle="tooltip" title="{{$consignment->total_bought - $consignment->total_sold}}/{{$consignment->total_bought}} remaining">
                  <div class="progress-bar progress-bar-<?php if($consignment->percentage<33) echo "danger"; else {  echo $consignment->percentage<66 ?  "warning" :  "success"; } ?>"
                       style="width: {{$consignment->percentage}}%"></div>
                </div>
              @endif
            </td>

          </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
          <th class="text-center" colspan="3"></th>
          <th class="text-right"></th>
          <th class="text-right"></th>
          <th class="text-right text-red"></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
        </tfoot>
      </table>
    </div>
  </div>


@endsection

@section('footer-scripts')
<script>
  $(document).ready(function() {

    let options = [
      { number : 3, prefix : "$ "},
      { number : 4, prefix : "৳ "},
      { number : 5, prefix : "৳ "},
    ];

    customFormatDataTable(options);

      table.order([7, 'desc'])
          .draw();
  });

</script>
@endsection