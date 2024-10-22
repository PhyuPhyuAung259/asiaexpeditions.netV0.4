@extends('layout.backend')
@section('title', 'Register New User')
<?php $active = 'users'; 
  $subactive ='user/register';
  use App\component\Content;
  $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
?>
@section('content')
  <div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">
      <section class="content"> 
        <div class="row">
          @include('admin.include.message')
          <div class="col-lg-12"><h3 class="border text-center">User Management</h3></div>
          <form method="POST" action="{{route('addUser')}}">
            {{csrf_field()}}
            <section class="col-lg-8 col-lg-offset-2">
              <div class="card"> 
                <div class="row">
                  <div class="col-md-6 col-xs-6">
                    <div class="form-group {{$errors->has('fullname')?'has-error has-feedback':''}}">
                      <label>Full Name <span style="color:#b12f1f;">*</span></label> 
                      <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Full Name" value="{{old('fullname')}}" required> 
                    </div> 
                  </div>
                  <div class="col-md-6 col-xs-6">
                    <div class="form-group {{$errors->has('username')?'has-error has-feedback':''}}">
                      <label>UserName <span style="color:#b12f1f;">*</span></label> 
                      <input type="text" class="form-control" name="username" id="username" placeholder="User Name" value="{{old('username')}}" required>
                    </div> 
                  </div>
                  <div class="col-md-6 col-xs-6">
                    <div class="form-group {{$errors->has('email')?'has-error has-feedback':''}}">
                      <label>Email Address <span style="color:#b12f1f;">*</span></label> 
                      <input type="email" class="form-control" name="email" id="email" placeholder="virak@asia-expeditions.com" value="{{old('email')}}"  required>
                    </div> 
                  </div>
                  <div class="col-md-6 col-xs-6">
                    <div class="form-group {{$errors->has('phone')?'has-error has-feedback':''}}">
                      <label>Phone <span style="color:#b12f1f;">*</span></label>
                      <input type="text" class="form-control" name="phone" id="phone" placeholder="(+855) 1234 567 890" value="{{old('phone')}}" required>
                    </div>
                  </div>
                  <div class="col-md-6 col-xs-6">
                    <div class="form-group {{$errors->has('password')?'has-error has-feedback':''}}">
                      <label>Password<span style="color:#b12f1f;">*</span> <small>(Password at less 6 character)</small></label> 
                      <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="{{old('password')}}" required>
                    </div> 
                  </div>
                  <div class="col-md-6 col-xs-6">
                    <div class="form-group {{$errors->has('con-password')?'has-error has-feedback':''}}">
                      <label>Confirm Password <span style="color:#b12f1f;">*</span>  <small>(Must be the same password)</small></label>
                      <input type="password" class="form-control" name="con-password" id="con-password" placeholder="Confirm Password" value="{{old('con-password')}}" required>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer" style="padding: 5px 13px;">
                <button type="submit" class="btn btn-success btn-flat btn-sm">Register Now</button>
                <a href="{{route('userList')}}" class="btn btn-primary btn-flat btn-sm">Back</a>
              </div>
            </section>
          </form>
        </div>
      </section>
    </div>  
  </div>  
  </script>
@endsection
