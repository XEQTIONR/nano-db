@extends('layout.mainlayout')


@section('content')

<form method="post" action="/hscodes">

  {{ csrf_field() }}

  Hscode <input type="text" name="inputHscode"> <br>
  <button type="submit" value="submit">Submit</button>

</form>

@endsection
