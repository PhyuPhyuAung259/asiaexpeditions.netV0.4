@extends('layout.backend')
@section('title', 'Company Profile')
<?php 
  $active = 'setting-options'; 
  $subactive ='company/profile';
  use App\component\Content;
  $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
?>
@section('content')
<style type="text/css">
  

</style>
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <div class="row">
        @include('admin.include.message')
        <div class="col-lg-12"><h3 class="border text-center">Company Profile</h3></div>
        <form method="POST" action="{{route('addCompany')}}" enctype="multipart/form-data"> 
          {{csrf_field()}}
          <section class="col-lg-8 col-lg-offset-2">
            <input type="hidden" name="company_id" value="{{Auth::user()->company->id}}">
            <div class="col-md-4 col-xs-6 col-md-offset-4 text-center">
                <div id="wrap-feature-image" style="position:relative;" {!! Auth::user()->company->logo ? "class='open-img'":'' !!}>
                  <input type="hidden" name="image" id="data-img" value="{{Auth::user()->company->logo}}">
                  <img id="feature-img" src="{{ Auth::user()->company->logo > 0 ? Storage::url(Auth::user()->company->logo) : Storage::url('/avata/logo.png') }}" style="width:100%;margin-bottom:12px; display: {{Auth::user()->company->logo ? 'block':'none'}};" class="btnUploadFiles" data-type="single-img" data-toggle="modal" data-target="#myUpload">
                  <i id="removeImage" class="fa fa-remove (alias)" title="Remove picture" style="display: none;"></i>
                </div>
                <a href="#uploadfile" class="btnUploadFiles" data-type="single-img" data-toggle="modal" data-target="#myUpload">Set Feature Image</a>
            </div>                
            <div class="clearfix"></div><br>
            <div class="col-md-6 col-xs-6">
              <div class="form-group {{$errors->has('title')?'has-error has-feedback':''}}">
                <label>Company Name<span style="color:#b12f1f;">*</span></label> 
                <input type="text" class="form-control" name="title" placeholder="Company Title" value="{{ Auth::user()->company->title}}" required=""> 
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group {{$errors->has('name')?'has-error has-feedback':''}}">
                <label>Sub Title <span style="color:#b12f1f;">*</span></label> 
                <input type="text" class="form-control" name="name" placeholder="Company Name" value="{{ Auth::user()->company->name}}" required="">
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Country <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control country" name="country" data-type="country" data-locat="data" required>
                  @foreach(App\Country::where('country_status', 1)->orderBy('country_name')->get() as $con)
                    <option value="{{$con->id}}" {{Auth::user()->company->country_id == $con->id ? 'selected': ''}}>{{$con->country_name}}</option>
                  @endforeach
                </select>
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>City <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control" name="city" id="dropdown-data" required>
                  @foreach(App\Province::where(['province_status'=> 1, 'country_id'=> Auth::user()->company->country_id])->orderBy('province_name')->get() as $pro)
                    <option value="{{$pro->id}}" {{ Auth::user()->company->province_id == $pro->id ? 'selected':''}}>{{$pro->province_name}}</option>
                  @endforeach
                </select>
              </div>  
            </div>  
        </section>
        <section class="container">
            <div class="col-md-6 col-xs-12">
              <div class="form-group {{$errors->has('password')?'has-error has-feedback':''}}">
                <label>Address</label>
                 <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
                <textarea class="form-control my-editor" name="address" rows="8" placeholder="Enter ...">{!! Auth::user()->company->address !!}</textarea>
              </div> 
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="form-group {{$errors->has('desc')?'has-error has-feedback':''}}">
                <label>About Company</label> 
                <textarea class="form-control my-editor" name="desc" rows="8" placeholder="Enter ...">{!! Auth::user()->company->desc !!}</textarea>
              </div> 
            </div>
        
            <div class="col-md-6 col-xs-6">
               <?php 
                  if (isset(Auth::user()->company->status) && Auth::user()->company->status == 1 ) {
                    $check = "checked";
                    $uncheck = "";
                  }else{
                    $check = "";
                    $uncheck = "checked";
                  }
                ?>
              <div class="form-group">
                <label>Status</label>&nbsp;
                <label style="font-weight:400;"> <input type="radio" name="status" value="1" {{$check}}>Publish</label>&nbsp;&nbsp;
                <label style="font-weight: 400;"> <input type="radio" name="status" value="0" {{$uncheck}}>UnPublish</label>
              </div> 
            </div>
            <div class="col-md-12 col-xs-12">
              <div class="modal-footer" style="padding: 5px 13px; text-align: center;">
                <button type="submit" class="btn btn-success btn-flat btn-sm" id="btnAddCompany">Publish</button>
              </div>   
            </div>                
          </section>
        </form>
      </div>
    </section>
  </div>  
</div>
@include('admin.include.editor')
@include('admin.include.windowUpload')
@endsection
