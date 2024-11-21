@extends('layout.backend')
@section('title', 'Update User')
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
      @include('admin.include.message')
      <div class="row">          
        <div class="col-lg-12"><h3 class="border text-center">User Management</h3></div>
          <form method="POST" action="{{route('changePermission')}}">
            {{csrf_field()}}
            <section class="col-lg-8 col-lg-offset-2">
              <div class="card"> 
                <div class="row">
                  <input type="hidden" name="eid" value="{{$user->id}}">
                  <div class="col-md-6 col-xs-6">
                    <div class="form-group {{$errors->has('fullname')?'has-error has-feedback':''}}">
                      <label>Full Name <span style="color:#b12f1f;">*</span></label> 
                      <input type="text" class="form-control" name="fullname" placeholder="Full Name" value="{{old('fullname', $user->fullname)}}"> 
                    </div> 
                  </div>
                  <div class="col-md-6 col-xs-6">
                    <div class="form-group {{$errors->has('username')?'has-error has-feedback':''}}">
                      <label>Username <span style="color:#b12f1f;">*</span></label> 
                      <input type="text" class="form-control" name="username" placeholder="User Name" value="{{old('username', $user->name)}}" >
                    </div> 
                  </div>
                  <div class="col-md-6 col-xs-6">
                    <div class="form-group {{$errors->has('email')?'has-error has-feedback':''}}">
                      <label>Email Address <span style="color:#b12f1f;">*</span></label> 
                      <input type="email" class="form-control" name="email" placeholder="virak@asia-expeditions.com"  value="{{old('email', $user->email)}}"  required>
                    </div> 
                  </div>
                  <div class="col-md-6 col-xs-6">
                    <div class="form-group">
                      <label>Currently Password </label> 
                      <input type="text" class="form-control" name="old_password" value="{{old('old_password', $user->password_text)}}" readonly="">
                    </div> 
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group {{$errors->has('password')?'has-error has-feedback':''}}">
                      <label>New Password</label> 
                      <input type="password" class="form-control" name="password" value="{{old('password')}}" placeholder="New Password">
                    </div> 
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group {{$errors->has('con-password')?'has-error has-feedback':''}}">
                      <label>Confirm Password</label> 
                      <input type="password" class="form-control" name="con-password" value="{{old('con-password')}}" placeholder="Confirm Password" >
                    </div> 
                  </div>
                  <div class="col-md-6 col-xs-6">
                    <div class="form-group">
                      <label>Role</label> 
                      <select class="form-control" name="role">
                        @foreach(App\Role::where('status',1)->orderBy('name')->get() as $role)
                        <option value="{{$role->id}}" {{$role->id== $user->role_id ? 'selected':''}}>{{$role->name}}</option>
                        @endforeach
                      </select>
                    </div> 
                  </div>
                  <div class="col-md-6 col-xs-6">
                    <div class="form-group">
                      <div><label>Status</label></div>
                      <label style="font-weight:400;"> <input type="radio" name="banned" value="0" {{$user->banned==0? 'checked':''}}> Inactive</label>&nbsp;&nbsp;
                      <label style="font-weight:400;"> <input type="radio" name="banned" value="1" {{$user->banned==1? 'checked':''}}> Active</label>
                    </div> 
                  </div>
                </div>
              </div>   
              <div class="modal-footer" style="padding: 5px 13px;">
                <button type="submit" class="btn btn-success btn-flat btn-sm">Update Now</button>
                <a href="{{route('userList')}}" class="btn btn-primary btn-flat btn-sm" data-dismiss="modal">back</a>
              </div>           
            </section>
          </form>
      </div>
    </section>
  </div>  
</div>  
@endsection
