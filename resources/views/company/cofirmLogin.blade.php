@extends('layout.frontend')
@section('title', config('app.title'))
<?php
  use App\component\Content;
  use Illuminate\Support\Facades\Crypt;

  $sentDate = date('d', strtotime($user->created_at));
  $currentData =  date('d');

?>
@section('content')
<div class="wrapper-login">
  <nav class="navbar-light text-center skin-purple" style=" border-radius: 0px;">
    <center><img src="{{url('img/jnglogo.png')}}" style="width: 30%;"></center>
  </nav>
  <div class="col-md-4 offset-md-4" style="margin-top: 22px;">
    <center>
      <h4> Welcome to JngTravelPro <strong style="text-shadow: 0px 1px 3px #f8f9fa;font-weight: 700;"></strong></h4>
    </center>
    <div class="card" style="padding:12px; margin-top: 22px;">  
      @if($sentDate == $currentData )
      <form action="{{route('ConfirmSignin')}}" method="POST" class="form-horizontal">     
          {{csrf_field() }} 
          <input type="hidden" name="_for_l_in" value="{{Crypt::encryptString($user->email)}}">
          <div class="box-body">
            <div class="form-group {{$errors->has('password') || Session::has('errors-sms') ? 'has-error has-feedback':''}}">
                <label class="control-label" for="inputError"></i>Password</label>
                <input type="password"  class="form-control" name="password" placeholder="Type Your Password" value="{{old('password', $user->password_text)}}">
                <span class="help-block">{{ Session::has('errors-sms') ? Session::get('errors-sms') : ''}}</span>
            </div>       
          </div>
          <div class="box-footer text-center">
            <button type="submit" class="btn btn-info text-center">Login</button>
          </div>
      </form>   
      @else
        Your link has been expired....!
      @endif
    </div>
  </div>
</div>
@endsection
