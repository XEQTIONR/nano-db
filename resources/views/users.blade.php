@extends('layouts.app')

@section('title')
  Users
@endsection
@section('subtitle')
  All users.
@endsection

@section('level')
  @component('components.level',
    ['crumb' => 'Users',
    'subcrumb' => 'All users',
     'link' => route('users.index')])
  @endcomponent
@endsection
@section('body')
  <div class="box">
    <div class="box-body">
      <table id="table_id" class="table table-hover table-bordered">
        <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Admin?</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
          <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>@if($user->admin) YES @else NO @endif</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>

@endsection
