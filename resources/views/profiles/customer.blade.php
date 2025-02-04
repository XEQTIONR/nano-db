@extends('layout.mainlayout')

@section('scripts')

<script>
  $(document).ready(function(){

    $('.form-control').hide();
    $('#cancelButton').hide();
    $('#submitButton').hide();
    $('#edit').hide();

    $('#editButton').click(function(){
      $(".tyre-info").hide();
      $('#editButton').hide();
      $('#cancelButton').show();
      $(".form-control").show();
      $('#submitButton').show();
      $('.nano-hide').hide();
      $('#edit').show();

    });

    $('#cancelButton').click(function(){
      $(".tyre-info").show();
      $(".form-control").hide();
      $('#editButton').show();
      $('#cancelButton').hide();
      $('#submitButton').hide();
      $('.nano-hide').show();
      $('#edit').hide();

    });

  });


</script>

<script>
  $( function() {
    $("#accordion").accordion({
      collapsible: true,
      active: false
    });
  });
</script>
@endsection

@section('content')

<div class="container">

  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="page-header">
        <h1><span id="edit">Edit </span>Customer Information</h1>
      </div>
    </div>
  </div>

<div class="row">
<div class="col-md-6 col-md-push-3">

<form method="post" action="/customers/{{$customer->id}}">

  {{ csrf_field() }}
  {{ method_field('PUT') }} <!--This replaces the form method to PUT (from POST in this case)-->

  <dl class="dl-horizontal">
    <dt class="nano-hide">Customer ID </dt>
    <dd>
      <span class="tyre-info">{{$customer->id}}</span>
      <input type="hidden" value="{{$customer->id}}" name="inputCustomerId">
    </dd>

    <dt>Customer/Party Name</dt>
    <dd>
      <span class="tyre-info">{{$customer->name}}</span>
      <input type="text" value="{{$customer->name}}" class="form-control" name="inputCustomerName" id="inputCustomerName">
    </dd>

    <dt>Address</dt>
    <dd>
      <span class="tyre-info">{{$customer->address}}</span>
      <input type="text" value="{{$customer->address}}" class="form-control" name="inputAddress" id="inputAddress">
    </dd>

    <dt>Phone #</dt>
    <dd>
      <span class="tyre-info">{{$customer->phone}}</span>
      <input type="text" value="{{$customer->phone}}" class="form-control" name="inputPhone" id="inputPhone">
    </dd>

    <dt>Notes</dt>
    <dd>
      <span class="tyre-info">{{$customer->notes}}</span>
      <input type="text" value="{{$customer->notes}}" class="form-control" name="inputNotes" id="inputNotes">
    </dd>


    <dt class="nano-hide">created_at</dt>
    <dd>
      <span class="tyre-info">{{$customer->created_at}}</span>
    </dd>

    <dt class="nano-hide">updated_at</dt>
    <dd>
      <span class="tyre-info">{{$customer->updated_at}}</span>
    </dd>
  </dl>

</div> <!--col-->
</div> <!--row-->


<div class="row">
<div class="col-md-1 col-md-offset-3">
  <a href="#" id="editButton" class="btn btn-default" role="button">Edit Info</a>
</div>

<div class="col-md-1 col-md-offset-2">
  <a href="#" id="cancelButton" class="btn btn-warning" role="button">Cancel</a>
</div>

<div class="col-md-1">
  <button id="submitButton" class="btn btn-primary" value="submit" type="submit">Submit Changes</button>
</div>
</div>
</form>
</div> <!--container-->


<div id="accordion" class="container">

  <h3>Order History</h3>
  <div>
    <table class="table table-hover table-bordered">
      <tr>
        <th>Order#</th>
        <th>Date</th>
        <th>Total Value</th>
      </tr>

      @foreach($orders as $order)
        <tr>
          <td>{{$order->Order_num}}</td>
          <td>{{$order->created_at}}</td>
          <td></td>

          <td><a href="/orders/{{$order->Order_num}}"
              class="btn btn-success">More Info</a></td>
        </tr>
      @endforeach

    </table>
  </div>

  <h3>Payment History</h3>
  <div>
    <table class="table table-hover table-bordered">
      <tr>
        <th>Order #</th>
        <th>Invoice #</th>
        <th>Total Amount</th>
        <th>Payment Date</th>
      </tr>

      @foreach($payments as $payment)
        <tr>
          <td>{{$payment->Invoice_num}}</td>
          <td>{{$payment->Order_num}}</td>
          <td>{{$payment->payment_amount}}</td>
          <td>{{$payment->created_at}}</td>

          {{--<td><a href="#"
              class="btn btn-success">New Payment</a></td>--}}
        </tr>
      @endforeach

    </table>

    <a href="/consignments/create/" class="btn btn-primary">Add Consignment</a>
  </div>

</div> <!-- container accordion -->


@endsection
