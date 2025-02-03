@extends('layout.mainlayout')

@section('scripts')
  <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css">
  <script src="/js/dough.js" type="text/javascript"></script>
  <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>

  <script src="/js/reports.js" type="text/javascript"></script>
@endsection

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="page-header">
        <h1>Payment Reports</h1>
      </div>
    </div>
  </div>

  <div class="row">
    @include('partials.timeperiodselect')
  </div>

  <div class="row" style="margin-top: 1em;">
  <div class="col-md-12">
    <button class="btn btn-default" onclick="generateMonthlyReport('payment');">Generate Monthly Report</button>
    <button class="btn btn-default" onclick="generateQuarterlyReport('payment');">Generate Quarterly Report</button>
    <button class="btn btn-default" onclick="generateYearlyReport('payment')">Generate Yearly Report</button>
  </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="white-card">
        <h6>Report generated on {{$date}}</h6>
        <h1 class="stat">PAYMENT REPORT FOR {{$time_frame}} OF {{$report_year}}</h1>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="white-card">
        <div class="row">
          <div class="col-md-3">
            <div class="row">
              <div class="col-md-12 stat stat-figure">
                {{$num_payments}}
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 stat stat-text">
                PAYMENTS RECEIVED
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="row">
              <div class="col-md-12 stat stat-figure">
                {{$num_orders}}
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 stat stat-text">
                #ORDERS FOR WHICH A PAYMENT WAS RECEIVED
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="row">
              <div class="col-md-12 stat stat-figure">
                {{$total_value}}
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 stat stat-text">
                TOTAL VALUE OF PAYMENTS RECEIVED
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="row">
              <div class="col-md-12 stat stat-figure">
                {{$avg_payment}}
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 stat stat-text">
                AVG VALUE OF PAYMENTS RECEIVED
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
        @include('partials.tables.payments')
      </div>
    </div>
  </div>
</div><!--container-->
@endsection
