@extends('layouts.app')

@section('title')
  Customers
@endsection
@section('subtitle')
  All our customers, past and present.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'Customers',
    'subcrumb' => 'All customers',
    'link' => route('customers.index')])
  @endcomponent
@endsection

@section('header-scripts')
  <style>
    .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
      background-color: #ff851b !important;
      border-color: #ff851b !important;
    }
  </style>
@endsection

@section('body')
  <div class="box box-orange">
    <div class="box-body">
      <table id ="table_id" class="table table-striped table-bordered table-condensed">
        <thead>
        <tr>
          <th class="col-xs-1">Customer ID</th>
          <th class="col-xs-2">Name</th>
          <th class="col-xs-2">Address</th>
          <th class="col-xs-1">Phone</th>
          <th class="col-xs-1"># of Orders</th>
          <th class="col-xs-1">Orders Total</th>
          <th class="col-xs-1">Revenue</th>
          <!-- something --><th class="col-xs-1">Commission</th>
          <th class="col-xs-1">Balance</th>
          <th class="col-xs-1">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($customers as $customer)
          <tr>
            <td class="col-xs-1 text-center strong">{{$customer->id}}</td>
            <td class="col-xs-2">{{$customer->name}}</td>
            <td class="col-xs-2">{{$customer->address}}</td>
            <td class="col-xs-1">{{$customer->phone}}</td>
            <td class="col-xs-1 text-center">{{$customer->number_of_orders}}</td>
            <td class="col-xs-1 text-right">{{$customer->sum_grand_total}}</td>
            <td class="col-xs-1 text-right">{{$customer->sum_payments_total}}</td>
            <td class="col-xs-1 text-right">{{$customer->sum_commission}}</td>
            <td class="col-xs-1 text-right strong @if(floatval($customer->balance_total)>0) text-red @else text-green @endif">{{$customer->balance_total}}</td>
            <td class="col-xs-1 text-center">
              <div class="btn-group" > <!-- style="display: block; min-width: 80px" -->
                <button type="button" data-toggle="tooltip" title="Edit" onclick="startEdit({{$customer->id}})" class="btn bg-orange-active" style="border-color : #FFF"><i class="fa  fa-edit"></i></button>
              </div>
            </td>
          </tr>
        @endforeach
        <tbody>
        <tfoot>
          <tr>
            <th class="col-xs-3 text-center" colspan="2"></th>
            <th class="col-xs-2"></th>
            <th class="col-xs-1"></th>

            <th class="col-xs-1 text-center"></th>

            <th class="col-xs-1 text-right"></th>
            <th class="col-xs-1 text-right"></th>
            <th class="col-xs-1 text-right"></th>
            <th class="col-xs-1 text-right text-red"></th>
            <th class="col-xs-1"></th>


          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <div class="modal modal-warning fade in" id="modalForm">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
          <h4 class="modal-title"> <i class="icon-users-s  mr-2" style="position: relative; top: 3px"></i> Edit Customer</h4>
        </div>
        <div class="modal-body">
          <div class="spinner-container" style="margin-top: 9vh; margin-bottom: 10vh;">
          <i style="margin-left: 48%; font-size: 2rem" class="fa fa-spinner fa-pulse"></i><br>
            <p class="text-center">Loading</p>
          </div>
          <div class="success-container">
            <i class="fa fa-check mr-3 text-success"></i> Customer successfully updated.
          </div>
          <form role="form" class="form">

            <input type="hidden" id="customer_id" class="form-control" placeholder="Enter ...">

            <!-- text input -->
            <div class="form-group">
              <label>Name</label>
              <input type="text" id="name" class="form-control" placeholder="Enter ...">
            </div>

            <div class="form-group">
              <label>Address</label>
              <textarea id="address" class="form-control" rows="3" placeholder="Enter ..."></textarea>
            </div>

            <div class="form-group">
              <label>Phone</label>
              <input type="text" id="phone" class="form-control" placeholder="Enter ...">
            </div>

            <!-- textarea -->

            <div class="form-group">
              <label>Notes</label>
              <textarea id="notes" class="form-control" rows="3" placeholder="Enter ..."></textarea>
            </div>

            <a onclick="finishEdit()" class="btn btn-flat btn-block btn-success">Update Customer</a>

          </form>
        </div>
      </div>
      <!-- /.modal-content -->
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
              url: '/customers/'+rowData[0],
              dataType: 'text',
              success: function ( view ) {
                  console.log('successed');
                  console.log(rowData);
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

      function startEdit(customer_id){

          $('#modalForm').modal();
          $('.spinner-container').show();
          $('.form').hide();
          $.ajax({
              url: '{{route('api.customers')}}',
              method: "POST",
              data : {
                  _token : "{{csrf_token()}}",
                  customer : customer_id
              },
              dataType : 'json',
              success : function(data){
                  console.log("success editCustomer");
                  console.log(data);

                  $('#customer_id').val(data.id);
                  $('#name').val(data.name);
                  $('#address').val(data.address);
                  $('#phone').val(data.phone);
                  $('#notes').val(data.notes);
                  $('.spinner-container').hide();
                  $('.form').show();

              },
              error : function(error){
                  console.log("ERROR editCustomer");
                  console.log(error);
              }

          });



      }

      function finishEdit(){

          var data = {
              _token : "{{csrf_token()}}",
              customer :  $('#customer_id').val(),
              name : $('#name').val(),
              address :  $('#address').val(),
              phone :  $('#phone').val(),
              notes :  $('#notes').val()
          };
          console.log(data);

          $.ajax({
              url: '{{route('api.customers.update')}}',
              method: "POST",
              data : data,
              success : function(data){
                  console.log("SUCCESS EDITING");
                console.log(data);
                $(".form").hide();
                $(".success-container").show();

                setTimeout(function(){
                    window.location.href = '{{route('customers.index')}}';
                }, 3000);
              },
              error : function(error){
                  console.log("Error saving");
              }
          });
      }

      $(document).ready(function() {



          table = $('#table_id').DataTable({
              destroy : true,
              columnDefs :[
                  {targets: [5,6,7,8], render : function(data, type, row){

                          if(type == "display")

                              if(data == '')
                                  return "0.00";
                              else
                                  return commafy(parseFloat(data));
                          else
                              return parseFloat(data);

                      }
                  },
                  {targets : 4, render : function(data, type, row){

                          if(type=="display"){
                              if(!isNaN(parseInt(data)))
                                  return parseInt(data);
                              return 0;
                          }
                          else
                              return data;

                      }
                  }
              ],
              footerCallback : function(row, data, start, end, display){
                  var api = this.api();

                  var page = $('.dataTables_filter input').val().length>0 ? 'current' : 'all';


                  var number_of_orders_total = api
                      .column( 4, {page: page} )
                      .data()
                      .reduce( function (a, b) {
                          console.log('b = ' + typeof b);
                          console.log('b = ' + b);
                          if(b == null || b=="")
                              return parseInt(a);
                          return parseInt(a) + parseInt(b);
                      }, 0 );

                  var total = api
                      .column( 5, {page: page} )
                      .data()
                      .reduce( function (a, b) {
                          console.log('b => ' + typeof b);
                          console.log('b = ' + b);
                          if(b == null || b=="")
                              return parseFloat(a);
                          return parseFloat(a) + parseFloat(b.replace(/,/g, ''));
                      }, 0 );

                  var payments_total = api
                      .column( 6, {page: page} )
                      .data()
                      .reduce( function (a, b) {
                          if(b == null || b=="")
                              return parseFloat(a);
                          return parseFloat(a) + parseFloat(b.replace(/,/g, ''));
                      }, 0 );


                  var commission_total = api
                      .column( 7, {page: page} )
                      .data()
                      .reduce( function (a, b) {
                          if(b == null || b=="")
                              return parseFloat(a);
                          return parseFloat(a) + parseFloat(b);
                      }, 0 );

                  var balance_total = api
                  .column( 8, {page: page} )
                  .data()
                  .reduce( function (a, b) {
                      if(b == null || b=="")
                          return parseFloat(a);
                      return parseFloat(a) + parseFloat(b);
                  }, 0 );

                  var footer_label = (page == 'current') ? 'TOTAL (current page)' : 'TOTAL (all pages)';



                  $( api.column( 0 ).footer() ).html(footer_label);
                  $( api.column( 4 ).footer() ).html(parseInt(number_of_orders_total));
                  $( api.column( 5 ).footer() ).html(commafy(total));
                  $( api.column( 6 ).footer() ).html(commafy(payments_total));
                  $( api.column( 7 ).footer() ).html(commafy(commission_total));
                  $( api.column( 8 ).footer() ).html(commafy(balance_total));
              }
          });

          table.order([8, 'desc'])
              .draw();




          $('.success-container').hide();
          $('#table_id tbody').on('click', 'tr', function (e) {

                  var tr = $(this).closest('tr');
                  var row = table.row(this);

                  console.log(row);

                  if (row.child.isShown()) {
                      row.child.hide();
                      tr.removeClass('shown');
                  } else {

                      table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                          this
                              .child(
                                  $('anything')
                              )
                              .hide();
                      });

                      $('.shown').removeClass('shown');
                      row.child(format(row.data())).show();
                      tr.addClass('shown');

                      $('.shown + tr').css('background-color', '#f5f5f5');

                  }
          } );

          $('#table_id tbody .btn-group').on('click', 'button', function (e) {
              e.stopPropagation(); // (A) .so that event doesn't spill over to tr
          });

          $("#table_id").on('draw.dt', function(){
              $('#table_id tbody .btn-group').on('click', 'button', function (e) {
                  e.stopPropagation(); // Do (A) Again on table redraw
              });
          })

      } );




  </script>

@endsection
