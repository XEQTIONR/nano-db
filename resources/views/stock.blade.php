@extends('layouts.app')

@section('title')
  Current Stock
@endsection
@section('subtitle')
  Our current inventory, items we can sell.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'Stock',
    'subcrumb' => 'Current inventory',
    'link' => route('stock')])
  @endcomponent
@endsection

@section('header-scripts')
  <style>
    .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
      background-color: #727272 !important;
      border-color: #727272 !important;
    }
  </style>
@endsection

@section('body')
  <div class="box" id="app">
    <div class="box-body">
      <table id="table_id" class="table table-striped table-bordered">
        <thead>
        <tr>
          <th class="text-center col-xs-1"> Tyre ID </th>
          <th class=" col-xs-3"> Brand </th>
          <th class=" col-xs-2"> Size </th>
          <th class=" col-xs-2"> Pattern </th>
          <th class=" col-xs-2"> LiSi </th>
          <th class="text-center col-xs-2"># in stock</th>
        </tr>
        </thead>
        <tbody>

        @foreach($in_stock as $item)
        @if($item->in_stock > 0)
        <tr>
          <td class="text-center col-xs-1 strong text-center">{{$item->tyre_id}}</td>
          <td class="col-xs-3">{{$item->brand}}</td>
          <td class="col-xs-2">{{$item->size}}</td>
          <td class="col-xs-2">{{$item->pattern}}</td>
          <td class="col-xs-2">{{$item->lisi}}</td>
          <td class="text-center col-xs-2 strong">{{$item->in_stock}}</td>
        </tr>
        @endif
        @endforeach
        </tbody>
        <tfoot>
        <tr>
          <th class="text-center" colspan="3"></th>
          <th></th>
          <th class="text-right"></th>
          <th class="text-center"></th>
{{--          <th class="text-right text-red"></th>--}}
        </tr>
        </tfoot>
      </table>
    </div>
  </div>

@endsection

@section('footer-scripts')
  <script>
      $(document).ready(function() {


          table = $('#table_id').DataTable({
              destroy : true,
              // columnDefs :[
              //     {targets: [4,5,6], render : function(data, type, row){
              //
              //             if(type == "display")
              //                 return number_format(parseFloat(data), 2);
              //
              //             else
              //                 return data;
              //
              //         }}
              // ],
              footerCallback : function(row, data, start, end, display){
                  //console.log("FOOTER CALLBACK");
                  //console.log(row);
                  var api = this.api(), data;

                  var page = $('.dataTables_filter input').val().length>0 ? 'current' : 'all';


                  var total = api
                      .column( 5, {page: page} )
                      .data()
                      .reduce( function (a, b) {
                          return parseFloat(a) + parseFloat(b);
                      }, 0 );

                  // var payments_total = api
                  //     .column( 5, {page: page} )
                  //     .data()
                  //     .reduce( function (a, b) {
                  //         return parseFloat(a) + parseFloat(b);
                  //     }, 0 );
                  //
                  //
                  // var balance_total = api
                  //     .column( 6, {page: page} )
                  //     .data()
                  //     .reduce( function (a, b) {
                  //         return parseFloat(a) + parseFloat(b);
                  //     }, 0 );

                  var footer_label = (page == 'current') ? 'TOTAL (current page)' : 'TOTAL (all pages)';



                  $( api.column( 0 ).footer() ).html(footer_label);
                  // $( api.column( 4 ).footer() ).html(number_format(total,2));
                  // $( api.column( 5 ).footer() ).html(number_format(payments_total, 2));
                  $( api.column( 5 ).footer() ).html(total);
              }
          });





          // table.order([5, 'desc'])
          //     .draw();
      });

  </script>
@endsection