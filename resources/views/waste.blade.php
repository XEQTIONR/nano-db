@extends('layouts.app')

@section('title')
  Waste
@endsection
@section('subtitle')
  Add Waste.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'Waste',
    'subcrumb' => 'Add Waste',
    'link' => ''])
  @endcomponent
@endsection

@section('body')

  <div v-cloak class="row justify-content-center">
    <div class="col-xs-12">
  <transition name="custom-classes-transition"
              enter-active-class="animated fadeIn faster"
              leave-active-class="animated fadeOut faster"
  >
      <div v-if="done" id="alert" role="alert" class="alert alert-success">
        <button type="button" aria-label="Close" data-dismiss="alert" class="close"><span aria-hidden="true">×</span></button>
        <h4><i class="icon icon-check-circle-s fa-check-circle"></i> Done</h4>
        Waste items added. Stock has been adjusted.
        <a href="{{route('waste.create')}}" class="btn btn-success ml-5">Add More Returns</a>
        <a href="{{route('stock')}}" class="btn btn-success">View Current Stock</a>
      </div>
  </transition>
    </div>
  </div>
  <div v-if="!done" class="row justify-content-center">
    <div class="col-xs-12 col-sm-11 col-lg-8">
      <div class="box box-info">
        <div class="box-header">
          <h3 class="page-header ml-3"><i class="icon-tire-flat mr-2" style="position: relative; top: 1px"></i> <span v-if="step==0">Add</span><span v-else>Confirm</span> items that have perished.</h3>
        </div>
      </div>
    </div>
  </div>
  <div  v-cloak class="row justify-content-center" v-for="(consignment,index) in remain" v-if="step ==0 || step==1 && consignmentWasteTotal(consignment) > 0">
    <div class="col-xs-11 col-lg-8" v-for="(contents, bol) in consignment">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><b>Bill Of Lading # </b>@{{ bol }}</h3>
        </div>
        <div class="box-body">
          <table class="table" v-for="(stock,container_num) in contents" v-if="step==0 || step==1 && containerWasteTotal(bol, container_num) > 0" style="margin-bottom: 5px !important;" >
          <thead>
            <tr>
              <th colspan="4">
                <i class="icon-container-storage-r fa-container-storage mr-3" style="position: relative; top: 3px; font-size : 1.5rem"></i>
                 # : @{{ container_num }}
              </th>
            </tr>
            <tr>
              <th class="col-xs-6">Tyre</th>
              <th class="col-xs-2 text-center">in_stock</th>
              <th class="col-xs-2 text-center">add waste</th>
              <th class="col-xs-2 text-center text-red">Updated Stock</th>
            </tr>
          </thead>
          <tbody>

            <tr v-for="(item, idx) in stock"  v-if="step==0 || step==1 && item.ret_amt > 0">
              <td class="col-xs-6"><b>(@{{ item.tyre_id }})</b> @{{ item.tyre.brand }} @{{ item.tyre.size }} @{{ item.tyre.pattern }} @{{ item.tyre.lisi }}</td>
              <td class="col-xs-2 text-center">@{{ item.in_stock }}</td>
              <td v-if="step==0" class="col-xs-2"><input class="text-center"   v-model="remain[index][bol][container_num][idx].ret_amt"> </td>
              <td v-if="step==1" class="col-xs-2"><span class="text-center d-block mx-auto"   v-text="remain[index][bol][container_num][idx].ret_amt"></span> </td>
              <td class="col-xs-2 text-red text-center" v-if="parseInt(remain[index][bol][container_num][idx].ret_amt)!=0">@{{ parseInt(item.in_stock) - parseInt(remain[index][bol][container_num][idx].ret_amt) }} </td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td class="col-xs-6 strong">Container Totals <small v-if="step==1">(Changed Only)</small></td>
              <td class="col-xs-2 strong text-center">@{{ containerTotal(bol, container_num) }}</td>
              <td class="col-xs-2 strong text-center">@{{ containerWasteTotal(bol, container_num) }}</td>
              <td class="col-xs-2 text-red text-center strong" v-if="parseInt(containerTotal(bol, container_num))!=parseInt(containerTotalAfterReturn(bol, container_num))">@{{ containerTotalAfterReturn(bol, container_num) }}</td>
            </tr>
          </tfoot>
        </table>
        </div>
      </div>
    </div>
  </div>

  <div v-cloak class="row justify-content-center" v-if="!done && change">
    <div class="col-xs-12 col-sm-11 col-lg-8">
      <button v-if="step == 1" class="btn btn-default" @click="back()"><i class="fa fa-chevron-left mr-2"></i>Back  </button>
      <button v-if="step == 1" class="btn btn-success pull-right" @click="submit()" ><i class="fa fa-check mr-1"></i> Confirm </button>
      <button v-else class="btn btn-info pull-right" @click="forward()">Continue <i class="fa fa-chevron-right ml-1"></i> </button>
    </div>
  </div>

@endsection

@section('footer-scripts')

  <script>
    var remain = JSON.parse('{!! $remain !!}');
    console.log('remain');
    console.log(remain);

    for(var i =0; i<remain.length; i++)
    {
        Object.keys(remain[i]).forEach(function(key){
            Object.keys(remain[i][key]).forEach(function(cont){
                for(var j=0; j<remain[i][key][cont].length; j++){
                    remain[i][key][cont][j].ret_amt = 0;
                    remain[i][key][cont][j].is_set = false;
                }
            });
        });
    }

    var app = new Vue({
        el: '#app',
        data :function() {
            return {
                remain: remain,
                change: false,
                step: 0,
                done: false
            }
        },

        watch : {

            remain : {
                deep :true,

                handler : function(new_val){

                    var change =  false;

                    for(var i=0;i<new_val.length; i++){
                        Object.keys(new_val[i]).forEach(function(key){

                            Object.keys(new_val[i][key]).forEach(function(cont){
                                for(var j=0; j<new_val[i][key][cont].length; j++){

                                    if(isNaN(new_val[i][key][cont][j].ret_amt) || new_val[i][key][cont][j].ret_amt==""
                                        || new_val[i][key][cont][j].ret_amt<0
                                        || new_val[i][key][cont][j].ret_amt > parseInt(new_val[i][key][cont][j].in_stock)
                                        || (typeof new_val[i][key][cont][j].ret_amt == "string" && new_val[i][key][cont][j].ret_amt.indexOf(".")!= -1))
                                    {
                                        new_val[i][key][cont][j].ret_amt = 0;
                                        new_val[i][key][cont][j].changed = false;
                                    }
                                    else if(typeof new_val[i][key][cont][j].ret_amt == "string" && new_val[i][key][cont][j].ret_amt[0] == "0")
                                    {
                                        new_val[i][key][cont][j].ret_amt = parseInt(new_val[i][key][cont][j].ret_amt);
                                        new_val[i][key][cont][j].changed = true;
                                    }
                                    else if(!isNaN(new_val[i][key][cont][j].ret_amt) && new_val[i][key][cont][j].ret_amt > 0)
                                    {
                                        change = true;
                                        new_val[i][key][cont][j].changed = true;

                                    }

                                    if(typeof  new_val[i][key][cont][j].changed == 'undefined')
                                    {
                                        new_val[i][key][cont][j].changed = false;
                                    }

                                    //remain[i][key][cont][j].ret_amt = 0;
                                }
                            });
                        });
                    }
                    app.change = change;
                }
            }
        },

        methods : {
            forward : function(){

                window.scrollTo(0,0);
                app.step = 1;
            }
            ,

            back : function(){
                app.step = 0;
            },

            submit : function(){
              //console.log("CONFIRM CLICKED");

              $.post("{{route('waste.store')}}",
                  {
                      "_token" : "{{csrf_token()}}",
                      data : this.remain
                  },
                  function(data){
                      console.log('data');
                      console.log(data);
                      app.done=true;
                      window.scrollTo(0,0);
                  });

            },

            containerTotal : function(bol, container_num){
                var ret = 0;
                var thestep = this.step; // cannot reference this directly inside callback
                for(var i=0; i<this.remain.length; i++)
                {
                    Object.keys(this.remain[i]).forEach(function(key){
                        if(key==bol && this.remain[i][bol]!=null)
                            Object.keys(this.remain[i][bol]).forEach(function(cont){
                                if(cont==container_num && this.remain[i][bol][container_num]!=null)
                                    for(var j=0; j<this.remain[i][bol][container_num].length; j++){
                                        // if(ret ==  null)
                                        //     ret = 0;
                                        if(thestep == 0 || thestep == 1 && parseInt(this.remain[i][bol][container_num][j].ret_amt)>0)
                                          ret += parseInt(this.remain[i][bol][container_num][j].in_stock);
                                    }
                            })

                    });
                }
                return ret;
            },
            containerTotalAfterReturn : function(bol, container_num){
                var ret = 0;
                var thestep = this.step; // cannot reference this directly inside callback
                for(var i=0; i<this.remain.length; i++)
                {
                    Object.keys(this.remain[i]).forEach(function(key){
                        if(key==bol && this.remain[i][bol]!=null)
                            Object.keys(this.remain[i][bol]).forEach(function(cont){
                                if(cont==container_num && this.remain[i][bol][container_num]!=null)
                                    for(var j=0; j<this.remain[i][bol][container_num].length; j++){
                                        if(ret ==  null)
                                            ret = 0;
                                        if(thestep == 0 || thestep == 1 && parseInt(this.remain[i][bol][container_num][j].ret_amt)>0)
                                          ret += (parseInt(this.remain[i][bol][container_num][j].in_stock) - parseInt(this.remain[i][bol][container_num][j].ret_amt));
                                    }
                            })
                    });
                }
                return ret;
            },
            containerWasteTotal : function(bol, container_num){
                var ret = 0;
                for(var i=0; i<this.remain.length; i++)
                {
                    Object.keys(this.remain[i]).forEach(function(key){
                        if(key==bol && this.remain[i][bol]!=null)
                            Object.keys(this.remain[i][bol]).forEach(function(cont){
                                if(cont==container_num && this.remain[i][bol][container_num]!=null)
                                    for(var j=0; j<this.remain[i][bol][container_num].length; j++){
                                        if(ret ==  null)
                                            ret = 0;
                                        ret += parseInt(this.remain[i][bol][container_num][j].ret_amt);
                                    }
                            })

                    });
                }
                return ret;
            },

            consignmentWasteTotal : function(consignment){
                var ret = 0;
                Object.keys(consignment).forEach(function(bol){
                    Object.keys(consignment[bol]).forEach(function(cont_num){
                        for(var i=0; i<consignment[bol][cont_num].length; i++)
                            ret += parseInt(consignment[bol][cont_num][i].ret_amt);
                    })
                });

                return ret;
            }
        },
        mounted : function(){

        }
    })
  </script>

@endsection
