@extends('layouts.app')

@section('title')
  Letters of credit
@endsection
@section('subtitle')
  Add a proforma invoice for a LC.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'Letters of Credit',
    'subcrumb' => 'An a proforma invoice',
     'link' => route('lcs.index')])
  @endcomponent
@endsection

@section('body')

  <div v-cloak class="row justify-content-center">
    <div class="col-xs-12">
      <transition name="custom-classes-transition"
                  enter-active-class="animated fadeIn faster"
                  leave-active-class="animated fadeOut faster"
      >
        <div v-if="is_complete" id="alert" class="alert alert-success"  role="alert">
          <button type="button" class="close" aria-label="Close" data-dismiss="alert"><span @click="dismiss_warning()" aria-hidden="true">&times;</span></button>
          <h4><i class="icon fa fa-check-circle"></i> Done</h4>
          New letter of credit information has been recorded.
          <a href="{{ route('lcs.index') }}"  class="btn btn-success ml-5">Click here to view all LCs</a>
        </div>
      </transition>
    </div>
  </div>
  <div v-cloak class="row justify-content-center">
    <transition  name="custom-classes-transition"
                 mode="out-in"
                 :enter-active-class="direction? 'animated fadeInRight fast' : 'animated fadeInLeft fast'"
                 :leave-active-class="direction? 'animated fadeOutLeft fast' : 'animated fadeOutRight fast'"
    >
      <div v-if="showForm == 0" class="col-xs-10">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="page-header ml-3"><i class="icon-file-invoice-dollar-s fa-file-invoice-dollar mr-3"></i> Select a Letter of Credit</h3>
          </div>
          <div class="box-body pb-5 pl-5 pr-5">

              <div  class="row border-bottom-header mb-5">
                <div class="col-xs-12 ">
                  @if(count($lcs))
                  <div class="form-group">
                    <label>Select a Letter of Credit without a proforma invoice</label>
                    <select v-model="lc_num" class="form-control">
                      <option value=null disabled>Select a Letter of Credit</option>
                      @foreach($lcs as $lc)
                        <option value="{{$lc->lc_num}}">{{$lc->lc_num}}</option>
                      @endforeach
                    </select>
                  </div>
                  @else
                    <span class="text-center">There are no Letters of credit without a proforma invoice.</span>
                  @endif
                </div>
              </div>
            <transition-group name="custom-classes-transition"
                              enter-active-class="animated fadeIn faster"
                              leave-active-class="animated fadeOut faster"
            >
              <div v-if="lc_num" key="row2" class="row invoice-info">

                <div class="col-sm-4 invoice-col">
                  <b>LC # </b><h4 class="my-0 ml-3 d-inline"><b>@{{ lc_num }}</b></h4> <br>
                  <b>Date Issued:</b> @{{ date_issued }}<br>
                  <b>Date Expiry:</b> @{{ date_expired }}<br>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Applicant</b>
                  <address v-html="applicant">
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Beneficiary</b>
                  <address v-html="beneficiary">
                  </address>
                </div>
                <!-- /.col -->
              </div>
              <div v-if="lc_num" key="row3" class="row  mt-3">
                <div class="col-sm-4 invoice-col">
                  <b>Currency Code: </b> @{{ currency_code }}<br>
                  <b>Exchange Rate:</b> ৳ @{{ exchange_rate | currency }} / @{{ currency_symbol }}<br>
                </div>


                <div class="col-sm-4 invoice-col">
                  <b>LC Value (Foreign): </b> <b class="ml-3">@{{ currency_symbol }} @{{ lc_value | currency}} </b><br>
                  <b>LC Value (Local): </b>  <b class="ml-3">৳</b> <h3 class="d-inline mr-2"><b>@{{ exchange_rate*lc_value | currency }}</b> </h3><br>
                </div>
              </div>
              <button v-if="lc_num" key="row4" type="button" class="btn btn-info pull-right" @click="submit()">
                Continue
                <i class="fa fa-chevron-right pt-1 ml-2"></i>
              </button>
            </transition-group>
          </div>
        </div>
      </div>
      <div  v-if="showForm == 1" key="1"  class="col-xs-7">

        <div class="box box-info">
          <div class="box-header">
            <h3 class="page-header ml-3"><i class="icon-receipt-r fa-receipt mr-3"></i> Enter Proforma Invoice</h3>
          </div>
          <div class="box-body pb-5 pl-5 pr-5">

            <form style="padding: 1rem;">
              <div class="row">
                <div class="col-xs-12 mb-5">

                  <div class="form-group" :class="{ 'has-error' :  errors.invoice_num }">
                    <label>Invoice #</label>
                    {{--<div class="input-group">--}}
                    <input v-model="invoice_num" type="text" class="form-control" placeholder="Enter the proforma invoice #">
                    {{--</div>--}}
                    <span v-if=" errors.invoice_num" class="help-block text-danger">@{{ errors.invoice_num }}</span>
                  </div>
                </div>
              </div>
              <div v-if=" errors.qty" class="row ml-3 mb-2 text-danger">@{{ errors.qty }}</div>
              <div v-if=" errors.unit_price" class="row ml-3 mb-2 text-danger">@{{ errors.unit_price }}</div>
              <div v-if=" errors.blank" class="row ml-3 mb-2 text-danger">@{{ errors.blank }}</div>
              <div class="row pb-1">
                <div class="col-xs-1 text-center"><strong>#</strong></div>
                <div class="col-xs-4 text-center"><strong>Tyre</strong></div>
                <div class="col-xs-2 text-center"><strong>Qty</strong></div>
                <div  class="col-xs-2 text-right"><strong>Unit Price</strong></div>
                <div  class="col-xs-2 text-right"><strong>Sub Total</strong></div>
                <div class="col-xs-1"></div>
              </div>

              <transition-group  name="custom-classes-transition"
                                 enter-active-class="animated fadeInDown"
                                 leave-active-class="animated fadeOutUp fast "
              >
                <div v-if="!proforma_invoice.length" key="default" class="row list-item justify-content-center my-4">
                  <span class="text-center "> Nothing in the proforma invoice yet</span>
                </div>
                <div class="row list-item pt-4" :class="{'bg-light-gray' : !(index%2)}" v-for="(item,index) in proforma_invoice" :key="item.tyre_id" >
                  <div class="col-xs-1 text-center">
                    @{{ index+1 }}
                  </div>
                  <div class="col-xs-4">
                    <b>(@{{ item.tyre_id }})</b> @{{item.brand}} @{{item.size}} @{{ item.lisi }} @{{item.pattern}}
                  </div>
                  <div class="col-xs-2 form-group" :class="{'has-error' : item.qty==0}">
                    <input class="text-right form-control" v-model="item.qty" type="number" step="1" min="1" value="1">
                  </div>
                  <div class="col-xs-2 form-group"  :class="{'has-error' : item.unit_price==0}">
                    <input class="text-right form-control" v-model="item.unit_price" type="number" step="0.01" min="0.01" value="0.01">
                  </div>
                  <div class="col-xs-2 text-right">
                    @{{ currency_symbol }} @{{ subTotal(index) | currency}}
                  </div>
                  <div class="col-xs-1">
                    <a class="text-danger" @click="removeTyre(index)">
                      <i class="icon-minus-circle-s fa-minus-circle mt-1"></i>
                    </a>
                  </div>
                </div>
              </transition-group>
              <div class="row list-item pt-2 border-light-gray">
                <div class="col-xs-8 col-sm-3 col-sm-offset-5">
                  <strong class="font-light-gray">Grand Total</strong>
                </div>
                <div class="col-xs-4 text-right mr-invoice">
                  <strong>@{{ currency_symbol }} @{{grand_total | currency}}/-</strong>
                </div>
              </div>

              <div class="row list-item mt-2 pt-2">
                <div class="col-xs-8 col-sm-3 col-sm-offset-5">
                  <strong>Grand Total <br>(in Taka)</strong>
                </div>
                <div class="col-xs-4 text-right mr-invoice">
                  ৳ @{{(grand_total* exchange_rate) | currency}}/-
                </div>
              </div>
              <div class="row list-item mt-2 pt-2">
                <div class="col-xs-8 col-sm-3 col-sm-offset-5">
                  <strong>Total Qty</strong>
                </div>
                <div class="col-xs-4 text-right mr-invoice pr-1">
                  @{{ total_qty }}
                </div>
              </div>

            </form>
            <button type="button" class="btn btn-default" @click="toggle(false)">
              <i class="fa fa-chevron-left pt-1 mr-2"></i>
              Back
            </button>
            <button type="button" class="btn btn-info pull-right" @click="submit()">
              Continue
              <i class="fa fa-chevron-right pt-1 ml-2"></i>
            </button>
          </div>
        </div>


      </div>

      <div v-if="showForm == 2" key="2" class="col-xs-12">
        <section class="invoice">
          <!-- title row -->
          <div class="row">
            <div class="col-xs-12">
              <h2 class="page-header">
                <i class="fa fa-check mr-3 text-success"></i>Confirm Proforma Invoice
                <small class="pull-right">Date: 2/10/2014</small>
              </h2>
            </div>
            <!-- /.col -->
          </div>
          <!-- info row -->
          <div class="row invoice-info">

            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
              <b>Applicant</b>
              <address v-html="applicant">
              </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
              <b>Beneficiary</b>
              <address v-html="beneficiary">
              </address>
            </div>

            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
              <b>LC # @{{ lc_num }}</b><br>
              <br>
              <b>Invoice # </b> @{{ invoice_num }}<br>

              <b>Date Issued:</b> @{{ date_issued }}<br>
              <b>Date Expiry:</b> @{{ date_expired }}<br>
            </div>

          </div>

          <!-- Table row -->
          <div class="row mt-4">
            <div class="col-xs-12 table-responsive">
              <table class="table table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Tyre</th>
                  <th>Qty</th>
                  <th>Unit Price</th>
                  <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(record, index) in proforma_invoice">
                  <td>@{{ index+1 }}</td>
                  <td><b>(@{{ record.tyre_id }})</b> @{{ record.brand }} @{{ record.size }} @{{ record.lisi }} @{{ record.pattern }}</td>
                  <td>@{{ record.qty }}</td>
                  <td>@{{ record.unit_price }}</td>
                  <td>@{{ currency_symbol }} @{{ record.qty*record.unit_price | currency }}</td>
                </tr>

                </tbody>
              </table>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <div class="row">
            <div class="col-xs-6">
              <div class="row">
                <p class="lead ml-5">Additional information</p>
              </div>
              <div class="row invoice-info well ml-1 mr-1 mb-4">
                <div class="col-sm-6 invoice-col">
                  <b>Foreign Currency Code:</b> @{{ currency_code }}<br>
                  <b>Exchange Rate:</b> ৳ @{{ exchange_rate | currency }} / @{{ currency_symbol }}<br>
                  <br>
                  <b>Departing Port:</b> @{{ departing_port }}<br>
                  <b>Arriving Port:</b> @{{ arriving_port }}<br>

                </div>

                <div class="col-sm-6 invoice-col">
                  <b>Expenses Foreign: </b> @{{ currency_symbol }} @{{ expense_foreign | currency }}<br>
                  <b>Expenses Local: </b> ৳ @{{ expense_local }}<br>
                  <br>
                  <b>LC Value : </b>@{{ currency_symbol }} @{{ lc_value | currency }}<br>
                  <b>LC Value in Taka: </b> ৳ @{{ lc_value*exchange_rate | currency }}<br>

                </div>

                <!-- /.col -->
              </div>
            </div>
            <div class="col-xs-6">
              {{--<p class="lead">Amount Due 2/22/2014</p>--}}

              <div class="table-responsive mt-5 pt-3">
                <table class="table">
                  <tbody><tr>
                    <th style="width:50%">Grand Total</th>
                    <td>@{{ currency_symbol }} @{{ grand_total | currency }}</td>
                  </tr>
                  <tr>
                    <th>Grand Total in TK</th>
                    <td>৳ @{{ grand_total*exchange_rate | currency }}</td>
                  </tr>
                  <tr>
                    <th>Expenses Foreign</th>
                    <td>@{{ currency_symbol }} @{{ expense_foreign }}</td>
                  </tr>
                  <tr>
                    <th>Expense Local:</th>
                    <td>৳ @{{ expense_local }}</td>
                  </tr>
                  </tbody></table>
              </div>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <!-- this row will not appear when printing -->
          <div v-if="!is_complete" class="row no-print">
            <div class="col-xs-12 border-light-gray pt-3">
              <button type="button" class="btn btn-default" @click="toggle(false)">
                <i class="fa fa-chevron-left pt-1 mr-2"></i>
                Back
              </button>
              <button type="button" class="btn btn-success pull-right" @click="toggle(true)">
                <i class="fa fa-check mr-2"></i>
                Confirm
              </button>
            </div>
          </div>
        </section>


      </div>




    </transition>
    <transition name="custom-classes-transition"
                :enter-active-class="direction? 'animated fadeInRight delay-1s fast' : 'animated fadeInLeft delay-1s fast'"
                :leave-active-class="direction? 'animated fadeOutLeft fast' : 'animated fadeOutRight fast'" >
      <div v-show="showForm == 1" class="col-xs-5">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Tyre Catalog</h3>
          </div>
          <div class="box-body">
            <table id ="table_id" class="table table-hover table-bordered">
              <thead>
              <tr>
                <th>Tyre ID</th>
                <th>Brand</th>
                <th>Size</th>
                <th>Li/Si</th>
                <th>Pattern</th>
                <th></th>

              </tr>
              </thead>
              <tbody>
              @if(isset($tyres) && count($tyres))
                @foreach ($tyres as $tyre)
                  <tr>
                    <td>{{$tyre->tyre_id}}</td>
                    <td>{{$tyre->brand}}</td>
                    <td>{{$tyre->size}}</td>
                    <td>{{$tyre->lisi}}</td>
                    <td>{{$tyre->pattern}}</td>
                    <td>
                      <a class="text-success" @click="addTyre({{$tyre->tyre_id}})">
                        <i class="icon-plus-circle-s fa-plus-circle"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
              @else
                <td class="text-center" colspan="5"> <strong>There are currently no tyres in stock</strong></td>
              @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </transition>

  </div>

@endsection

@section('footer-scripts')

  <script>

      var tyre_catalog = JSON.parse('{!! json_encode($tyres) !!}');


      Vue.filter('currency', function (value) {
          return parseFloat(value).toFixed(2);
      });

      var app = new Vue({
          el: '#app',
          data: {
              showForm : 0,
              showCatalog : false,
              tyre_catalog : tyre_catalog,

              lcs :  JSON.parse('{!! json_encode($lcs) !!}'),
              lc_num : null,
              invoice_num : null,
              date_issued : null,
              date_expired : null,
              applicant : null,
              beneficiary : null,
              departing_port : null,
              arriving_port : null,
              currency_code : null,
              exchange_rate : null,
              lc_value : null,
              expense_foreign : "",
              expense_local : "",
              notes : null,

              proforma_invoice : [],

              direction : true,

              date_flag: false,
              date1: null,
              date2: null,
              currency_symbol: '$',

              errors : [],
              is_alert : false,
              is_complete : false,
              is_duplicate : false,

              is_verifying : false,

              alert_class : 'alert-warning',



          },
          computed:{



              lc_local_value : function()
              {
                  var ret_val = 0;
                  if(!isNaN(this.lc_value)  && !isNaN(this.exchange_rate))
                      ret_val = this.lc_value * this.exchange_rate;
                  return ret_val;
              },

              grand_total : function(){

                  var ret_val = 0;
                  if(this.proforma_invoice.length)
                  {

                      this.proforma_invoice.forEach(function(value, index){

                          if(
                              typeof (value.qty) !== 'undefined' &&
                              typeof (value.unit_price) !== 'undefined' &&
                              value.qty.length &&
                              value.unit_price.length
                          )
                              ret_val += parseInt(value.qty) * parseFloat(value.unit_price)

                      });


                  }
                  return ret_val;
              },

              total_qty : function(){

                  var ret_val = 0;

                  this.proforma_invoice.forEach(function(value){
                      ret_val += parseInt(value.qty);
                  });

                  return ret_val;

              }
          },
          watch : {
              lc_num : function(new_val){

                  this.lcs.forEach(function(item){
                    if(item.lc_num==new_val)
                    {
                        app.applicant = item.applicant;
                        app.beneficiary = item.beneficiary;


                        app.arriving_port = item.port_arrive;
                        app.departing_port = item.port_depart;
                        app.date_issued = item.date_issued;
                        app.date_expired = item.date_expiry;
                        app.lc_value = parseFloat(item.foreign_amount);
                        app.exchange_rate = parseFloat(item.exchange_rate);
                        app.currency_code = item.currency_code;
                        if(currencies[item.currency_code])
                          app.currency_symbol = currencies[item.currency_code];
                    }
                  });
              }
          },

          methods: {
              dismiss_warning : function(){
                  this.is_alert = false;

              },

              validate : function(){

                  var errors = [];
                  console.log("VALIDATING");
                  console.log("showform= " + this.showForm);
                  switch (this.showForm)
                  {
                      case 1 :
                          if(!this.invoice_num || !this.invoice_num.length)
                              errors['invoice_num'] = 'The proforma invoice number is required.';

                          //
                          else if (!this.proforma_invoice.length)
                          {
                              errors['blank'] = 'There are no items in the proforma invoice. Add items from the catalog.';
                          }
                          else{
                              this.proforma_invoice.forEach(function(value){

                                  if(value.unit_price<=0)
                                      errors['unit_price'] = 'Unit price must be greater than zero.';
                                  if(value.qty<=0)
                                      errors['qty'] = 'Quantity must be greater than zero.';
                              });
                          }
                          break;
                  }

                  if( Object.entries(errors).length) // because errors is an obj and does not have length
                      return { status : 'error', 'errors' : errors };
                  return {status : 'success'};
              },

              submit : function(){

                  this.is_alert = false;
                  this.errors = [];

                  var validate = this.validate();

                  if(validate.status == 'success')
                      this.toggle(true);

                  else if(validate.errors)//errors
                      this.errors = validate.errors;

              },

              save : function(){

                  $.post("{{route('proforma_invoice.store')}}",
                      {
                          "_token" : "{{csrf_token()}}",

                          "lc_num" : this.lc_num,
                          "invoice_num" : this.invoice_num,
                          "proforma_invoice": this.proforma_invoice
                      } ,
                      function(data)
                      {
                          if(data.status == 'success')
                              app.is_complete = true;
                      });

              },

              subTotal : function(i){

                  if(this.proforma_invoice.length>i &&
                      this.proforma_invoice.length>0 &&
                      typeof (this.proforma_invoice[i].qty) !== 'undefined' &&
                      typeof (this.proforma_invoice[i].unit_price) !== 'undefined' &&
                      this.proforma_invoice[i].qty.length &&
                      this.proforma_invoice[i].unit_price.length
                  )
                      return parseInt(this.proforma_invoice[i].qty) * parseFloat(this.proforma_invoice[i].unit_price);
                  return 0;
              },


              toggle : function(direction){

                  this.errors = [];
                  this.is_alert = false;

                  this.direction = direction;

                  if(direction)
                      (this.showForm  == 2) ? this.save() : this.showForm++;
                  else
                      (this.showForm  == 0) ? this.showForm = 2 : this.showForm--;

                  window.scrollTo(0,0);

              },

              tyreCatalog : function(){

                  this.showCatalog = !this.showCatalog;
              },

              addTyre : function(id){
                  for(var i=0; i<this.tyre_catalog.length; i++)
                  {
                      if(tyre_catalog[i].tyre_id == id)
                      {
                          var obj = Object.assign ({}, tyre_catalog[i]);

                          this.proforma_invoice.push(obj);
                          break;
                      }
                  }
              },

              removeTyre : function(index){

                  this.proforma_invoice = this.proforma_invoice.filter(function(value, i, array){
                      // all items except for the current index
                      return index != i;
                  });
              },

              helperPositiveFloat : function(new_val, who){
                  if(!(parseFloat(new_val)>= 0 ) )
                  {
                      app[who] = 0;
                  }

                  var leading = 0;
                  var lead_mid = false;
                  var decimal_count = 0;
                  var lead_or_trail = "lead";

                  for(var i=0; i<new_val.length; new_val++)
                  {
                      if(new_val[i] == '0')
                      {
                          if(lead_or_trail == "lead" && !lead_mid)
                              leading++;
                      }
                      else if(new_val[i] == '.')
                      {
                          decimal_count++;
                          lead_or_trail = "trail";
                      }
                      else{
                          if(lead_or_trail == "lead")
                              lead_mid = true;
                      }
                  }

                  if(decimal_count>1)
                      app[who] = 0;
                  else if(leading>0)
                      app[who] = app[who].substr(leading);


              }
          }

      })
  </script>
@endsection