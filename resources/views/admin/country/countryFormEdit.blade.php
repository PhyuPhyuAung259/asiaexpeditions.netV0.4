@extends('layout.backend')
@section('title', 'Add Country')
<?php
  $active = 'country';  
  $subactive ='country/create/new';
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
          <div class="col-lg-12"><h3 class="border">Country Management</h3></div>
          <form method="POST" action="{{route('updateCountry')}}">
              {{csrf_field()}}
              <input type="hidden" name="eid" value="{{$country->id}}">
              <section class="col-lg-9 connectedSortable">
                <div class="card">                                
                  <div class="row">
                    <div class="col-md-6 col-xs-12">
                      <div class="form-group">
                        <label>Country Name<span style="color:#b12f1f;">*</span></label> 
                        <input value="{{$country->country_name}}" type="text" placeholder="Country Name" class="form-control" name="name" required>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-12">
                      <div class="form-group">
                        <label>Country Title<span style="color:#b12f1f;">*</span></label> 
                        <input value="{{$country->country_title}}" type="text" class="form-control" name="country_title"  required>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-12">
                      <div class="form-group">
                        <label>Nationality<span style="color:#b12f1f;">*</span></label> 
                        <input type="text" placeholder="Nationality" name="nationality" class="form-control" name="nationality" value="{{$country->nationality}}" required>
                      </div> 
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
                    <textarea class="form-control my-editor" name="country_intro" rows="6" placeholder="Enter ...">{!! $country->country_intro  !!}</textarea>             
                  </div>
                  <div class="form-group">
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Status</label>&nbsp;
                        <label style="font-weight:400;"> <input type="radio" name="status" value="1" checked="">Publish</label>&nbsp;&nbsp;
                        <label style="font-weight: 400;"> <input type="radio" name="status" value="0">UnPublish</label>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Website</label>&nbsp;
                        <label style="font-weight:400;"> <input type="radio" name="web" value="1">Yes</label>&nbsp;&nbsp;
                        <label style="font-weight: 400;"> <input type="radio" name="web" value="0" checked="">No</label>
                      </div> 
                    </div>
                  </div>             
                </div>
              </section>
              <section class="col-lg-3 connectedSortable"><br>
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div id="wrap-feature-image" style="position:relative;">
                      <input type="hidden" name="image" value="{{$country->country_photo}}" id="data-img">

                      <img id="feature-img" src="{{Content::urlImage($country->country_photo)}}" 
                      style="width:100%;display:<?php echo $country->country_phone == ''? 'block': 'none';?>;margin-bottom:12px;" class="btnUploadFiles" data-type="single-img" data-toggle="modal" data-target="#myUpload">
                      
                      <i id="removeImage" class="fa fa-remove (alias)" title="Remove picture" style="display: none;"></i>
                    </div>
                    <a href="#uploadfile" class="btnUploadFiles" data-type="single-img" data-toggle="modal" data-target="#myUpload">Set Feature Image</a>
                  </div>
                  <div class="panel-footer">Supplier Logo</div>
                </div>
                <div class="panel panel-default">
                  <div class="panel-body" style="padding: 8px;">
                    <div id="wrap-gallery-image" style="position:relative;">
                      <ul class="list-ustyled">
                      </ul>                    
                      <div class="clearfix"></div>
                      <i id="removeImage" class="fa fa-remove (alias)" title="Remove picture" style="display: none;"></i>
                    </div>
                    <a href="#uploadfile" class="btnUploadFiles" data-type="multi-img" data-toggle="modal" data-target="#myUpload">Set Gallery Image</a>
                  </div>
                  <div class="panel-footer">Gallery Image</div>
                </div>
                <div class="form-group"> 
                  <button type="submit" class="btn btn-success btn-flat btn-sm">Submit</button>&nbsp;&nbsp;
                  <a href="{{route('CountryList')}}" class="btn btn-danger btn-flat btn-sm">Back</a>
                </div>
              </section>
          </form>
        </div>
      </section>
    </div>  
  </div>
  @include('admin.include.windowUpload')
  @include('admin.include.editor')
@endsection
