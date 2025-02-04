@extends('layouts.app')

@section('title')
  Letters of credit
@endsection
@section('subtitle')
  All LCs applied for.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'Letters of Credit',
    'subcrumb' => 'All LCs',
     'link' => route('lcs.index')])
  @endcomponent
@endsection

@section('body')

  <div class="box box-primary">
    <div class="box-body">
      <table id ="table_id" class="table table-hover table-bordered">
        <thead>
        <tr>
          <th>LC#</th>
          <th>Date Issued</th>
          <th>Date Expiry</th>
          <th>Exchange Rate (&#2547/$)</th>

          <th>LC Value($)</th>
          <th>LC Value(&#2547)</th>
          <th>Expenses Total(&#2547)</th>
          <th>Created</th>

        </tr>
        </thead>

        <tbody>
        @foreach ($LCs as $LC)
          <tr style="cursor: pointer;" onclick="location.href='/lcs/{{$LC->lc_num}}'">
            <td class="text-center strong">{{$LC->lc_num}}</td>
            <td class="text-center">{{$LC->date_issued}}</td>
            <td class="text-center">{{$LC->date_expiry}}</td>
            <td class="text-right">{{$LC->exchange_rate}} </td>

            <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),$LC->foreign_amount)}}</td>
            <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),$LC->foreign_amount * $LC->exchange_rate)}}</td>
            <td class="text-right">{{numfmt_format(resolve('CurrencyFormatter'),(($LC->foreign_expense * $LC->exchange_rate)+$LC->domestic_expense))}}</td>
            <td class="text-center">{{$LC->created_at}}</td>
          </tr>
        @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th class="text-center" colspan="3"></th>
            <th></th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th class="text-right text-red"></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>


@endsection

@section('footer-scripts')
  <script>
      $(document).ready(function() {


        let options = [
          { number : 4, prefix : "$ "},
          { number : 5, prefix : "৳ "},
          { number : 6, prefix : "৳ "},
        ];

        customFormatDataTable(options);

          table.order([7, 'desc'])
              .draw();
      });

  </script>
@endsection
