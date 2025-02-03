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

@endsection
@section('content')

<div class="container"> <!-- bootsreap container -->
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="page-header">
        <h1><span id="edit">Edit </span>Tyre information</h1>
      </div>
    </div>
  </div>

<form method="post" action="/tyres/{{$tyre->tyre_id}}">

  {{  csrf_field()  }}
  {{  method_field('PUT') }} <!-- overwrite default POST method -->
  <div class="row">
    <div class="col-md-6 col-md-push-3">
      <dl class="dl-horizontal">
        <dt>Tyre ID</dt>
        <dd>{{$tyre->tyre_id}}</dd>

        <dt>Brand</dt>
        <dd>
          <span class="tyre-info">{{$tyre->brand}}</span>
          <input type="text" value="{{$tyre->brand}}" class="form-control" name="inputTyreBrand" id="inputTyreBrand">
        </dd>

        <dt>Size</dt>
        <dd>
          <span class="tyre-info">{{$tyre->size}}</span>
          <input type="text" value="{{$tyre->size}}" class="form-control" name="inputTyreSize" id="inputTyreSize">
        </dd>

        <dt>Pattern</dt>
        <dd>
          <span class="tyre-info">{{$tyre->pattern}}</span>
          <input type="text" value="{{$tyre->pattern}}" class="form-control" name="inputTyrePattern" id="inputTyrePattern">
        </dd>

        <dt class="nano-hide">created_at</dt>
          <span class="tyre-info"><dd>{{$tyre->created_at}}</span>
        </dd>

        <dt class="nano-hide">updated_at</dt>
          <span class="tyre-info"><dd>{{$tyre->updated_at}}</span>
        </dd>
      </dl>
    </div>
  </div>

  <div class="row">
  <div class="col-md-8 col-md-push-4">
    <button type="button" id="editButton" class="btn">Edit</button>
    <button type="button" id="cancelButton" class="btn">Cancel</button>
    <button type="submit" value="submit" id="submitButton" class="btn btn-primary">Submit</button>
  </div>
  </div>
</form>
</div> <!-- container-->
@endsection
