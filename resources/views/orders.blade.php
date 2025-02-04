@extends('layouts.app')

@section('title')
  Orders
@endsection
@section('subtitle')
  All orders placed.
@endsection

@section('level')
  @component('components.level',['crumb' => 'Orders', 'subcrumb' => 'All orders'])
  @endcomponent
@endsection

@section('header-scripts')
  <style>
  .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
    background-color: #00c0ef !important;
    border-color: #00c0ef !important;
  }
  </style>
@endsection

@section('body')
  <div class="box box-info">
    <div class="box-body">
      @include('partials.tables.orders')
    </div>
  </div>

@endsection

@section('footer-scripts')
<script>


  function format ( rowData ) {
      var div = $('<div/>')
          .addClass( 'loading' )
          .html( '<i style="margin-left: 50%; font-size: 2rem" class="fa fa-spinner fa-pulse"></i>' );

      $.ajax( {
          url: '/orders/'+rowData[0],
          dataType: 'text',
          success: function ( view ) {
              div
                  .html( view )
                  .removeClass( 'loading' );
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

      let options = [
          { number : 4, prefix : "৳ "},
          { number : 5, prefix : "৳ "},
          { number : 6, prefix : "৳ "},
          { number : 7, prefix : "৳ "},
      ];

      customFormatDataTable(options);



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
