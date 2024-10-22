@extends('layout.backend')
@section('title', 'Update User')
<?php 
  $active = 'users'; 
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
        <form method="POST" action="{{route('updateUser')}}" enctype="multipart/form-data"> 
          {{csrf_field()}}
          <section class="col-lg-8 col-lg-offset-2">
            <div class="card"> 
              <div class="row">
                <div class="col-md-6 col-xs-6 col-md-offset-3 text-center">
                    <a id="choosImg" href="javascript:void(0)">Choose Image</a>
                    <input name="image" type='file' id="imgInp" style="opacity: 0;" />
                    <center>
                      <img class="img-responsive" id="blah" src="/storage/avata/{{$user->picture}}" style="display: {{$user->picture? 'block':'none'}}; box-shadow: 0px 1px 0px 8px #ddd; border-radius:5px;"/>
                    </center>   
                    <input type="hidden" name="oldFile" value="{{$user->picture}}">
                </div>
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
                    <input type="text" class="form-control" name="username" placeholder="User Name" value="{{old('username', $user->name)}}" readonly>
                  </div> 
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group {{$errors->has('email')?'has-error has-feedback':''}}">
                    <label>Email Address <span style="color:#b12f1f;">*</span></label> 
                    <input type="email" class="form-control" name="email" placeholder="virak@asia-expeditions.com" value="{{old('email', $user->email)}}">
                  </div> 
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group {{$errors->has('phone')?'has-error has-feedback':''}}">
                    <label>Phone <span style="color:#b12f1f;">*</span></label>
                    <input type="text" class="form-control" name="phone" placeholder="(+855) 1234 567 890" value="{{old('phone', $user->phone)}}" required>
                  </div>
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group {{$errors->has('phone')?'has-error has-feedback':''}}">
                    <label>Company Name <span style="color:#b12f1f;">*</span></label>
                      <select class="form-control" name="company" required>
                      @foreach(App\Company::orderBy('name')->get() as $con)
                        <option value="{{$con->id}}" {{$user->company_id == $con->id ? 'selected':''}}>{{$con->title}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group {{$errors->has('password')?'has-error has-feedback':''}}">
                    <label>Position</label> 
                    <input type="text" class="form-control" name="position" placeholder="Position" value="{{old('position', $user->position)}}" required>
                  </div> 
                </div>
                <div class="col-md-6 col-xs-6">
                  <div class="form-group {{$errors->has('zipcode')?'has-error has-feedback':''}}">
                    <label>Zip/Code</label> 
                    <input type="text" class="form-control" name="zipcode" value="{{old('zipcode', $user->postal)}}" required>
                  </div> 
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Country <span style="color:#b12f1f;">*</span></label> 
                    <select class="form-control country" name="country" data-type="country" required>
                      @foreach(App\Country::where('country_status', 1)->orderBy('country_name')->get() as $con)
                        <option value="{{$con->id}}" {{$user->country_id == $con->id ? 'selected':''}}>{{$con->country_name}}</option>
                      @endforeach
                    </select>
                  </div> 
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>City <span style="color:#b12f1f;">*</span></label> 
                    <select class="form-control" name="city" id="dropdown-data" required>
                      @foreach(App\Province::where(['province_status'=> 1])->orderBy('province_name')->get() as $pro)
                        <option value="{{$pro->id}}" {{$user->province_id == $pro->id ? 'selected':''}}>{{$pro->province_name}}</option>
                      @endforeach
                    </select>
                  </div> 
                </div>                  
                <div class="col-md-6 col-xs-6">
                  <div class="form-group {{$errors->has('password')?'has-error has-feedback':''}}">
                    <label>Address</label>
                    <textarea class="form-control" name="address" rows="8" placeholder="Enter ...">{{old('address', $user->address)}}</textarea>
                  </div> 
                </div>
                <div class="col-md-6 col-xs-6">
                  <div class="form-group {{$errors->has('desc')?'has-error has-feedback':''}}">
                    <label>Description</label> 
                    <!-- <textarea class="form-control my-editor" name="desc" rows="8" placeholder="Enter ...">{{old('desc', $user->descs)}}</textarea> -->
                    <div id="container" >
                      <div class="row">
                        @include('include.editor')
                      </div>
                        <div class="editor1" style="resize:both; overflow:auto;max-width:100%;min-width: 100%;" class="titletou" contenteditable="true"  data-text="Enter comment....">{!! old('desc', $user->descs) !!}
                        </div>
                        <textarea class="form-control my-editor" name="desc" id="_desc" rows="7" placeholder="Description..." style="display: none ;">{!! old('desc', $user->descs) !!}</textarea>
                    </div> 
                  </div> 
                </div>
                <div class="col-md-6 col-xs-6">
                  <div class="form-group">
                    <label>Website</label>&nbsp;
                    <label style="font-weight:400;"> <input type="radio" name="web" value="1" {{$user->web==1? 'checked':''}}>Yes</label>&nbsp;&nbsp;
                    <label style="font-weight:400;"> <input type="radio" name="web" value="0" {{$user->web==0? 'checked':''}}>No</label>
                  </div> 
                </div>
              </div>
            </div>   
            <div class="modal-footer" style="padding: 5px 13px;">
                <button type="submit" class="btn btn-success btn-flat btn-sm">Update Now</button>
                <a href="{{route('userList')}}" class="btn btn-primary btn-flat btn-sm" data-dismiss="modal">Cancel</a>
            </div>           
          </section>
        </form>
      </div>
    </section>
  </div>  
</div>
<script type="text/javascript">
  $(document).ready(function(){
  $('.editor1').bind('change keyup input',function(){
      var gettext = $(document).find('.editor1').html();
      $('#_desc').val(gettext);
    });
  });
</script>
@endsection
