@extends('layouts.app')

@section('title')
  Payments
@endsection
@section('subtitle')
  All recorded payments made by customers.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'Payments',
    'subcrumb' => 'All payments',
    'link'  =>  route('payments.index')])
  @endcomponent
@endsection

@section('header-scripts')
  <style>
    .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
      background-color: #D81B60 !important;
      border-color: #D81B60 !important;
    }
  </style>
@endsection

@section('body')
  <div class="box box-maroon">
    <div class="box-body">
      @include('partials.tables.payments')
    </div>
  </div>


@endsection


@section('footer-scripts')
  <script>


      function format ( rowData ) {
          console.log('rowData');
          console.log(rowData);
          var div = $('<div/>')
              .addClass( 'loading' )
              .html( '<i style="margin-left: 50%; font-size: 2rem" class="fa fa-spinner fa-pulse"></i>' );
          $.ajax( {
              url: '/orders/'+rowData[1],
              dataType: 'text',
              success: function ( view ) {
                  console.log('successed');
                  console.log(rowData);
                  div
                      .html( view )
                      .removeClass( 'loading' );

                  //div.addClass('zeload');

              },

              error : function(error){

                  div
                      .text('Error : Some kind of error occurred')
                      .removeClass( 'loading' );
                  console.log('error');
                  console.log(error);
              }
          } );

          return div;
      }

      $(document).ready(function() {

          table = $('#table_id').DataTable({
              destroy : true,
              columnDefs :[
                  {targets: [2,3], render : function(data, type, row){

                          if(type == "display")
                            return data;

                          else
                              return data.replace(/,/g, '');

                      }}
              ],
              footerCallback : function(row, data, start, end, display){

                  var api = this.api(), data;

                  var page = $('.dataTables_filter input').val().length>0 ? 'current' : 'all';

                  var num_orders = api
                      .column( 1, {page: page} )
                      .data()
                      .reduce( function (a, b) {
                          return parseFloat(a) + 1;
                      }, 0 );

                  var total = api
                      .column( 2, {page: page} )
                      .data()
                      .reduce( function (a, b) {
                          return parseFloat(a) + parseFloat(b.replace(/,/g, ''));
                      }, 0 );


                  var refund_total = api
                      .column( 3, {page: page} )
                      .data()
                      .reduce( function (a, b) {
                          return parseFloat(a) + parseFloat(b.replace(/,/g, ''));
                      }, 0 );

                  var footer_label = (page == 'current') ? 'TOTAL (current page)' : 'TOTAL (all pages)';



                  $( api.column( 0 ).footer() ).html(footer_label);
                  $( api.column( 1 ).footer() ).html(num_orders);
                  $( api.column( 2 ).footer() ).html(commafy(total));
                  $( api.column( 3 ).footer() ).html(commafy(refund_total));
              }
          });

          table.order([0, 'desc'])
            .draw();

          $('#table_id tbody').on('click', 'tr', function () {
              var tr = $(this).closest('tr');
              var row = table.row( this );


              if ( row.child.isShown() ) {
                  row.child.hide();
                  tr.removeClass('shown');
              }
              else {

                  table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                      this
                          .child(
                              $('anything')
                          )
                          .hide();
                  } );

                  $('.shown').removeClass('shown');
                  row.child( format(row.data()) ).show();
                  tr.addClass('shown');

                  $('.shown + tr').css('background-color', '#f5f5f5');

              }
          } );
      } );




  </script>
@endsection