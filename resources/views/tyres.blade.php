@extends('layouts.app')

@section('title')
  Tyre Catalog
@endsection
@section('subtitle')
  All kinds of tyres imported.
  @endsection

  @section('level')
    @component('components.level',
      ['crumb' => 'Tyres',
      'subcrumb' => 'All tyres',
      'link' => route('tyres.index')])
    @endcomponent
  @endsection

@section('header-scripts')
  <style>
    .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
      background-color: #727272 !important;
      border-color: #727272 !important;
    }
  </style>
@endsection

@section('body')
  <div class="box">
    <div class="box-body">
      @include('partials.tyres')
    </div>
  </div>


@endsection
