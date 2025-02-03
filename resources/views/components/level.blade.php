<ol class="breadcrumb">
  <li><a href="@if(isset($link)) {{  $link  }} @endif"><i class="fa fa-dashboard"></i>{{$crumb}}</a></li>
  @if(isset($subcrumb))
  <li class="active">{{$subcrumb}}</li>
  @endif
  @if(isset($extra))
  {{$extra}}
  @endif
</ol>