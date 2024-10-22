@extends('layout.frontend')
@section('title', config('app.title'))
<?php
  use App\component\Content;
?>
<link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">    
<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
@section('content')
<div class="wrapper-login">
  <div class="col-md-4 offset-md-4">
    <center>
      <img src="{{url(config('app.logo'))}}" style="width: 30%;">
      <p><strong style="text-shadow: 0px 1px 3px #f8f9fa;font-weight: 700;">COMPLETE TOUR BOOKING SYSTEM</strong></p>
    </center>
      @include('include.message')
    <div class="card" style="padding:12px;">  
      <form action="{{route('doLogin')}}" method="POST">     
        {{csrf_field() }}
        <div class="form-group">
          <label for="phone">Email Address</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Email" required="required" value="{{old('email')}}">
        </div>
        <div class="form-group">
          <label for="phone">Password</label>            
          <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="required" value="{{old('password')}}">
        </div>
        <div class="form-group">
          <label><input type="checkbox" name="remember">Remember me ?</label>
        </div>
        <div class="form-group">
          <input type="submit" name="btnLogin" class="btn btn-info">
        </div>
      </form>   
    </div>
  </div>
</div>
@endsection
