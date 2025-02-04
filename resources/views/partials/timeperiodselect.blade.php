<div class="col-md-12 form-inline">
  <div class="col-md-3">
    <div class="form-group">
      <label for="month">REPORT FOR   Month</label>
      <select class="form-control" id="month" placeholder="sth">
        @for ($i=1; $i <13 ; $i++)
          <option>{{$i}}</option>
        @endfor
      </select>
{{--<input type="text" class="form-control" id="month" placeholder="Report for Month">--}}
    </div>
  </div>
  <div class="col-md-2 col-md-offset-1">
    <div class="form-group">
      <label for="quarter">   OR Quarter</label>
      <input type="number" class="form-control" id="quarter" min="1" max=4 placeholder="1-4">
    </div>
  </div>

  <div class="col-md-3 col-md-offset-2">
    <div class="form-group">
      <label for="year">OF Year</label>
      {{--<input type="text" class="form-control" id="year" placeholder="Report for Year">--}}
      <select class="form-control" name="year" id="year">
        @for ($i=$year; $i>=2010; $i--)
          <option>{{$i}}</option>
        @endfor
      </select>
    </div>
  </div>
</div>
