@extends('layout.mainlayout')

@section('scripts')
  <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css">
  <script src="/js/dough.js" type="text/javascript"></script>
  <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>


  <style>

    .ui-datepicker-calendar{
      display: none;
    }
    path.color0 {
        fill: teal;  /*filled section color*/
    }
    path.color1 {
        fill: #AAA; /*unfilled section color*/
    }
    text {
        font-size: 2em;
        font-weight: 400;
        line-height: 16em;
        fill: teal;
    }
  </style>
@endsection

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="page-header">
        <h1>Outstanding Balance Reports</h1>
      </div>
    </div>
  </div>

  {{--<div class="row" style="margin-top: 1em;">
  <div class="col-md-12">
    <button class="btn btn-default" onclick="">By Order</button>
    <button class="btn btn-default" onclick="">By Customer</button>
  </div>
  </div>--}}

  <div class="row">
    <div class="col-md-12">
      <div class="white-card">
      {{--  <h6>Report generated on {{$date}}</h6>--}}
          <h1 class="stat">OUTSTANDING BALANCE REPORT</h1>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="white-card">
        <div class="row">

          <div class="col-md-4">
            <div class="row">
              <div class="col-md-12 stat stat-figure">
                {{$num_orders}}
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 stat stat-text">
                # OF ORDERS WITH OUTSTANDING BALANCE.
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="row">
              <div class="col-md-12 stat stat-figure">
                {{$total_value_formatted}}
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 stat stat-text">
                TOTAL VALUE OF THESE ORDERS
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="row">
              <div class="col-md-12 stat stat-figure">
                {{$num_customers}}
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 stat stat-text">
                # OF CUSTOMER WHO OWE US
              </div>
            </div>
          </div>


          <div class="col-md-4">
            <div class="row">
              <div class="col-md-12 stat stat-figure">
                {{$avg_per_customer}}
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 stat stat-text">
                AVERAGE AMOUNT OWED BY EACH CUSTOMER
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="row">
              <div class="col-md-12 stat stat-figure">
                {{$avg_per_order}}
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 stat stat-text">
                AVERAGE AMOUNT OWED PER ORDER
              </div>
            </div>
          </div>

        </div>
      </div> <!--white-card-->
    </div> <!--col-md-12-->
  </div> <!--first-row-->

  <div class="row">
    <div class="col-md-12">
      <div class="white-card">
        <div class="row">
        <div class="stat-diagram col-md-4" id="doughnut">
        <script>
          build({{$total_value-$total_owed}}, {{$total_value}},".2f","TK ",0,"#doughnut");
        </script>
        </div>
        <div class="stat-text col-md-6" style="border:1px dashed blue;">
          <div class="row marg-top">
            <div class="col-md-12 stat stat-figure">
              {{$total_owed_formatted}}
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 stat stat-text">
              AMOUNT OWED
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="white-card">
        @include('partials.tables.orders')
      </div>
    </div>
  </div>
</div><!--container-->
@endsection
