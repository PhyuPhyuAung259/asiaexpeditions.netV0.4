@extends('layout.frontend')
@section('title', config('app.title'))
<?php
  use App\component\Content;
?>
@section('content')
<div class="wrapper-login">
  <nav class="navbar-light text-center" style=" border-radius: 0px;">
    <center>
      <img src="{{url('img/jnglogo.png')}}" style="width: 30%;">
    </center>
  </nav>
  <div class="col-md-6 offset-md-3" style="margin-top: 22px;">
    <center> 
      <h4>Add your Company to Complete your Account <strong style="text-shadow: 0px 1px 3px #f8f9fa;font-weight: 700;"> {{$user->fullname}}</strong></h4>
    </center>
      @include('include.message')
    <div class="card" style="padding:12px; margin-top: 22px;">  
      <form action="{{route('setupCompany')}}" method="POST" class="form-horizontal">     
        <input type="hidden" name="cpid" value="{{$user->id}}">
        {{csrf_field() }}
          <div class="box-body">
            <div class="form-group">
              <div class="row">
                <label class="col-sm-5 text-right">Your Company Name?</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="company" placeholder="What is your Company/Organization Name">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <label class="col-sm-5 text-right">Sub title ?</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="name" placeholder="What is your Sub Title">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <label class="col-sm-5 text-right">Choose Location of your company</label>
                <div class="col-sm-7">
                  <select class="form-control" name="country">
                    <?php $getCountry=App\Country::where(["country_status"=>1])->orderBy('country_name', 'ASC')->get(); ?>
                    @foreach($getCountry as $key => $con)
                      <option value="{{$con->id}}">{{$con->country_name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <label class="col-sm-5 text-right">What is your time zone?</label>
                <div class="col-sm-7">
                  <select class="form-control" name="timezone">
                    <?php $getCountry = App\TimeZone::where(["status"=>1])->orderBy('zone', 'ASC')->get(); ?>
                    @foreach($getCountry as $key => $coun)
                      <option value="{{$coun->id}}">{{$coun->zone}}, {{$coun->city_name}}</option>
                    @endforeach
                  </select>                    
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <label class="col-sm-5 text-right">Choose Currency for your company </label>
                <div class="col-sm-7">
                  <select class="form-control" name="currency">
                    <?php $getCurrency = App\Currency::where(["status"=>1])->orderBy('title', 'ASC')->get(); ?>
                    @foreach($getCurrency as $key => $curren)
                      <option value="{{$curren->id}}">{{$curren->title}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer text-center">
            <button type="submit" class="btn btn-info text-center">Start Now</button>
          </div>
      </form>   
    </div>
  </div>
</div>
@endsection
