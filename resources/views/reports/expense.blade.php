@extends('layout.mainlayout')

@section('scripts')
  <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css">
  <script src="/js/dough.js" type="text/javascript"></script>
  <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>

  <script src="/js/reports.js" type="text/javascript"></script>




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
    @include('partials.timeperiodselect')
  </div>

  <div class="row" style="margin-top: 1em;">
  <div class="col-md-12">
    <button class="btn btn-default" onclick="generateMonthlyReport('expense');">Generate Monthly Report</button>
    <button class="btn btn-default" onclick="generateQuarterlyReport('expense');">Generate Quarterly Report</button>
    <button class="btn btn-default" onclick="generateYearlyReport('expense')">Generate Yearly Report</button>
  </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="white-card">
        <h6>Report generated on {{--$date--}}</h6>
        <h1 class="stat">EXPENSE REPORT FOR {{--$time_frame--}} OF {{--$report_year--}}</h1>
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
                {{$exp_local}}
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 stat stat-text">
                TOTAL LOCAL EXPENSE
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="row">
              <div class="col-md-12 stat stat-figure">
                {{$exp_foreign}}
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 stat stat-text">
                TOTAL FOREIGN EXPENSES
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="row">
              <div class="col-md-12 stat stat-figure">
                {{$exp_lc}}
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 stat stat-text">
                TOTAL LC EXPENSES
              </div>
            </div>
          </div>
        </div>
      </div> <!--white-card-->
    </div> <!--col-md-12-->
  </div> <!--first-row-->

<div class="row">
      <div class="col-md-6">
        <div class="white-card">
          <div class="row">
            <div class="col-md-12 stat stat-figure">
                 {{$exp_consignment}}
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 stat stat-text">
              TOTAL CONSIGNMENT EXPENSES
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="white-card">
          <div class="row">
            <div class="col-md-12 stat stat-figure">
                 {{$count_con_exp}}
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 stat stat-text">
                NO OF CONSIGNMENT EXPENSES
            </div>
          </div>
        </div>
      </div>
</div> <!-- second  row-->

</div><!--container-->

@endsection
