@extends('layouts.app')

@section('title')
  Expenses
@endsection
@section('subtitle')
  Add a new consignment expense.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'expenses',
    'subcrumb' => 'Add an expense',
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
        <div v-if="is_complete" id="alert" class="alert alert-success"  role="alert">
          <button type="button" class="close" aria-label="Close"><span @click="dismiss_warning()" aria-hidden="true">&times;</span></button>
          <h4><i class="icon fa fa-check-circle"></i> Done</h4>
          New expenses recorded. You can keep adding more expenses.
        </div>
      </transition>
    </div>
  </div>
  <div v-cloak class="row justify-content-center">
      <div  class="col-xs-12 col-md-8">


        <div class="box box-info">
          <div class="box-header">
            <h3 v-if="bol == null" class="page-header ml-3">
              <i class="icon-anchor-r  fa-anchor mr-3"></i>
              Select a consignment to add expenses to.
            </h3>
            <h3 v-else class="page-header ml-3">
              <i class="far fa-coins mr-3"></i>
              Add expenses to this consignment.
            </h3>
          </div>
          <div class="box-body">

            <form class="form">
              <div class="box-body">
                <div class="row mx-2">
                  <div class="col-xs-12">
                    <div class="form-group" :class="{ 'has-error' :  errors.bol }">
                      <label>Bill of Lading #</label>

                      <select id="lc_num" v-model="bol" class="form-control" placeholder="sel ect an LC">
                        <option disabled :value="null">Select a consignment</option>
                        <option  v-for="consignment in consignments" :value="consignment.BOL">@{{consignment.BOL}}</option>
                      </select>

                      <span v-if=" errors.bol" class="help-block">@{{ errors.bol }}</span>
                    </div>
                    <div v-if="bol" class="row">
                      <div class="col-xs-6">
                        <div class="form-group">
                          <label>LC #</label> <br>
                          <span>  @{{ consignments[consignment_index].lc }}</span>
                        </div>
                      </div>
                      <div class="col-xs-6">
                        <div class="form-group">
                          <label>Land date</label> <br>
                          <span>  @{{ consignments[consignment_index].land_date | date }}</span>
                        </div>
                      </div>
                    </div>
                    <div v-if="bol" class="row">
                      <div class="col-xs-6">
                        <div class="form-group">
                          <label>Exchange rate</label> <br>
                          <span> @{{ consignments[consignment_index].exchange_rate}} / ৳ </span>
                        </div>
                      </div>
                      <div class="col-xs-6">
                        <div class="form-group">
                          <label>Value</label> <br>
                          <span>@{{ currency_symbol  }} @{{ consignments[consignment_index].value}}</span>
                        </div>
                      </div>
                    </div>
                    <div v-if="bol" class="row">
                      <div class="col-xs-6">
                        <div class="form-group">
                          <label>Total Tax</label> <br>
                          <span> ৳ @{{ consignments[consignment_index].tax}}</span>
                        </div>
                      </div>

                      <div class="col-xs-6">
                        <div class="form-group">
                          <label>Value (in TK)</label> <br>
                          <span> ৳ @{{ parseFloat(consignments[consignment_index].value) * parseFloat(consignments[consignment_index].exchange_rate) | currency}}</span>
                        </div>
                      </div>
                    </div>
                    <div v-if="bol" class="row">
                      <div class="col-xs-6">
                        <div class="form-group">
                          <label>Total Cost (Value + Tax)</label> <br>
                          <span> ৳
                            @{{ parseFloat(consignments[consignment_index].value)* parseFloat(consignments[consignment_index].exchange_rate) + parseFloat(consignments[consignment_index].tax)| currency}}</span>
                        </div>
                      </div>
                    </div>
                    <div v-if="bol" class="row">
                      <div class="col-xs-12">
                        <table class="table table-striped table-responsive table-bordered">
                          <thead>
                            <tr>
                              <th>id</th>
                              <th>Expense Notes</th>
                              <th>Expense Foreign</th>
                              <th>Expense Local</th>
                              <th>Expense Total</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="expense in consignments[consignment_index].expenses">
                              <td>@{{ expense.expense_id }}</td>
                              <td>@{{ expense.expense_notes }}</td>
                              <td class="text-right">@{{ currency_symbol }} @{{ expense.expense_foreign | currency }}</td>
                              <td class="text-right">৳ @{{ expense.expense_local | currency }}</td>
                              <td class="text-right">৳ @{{ parseFloat(consignments[consignment_index].exchange_rate)*parseFloat(expense.expense_foreign) + parseFloat(expense.expense_local) | currency }}</td>
                              <td></td>
                            </tr>
                            {{--<tr class="info" v-for="(expense, index) in new_expenses">--}}
                              {{--<td class="pt-4 pb-0"><span class="label bg-blue">NEW</span></td>--}}
                              {{--<td>@{{ expense.expense_notes }}</td>--}}
                              {{--<td class="text-right">@{{ currency_symbol }} @{{ expense.expense_foreign | currency }}</td>--}}
                              {{--<td class="text-right">৳ @{{ expense.expense_local | currency }}</td>--}}
                              {{--<td class="text-right">৳ @{{ parseFloat(expense.expense_foreign)*parseFloat(exchange_rate) + parseFloat(expense.expense_local) | currency }}</td>--}}
                              {{--<td>--}}
                                {{--<i @click="remove_expense(index)" class="fas fa-minus-circle text-danger"></i>--}}
                              {{--</td>--}}
                            {{--</tr>--}}
                            <tr v-for="(expense, index) in new_expenses" is="an-expense"
                                         :expense="expense"
                                         :exchange_rate="exchange_rate"
                                         :currency_symbol="currency_symbol"
                                         :key="index"
                                         :index="index">
                            </tr>
                            <tr class="warning">
                              <td></td>
                              <td><b>Totals</b></td>
                              <td class="text-right"><b>@{{ currency_symbol }} @{{ expense_foreign_total | currency }}</b></td>
                              <td class="text-right"><b>৳ @{{ expense_local_total | currency }}</b></td>
                              <td class="text-right"><b>৳ @{{ expense_foreign_total*exchange_rate + expense_local_total | currency }}</b></td>
                              <td></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>


                    <div v-if="bol" class="row justify-content-center">
                      <div class="col-xs-12 col-md-4">
                        <div class="form-group" :class="{ 'has-error' :  errors.expense }">
                          <label class="mr-5">Expense Foreign</label>
                          <div class="input-group">
                            <span class="input-group-addon"><b>@{{ currency_symbol }}</b></span>
                            <input class="w-100" v-model="expense_foreign" type="number" placeholder="0.00">
                          </div>
                          <span v-if=" errors.expense" class="help-block text-danger">@{{ errors.expense }}</span>
                        </div>
                      </div>
                      <div class="col-xs-12 col-md-4 d-flex justify-content-center align-items-center">
                        {{--<div class="form-group">--}}
                          <span class="text-center d-block text-gray">and / or</span>
                          {{--<div class="input-group">--}}
                          {{--<input type="number">--}}
                          {{--</div>--}}
                        {{--</div>--}}
                      </div>
                      <div class="col-xs-12 col-md-4">
                        <div class="form-group" :class="{ 'has-error' :  errors.expense }">
                          <label class="mr-5">Expense Local</label>
                          <div class="input-group">
                            <span class="input-group-addon"><b>৳</b></span>
                            <input class="w-100" v-model="expense_local" type="number" placeholder="0.00">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div v-if="bol" class="row">
                      <div class="col-xs-12 col-md-4 col-md-offset-8">
                        <div class="form-group w-100">
                          <label>Expense Total</label>
                          <div class="input-group w-100">
                            <span><b>৳ @{{ expense_new_total | currency }}</b></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div v-if="bol" class="row justify-content-center">
                      <div class="col-xs-12">
                        <div class="form-group w-100" :class="{ 'has-error' :  errors.expense_notes }">
                          <label>Note/Description</label>
                          <div class="input-group w-100">
                          <textarea v-model="expense_notes" class="w-100" rows="7"></textarea>
                          </div>
                          <span v-if=" errors.expense_notes" class="help-block text-danger">@{{ errors.expense_notes }}</span>
                        </div>
                      </div>
                    </div>
                    <div v-if="bol" class="row justify-content-center">
                      <div class="col-xs-12">
                        <button type="button" @click="add_expense()" class="btn btn-block btn-primary">Add Expense</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div v-if="new_expenses.length" class="row mx-2 mt-5">
                  <div class="col-xs-12">
                    <button type="button" class="btn btn-success pull-right" @click="save()">
                      <i class="fa fa-check mr-3"></i>
                      Save
                    </button>
                  </div>
                </div>

              </div>
            </form>


          </div>

        </div>
      </div>
  </div>

@endsection

@section('footer-scripts')

  <script>

      $(window).scroll(function(){
          var top = (parseFloat($(window).scrollTop()) -100 );
          $('#catalogContainer').css('top', top>0 ? top : 0);
      });

      var NewExpense = Vue.component('NewExpense', {

         props : ['currency_symbol','expense', 'exchange_rate', 'index'],
         template : '<tr id="anew" class="info" >' +
         '                              <td style="display:none" class="pt-4 pb-0"><span class="label bg-blue">NEW</span></td>' +
         '                              <td style="display:none">@{{ expense.expense_notes }}</td>' +
         '                              <td style="display:none" class="text-right">@{{ currency_symbol }} @{{ expense.expense_foreign | currency }}</td>' +
         '                              <td style="display:none" class="text-right">৳ @{{ expense.expense_local | currency }}</td>' +
         '                              <td style="display:none" class="text-right">৳ @{{ parseFloat(expense.expense_foreign)*parseFloat(exchange_rate) + parseFloat(expense.expense_local) | currency }}</td>' +
         '                              <td style="display:none">' +
         '                                <i @click="remove_expense(index)" class="icon-minus-circle-s fa-minus-circle text-danger"></i>' +
         '                              </td>' +
         '                            </tr>',

         methods : {
             remove_expense : function(index){
                 this.$parent.remove_expense(index);
             }
         }

      });

      var app = new Vue({
          el: '#app',
          components: {
            'an-expense' : NewExpense
          },
          data: {

              showCatalog : false,
              lcs : null,
              lc_num : null,
              bol: null,

              consignment_index : null,
              consignments : JSON.parse('{!! json_encode($consignments) !!}'),

              tax : null,

              currency_code : null,
              exchange_rate : null,
              value : null,
              expense_foreign : "",
              expense_local : "",
              expense_notes : null,
              new_expenses : [],


              containers : [],
              container_step : 0,
              container_num : null,
              selected_container : null,

              currency_symbol: '$',

              errors : {},
              is_complete : false,

          },
          computed:{

              expense_new_total : function(){

                  var ret_val = 0;

                  if(parseFloat(this.expense_foreign) > 0)
                      ret_val = parseFloat(this.expense_foreign) * parseFloat(this.exchange_rate);

                  if(parseFloat(this.expense_local) > 0)
                      ret_val += parseFloat(this.expense_local);

                  return ret_val;
              },

              expense_foreign_total : function(){

                  var ret_val = 0;

                  this.consignments[this.consignment_index].expenses.forEach(function(expense){
                      if(parseFloat(expense.expense_foreign)>0)
                        ret_val += parseFloat(expense.expense_foreign);
                  });

                  if(this.new_expenses.length)
                  {
                      this.new_expenses.forEach(function(expense){
                          if(parseFloat(expense.expense_foreign)>0)
                              ret_val += parseFloat(expense.expense_foreign);
                      });
                  }

                  return ret_val;
              },

              expense_local_total : function(){

                  var ret_val = 0;

                  this.consignments[this.consignment_index].expenses.forEach(function(expense){
                      if(parseFloat(expense.expense_local)>0)
                          ret_val += parseFloat(expense.expense_local);
                  });

                  if(this.new_expenses.length)
                  {
                      this.new_expenses.forEach(function(expense){
                          if(parseFloat(expense.expense_local)>0)
                              ret_val += parseFloat(expense.expense_local);
                      });
                  }

                  return ret_val;
              },
          },
          watch : {

              expense_foreign : function(new_val){
                  app.helperPositiveFloat(new_val, "expense_foreign");
              },
              expense_local : function(new_val){
                  app.helperPositiveFloat(new_val, "expense_local");
              },

              bol : function(new_val){

                  if(new_val != null)
                  {
                      this.consignments.forEach(function(a_consignment, index){

                          if(a_consignment.BOL == new_val)
                          {
                              app.consignment_index = index;
                              app.lc_num = a_consignment.lc;
                              app.date_landed = a_consignment.land_date;
                              app.exchange_rate = parseFloat(a_consignment.exchange_rate);
                              app.value = parseFloat(a_consignment.value);
                              app.tax = parseFloat(a_consignment.tax);
                              app.currency_code = a_consignment.letter_of_credit.currency_code;
                              app.currency_symbol = currencies[app.currency_code];
                          }

                      });

                      this.errors = [];
                  }

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
          },

          methods: {

              add_expense : function(){

                  this.errors = {};

                  var validate = this.validate();

                  if(validate.status == 'success')
                  {
                      var expense = {
                          expense_local : this.expense_local,
                          expense_foreign : this.expense_foreign,
                          expense_notes : this.expense_notes
                      };

                      this.new_expenses.push(expense);

                      this.expense_local = 0;
                      this.expense_foreign = 0;
                      this.expense_notes = null;

                      this.$nextTick(function(){
                         $('#anew td').fadeIn(300, function(){
                             $('#anew').removeAttr('id');
                         });
                      });
                  }
                  else
                  {
                      console.log('this.errors');
                      this.errors = validate.errors;
                  }

              },

              remove_expense : function(index){


                  $('tr.info').eq(index).fadeOut(400, function(){
                      app.new_expenses.splice(index, 1);
                      $('tr.info').show(); // because it keeps hiding a second one. BUT WHY ? :@

                  });

              },

              cancel : function(){
                  this.container_step=0;
                  this.errors = {};
              },


              dismiss_warning : function(){
                  this.is_complete = false;

              },

              validate : function(){

                  var errors = {};

                  this.expense_local = parseFloat(this.expense_local);
                  this.expense_foreign = parseFloat(this.expense_foreign);
                  console.log('expense_local');
                  console.log(this.expense_local);

                  console.log('expense_foreign');
                  console.log(this.expense_foreign);

                  if(isNaN(this.expense_foreign))
                      this.expense_foreign = 0;

                  if(isNaN(this.expense_local))
                      this.expense_local = 0;

                  if( (this.expense_local <=0)&&(this.expense_foreign<=0) )
                      errors['expense'] = "Add a valid local and/or foreign expense value";

                  if(this.expense_notes == null || this.expense_notes == "")
                      errors['expense_notes'] = "Add a note desribing this expense";

                  if( Object.entries(errors).length)
                      return { status : 'error', 'errors' : errors };

                  return {status : 'success'};
              },

              save : function(){

                  $.post("{{route('consignment_expenses.store')}}",
                      {
                          "_token" : "{{csrf_token()}}",
                          "bol" : this.bol ,
                          "expenses": this.new_expenses
                      } ,
                      function(data)
                      {
                          if(data.status == 'success')
                          {
                              app.is_complete = true;
                              app.consignments[app.consignment_index].expenses = data.expenses;
                              app.new_expenses = [];
                              $(window).scrollTop(0);

                              setTimeout(function(){
                                  app.is_complete = false;
                              }, 5000);
                          }
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