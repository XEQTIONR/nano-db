@extends('layouts.app')

@section('title')
  Containers
@endsection
@section('subtitle')
  All containers arrived in country.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'Containers',
    'subcrumb' => 'All Containers',
    'link'  =>  route('consignment_containers.index')])
  @endcomponent
@endsection


@section('header-scripts')
  <style>
    .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
      background-color: #605ca8 !important;
      border-color: #605ca8 !important;
    }
  </style>
@endsection

@section('body')
  <div class="box box-purple">
    <div class="box-body">
      <table id="table_id" class="table table-hover table-bordered">
        <thead>
        <tr>
          <th>Container#</th>
          <th>BOL#</th>
          <th>Created</th>
          <th>Updated</th>
          <th>Stock</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($containers as $container)
          <tr style="cursor: pointer">
            <td>{{$container->Container_num}}</td>
            <td>{{$container->BOL}}</td>
            <td>{{$container->created_at}}</td>
            <td>{{$container->updated_at}}</td>
            <td>
              @if($container->percentage == 0)
                <small class="label bg-gray" data-toggle="tooltip" title="All {{$container->total_sold}} sold">empty</small>
              @elseif($container->percentage == 100)
                <small class="label bg-primary" data-toggle="tooltip" title="{{$container->total_bought}} total">full</small>
              @else
                <div class="progress progress-xs" data-toggle="tooltip" title="{{$container->total_bought - $container->total_sold}}/{{$container->total_bought}} remaining">
                  <div class="progress-bar progress-bar-<?php if($container->percentage<33) echo "danger"; else {  echo $container->percentage<66 ?  "warning" :  "success"; } ?>"
                       style="width: {{$container->percentage}}%"></div>
                </div>
              @endif
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
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
              url: '/consignment_containers/'+rowData[1]+'/'+rowData[0],
              data: {
                  container : rowData[0],
                  consignment : rowData[1]
              },
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
