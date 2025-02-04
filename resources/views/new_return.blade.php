@extends('layouts.app')

@section('title')
  Returns
@endsection
@section('subtitle')
  Record returned items from orders.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'Payments',
    'subcrumb' => 'Record a payment',
     'link' => route('payments.create')])
  @endcomponent
@endsection

@section('modal')
  <div v-cloak class="modal modal-warning fade in" id="modal-warning">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Confirm returns</h4>
        </div>
        <div class="modal-body" v-if="order">
          <p> Confirm returns of <span class="lead"><b>@{{ returnQty }}</b></span> tyres for a refund of <span class="lead"><b>৳ @{{ grandTotalReturn | currency }}</b></span>.
          </p>
          <p>
            The new bill adjusted is <span class="lead"><b>৳ @{{ grandTotal - grandTotalReturn  | currency }}</b></span>.
          </p>

          <p v-if="credits > 0">
            PLEASE RETURN <span class="lead"><b>৳ @{{ credits | currency }}</b></span> or offer the amount as credit on the next order.
          </p>
          <p>
            Click continue if this information is correct.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
          <button @click="confirm()" data-dismiss="modal" type="button" class="btn btn-outline">Confirm Return</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
@endsection

@section('body')

  <div v-cloak class="row justify-content-center">
    <div class="col-xs-12">

      <transition name="custom-classes-transition"
                  enter-active-class="animated fadeIn faster"
                  leave-active-class="animated fadeOut faster"
      >
        <div v-if="is_complete && show_alert" id="alert" class="alert alert-success no-print"  role="alert">
          <button type="button" class="close" aria-label="Close" data-dismiss="alert"><span @click="dismiss_warning()" aria-hidden="true">&times;</span></button>
          <h4><i class="icon icon-check-circle-s fa fa-check-circle"></i> Items Returned</h4>
          The items have been returns and the bills have been adjusted.
          {{--<a href="{{ route('lcs.index') }}"  class="btn btn-success ml-5">Click here to view all LCs</a>--}}
        </div>
      </transition>
    </div>
  </div>
  <transition v-cloak name="custom-classes-transition"
              mode="out-in"
              enter-active-class="animated fadeInRight"
              leave-active-class="animated fadeOutLeft"
  >
    <div v-if="step == 0" key="0" class="row justify-content-center">
      <div class="col-xs-12">

        <div class="box box-info">
          <div class="box-header">
            <h3 class="page-header ml-3"><i class="icon-hand-holding-usd-s fa-hand-holding-usd mr-3"></i>Record returned items for orders</h3>
          </div>
          <div class="box-body">
            <form>

              <div class="box-body">
                <div class="row">
                  <div class="col-xs-12">
                    <div class="form-group">
                      <label for="inputOrder">Order</label>
                      <v-select id="order" class="form-control" placeholder="Select an order" name="inputOrder"
                                v-model="order" :options="orders" label="Order_num"
                      >
                      </v-select>
                    </div>
                  </div>
                </div>

                <div v-if="order" class="row">
                  <div class="col-xs-12">
                    <address>
                      <b>@{{ order.customer.name }}</b> <br>
                      <span v-html="order.customer.address"></span> <br>
                      @{{ order.customer.phone }}
                    </address>
                  </div>
                </div>

                <div class="row">
                  <div class="col-xs-12 mx-2">
                    <table class="table table-striped" v-if="order">
                      <thead>
                      <tr>
                        <th class="col-xs-1">#</th>
                        <th class="col-xs-5">Tyre</th>
                        <th class="col-xs-2">Qty</th>
                        <th class="col-xs-2">Unit Price</th>
                        {{--<th class="col-xs-1">Percentage</th>--}}
                        <th class="col-xs-2 text-right">Subtotal</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr v-for="(item, index) in order.order_contents" :class="{'strikethrough-red' : (item.qty - returnCount(index)) == 0}">
                        <td class="col-xs-1">@{{ index+1 }}</td>
                        <td class="col-xs-5"><b>(@{{ item.tyre.tyre_id }})</b> @{{ item.tyre.brand }} @{{ item.tyre.size }} @{{ item.tyre.pattern }} @{{ item.tyre.lisi }}</td>
                        <td class="col-xs-2">@{{ item.qty - returnCount(index) }} <i v-if="(item.qty - returnCount(index))" @click="returnItem(item, index)" class="icon-arrow-alt-circle-down-s ml-5"></i></td>
                        <td class="col-xs-2">৳ @{{ item.unit_price }}</td>
                        {{--<td class="col-xs-2"> @{{ parseFloat(item.unit_price)* parseInt(item.qty) / parseFloat(subTotal) |percentage_rounded}}</td>--}}
                        <td class="col-xs-2 text-right">৳ @{{ parseFloat(item.unit_price)* parseInt(item.qty- returnCount(index)) | currency}}</td>
                      </tr>
                      <tr>
                        <th class="col-xs-1"></th>
                        <th class="col-xs-5">Total</th>
                        <th class="col-xs-2"></th>
                        <th class="col-xs-2"></th>
                        <th class="col-xs-2 text-right">৳ @{{ subTotal - subTotalReturn | currency }}</th>
                      </tr>
                      <tr>
                        <th></th>
                        <th>Discount <span class="ml-2">(@{{ order.discount_percent }} %)</span></th>
                        <th>
                          <span  :class="{'strikethrough-red' : parseFloat(order.discount_amount)!= old_discount_amount}"><i class="fa fa-minus mr-3"></i> ৳ @{{ old_discount_amount | currency }}</span>
                          <button v-if="!edit_discount" @click="edit_discount = true" type="button" class="btn btn-default ml-2"><i class="icon-sliders-h"></i></button>
                        </th>

                        <th>
                          <div v-if="edit_discount" class="input-group input-group-sm">
                            <input v-model="order.discount_amount"  type="number" min="0" class="form-control">
                            <span class="input-group-btn">
                              <button type="button" class="btn btn-success" @click="verify_amount('discount')">
                                <i class="fa fa-check"></i>
                              </button>
                              <button type="button" class="btn btn-danger" @click="cancel_amount('discount')">
                                <i class="fa fa-times"></i>
                              </button>
                            </span>
                          </div>
                          <div v-if="!edit_discount && order.discount_amount != old_discount_amount">
                            ৳ @{{ order.discount_amount | currency }}
                          </div>
                        </th>


                        <th class="text-right"><i class="fa fa-minus mr-3"></i>৳ @{{ discountTotal - discountReturnPercentAmount | currency }}</th>
                      </tr>
                      <tr>
                        <th></th>
                        <th>Tax <span class="ml-2">(@{{ order.tax_percentage }} %)</span></th>
                        <th>
                          <span  :class="{'strikethrough-red' : parseFloat(order.tax_amount)!= old_tax_amount}"><i class="fa fa-plus mr-3"></i> ৳ @{{ old_tax_amount | currency }}</span>
                          <button v-if="!edit_tax" @click="edit_tax = true" type="button" class="btn btn-default ml-2"><i class="icon-sliders-h"></i></button>
                        </th>
                        <th>
                          <div v-if="edit_tax" class="input-group input-group-sm">
                            <input v-model="order.tax_amount"  type="number" min="0" class="form-control">
                            <span class="input-group-btn">
                              <button type="button" class="btn btn-success" @click="verify_amount('tax')">
                                <i class="fa fa-check"></i>
                              </button>
                              <button type="button" class="btn btn-danger" @click="cancel_amount('tax')">
                                <i class="fa fa-times"></i>
                              </button>
                            </span>
                          </div>
                          <div v-if="!edit_tax && order.tax_amount != old_tax_amount">
                            ৳ @{{ order.tax_amount | currency }}
                          </div>
                        </th>
                        <th class="text-right"><i class="fa fa-plus mr-3"></i>৳ @{{ taxTotal - taxReturnPercentAmount  | currency }}</th>
                      </tr>
                      <tr>
                        <th></th>
                        <th class="text-uppercase">Grand Total</th>
                        <th></th>
                        <th></th>
                        <th class="text-right">৳ @{{ grandTotal - grandTotalReturn  | currency }}</th>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div v-if="order" class="row">
                  <div class="col-xs-12 mx-2">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th class="col-xs-1">#</th>
                          <th class="col-xs-5">Tyre</th>
                          <th class="col-xs-2">Qty</th>
                          <th class="col-xs-2">Unit Price</th>
                          {{--<th class="col-xs-1">Percentage</th>--}}
                          <th class="col-xs-2 text-right">Subtotal</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(item, index) in filtered">
                          <td class="col-xs-1">@{{ item.index + 1 }}</td>
                          <td class="col-xs-5"><b>(@{{ item.tyre_id }})</b> @{{ item.brand }} @{{ item.size }} @{{ item.pattern }} @{{ item.lisi }}</td>
                          <td class="col-xs-2">@{{ item.qty }} <i @click="putBack(item.index)" class="icon-arrow-alt-circle-up-s ml-5"></i></td>
                          <td class="col-xs-2">৳ @{{ item.unit_price }}</td>
                          <td class="col-xs-2 text-right">৳ @{{ parseFloat(item.unit_price) * parseInt(item.qty) | currency }}</td>
                        </tr>

                        <tr>
                          <td class="col-xs-1"></td>
                          <td class="col-xs-5"><b>Total</b></td>
                          <td class="col-xs-2"></td>
                          <td class="col-xs-2"></td>
                          <td class="col-xs-2 text-right"><b>৳ @{{ subTotalReturn | currency }}</b></td>
                        </tr>
                        <tr>
                          <td class="col-xs-1"></td>
                          <td class="col-xs-5"><b>Discount Adjusment</b></td>
                          <td class="col-xs-2"></td>
                          <td class="col-xs-2"></td>
                          <td class="col-xs-2 text-right"><i class="fa fa-minus mr-3"></i><b>৳ @{{ discountReturnPercentAmount | currency }}</b></td>
                        </tr>
                        <tr>
                          <td class="col-xs-1"></td>
                          <td class="col-xs-5"><b>Tax Refund</b></td>
                          <td class="col-xs-2"></td>
                          <td class="col-xs-2"></td>
                          <td class="col-xs-2 text-right"><i class="fa fa-plus mr-3"></i><b>৳ @{{ taxReturnPercentAmount | currency }}</b></td>
                        </tr>
                        <tr>
                          <td class="col-xs-1"></td>
                          <td class="col-xs-5 text-uppercase"><b>Total Refund</b></td>
                          <td class="col-xs-2"></td>
                          <td class="col-xs-2"></td>
                          <td class="col-xs-2 text-right"><b>৳ @{{ grandTotalReturn | currency }}</b></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div v-if="order" class="row">
                  <div class="col-xs-12 mx-2">
                    <table class="table table-bordered  table-responsive">
                      <thead>
                      <tr>
                        <th class="col-xs-1">Transaction Id</th>
                        <th class="col-xs-3">Payment Date</th>
                        <th class="col-xs-2">Amount Paid</th>
                        <th class="col-xs-2">Refund amount</th>
                        <th class="col-xs-2"></th>
                        <th class="col-xs-2 text-right">Balance</th>
                      </tr>
                      </thead>
                      <tbody v-if="order && order.payments">
                      <tr  v-for="(payment, index) in order.payments" :class="[{'danger' : payment.refund_amount>0},{'strikethrough-red' : payment.refund_amount==payment.payment_amount}]">
                        <td class="col-xs-1"> @{{ payment.transaction_id | transactionid_zerofill}}</td>
                        <td class="col-xs-3"> @{{ payment.created_at | ddmmyyyy }}</td>
                        <td class="col-xs-2">৳ @{{ parseFloat(payment.payment_amount) | currency }}</td>
                        <td class="col-xs-2">৳ @{{ parseFloat(payment.refund_amount) | currency }}</td>
                        <td class="col-xs-2"></td>
                        <td class="col-xs-2 text-right">৳ @{{ runningTotal(index) | currency }}</td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                  <button v-if="validate" @click="showModal()" type="button" class="btn btn-primary pull-right">Continue <i class="fa fa-chevron-right pt-1 ml-2"></i> </button>
                  </div>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>




    <div v-else key="1" class="row justify-content-center">
      <div class="col-xs-12 col-md-8">
        <section class="invoice">
          {{--<div class="box box-info">--}}
            {{--<div class="box-body">--}}
              <div class="row">
                <div class="col-xs-12">
                  <h2 class="page-header">
                    <img class="d-block mx-auto" src="/images/crosscountry.png" height="75" width="auto">
{{--                    <small class="pull-right">Date : @{{ date | ddmmyyyy }}</small>--}}
                  </h2>
                </div>
              </div>
              <div v-if="is_complete" class="row">
                <div class="col-xs-4"></div>
                <div class="col-xs-4"><h2 v-if="is_complete" class="text-center text-uppercase mb-4"><b>Return Slip</b></h2></div>
                <div class="col-xs-4"><small class="pull-right"><strong>Date :</strong> @{{ date | ddmmyyyy }}</small></div>
              </div>
                <!-- /.col -->




              <div class="row">
                <div class="col-xs-12">
                  <div class="form-group">
                    <span for="inputOrder">Order # @{{ order.Order_num }}</span>
                  </div>
                </div>
              </div>

              <div class="row invoice-info">
                <div class="col-xs-12 invoice-col">
                  <address>
                    <b>@{{ order.customer.name }}</b> <br>
                    <span v-html="order.customer.address"></span> <br>
                    @{{ order.customer.phone }}
                  </address>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12">
                  <table class="table table-striped table-responsive">
                    <thead>
                    <tr>
                      <th class="">#</th>
                      <th class="">Tyre</th>
                      <th class="">Qty</th>
                      <th class="">Unit Price</th>
                      {{--<th class="col-xs-1">Percentage</th>--}}
                      <th class=" text-right">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in order.order_contents" :class="{'strikethrough-red' : (item.qty - returnCount(index)) == 0}">
                      <td class="">@{{ index+1 }}</td>
                      <td class=""><b>(@{{ item.tyre.tyre_id }})</b> @{{ item.tyre.brand }} @{{ item.tyre.size }} @{{ item.tyre.pattern }} @{{ item.tyre.lisi }}</td>
                      <td class="">@{{ item.qty - returnCount(index) }}</td>
                      <td class="">৳ @{{ item.unit_price }}</td>
                      {{--<td class="col-xs-2"> @{{ parseFloat(item.unit_price)* parseInt(item.qty) / parseFloat(subTotal) |percentage_rounded}}</td>--}}
                      <td class="text-right">৳ @{{ parseFloat(item.unit_price)* parseInt(item.qty- returnCount(index)) | currency}}</td>
                    </tr>
                    <tr>
                      <th class=""></th>
                      <th class="">Total</th>
                      <th class=""></th>
                      <th class=""></th>
                      <th class="text-right">৳ @{{ subTotal - subTotalReturn | currency }}</th>
                    </tr>
                    <tr>
                      <th></th>
                      <th>Discount <span class="ml-2">(@{{ order.discount_percent }} %)</span></th>
                      <th>
                        <span  :class="{'strikethrough-red' : parseFloat(order.discount_amount)!= old_discount_amount}"><i class="fa fa-minus mr-3"></i> ৳ @{{ old_discount_amount | currency }}</span>
                        {{--<button v-if="!edit_discount" @click="edit_discount = true" type="button" class="btn btn-default ml-2"><i class="fas fa-sliders-h"></i></button>--}}
                      </th>
                      <th>
                        <div v-if="!edit_discount && order.discount_amount != old_discount_amount">
                          ৳ @{{ order.discount_amount | currency }}
                        </div>
                      </th>
                      <th class="text-right"><i class="fa fa-minus mr-3"></i>৳ @{{ discountTotal | currency }}</th>
                    </tr>
                    <tr>
                      <th></th>
                      <th>Tax <span class="ml-2">(@{{ order.tax_percentage }} %)</span></th>
                      <th>
                        <span  :class="{'strikethrough-red' : parseFloat(order.tax_amount)!= old_tax_amount}"><i class="fa fa-plus mr-3"></i> ৳ @{{ old_tax_amount | currency }}</span>
                        {{--<button v-if="!edit_tax" @click="edit_tax = true" type="button" class="btn btn-default ml-2"><i class="fas fa-sliders-h"></i></button>--}}
                      </th>
                      <th>
                        <div v-if="!edit_tax && order.tax_amount != old_tax_amount">
                          ৳ @{{ order.tax_amount | currency }}
                        </div>
                      </th>
                      <th class="text-right"><i class="fa fa-plus mr-3"></i>৳ @{{ taxTotal | currency }}</th>
                    </tr>
                    <tr>
                      <th></th>
                      <th class="text-uppercase">Grand Total</th>
                      <th></th>
                      <th></th>
                      <th class="text-right">৳ @{{ grandTotal - subTotalReturn  | currency }}</th>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <table class="table table-striped table-responsive">
                    <thead>
                    <tr>
                      <th class="">#</th>
                      <th class="">Tyre</th>
                      <th class="">Qty</th>
                      <th class="">Unit Price</th>
                      {{--<th class="col-xs-1">Percentage</th>--}}
                      <th class="text-right">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in filtered">
                      <td class="">@{{ item.index + 1 }}</td>
                      <td class=""><b>(@{{ item.tyre_id }})</b> @{{ item.brand }} @{{ item.size }} @{{ item.pattern }} @{{ item.lisi }}</td>
                      <td class="">@{{ item.qty }}</td>
                      <td class="">৳ @{{ item.unit_price }}</td>
                      <td class="text-right">৳ @{{ parseFloat(item.unit_price) * parseInt(item.qty) | currency }}</td>
                    </tr>

                    <tr>
                      <td class=""></td>
                      <td class=""><b>Total</b></td>
                      <td class=""></td>
                      <td class=""></td>
                      <td class="text-right"><b>৳ @{{ subTotalReturn | currency }}</b></td>
                    </tr>
                    <tr>
                      <td class=""></td>
                      <td class=""><b>Discount Adjusment</b></td>
                      <td class=""></td>
                      <td class=""></td>
                      <td class="text-right"><i class="fa fa-minus mr-3"></i><b>৳ @{{ discountReturnPercentAmount | currency }}</b></td>
                    </tr>
                    <tr>
                      <td class=""></td>
                      <td class=""><b>Tax Refund</b></td>
                      <td class=""></td>
                      <td class=""></td>
                      <td class="text-right"><i class="fa fa-plus mr-3"></i><b>৳ @{{ taxReturnPercentAmount | currency }}</b></td>
                    </tr>
                    <tr>
                      <td class=""></td>
                      <td class="text-uppercase"><b>Total Refund</b></td>
                      <td class=""></td>
                      <td class=""></td>
                      <td class="text-right"><b>৳ @{{ grandTotalReturn | currency }}</b></td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="row no-print">
                <div class="col-xs-12">
                  <button onclick="window.print()" class="btn bg-navy">
                    <i class="fa fa-print"></i> Print
                  </button>
                </div>
              </div>
            {{--</div> <!-- box-body -->--}}
          {{--</div> <!-- box -->--}}
        </section>

      </div>
    </div>
  </transition>

@endsection



@section('footer-scripts')
  <script>

      var orders = JSON.parse('{!! str_replace("'", "\'",$orders) !!}');

      var app = new Vue({
          el: '#app',
          data: {
              orders : Object.values(orders), // object to array
              order : null,
              returns : [],
              amount : 0,
              edit_discount : false,
              edit_tax : false,
              old_discount_amount : null,
              old_tax_amount : null,

              new_discount_amount : null,
              new_tax_amount : null,
              numberToWords : numberToWords,
              is_complete : false,
              show_alert : false,
              transaction_id : null,
              payment_at : null,

              date : '{{\Carbon\Carbon::now()}}',

              step : 0
          },

          watch:{

              // amount : function(new_val){
              //
              //     if(parseFloat(new_val)> this.grandTotal - this.paymentsTotal())
              //         this.amount = this.grandTotal- this.paymentsTotal();
              //     else
              //         this.helperPositiveFloat(new_val, "amount");
              // },

              order : function(new_val){

                  this.returns = [];

                  this.old_discount_amount = parseFloat(new_val.discount_amount);
                  this.old_tax_amount = parseFloat(new_val.tax_amount);
              },
          },

          computed : {

              validate : function(){

                  if(this.returns.length && !this.edit_discount && !this.edit_tax)
                  {
                      for(var i=0; i<this.returns.length; i++)
                      {
                          if(this.returns[i]!= undefined)
                              return true;
                      }
                  }

                  return false;
              },

              filtered : function(){

                  if(this.returns.length)
                  {
                      return this.returns.filter(function(value){
                          return typeof value != "undefined";
                      });
                  }
                  return [];
              },

              toWordsPoisha : function(){

                  var amount;

                  if((typeof this.amount) == "number")
                      amount = this.amount.toString();
                  else
                      amount = this.amount;

                  if(amount.indexOf('.') == -1)
                      return "";
                  else if(parseFloat(amount.split('.')[1])==0)
                      return "";

                  var poisha = amount.split('.')[1];

                  if(poisha.length == 1)
                      poisha = poisha + '0';
                  if(poisha.length>2)
                      poisha = poisha.substr(0, 2);//+'.'+poisha.substr(2, poisha.length);

                  return ' '+poisha + '&frasl;100';

              },

              unpaidOrders : function(){

                  var unpaid = [];
                  // console.log(this.orders);
                  // console.log(this.orders.length);

                  this.orders.forEach(function (value, index) {

                      var paymentsTotal = 0;
                      var subTotal = 0;

                      value.payments.forEach(function(value){

                          paymentsTotal+= (parseFloat(value.payment_amount)-parseFloat(value.refund_amount));
                      });

                      value.order_contents.forEach(function(value){

                          subTotal+= parseInt(value.qty)*parseFloat(value.unit_price);
                      });

                      var discountTotal = (subTotal * parseFloat(value.discount_percent)/100.0) + parseFloat(value.discount_amount);
                      var taxTotal = (subTotal * parseFloat(value.tax_percentage)/100.0) + parseFloat(value.tax_amount);

                      var grandTotal = subTotal - discountTotal + taxTotal;

                      console.log('index : ' + index);
                      console.log('grandTotal: ' + grandTotal);
                      console.log('paymentsTotal: ' + paymentsTotal);


                      if(grandTotal > paymentsTotal)
                          unpaid.push(value);
                  });

                  return unpaid;
              },

              subTotal : function(){

                  var ret = 0;

                  if(app.order)
                      app.order.order_contents.forEach(function(value){
                          ret+= parseFloat(value.unit_price)* parseInt(value.qty);
                      });

                  return ret;

              },

              subTotalReturn : function(){

                  var ret = 0;

                  if(this.filtered.length)
                      for(var i = 0; i< this.filtered.length; i++)
                          ret += (parseFloat(this.filtered[i].unit_price) * parseFloat(this.filtered[i].qty));

                  return ret;
              },

              discountTotal : function() {

                  var discount_percentage_amount = 0;
                  var discount_amount_amount = 0;

                  if(app.order)
                  {
                      discount_percentage_amount = app.subTotal * parseFloat(app.order.discount_percent)/100.0;
                      discount_amount_amount = parseFloat(app.order.discount_amount);
                  }

                  return (discount_percentage_amount + discount_amount_amount);
              },

              discountReturnPercentAmount : function(){

                  var discount_percentage_amount = 0;
                  //var discount_amount_amount = 0;

                  if(app.order)
                  {
                      discount_percentage_amount = app.subTotalReturn * parseFloat(app.order.discount_percent)/100.0;
                      //discount_amount_amount = parseFloat(app.order.discount_amount);
                  }

                  return discount_percentage_amount;
                  //return (discount_percentage_amount + discount_amount_amount);

              },

              taxTotal : function() {

                  var tax_percentage_amount = 0;
                  var tax_amount_amount = 0;

                  if(app.order)
                  {
                      tax_percentage_amount = app.subTotal * parseFloat(app.order.tax_percentage)/100.0;
                      tax_amount_amount = parseFloat(app.order.tax_amount);
                  }

                  return (tax_percentage_amount + tax_amount_amount);
              },

              taxReturnPercentAmount : function() {

                  var tax_percentage_amount = 0;
                  //var tax_amount_amount = 0;

                  if(app.order)
                  {
                      tax_percentage_amount = app.subTotalReturn * parseFloat(app.order.tax_percentage)/100.0;
                      //tax_amount_amount = parseFloat(app.order.tax_amount);
                  }

                  return tax_percentage_amount;
                  //return (tax_percentage_amount + tax_amount_amount);
              },

              grandTotal : function(){
                  return this.subTotal - this.discountTotal + this.taxTotal
              },

              grandTotalReturn : function(){

                  return this.subTotalReturn - this.discountReturnPercentAmount + this.taxReturnPercentAmount;
              },

              credits : function(){

                  var val = (this.paymentsTotal - (this.grandTotal-this.grandTotalReturn));
                  return val>0 ? val :  0 ;
              },

              amountToWords : function(){

                  return this.numberToWords.toWords(parseFloat(this.amount));
              },

              returnQty : function(){

                  var count = 0;

                  if(this.returns.length)
                  {
                      for(var i=0; i<this.returns.length; i++)
                          if(this.returns[i]!=undefined)
                             count+= parseInt(this.returns[i].qty);
                  }

                  return count;
              },
              paymentsTotal : function(){

                  // index-th order
                  var total = 0;

                  if(this.order)
                      this.order.payments.forEach(function(value){

                          total+= (parseFloat(value.payment_amount)-parseFloat(value.refund_amount));
                      });
                  return total;
              },


          },

          methods : {

              // for each payment alread
              runningTotal : function(index){

                  var total =  this.grandTotal - this.grandTotalReturn; // tax and discount already mutates in grandTotal

                  for(var i=0; i<=index; i++)
                  {
                      total -= (parseFloat(this.order.payments[i].payment_amount) - parseFloat(this.order.payments[i].refund_amount));
                  }

                  return total;
              },

              showModal : function(){
                  $('#modal-warning').modal('show');
              },

              confirm : function(){

                  var params = {

                      "_token" : "{{csrf_token()}}",
                      order : app.order.Order_num,
                      returns : app.filtered,
                      credits : app.credits
                  };

                  if(this.order.tax_amount != this.old_tax_amount)
                      params.tax = this.order.tax_amount;

                  if(this.order.discount_amount != this.old_discount_amount)
                      params.discount = this.order.discount_amount;

                  $.post("{{  route('returns.store')  }}",

                      params,
                      function(data)
                      {
                          console.log(data);

                          if(data.status ==  'success')
                          {
                            app.step = 1;
                            app.is_complete = true;
                            app.show_alert = true;
                            window.scrollTo(0,0);
                          }
                      });
              },

              //HELPERs

              returnCount : function(index){
                  if(this.returns.length > index && typeof this.returns[index] != "undefined")
                      return this.returns[index].qty;
                  return 0;
              },

              returnItem : function(item, index){
                  if(typeof this.returns[index] == "undefined")
                  {
                      var entry = {};
                      var array = this.returns.slice(); //copy
                      entry.Order_num = item.Order_num;
                      entry.tyre_id = item.tyre_id;
                      entry.brand = item.tyre.brand;
                      entry.size = item.tyre.size;
                      entry.lisi = item.tyre.lisi;
                      entry.pattern = item.tyre.pattern;
                      entry.container_num = item.container_num;
                      entry.bol = item.bol;
                      entry.unit_price = item.unit_price;
                      entry.qty = 1;

                      entry.index = index;

                      array[index] = entry;
                      this.returns = array;
                  }
                  else
                  {
                      if(this.returnCount(index) < this.order.order_contents[index].qty)
                          this.returns[index].qty++;
                  }
              },

              putBack : function(index){

                  console.log("PUT BACK : " + index);
                  var array = this.returns.slice();

                  array[index].qty--;

                  if(array[index].qty<=0)
                      array[index] = undefined;

                  this.returns = array;
              },

              verify_amount : function(who){

                  switch(who){

                      case "tax" :

                          if(!(parseFloat(this.order.tax_amount)>=0))
                              this.order.tax_amount = this.old_tax_amount;

                          this.edit_tax = false;

                          break;

                      case "discount" :

                          if(!(parseFloat(this.order.discount_amount)>=0))
                              this.order.discount_amount = this.old_discount_amount;

                          this.edit_discount = false;

                          break;
                  }
              },

              cancel_amount : function(who){
                  switch(who){

                      case "tax" :
                          this.order.tax_amount = this.old_tax_amount;
                          this.edit_tax = false;
                          break;

                      case "discount" :
                          this.order.discount_amount = this.old_discount_amount;
                          this.edit_discount = false;
                          break;
                  }
              }


          },

          mounted: function(){
              $('#modal-warning').modal();
              $('#modal-warning').modal('hide');
          }
      })
  </script>
@endsection
