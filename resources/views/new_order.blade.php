@extends('layouts.app')

@section('title')
  Orders
@endsection
@section('subtitle')
  Create a new Order.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'Orders',
    'subcrumb' => 'Create a new Order',
     'link' => route('orders.create')])
  @endcomponent
@endsection

@section('body')

  <div v-cloak class="row justify-content-center no-print">
    <div class="col-xs-12">
      <transition name="custom-classes-transition"
                  enter-active-class="animated fadeIn faster"
                  leave-active-class="animated fadeOut faster"
      >
        <div v-if="is_complete" id="alert" class="alert alert-success"  role="alert">
          <button type="button" class="close" aria-label="Close" data-dismiss="alert"><span @click="dismiss_warning()" aria-hidden="true">&times;</span></button>
          <h4><i class="icon icon-check-circle-s fa-check-circle"></i> Done</h4>
          New order saved
          <a href="{{ route('orders.index') }}"  class="btn btn-success ml-5">Click here to view all orders</a>
        </div>
      </transition>
    </div>
  </div>
  <div v-cloak class="modal modal-danger fade in" id="modal-error">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
          <h4 class="modal-title"> <i class="fa fa-times-circle mr-2"></i> Error</h4>
        </div>
        <div class="modal-body">
          <p>@{{ error_message }}</p>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div v-cloak class="row justify-content-center">
    <transition  name="custom-classes-transition"
                 mode="out-in"
                 :enter-active-class="toggle? 'animated fadeInRight' : 'animated fadeInLeft'"
                 :leave-active-class="toggle? 'animated fadeOutLeft' : 'animated fadeOutRight'"
    >
      <div v-if="toggle==false" :key="false" class="col-xs-12 col-md-6">
        <div class="box box-info">
          <div class="box-header">
            <h3 class="page-header ml-3"><i class="icon-dolly-s fa-dolly mr-3"></i>Enter new order information</h3>
          </div>
          <div class="box-body">
            <div class="form">
              <div class="box-body">
                <div class="row mb-4">
                  <div class="col-xs-6">
                    <div class="form-group" :class="{'has-error' : errors.past_date}">
                    <label v-show="past == 1">Enter Order date</label>
                    <div v-show="past == 1" class="input-group">
                      <input v-model="past_date" @blur="copyDate()" @click="datetify()" id="pastDate" type="text" class="form-control date" placeholder="dd/mm/yyyy">
                      <div class="input-group-addon">
                        <i class="icon-calendar-alt-s fa-calendar-alt"></i>
                      </div>
                    </div>
                    </div>
                  </div>

                  <div class="col-xs-6 col-md-8 col-lg-9">
                    <button v-if="past == 1" @click="past = 0" class="btn btn-warning btn-xs pull-right mt-2">Past Order</button>
                    <button v-else @click="past = 1" class="btn btn-primary btn-xs pull-right mt-2">Current Order</button>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <div class="form-group" :class="{'has-error' : errors.customer && customer==null}">
                      <label for="inputCustomerId">Customer</label>
                      <v-select id="customer" class="form-control" placeholder="Select a customer"
                                v-model="customer" :options="customers" label="name"
                      >
                        <template slot="option" slot-scope="option">
                          <b>@{{ option.name }}</b> -- @{{ option.address_single_line }}

                        </template>
                      </v-select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">



                    <div id="itemList" class="">

                      <table class="table table-bordered table-striped">

                        <thead>
                          <tr>
                            <th style="width: 5%"  >#</th>
                            <th style="width: 45%" >Tyre</th>
                            <th style="width: 15%" >Qty</th>
                            <th style="width: 15%" >Unit Price</th>
                            <th style="width: 15%" class="text-right" >Subtotal</th>
                            <th style="width: 5%" ></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr id="selector" class="selector" v-for="(content,index) in order_contents">
                            <td style="width: 5%;" >@{{ index+1 }}</td>
                            <td style="width: 45%;" ><b>(@{{ content.tyre_id }})</b> @{{ content.brand }} @{{ content.size }} @{{ content.pattern }} @{{ content.lisi }}</td>
                            <td style="width: 15%;" >
                              <div class="form-group" :class="{'has-error' : (errors.qty && parseInt(content.qty)<=0) || (helperStockLive(content.i)<0)}">
                                <input class="text-right form-control " type="number" step="1" min="1" value="1"
                                       v-model="content.qty" @keyup="content.qty = helperValidQty(content.qty, content.i)">
                              </div>
                            </td>
                            <td style="width: 15%;" >
                              <div class="form-group" :class="{'has-error' : errors.unit_price && !parseFloat(content.unit_price)>0}">
                                <input class="text-right form-control" type="number" step="1" min="1" value="1"
                                       v-model="content.unit_price" @keyup="content.unit_price = helperValidUnitPrice(content.unit_price)">
                              </div>
                            </td>
                            <td style="width: 15%;" class="text-right" >৳ @{{ parseFloat(content.qty) * parseFloat(content.unit_price) | currency}}</td>
                            <td style="width: 5%;" >
                              <a class="text-danger" @click="remove(index)">
                                <i class="icon-minus-circle-s fa-minus-circle mt-1"></i>
                              </a>
                            </td>
                          </tr>

                          <tr id="subTotal" class="">
                            <th style="width: 5%"></th>
                            <th style="width: 45%">Total</th>
                            <th style="width: 15%" class="text-right" style="padding-right: 5%;">@{{ totalQty }}</th>
                            <th style="width: 15%"></th>
                            <th style="width: 15%" class="text-right"  scope="col">৳ @{{ subTotal | currency }}</th>
                            <th style="width: 5%" ></th>
                          </tr>

                          <tr id="discount" class="warning">
                            <th style="width: 5%"></th>
                            <th style="width: 45%">Discount</th>
                            <th class="text-right" style="width: 30%" colspan="2"><i class="fa fa-minus"></i></th>
                            <th style="width: 15%" class="text-right"  scope="col">৳ @{{ total_discount_amount | currency }}</th>
                            <th style="width: 5%" ></th>
                          </tr>
                          <tr id="tax" class="danger">
                            <th style="width: 5%"></th>
                            <th style="width: 45%">Tax</th>
                            <th class="text-right" style="width: 30%" colspan="2"><i class="fa fa-plus"></i></th>
                            <th style="width: 15%" class="text-right">৳ @{{ total_tax_amount | currency }}</th>
                            <th style="width: 5%" ></th>
                          </tr>
                          <tr id="grandTotal" class="info">
                            <th style="width: 5%"></th>
                            <th style="width: 45%" class="text-uppercase">Grand Total</th>
                            <th style="width: 45%" class="text-right"  colspan="3">৳ @{{ grandTotal | currency }}</th>
                            <th style="width: 5%" ></th>
                          </tr>
                        </tbody>

                      </table>
                    </div>
                    <br>

                    <transition-group  name="custom-classes-transition"
                                 {{--mode="out-in"--}}
                                 enter-active-class="animated fadeIn fast"
                                 leave-active-class="animated fadeOut fast"
                    >
                    <div v-if="order_contents.length" key="1" class="form-group col-xs-12 px-0">
                      <label for="inputTaxAmount" class="col-xs-12 col-md-2 control-label pl-0">Num items</label>
                      <div class="col-xs-3">
                        <input v-model="order_contents.length" type="text" class="form-control"  readonly>
                      </div>
                    </div>

                    <div v-if="order_contents.length" key="2" class="form-group col-xs-12 px-0">
                      <label for="discountPercent" class="col-xs-12 control-label px-0">Discount</label>
                      <div class="col-xs-4 px-0">
                        <div class="input-group">
                          <input v-model="discount_percent" type="number" min="0" step="0.01" class="form-control" >
                          <div class="input-group-addon">
                            <i class="icon-percent-s fa-percent"></i>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-4">
                        <span class="text-center d-block mx-auto">and / or</span>
                      </div>
                      <div class="col-xs-4 px-0">
                        <div class="input-group">
                          <div class="input-group-addon"><b>৳</b></div>
                          <input v-model="discount_amount" type="number" class="form-control" >
                        </div>
                      </div>
                      <label v-if="discount_percentage_amount>0" class="col-xs-4 control-label  mt-4 px-0">
                        <i class="icon-equals-s fa-equals ml-3 mr-5"></i>
                        {{--<i class="fas fa-minus mr-1"></i> --}}
                        ৳ @{{ discount_percentage_amount }}
                      </label>
                    </div>

                    <div v-if="order_contents.length" key="3" class="form-group col-xs-12 px-0">
                      <label for="inputDiscountPercent" class="col-xs-12 control-label px-0">Tax</label>
                      <div class="col-xs-4 px-0">

                        <div class="input-group">
                          <input v-model="tax_percent" type="number" class="form-control">
                          <div class="input-group-addon">
                            <i class="icon-percent-s fa-percent"></i>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-4">
                        <span class="text-center d-block mx-auto">and / or</span>
                      </div>
                      <div class="col-xs-4 px-0">
                        <div class="input-group">
                          <div class="input-group-addon"><b>৳</b></div>
                          <input v-model="tax_amount" type="number" class="form-control" >
                        </div>
                      </div>
                      <label v-if="tax_percentage_amount > 0" class="col-xs-4 control-label  mt-4 px-0">
                        <i class="icon-equals-s fa-equals ml-3 mr-5"></i>
                        {{--<i class="fas fa-minus mr-1"></i> --}}
                        ৳ @{{ tax_percentage_amount }}
                      </label>
                    </div>
                    </transition-group>
                  </div>
                </div>
                {{--<div class="row">--}}
                  {{--<div class="col-xs-12">--}}

                  {{----}}

                  {{--<span class="col-md-3 col-md-offset-3"><i>and / or</i></span><br>--}}

                  {{----}}


                  {{----}}

                  {{--<span class="col-md-3 col-md-offset-3"><i>and / or</i></span><br>--}}
                {{--</div>--}}
                {{--</div>--}}

              </div>
              <div class="box-footer">
                <div class="row">
                  <div class="col-xs-12">
                    <button v-if="order_contents.length" type="button" class="btn btn-info pull-right" @click="continue_f()">
                      Continue
                      <i class="fa fa-chevron-right pt-1 ml-2"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="toggle==true" :key="true" class="col-xs-12 col-lg-8 col-xl-4">
        <section class="invoice" style="min-height: 95vh">
          <div class="row">
            <div class="col-xs-12">
              <h2 v-if="!is_complete" class="page-header">
                <span><i class="fa fa-check mr-3 text-success"></i>Confirm new Order information</span>

              </h2>

{{--              <img v-else class="d-block mx-auto" src="/" height="75" width="auto">--}}
              <h2 v-if="is_complete" class="page-header">
                <img class="d-block mx-auto" src="/images/crosscountry.png" height="75" width="auto">

              </h2>
              <div v-if="is_complete" class="row">
                <div class="col-xs-4"></div>
                <div class="col-xs-4"><h2 v-if="is_complete" class="text-center text-uppercase mb-4"><b>Invoice</b></h2></div>
                <div class="col-xs-4"><small class="pull-right"><strong>Date :</strong> {{\Carbon\Carbon::now()->format('d/m/Y')}}</small></div>
              </div>


            </div>
            <!-- /.col -->
          </div>

          <div class="row invoice-info">

            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
              <small class="text-uppercase">Bill To</small><br>
              <address v-if="customer">
                <b>@{{ customer.name }}</b> <br>
                <span v-html="customer.address"></span> <br>
                @{{ customer.phone }}
              </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
              <small class="text-uppercase">Beneficiary</small><br>
              <address>
                <b>Cross Country</b> <br>
                7/5 Ring Road, <br>
                Shyamoli, <br>
                Dhaka - 1207 <br>
                <b>Ph:</b> +8801742162518, +8801815440669, +8801716427861
              </address>
            </div>

            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
              <b>Order # </b>@{{ order_num }}<br>
            </div>

          </div>

          <div class="row mt-4">
            <div class="col-xs-12 ">
              <table class="table table-striped table-responsive">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Tyre</th>
                  <th>Qty</th>
                  <th>Unit Price</th>
                  <th class="text-right pr-5">Sub-total</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(record, index) in order_contents">
                  <td>@{{ index+1 }}</td>
                  <td><b>(@{{ record.tyre_id }})</b> @{{ record.brand }} @{{ record.size }} @{{ record.pattern }}  @{{ record.lisi }}</td>
                  <td>@{{ record.qty }}</td>
                  <td>৳ @{{ record.unit_price | currency }}</td>
                  <td class="text-right pr-5">৳ @{{ record.qty*record.unit_price | currency }}</td>
                </tr>
                <tr class="warning">
                  <td></td>
                  <td><b>Total</b></td>
                  <td><b>@{{ totalQty }}</b></td>
                  <td></td>
                  <td class="text-right pr-5">৳ @{{ subTotal | currency }}</td>
                </tr>

                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-6">
              <div class="row">
                <p class="lead ml-5 no-print">Additional information</p>
              </div>
              <p class="text-muted well well-sm no-shadow no-print" style="margin-top: 10px;">
                Add orders are final after confirming. Payments can be made against the order number. You can print
                this after finalizing.
              </p>
            </div>

            <div class="col-xs-6">
              <div class="table-responsive mt-5 pt-3">
                <table class="table">
                  <tbody>
                    <tr>
                      <th style="width: 60%;" colspan="2">Total</th>
                      <td class="text-right">৳ @{{ subTotal | currency }}</td>
                      <td></td>
                    </tr>
                    <tr :class="{'no-print' : parseFloat(total_tax_amount) == 0.0}">
                      <th>Tax
                        <br>
                        <small v-if="tax_percent>0">(@{{  tax_percent  }} %)</small>
                        <small v-if="tax_amount>0" class="ml-2">
                          <i class="fa fa-plus mr-2"></i> ৳ @{{  tax_amount  }}
                        </small>
                      </th>
                      <td><i class="fa fa-plus mr-2"></i></td>
                      <td class="text-right">৳ @{{ total_tax_amount | currency }}</td>
                      <td></td>
                    </tr>
                    <tr>
                      <th>Discount
                        <br>
                          <small v-if="discount_percent>0">(-@{{  discount_percent  }} %)</small>
                          <small v-if="discount_amount>0" class="ml-2">
                            <i class="fa fa-minus mr-2"></i> ৳ @{{  discount_amount  }}
                          </small>
                      </th>
                      <td><i class="fa fa-minus mr-2"></i></td>
                      <td class="text-right">৳ @{{ total_discount_amount | currency }}</td>
                      <td></td>
                    </tr>
                    <tr>
                      <th colspan="2" style="border-top: 1px solid #bbb;">Grand Total:</th>
                      <td class="text-right" style="border-top: 1px solid #bbb;"><b>৳ @{{ grandTotal | currency }}</b></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div v-if="!is_complete" class="row no-print">
            <div class="col-xs-12">
              {{--<a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>--}}
              <button @click="back()" type="button" class="btn btn-primary" style="margin-right: 5px;">
                <i class="fa fa-chevron-left mr-2"></i> Back
              </button>
              <button @click="save()" type="button" class="btn btn-success pull-right"><i class="fa fa-check mr-2"></i> Confirm Order
              </button>

            </div>
          </div>
          <div v-if="is_complete" class="row no-print">
            <div class="col-xs-12">
              <button onclick="window.print()" class="btn btn-default">
                <i class="fa fa-print"></i> Print
              </button>
            </div>
          </div>
          <div v-if="is_complete" class="print-footer">
          <div class="col-xs-12">
            <div class="col-xs-5">
              <div class="row" style="border-top: 1px solid rgb(187, 187, 187);">
                <h4 class="mx-auto">Received by</h4>
              </div>
            </div>
            <div class="col-xs-1"></div>
            <div class="col-xs-1"></div>
            <div class="col-xs-5">
              <div class="row" style="border-top: 1px solid rgb(187, 187, 187);">
                <h4 class="mx-auto">For Cross Country</h4>
              </div>
            </div>
          </div>

          </div>

        </section>
      </div>
    </transition>
    <transition  name="custom-classes-transition"
                 mode="out-in"
                 enter-active-class="animated fadeIn delay-2s"
                 leave-active-class="animated fadeOut"
    >
    <div v-show="toggle==false" class="col-xs-12 col-md-6">
      <div class="box box-default">
        <div class="box-header">
          <h3 class="page-header ml-3"><i class="icon-warehouse-s fa-warehouse mr-3"></i></i></i>Current Stock</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-xs-12">
              @include('partials.currentstock')
            </div>
          </div>
        </div>
      </div>
    </div>
    </transition>
  </div>



@endsection

@section('footer-scripts')

  <script>

    var stock = JSON.parse('{!! $in_stock !!}');
    var customers = JSON.parse('{!! str_replace("'", "\'",$customers) !!}');

    var app = new Vue({
        el: '#app',
        data: {
            stock : stock,
            order_contents : [],
            errors : {},
            toggle : false,

            discount_percent : 0,
            discount_amount : 0,
            tax_percent : 0,
            tax_amount : 0,
            customer : null,
            customers : customers,
            is_complete : false,

            order_num : null,
            date : null,
            past : 0,
            past_date : null,
            error_message : null,
            random_string : Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15)
        },

        watch: {

            past : function(new_val)
            {
                if(new_val == 0)
                    app.past_date = null;

            },
            discount_percent : function(new_val)
            {
                app.helperPositiveFloat(new_val, "discount_percent");

            },

            discount_amount : function(new_val)
            {
                app.helperPositiveFloat(new_val, "discount_amount");

            },

            tax_percent : function(new_val)
            {
                app.helperPositiveFloat(new_val, "tax_percent");

            },

            tax_amount : function(new_val)
            {
                app.helperPositiveFloat(new_val, "tax_amount");

            },

            total_discount_amount : function(new_val, old_val)
            {


                if(parseFloat(new_val)>0 && !(parseFloat(old_val)>0))
                    $("#discount").fadeIn(400);
                else if(!parseFloat(new_val)>0 && parseFloat(old_val)>0)
                    $("#discount").fadeOut(400);
            },

            total_tax_amount : function(new_val, old_val)
            {

                if(parseFloat(new_val)>0 && !(parseFloat(old_val)>0))
                    $("#tax").fadeIn(400);
                else if(!parseFloat(new_val)>0 && parseFloat(old_val)>0)
                    $("#tax").fadeOut(400);
            },

            subTotal : function(new_val, old_val)
            {

                if(parseFloat(new_val)>0 && !(parseFloat(old_val)>0))
                    $("#subTotal").fadeIn(400);
                else if(!parseFloat(new_val)>0 && parseFloat(old_val)>0)
                    $("#subTotal").fadeOut(400);

            },

            grandTotal : function(new_val, old_val)
            {
                if(isNaN(new_val))
                    new_val = 0;
                if(parseFloat(new_val)>0 && !(parseFloat(old_val)>0))
                    $("#grandTotal").fadeIn(400);
                else if(!parseFloat(new_val)>0 && parseFloat(old_val)>0)
                    $("#grandTotal").fadeOut(400);

            }


        },

        computed: {

            total_discount_amount : function(){

              var ret = 0;

              if(parseFloat(this.subTotal)>0 && parseFloat(this.discount_percent)>=0 && parseFloat(this.discount_percent)<100 && this.discount_amount>=0)
                ret = parseFloat(this.subTotal) * parseFloat(this.discount_percent) /100.0 + parseFloat(this.discount_amount);

              return ret;
            },

            discount_percentage_amount : function(){
                var ret = 0;

                if(parseFloat(this.subTotal)>0 && parseFloat(this.discount_percent)>=0 && parseFloat(this.discount_percent)<100)
                    ret = parseFloat(this.subTotal) * parseFloat(this.discount_percent) /100.0;

                return ret;

            },

            total_tax_amount : function(){

                var ret = 0;

                if(parseFloat(this.subTotal)>0 && parseFloat(this.tax_percent)>=0 && parseFloat(this.tax_percent)<100 && this.tax_amount>=0)
                    ret = parseFloat(this.subTotal) * parseFloat(this.tax_percent) /100.0 + parseFloat(this.tax_amount);

                return ret;
            },

            tax_percentage_amount : function(){
                var ret = 0;

                if(parseFloat(this.subTotal)>0 && parseFloat(this.tax_percent)>=0 && parseFloat(this.tax_percent)<100)
                    ret = parseFloat(this.subTotal) * parseFloat(this.tax_percent) /100.0;

                return ret;

            },
            subTotal : function(){

                var ret = 0;

                this.order_contents.forEach(function(item){
                    if(parseInt(item.qty)>0 || parseFloat(item.unit_price)>0)
                        ret += (parseInt(item.qty) * parseFloat(item.unit_price));
                });

                return ret;
            },

            grandTotal : function(){

                return (this.subTotal + this.total_tax_amount - this.total_discount_amount);
            },

            totalQty : function(){
                var ret = 0;

                this.order_contents.forEach(function(item){
                    if(parseInt(item.qty)>0)
                        ret += parseInt(item.qty);
                });

                return ret;

            }
        },

        methods:{


            add : function(index){

                var obj = Object.assign ({}, this.stock[index]);
                this.order_contents.push(obj);

                this.$nextTick(function(){
                    $('#selector').fadeIn(300, function(){
                        $('#selector').removeAttr('id');
                    });
                });
            },

            remove : function(index){

                console.log('remove called');

                $('tr.selector').eq(index).fadeOut(300, function(){
                    app.order_contents.splice(index,1);
                    $('tr.selector').show(); // because it keeps hiding a second one. BUT WHY ? :@
                });
            },

            validate : function(){
                var errors = [];

                console.log('IN VALIDATE');
                console.log(this.past_date);

                if(this.past == 1)
                {
                    var pattern = new RegExp(/\d\d\/\d\d\/\d\d\d\d/);
                    if(!pattern.test(this.past_date))
                      errors['past_date'] = "Enter a valid date";
                    //    console.log('REGEX PASS');
                    //else
                    //    console.log('REGEX FAIL');
                }

                if(this.customer == null)
                    errors['customer'] = "Select a customer";

                this.order_contents.forEach(function(item){

                    console.log('item');
                    console.log(item);
                    if(!(parseInt(item.qty) > 0))
                        errors['qty'] = "Quantity must be greater than zero (0).";
                    if(!(parseFloat(item.unit_price) > 0))
                        errors['unit_price'] = "Unit price must be greater than zero (0).";

                    app.stock.forEach(function(item){

                        if(app.helperStockLive(item.i)<0)
                            errors['qty'] = 'More items than in stock';
                    });
                });

                if( Object.entries(errors).length) // because errors is an obj and does not have length
                    return { status : 'error', 'errors' : errors };
                return {status : 'success'};
            },

            continue_f : function(){

                var validate =  this.validate();

                if(validate.status == 'success')
                {
                    this.toggle = true;
                    this.helperConsolidate();
                }

                else if(validate.errors)//errors
                {
                    console.log('validate.errors');
                    console.log(validate.errors);
                    this.errors = validate.errors;
                }

            },

            datetify : function(){
                $('.date').inputmask('dd/mm/yyyy');
            },

            back : function(){
                this.toggle = false;


                setTimeout(function(){

                    $('tr.selector').fadeIn(300);
                    $('#subTotal').fadeIn(300);
                    $('#grandTotal').fadeIn(300);

                    if(app.total_discount_amount>0)
                      $('#discount').fadeIn(300);

                    if(app.total_tax_amount>0)
                        $('#tax').fadeIn(300);
                }, 2000);
            },

            save : function(){
                $.post("{{route('orders.store')}}",
                    {
                        "_token" : "{{csrf_token()}}",

                        "customer" : this.customer.id,
                        "order_contents" : this.order_contents,
                        "discount_percent" : parseFloat(this.discount_percent),
                        "discount_amount" : parseFloat(this.discount_amount),
                        "tax_percent" : parseFloat(this.tax_percent),
                        "tax_amount" : parseFloat(this.tax_amount),

                        "past" : this.past,
                        "past_date" : this.past_date,
                        "random_string" : this.random_string
                    },
                    function(data){

                      console.log('return handler');
                      console.log(data);
                      if(data.status == 'success') {
                          app.is_complete = true;
                          app.order_num = data.order_num;
                          app.date = data.date;
                          window.scrollTo(0,0);
                      }
                      else{
                          if(data.status == 'failed' && data.message)
                          {
                              app.error_message = data.message;
                              $('#modal-error').modal('show');
                          }
                      }

                    });
            },

            helperConsolidate : function(){

                console.log('helper consolidate function');
                var contents = this.order_contents;

                console.log(contents);

                if(contents.length)
                for(var i=0; i<contents.length; i++)
                    for(var j=contents.length-1; j>i; j--)
                        if(parseInt(contents[i].tyre_id) == parseInt(contents[j].tyre_id))
                            if(parseFloat(contents[j].unit_price) == parseFloat(contents[i].unit_price))
                            {
                                contents[i].qty = parseInt(contents[i].qty) + parseInt(contents[j].qty);
                                contents.splice(j,1);
                            }

                console.log('contents HERE :');
                console.log(contents);
                this.order_contents = contents;

            },

            helperValidQty : function(val, index) {
                var ret = parseInt(val);

                if (this.helperStockLive(index) < 0 || val=="" || ret < 0)
                    ret = 0;

                return ret;
            },

            helperValidUnitPrice : function(val)
            {
                var ret = parseFloat(val);

                if(val == "" || ret < 0)
                    ret = 0;

                return ret;
            },

            helperPositiveFloat : function(new_val, who){
                console.log("HELPER POSITIVE FLOAT");
                console.log(new_val);
                console.log(who);
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
            ,
            helperStockLive : function(index){

                // delete(app.errors['qty']);
                var tyre = this.stock[index].tyre_id;
                var qty = parseInt(this.stock[index].in_stock);

                this.order_contents.forEach(function(value){

                    if(value.tyre_id == tyre && Number.isInteger(parseInt(value.qty)))
                        qty-= parseInt(value.qty);
                });

                //if(qty<0)
                    //app.errors['qty'] = 'Quantity ordered is greater than stock available';

                return qty;

            },

            copyDate : function(){
                this.past_date = document.getElementById('pastDate').value;
            }



        },

        mounted : function() {
            // $('#customer').select2()
            //     .on('change', function(){
            //
            //     })
            $('.modal').modal();
            $('.modal').modal('hide');
        }
    })

  </script>
@endsection


