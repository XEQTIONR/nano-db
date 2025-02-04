@extends('layouts.app')


@section('title')
  Customer
@endsection
@section('subtitle')
  Register a new Customer.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'Customers',
    'subcrumb' => 'Add a customer',
     'link' => route('tyres.index')])
  @endcomponent
@endsection

@section('body')


  <div v-cloak class="row justify-content-center">
    <div class="col-xs-12">
      <transition name="custom-classes-transition"
                  enter-active-class="animated fadeIn faster"
                  leave-active-class="animated fadeOut faster"
      >
        {{--<div v-if="is_alert" id="alert" class="alert" :class="alert_class" role="alert">--}}
        {{--<button type="button" class="close" aria-label="Close"><span @click="dismiss_warning()" aria-hidden="true">&times;</span></button>--}}
        {{--<h4><i class="icon fa fa-warning"></i> No Proforma Invoice !</h4>--}}
        {{--You have not entered a proforma invoice. It is recommended that you enter proforma invoice information.--}}
        {{--<button @click="toggle(true)" type="button" class="btn btn-warning ml-5">Click here to skip (not recommended)</button>--}}
        {{--</div>--}}
        <div v-if="is_complete" id="alert" class="alert alert-success"  role="alert">
          <button type="button" class="close" aria-label="Close" data-dismiss="alert"><span @click="dismiss_warning()" aria-hidden="true">&times;</span></button>
          <h4><i class="icon fa fa-check-circle"></i> Done</h4>
          Customer <b>@{{new_id}}</b> has been added. You can keep adding more customers.
        </div>
      </transition>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-xs-12 col-md-8 col-lg-6">
      <div class="box box-orange">
        <div class="box-header">
          <h3 class="page-header ml-3"><i class="icon-users-s fa-user mr-3"></i></i>Add a new customer</h3>
        </div>
        <div class="box-body">
          <form class="form-horizontal" method="post" action="/tyres">
            <div class="box-body m-3">


              {{--<div class="row">--}}
              {{--{{ csrf_field() }}--}}
              <div class="row justify-content-center">
                <div class="col-xs-12">
                  <div class="form-group" :class="{ 'has-error' : errors.name && (name== null || name=='') }">
                    <label for="inputBrand">Full Name</label>
                    <div class="input-group">
                    <span class="input-group-addon">
                      <i class="icon-address-card-r fa-address-card"></i>
                    </span>
                    <input v-model="name" type="text" class="form-control" name="Brand" id="inputBrand" placeholder="Enter customer name" required>
                    </div>
                  </div>

                  <div class="form-group" :class="{ 'has-error' : errors.address && (address== null || address=='') }">
                    <label for="inputSize">Address</label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="icon-store-alt-s fa-store-alt"></i> </span>
                      <textarea v-model="address" type="text" class="form-control" rows="3" placeholder="Enter customer store name and address" required>
                    </textarea>
                    </div>
                  </div>

                  <div class="form-group" :class="{ 'has-error' : errors.phone && (phone== null || phone=='') }">
                    <label for="inputLisi">Phone #</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="fa fa-phone"></i>
                        {{--<i class="fas fa-phone"></i>--}}
                      </span>
                    <input v-model="phone" type="text" class="form-control" name="Lisi" placeholder="Enter customer's contact number">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputPattern">Notes</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="icon-clipboard-list-r fa-clipboard-list" style="font-size: 16px"></i>
                      </span>
                      <textarea v-model="notes" type="text" class="form-control" rows="3" name="Pattern" id="inputPattern" placeholder="Any additional information about this customer (Optional)">
                      </textarea>
                    </div>
                  </div>
                </div>
              </div>



              {{--</div><!--row-->--}}

              <div class="row">
                <div class="col-xs-12 px-0">
                  <button class="btn btn-success pull-right" type="button" @click="save()">
                    <i class="fa fa-check mr-1"></i>
                    Submit
                  </button>
                </div>
              </div>
            </div>
            <!--</div>panel-body-->
            <!--</div> panel-->
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('footer-scripts')

  <script>
      var app = new Vue({
          el : '#app',
          data : {

              name        : null,
              address     : null,
              notes       : null,
              phone       : null,
              is_complete : false,
              errors      : {},
              new_id      : null
          },

          methods : {

              validate : function(){

                  this.errors = {};

                  var is_error = false;

                  if(this.name == null || this.name == "")
                  {
                      this.errors.name = "Enter the customer name";
                      is_error = true;
                  }
                  if(this.address == null || this.address == "")
                  {
                      this.errors.address = "Enter the customer name";
                      is_error = true;
                  }
                  if(this.phone == null || this.phone == "")
                  {
                      this.errors.phone = "Enter the customer name";
                      is_error = true;
                  }

                  return !is_error;
              },

              save : function (){

                  if(this.validate())
                  {
                      $.post("{{ route('customers.store')}}",
                          {
                              "_token" : "{{csrf_token()}}",
                              "name" : this.name,
                              "address" : this.address,
                              "notes" : this.notes,
                              "phone" : this.phone
                          },

                          function(data)
                          {
                              console.log(data);
                              if(data.status == 'success')
                              {
                                  app.is_complete = true;
                                  app.name = null;
                                  app.address = null;
                                  app.notes = null;
                                  app.phone = null;
                                  app.new_id = data.customer_id;

                                  $(window).scrollTop(0);

                                  setTimeout(function(){
                                      app.is_complete = false;
                                      app.new_id = null;
                                  }, 5000);
                              }

                          }
                      );
                  }


              }

          }
      });
  </script>
@endsection
