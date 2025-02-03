@extends('layouts.app')

@section('title')
  Consignments
@endsection
@section('subtitle')
  Add a new consignment arrived.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'Consignments',
    'subcrumb' => 'An a consignment',
     'link' => route('consignments.index')])
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
          <h4><i class="icon icon-check-circle-s fa fa-check-circle"></i> Done</h4>
          New consignment has been saved.
          <a href="{{ route('consignments.index') }}"  class="btn btn-success ml-5">Click here to view all Consignemnts</a>
        </div>
      </transition>
    </div>
  </div>
  <div v-cloak class="row justify-content-center">
    <transition  name="custom-classes-transition"
                 mode="out-in"
                 {{--enter-class = "mimi"--}}
                 :enter-active-class="direction? 'animated fadeInRight fast' : 'animated fadeInLeft fast'"
                 :leave-active-class="direction? 'animated fadeOutLeft fast' : 'animated fadeOutRight fast'"
      {{--enter-active-class="animated fadeInRight fast"--}}
      {{--leave-active-class="animated fadeOutLeft fast"--}}
    >
      <div  v-if="showForm == 0" key="0" class="col-xs-12 col-md-6">


        <div class="box box-teal">
          <div class="box-header">
            <h3 class="page-header ml-3">
              <i class="icon-anchor-r fa-anchor mr-3"></i>
              Enter Consignment Information
            </h3>
          </div>
          <div class="box-body">

            <form>
              <div class="box-body">
                <div class="row mx-2">
                  <div class="col-xs-12">
                    <div class="form-group" :class="{ 'has-error' :  errors.bol }">
                      <label>Bill of Lading #</label>
                        <input id="bol" v-model="bol" type="text" class="form-control" placeholder="Enter Bill of lading number">
                      {{--</div>--}}
                      <span v-if=" errors.bol" class="help-block">@{{ errors.bol }}</span>
                    </div>
                  </div>
                </div>

                <div class="row mx-2">
                  <div class="col-xs-12">
                    <div class="form-group" :class="{ 'has-error' :  errors.lc_num }">
                      <label>Letter of Credit #</label>
                      {{--<div class="input-group">--}}
                        {{--<span class="input-group-addon">--}}
                          {{--<span v-if="!is_verifying">F20</span>--}}
                          {{--<i v-else class="fas fa-spinner fa-pulse"></i>--}}
                        {{--</span>--}}
                        {{--<input type="text" class="form-control" placeholder="Enter LC number">--}}
                      <select id="lc_num" v-model="lc_num" class="form-control" placeholder="select an LC">
                        <option disabled :value="null">Select a LC</option>
                        <option  v-for="lc in lcs" :value="lc.lc_num">@{{lc.lc_num}}</option>
                      </select>

                      {{--</div>--}}
                      <span v-if=" errors.lc_num" class="help-block">@{{ errors.lc_num }}</span>
                    </div>
                  </div>
                </div>

                <div class="row mx-2">
                  <div class="col-xs-12 col-md-5">
                    <div class="form-group" :class="{ 'has-error' :  errors.date_landed }">
                      <label>Land Date</label>
                      <div class="input-group">
                        <span class="input-group-addon">F31C</span>
                        <input v-model="date1"  @click="datetify()" @blur="copyDate(1)" id="dateIssued" type="text" class="form-control date" placeholder="dd/mm/yyyy">
                        <div class="input-group-addon">
                          <i class="icon-calendar-alt-s  fa-calendar-alt"></i>
                        </div>
                      </div>
                      <span v-if=" errors.date_landed" class="help-block">@{{ errors.date_landed }}</span>
                    </div>
                  </div>

                  <div class="col-xs-7">
                    <div class="form-group" :class="{ 'has-error' :  errors.value }">
                      <label>Value (in foreign currency)</label>
                      <div class="input-group">
                        <span class="input-group-addon"><strong>@{{currency_symbol}}</strong></span>
                        <input v-model="value" type="number" step="0.01" class="form-control" placeholder="0.00" min="0.00">
                      </div>
                      <span v-if=" errors.value" class="help-block">@{{ errors.value }}</span>
                    </div>

                  </div>
                </div>
                <div class="row mx-2">



                  <div class="col-xs-5">
                    <div class="form-group" :class="{ 'has-error' :  errors.exchange_rate }">
                      <label>Exchange Rate</label>
                      <div class="input-group">

                        <input v-model="exchange_rate" type="number" step="0.01" class="form-control" placeholder="0.00">
                        <span class="input-group-addon"><strong>/ @{{currency_symbol}}</strong></span>
                      </div>
                      <span v-if=" errors.exchange_rate" class="help-block">@{{ errors.exchange_rate }}</span>
                    </div>
                  </div>


                  <div class="col-xs-12 col-md-7">
                    <div class="form-group">
                      <label>Consignment Value (in local currency) </label>
                      <div class="input-group">
                        <span class="input-group-addon"><strong>৳</strong></span>
                        <input type="number" step="0.01" class="form-control" :value="value_local | currency" disabled>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mx-2">

                  <div class="col-xs-12 col-md-5">
                    <div class="form-group" :class="{ 'has-error' :  errors.tax }">
                      <label>Tax Paid</label>
                      <div class="input-group">
                        <span class="input-group-addon"><strong>৳</strong></span>
                        <input v-model="tax" type="number" step="0.01" min="0" class="form-control" placeholder="0.00">
                      </div>
                      <span v-if=" errors.tax" class="help-block">@{{ errors.tax }}</span>
                    </div>
                  </div>

                  <div class="col-xs-12 col-md-7">
                    <div class="form-group">
                      <label>Total Cost to obtain consignment</label>
                      <div class="input-group">
                        <span class="input-group-addon"><strong>৳</strong></span>
                        <input :value="total_cost | currency" type="number" step="0.01" min="0" class="form-control" disabled placeholder="0.00">
                      </div>
                      <span class="help-block"></span>
                    </div>
                  </div>
                </div>

                <div class="row mx-2">
                  <div class="col-xs-12">
                      <button v-if="!is_duplicate" type="button" class="btn btn-info pull-right" @click="submit()">
                        Continue
                        <i class="fa fa-chevron-right pt-1 ml-2"></i>
                      </button>
                  </div>
                </div>

              </div>
            </form>


          </div>

        </div>
      </div>

      <div  v-if="showForm == 1" key="1"  class="col-xs-7">
        <div class="box box-purple">
          <div class="box-header">
            <h3 class="page-header ml-3">
              <i class="icon-container-storage-r fa-container-storage mr-3"></i>
              Add containers to your consignment
            </h3>
          </div>
          <div class="box-body pb-5 px-5">

            <div class="form-group" :class="{ 'has-error' :  Object.entries(errors).length }">
              <ul>
                <li class="help-block" v-for="error in errors">@{{ error }}</li>
              </ul>
            </div>
            <form style="padding: 1rem;">
              <transition-group  name="custom-classes-transition"
                                 {{--tag="ol"--}}
                                 {{--mode="out-in"--}}
                                 {{--enter-class = "mimi"--}}
                                 enter-active-class="animated fadeInDown"
                                 {{--enter-class="animated tada"--}}
                                 leave-active-class="animated fadeOut fast "
              >
              <div v-for="(container, index) in containers" key="container.container_num" @click="select_container(index)" class="row" {{--:Class="{'border-dash' : selected_container == index}"--}}  style="flex-direction: column">
                <div class="box box-solid" :id="container.container_num" :class="[selected_container == index ? 'box-warning' : 'box-default']">

                  <div class="box-header">
                    <h4 class="box-title"><i class="icon-container-storage-r fa-container-storage mr-3"></i> # @{{ container.container_num }}</h4>
                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" :id="container.container_num + '_close'"><i class="fa fa-minus"></i></button>
                      <button @click="remove_container(index)" type="button" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
                    </div>
                  </div>

                  <div class="box-body px-0 pb-0">
                      {{--<button type="button" @click="test()" class="btn btn-primary">Click me</button>--}}
                      <div class="col-xs-12" style="min-height: 100px">
                        <div class="row list-item pb-1">
                          <div class="col-xs-1 text-center"><strong>#</strong></div>
                          <div class="col-xs-2 text-center"><strong>Tyre</strong></div>
                          <div class="col-xs-2 text-center"><strong>Qty</strong></div>
                          <div  class="col-xs-2 text-right"><strong>Unit Price</strong></div>
                          <div class="col-xs-2 text-center"><strong>Total Weight (kg)</strong></div>
                          <div class="col-xs-2 text-center"><strong>Total Tax (৳)</strong></div>
                          <div  class="col-xs-1 text-right"><strong>Sub Total</strong></div>
                          <div  class="col-xs-1 text-right"></div>
                        </div>
                        <transition-group  name="custom-classes-transition"
                                           {{--tag="ol"--}}
                                           {{--mode="out-in"--}}
                                           {{--enter-class = "mimi"--}}
                                           enter-active-class="animated fadeInDown"
                                           {{--enter-class="animated tada"--}}
                                           leave-active-class="animated fadeOutUp fast "
                        >
                        <div v-for="(item, item_index) in container.contents" key="index"  class="row py-2" :class="{'bg-light-gray' : !(item_index%2)}">
                          <div class="col-xs-1 text-center"><strong>@{{ item_index + 1 }}</strong></div>
                          <div class="col-xs-2 text-center"><b>(@{{item.tyre_id}})</b> @{{item.brand}} @{{item.size}} @{{ item.lisi }} @{{item.pattern}}</div>
                          <div class="col-xs-2 text-center">
                            <div class="form-group"  :class="{ 'has-error' :  errors.qty && parseInt(item.qty)<=0 }">
                              <input class="form-control" v-model="item.qty" type="number" min="0" step="1">
                            </div>
                          </div>
                          <div class="col-xs-2 text-center">
                            <div class="form-group" :class="{ 'has-error' :  errors.unit_price && parseFloat(item.unit_price)<=0 }">
                              <input class="form-control" v-model="item.unit_price" type="number" min="0" step="0.01">
                            </div>
                          </div>
                          <div class="col-xs-2 text-center">
                            <div class="form-group">
                              <input class="form-control" v-model="item.total_weight" type="number" min="0" step="0.01">
                            </div>
                          </div>
                          <div  class="col-xs-2 text-right">
                            <div class="form-group">
                              <input class="form-control" v-model="item.total_tax" type="number" min="0" step="0.01">
                            </div>
                          </div>
                          <div  class="col-xs-1 text-right">@{{item.unit_price * item.qty | currency }}</div>
                          <div class="col-xs-1">
                            <a class="text-danger" @click="removeTyre(index, item_index)">
                              <i class="icon-minus-circle-s fa-minus-circle mt-1"></i>
                            </a>
                          </div>
                        </div>
                        </transition-group>

                      </div>

                  </div>
                  <div class="box-footer" >
                    {{--<div class="row justify-content-center">--}}
                      <div class="col-xs-3">
                        <b>Container Totals</b>
                      </div>
                      <div class="col-xs-2 pl-3">
                        @{{ container_total_qty(index) }}
                      </div>
                      <div class="col-xs-1 mr-5"></div>
                      <div class="col-xs-2">
                        @{{ container_total_weight(index) }}
                      </div>
                      <div class="col-xs-2 pr-0">
                        @{{ container_total_tax(index) | currency }}
                      </div>

                      <div class="col-xs-1 px-0">
                        @{{ container_total_value(index) | currency }}
                      </div>
                    {{--</div>--}}
                  </div>
                </div>
              </div>
              </transition-group>
              <div v-if="containers.length" class="row">
                <div class="col-xs-3 px-5">
                  <b>Grand Total</b>
                </div>
                <div class="col-xs-2 pl-5 pr-0">
                  <b> @{{ total_qty }}</b>

                </div>
                <div class="col-xs-2">
                </div>
                <div class="col-xs-2 pr-2">
                  <b> @{{ total_weight }}</b>

                </div>

                <div class="col-xs-2 pl-4-5">
                 <b>  @{{ total_tax | currency }}</b>

                </div>

                <div class="col-xs-2 pl-3">
                 <b>  @{{ total_value | currency }}   </b>
                </div>
              </div>


            </form>



          </div>
          <div class="box-footer px-4">


            <div class="my-4 btn bg-purple btn-block">
              <transition  name="custom-classes-transition"
                           {{--tag="ol"--}}
                           mode="out-in"
                           {{--enter-class = "mimi"--}}
                           enter-active-class="animated fadeIn fast"
                           {{--enter-class="animated tada"--}}
                           leave-active-class="animated fadeOutRight fast "
              >
                <div v-if="container_step==0" key="0" @click="container_step=1" class="row justify-content-center align-items-center p-5">
                  <span class="mr-2" style="font-size: 10px"><i class="fa fa-plus"></i></span>
                  <i style="font-size: 20px;" class="icon-container-storage-r fa-container-storage mr-3"></i>
                  <span style="font-size : 15px;"><b> Add a container</b></span>
                </div>
                <div v-if="container_step==1" key="1" class="row justify-content-center align-items-center p-5 ">

                  <i style="font-size: 20px;" class="icon-container-storage-r fa-container-storage mr-3"></i>
                  <span style="font-size : 15px;"><b>#</b></span>
                  <input v-model="container_num" type="text" class="ml-3" placeholder="Enter Container Number" style="color: #000;">
                  <button @click="add_container()" type="button" class="btn btn-success ml-2"><i class="fa fa-plus"></i></button>
                  <button @click="cancel()" type="button" class="btn btn-danger mr-2"><i class="fa fa-times"></i> </button>
                </div>
              </transition>
            </div>

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
                <i class="fa fa-check mr-3 text-success"></i>Confirm new Consignment information
                <small class="pull-right">Date: 2/10/2014</small>
              </h2>
            </div>
            <!-- /.col -->
          </div>
          <!-- info row -->
          <div class="row invoice-info">

            <!-- /.col -->
            <div class="col-sm-6 invoice-col">
              <b>Bill of lading # @{{ bol }}</b><br>
              <br>
              <b>LC # </b> @{{ lc_num }}<br>
              <b>Land Date : </b> @{{ date_landed }}<br>
            </div>

            <div class="col-sm-6 invoice-col">
              <b>Exchange Rate :</b> ৳ @{{ exchange_rate }} / @{{ currency_symbol }}<br>
              <br>
              <b>Consignment Value : </b>@{{ currency_symbol }} @{{ value | currency }}<br>
              <b>Value in local currency</b> ৳ @{{ value * exchange_rate | currency }}<br>
            </div>

          </div>

          <!-- Table row -->
          <div v-for="container in containers" class="row mt-4">
            <div class="col-xs-12 table-responsive">
              <table class="table table-striped">
                <thead>
                <tr>
                  <th colspan="5">
                    <i class="icon-container-storage-r fa-container-storage mr-2"></i>
                    <span class="">#</span>
                    @{{ container.container_num }}
                  </th>
                </tr>
                <tr>
                  <th>#</th>
                  <th>Tyre</th>
                  <th>Qty</th>
                  <th>Unit Price</th>
                  <th>Total Weight</th>
                  <th>Total Tax</th>
                  <th>Sub-total</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(record, index) in container.contents">
                  <td>@{{ index + 1 }}</td>
                  <td><b>(@{{ record.tyre_id }})</b> @{{ record.brand }} @{{ record.size }} @{{ record.lisi }} @{{ record.pattern }}</td>
                  <td>@{{ record.qty }}</td>
                  <td>@{{ record.unit_price | currency }}</td>
                  <td>@{{ record.total_weight }}</td>
                  <td>@{{ record.total_tax }}</td>
                  <td>@{{ currency_symbol }} @{{ record.qty*record.unit_price | currency }}</td>
                </tr>

                </tbody>
              </table>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <div class="row mt-3">
            <div class="col-xs-6">
              <div class="row">
                <p class="lead ml-5">Actual Values</p>
              </div>
              <div class="row invoice-info well ml-1 mr-1 mb-4">
                <div class="col-sm-6 invoice-col">
                  <b>Foreign Currency Code:</b> <br>
                  @{{ currency_code }}<br>
                  <br>
                  <b>Exchange Rate:</b> <br>
                  ৳ @{{ exchange_rate | currency }} / @{{ currency_symbol }}
                  <br>
                  <br>
                  <b>Tax Paid :</b> <br>
                  ৳ @{{ tax | currency }}
                  {{--<b>Arriving Port:</b> @{{ arriving_port }}<br>--}}

                </div>

                <div class="col-sm-6 invoice-col">
                  <b>Consignment Value: </b><br>
                  @{{ currency_symbol }} @{{ value | currency }}<br>
                  <br>
                  <b>Value in Taka </b><br>
                  ৳ @{{ parseFloat(value)*parseFloat(exchange_rate) | currency }}<br>
                  <br>
                  <b>Total Cost (Value + Tax)</b><br>
                  ৳ @{{ (parseFloat(value)*parseFloat(exchange_rate)) + parseFloat(tax) | currency }}<br>
                  {{--<b>LC Value in Taka: </b> ৳ @{{ lc_value*exchange_rate | currency }}<br>--}}

                </div>

                <!-- /.col -->
              </div>
            </div>
            <div class="col-xs-6">
              <p class="lead">Estimated Values</p>

              <div class="table-responsive">
                <table class="table">
                  <tbody><tr>
                    <th style="width:50%">Grand Total (Calculated)</th>
                    <td>@{{ currency_symbol }} @{{ grand_total_foreign | currency }}</td>
                  </tr>
                  <tr>
                    <th>Grand Total in TK (Calculated)</th>
                    <td>৳ @{{ grand_total_foreign*parseFloat(exchange_rate) | currency }}</td>
                  </tr>
                  <tr>
                    <th>Total Tax in TK (Calulated)</th>
                    <td>৳ @{{ total_tax | currency }}</td>
                  </tr>
                  <tr>
                    <th>Total Weight</th>
                    <td>@{{ total_weight }} kg</td>
                  </tr>
                  <tr>
                    <th># of Tyres</th>
                    <td>@{{ total_count }} pcs.</td>
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
                :leave-active-class="direction? 'animated fadeOutRight fast' : 'animated fadeOutLeft fast'" >
      <div v-show="showForm == 1 && containers.length && selected_container != null" class="col-xs-5">
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Tyre Catalog</h3>
          </div>
          <div class="box-body">
            <table id ="table_id" class="table table-hover table-bordered">
              <thead>
              <tr>
                <th><i class="icon-tyre-s fa-tire"></i> ID</th>
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




      var app = new Vue({
          el: '#app',
          data: {
              showForm : 0,
              showCatalog : false,
              tyre_catalog : tyre_catalog,


              lcs : JSON.parse('{!! json_encode($lcs) !!}'),
              lc_num : null,
              bol: null,

              tax : null,
              date_landed : null,
              date_expired : null,
              currency_code : null,
              exchange_rate : null,
              value : null,
              expense_foreign : null,

              proforma_invoice : [],

              containers : [],
              container_step : 0,
              container_num : null,
              selected_container : null,

              direction : true,

              date_flag: false,
              date1: null,
              currency_symbol: '$',

              errors : {},
              is_alert : false,
              is_complete : false,
              is_duplicate : false,

              is_verifying : false,

              alert_class : 'alert-warning',



          },
          computed:{

              grand_total_foreign : function()
              {
                  var ret_val = 0;

                  this.containers.forEach(function(value){
                      value.contents.forEach(function(value){
                          if(parseInt(value.qty) * parseFloat(value.unit_price) > 0)
                              ret_val += parseInt(value.qty)* parseFloat(value.unit_price);
                      });
                  });

                  return ret_val;
              },
              total_qty : function()
              {
                  var ret_val = 0;

                  this.containers.forEach(function(value){
                     value.contents.forEach(function(value){
                        if(parseInt(value.qty) > 0)
                            ret_val += parseInt(value.qty);
                     });
                  });

                  return ret_val;
              },
              //grand total_weight
              total_weight : function()
              {
                  var ret_val = 0;

                  this.containers.forEach(function(value){
                      value.contents.forEach(function(value){
                          if(parseFloat(value.total_weight) > 0)
                              ret_val += parseFloat(value.total_weight);
                      });
                  });

                  return ret_val;
              },

              total_tax : function()
              {
                  var ret_val = 0;

                  this.containers.forEach(function(value){
                      value.contents.forEach(function(value){
                          if(parseFloat(value.total_tax) > 0)
                              ret_val += parseFloat(value.total_tax);
                      });
                  });

                  return ret_val;
              },

              total_count : function()
              {
                  var ret_val = 0;

                  this.containers.forEach(function(value){
                      value.contents.forEach(function(value){
                          if(parseInt(value.qty) > 0)
                              ret_val += parseInt(value.qty);
                      });
                  });

                  return ret_val;
              },



              total_value : function()
              {
                  var ret_val = 0;

                  this.containers.forEach(function(container){
                      container.contents.forEach(function(value){
                         if(parseFloat(value.qty)>0 && parseFloat(value.unit_price)>0)
                             ret_val += (parseFloat(value.qty) * parseFloat(value.unit_price));
                      });
                  })

                  return ret_val;
              },

              value_local : function()
              {
                  var ret_val = 0;
                  if(!isNaN(parseFloat(this.value))  && !isNaN(parseFloat(this.exchange_rate)))
                      ret_val = this.value * this.exchange_rate;
                  return ret_val;
              },

              total_cost :function()
              {
                  var ret_val = 0;

                  if(!isNaN(parseFloat(this.value)) && !isNaN(parseFloat(this.exchange_rate)))
                  {
                      ret_val = parseFloat(this.value)* parseFloat(this.exchange_rate);
                      if(!isNaN(parseFloat(this.tax)))
                          ret_val = ret_val + parseFloat(this.tax);
                  }
                  return ret_val;

              },
              //
              // grand_total : function(){
              //
              //     var ret_val = 0;
              //     if(this.proforma_invoice.length)
              //     {
              //
              //         this.proforma_invoice.forEach(function(value, index){
              //
              //             if(
              //                 typeof (value.qty) !== 'undefined' &&
              //                 typeof (value.unit_price) !== 'undefined' &&
              //                 value.qty.length &&
              //                 value.unit_price.length
              //             )
              //                 ret_val += parseInt(value.qty) * parseFloat(value.unit_price)
              //
              //         });
              //
              //
              //     }
              //     return ret_val;
              // },
              //
              // total_qty : function(){
              //
              //     var ret_val = 0;
              //
              //     this.proforma_invoice.forEach(function(value){
              //         ret_val += parseInt(value.qty);
              //     });
              //
              //     return ret_val;
              //
              // }
          },
          watch : {

              lc_num : function(new_val){
                this.lcs.forEach(function(lc){

                    console.log("LC : " + lc.lc_num);
                    if(lc.lc_num == new_val)
                    {
                        app.currency_code = lc.currency_code;
                    }
                });
              },
              currency_code : function(new_val)
              {
                  if (typeof currencies[new_val] !== 'undefined')
                      this.currency_symbol = currencies[new_val.toUpperCase()];
                  else
                      this.currency_symbol = '$';

                  if(new_val.toUpperCase() != new_val)
                      this.currency_code = new_val.toUpperCase();
              },
              exchange_rate : function(new_val)
              {
                  app.helperPositiveFloat(new_val, "exchange_rate");
              },

              value : function(new_val)
              {
                  app.helperPositiveFloat(new_val, "value");
              },

              tax : function(new_val){
                  app.helperPositiveFloat(new_val, "tax");
              },

              date_landed : function(new_val){
                  this.date1 = new_val;
              },
          },

          methods: {

              container_total_qty : function(index){

                  var ret_val = 0;
                  if(this.containers[index].contents.length)
                      this.containers[index].contents.forEach(function(value){

                          if(parseInt(value.qty) > 0)
                              ret_val += parseInt(value.qty);
                      });

                  return ret_val;
              },

              container_total_weight : function(index){

                  var ret_val = 0;
                  if(this.containers[index].contents.length)
                      this.containers[index].contents.forEach(function(value){

                          if(parseFloat(value.total_weight) > 0)
                              ret_val += parseFloat(value.total_weight);
                      });

                  return ret_val;
              },

              container_total_tax : function(index){

                  var ret_val = 0;
                  if(this.containers[index].contents.length)
                      this.containers[index].contents.forEach(function(value){

                          if(parseFloat(value.total_tax) > 0)
                              ret_val += parseFloat(value.total_tax);
                      });

                  return ret_val;
              },

              container_total_value : function(index){

                  var ret_val = 0;
                  if(this.containers[index].contents.length)
                    this.containers[index].contents.forEach(function(value){

                        if(parseFloat(value.qty)>0 && parseFloat(value.unit_price)>0)
                            ret_val += (parseFloat(value.qty) * parseFloat(value.unit_price));
                    });

                  return ret_val;
              },

              cancel : function(){
                  this.container_step=0;
                  this.errors = {};
              },

              add_container : function(){

                  this.errors = {};

                  if(!this.container_num || !this.container_num.length)
                      this.errors['container_num'] = "Enter a container number";

                  else
                  {
                      var container = {
                          "container_num": this.container_num,
                          "contents": [],
                          "collapse": false,
                      };

                      this.containers.push(container);

                      //
                      // this.containers.forEach(function(value, index){
                      //     console.log("value");
                      //     console.log(value.container_num);
                      //     console.log(app.container_num);
                      //     if(value.container_num == app.container_num)
                      //         app.selected_container = index;
                      //
                      //     $('#'+value.container_num).boxWidget({
                      //         animationSpeed: 500,
                      //         collapseTrigger: '#'+value.container_num +'_close',
                      //         // removeTrigger: '#my-remove-button-trigger',
                      //         collapseIcon: 'fa-minus',
                      //         expandIcon: 'fa-plus',
                      //         // removeIcon: 'fa-times'
                      //     });
                      // });

                      this.container_num = null;
                      this.container_step = 0;
                  }
              },

              remove_container : function(index){

                  this.containers.splice(index, 1);
              },

              select_container : function(index){

                  this.selected_container = index;
                  if(!this.containers[index].collapse)
                  {
                          $('#'+ this.containers[index].container_num).boxWidget({
                              animationSpeed: 500,
                              collapseTrigger: '#'+this.containers[index].container_num +'_close',
                              // removeTrigger: '#my-remove-button-trigger',
                              collapseIcon: 'fa-minus',
                              expandIcon: 'fa-plus',
                              // removeIcon: 'fa-times'
                          });

                          this.containers[index].collapse = true;
                  }

              },



              dismiss_warning : function(){
                  this.is_alert = false;

              },
              datetify : function(){
                  if(this.date_flag)
                  {
                      this.date_flag = false;
                      $('.date').inputmask('dd/mm/yyyy');
                  }
              },

              copyDate : function(i){

                  if(i==1)
                      this.date_landed = document.getElementById('dateIssued').value;

              },

              copyDates : function(){
                  this.copyDate(1);
              },

              validate : function(){

                  var errors = {};

                  switch (this.showForm)
                  {
                      case 0 :
                          if(!this.bol || !this.bol.length)
                              errors['bol'] = 'The bill of lading number is required.';

                          if(!this.lc_num || !this.lc_num.length)
                              errors['lc_num'] = 'Enter a LC number';

                          var regEx = /\d\d\/\d\d\/\d\d\d\d/;

                          if(!regEx.test(this.date_landed))
                          {
                              errors['date_landed'] = 'Enter a valid land date';
                          }
                          if(!this.value || this.value<=0)
                              errors['value'] = 'Enter consignment value.';

                          if(!this.exchange_rate || this.exchange_rate<=0)
                              errors['exchange_rate'] = 'Exchange rate required.';

                          if(!this.tax || !this.tax.length)
                              this.tax = 0;

                          break;

                      case 1:

                          if(!this.containers.length)
                              errors['containers'] = 'Must add atleast one (1) container.';

                          else{

                              this.containers.forEach(function(value){

                                  if(!value.contents.length)
                                      errors['contents'] = 'Container # ' + value.container_num + ' is empty';

                                  else{
                                      value.contents.forEach(function(record){

                                          // if(record.)
                                          if(record.qty <= 0)
                                              errors['qty'] = 'Quantity cannot be zero (0).';
                                          if(record.unit_price <= 0)
                                              errors['unit_price'] = 'Unit price cannot be zero (0).';
                                      });
                                  }
                              });

                          }


                          break;
                  }

                  if( Object.entries(errors).length)
                  {
                      return { status : 'error', 'errors' : errors };
                  }// because errors is an obj and does not have length
                  else if(this.showForm == 1)
                  {
                      this.helperConsolidate();
                  }

                  return {status : 'success'};
              },

              submit : function(){

                  this.is_alert = false;
                  this.errors = {};

                  var validate = this.validate();
                  console.log(validate);
                  if(validate.status == 'success')
                      this.toggle(true);

                  else if(validate.errors)//errors
                  {
                      console.log("CAUGHT ERRORS");
                      this.errors = validate.errors;
                      console.log(this.errors);
                  }


              },

              save : function(){

                  $.post("{{route('consignments.store')}}",
                      {
                          "_token" : "{{csrf_token()}}",

                          "lc_num" : this.lc_num,
                          "bol" : this.bol ,
                          "value" : this.value ,
                          "exchange_rate" : this.exchange_rate,
                          "land_date" : this.date_landed,
                          "tax" : this.tax,
                          "containers": this.containers
                      } ,
                      function(data)
                      {
                          console.log("HERE");
                          console.log(data.containers);
                          if(data.status == 'success')
                              app.is_complete = true;
                      });

              },
              toggle : function(direction){

                  this.errors = [];
                  this.is_alert = false;
                  if(this.showForm==0)
                  {
                      this.date_flag = false;
                      this.copyDates();

                  }
                  else
                  {
                      this.date_flag = true;

                      this.date1 = this.date_landed;
                  }


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

                          this.containers[this.selected_container].contents.push(obj);
                          break;
                      }
                  }
              },

              removeTyre : function(container_index, tyre_index){


                  this.containers[container_index].contents = this.containers[container_index].contents.filter(function(value, i, array){
                      // all items except for the current index
                      return tyre_index != i;
                  });
              },
              //

              helperConsolidate : function(){


                this.containers.forEach(function(value){

                    var contents = value.contents;

                    for(var i=0; i<contents.length; i++)
                    {
                        var tyre_id = parseInt(contents[i].tyre_id);
                        var unit_price = parseFloat(contents[i].unit_price);

                        for(var j=contents.length-1; j>i; j--)
                            if(parseInt(contents[j].tyre_id) == tyre_id)
                                    if(parseFloat(contents[j].unit_price) == unit_price)
                                    {
                                        contents[i].qty = parseInt(contents[i].qty) + parseInt(contents[j].qty);
                                        contents[i].total_weight = parseInt(contents[i].total_weight) + parseInt(contents[j].total_weight);
                                        contents[i].total_tax = parseInt(contents[i].total_tax) + parseInt(contents[j].total_tax);
                                        contents.splice(j,1);
                                    }

                    }

                    value.contents = contents;
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
          },

          mounted : function(){

          }

      })
  </script>
@endsection