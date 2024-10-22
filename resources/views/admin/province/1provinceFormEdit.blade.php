@extends('layout.backend')
@section('title', 'Edit Province')
<?php
  $active = 'country';  
  $subactive ='province';
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
          <form method="POST" action="{{route('updateProvince')}}">
              {{csrf_field()}}
              <input type="hidden" name="eid" value="{{$province->id}}">
              <section class="col-lg-9 connectedSortable">
                <div class="card">                                
                  <div class="row">
                    <div class="col-md-6 col-xs-12">
                      <div class="form-group">
                        <label> Province Name<span style="color:#b12f1f;">*</span></label> 
                        <input autofocus="" type="text" class="form-control" name="province_name" value="{{$province->province_name}}" required>
                      </div> 
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <div class="form-group">
                        <label>Country<span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control" name="country">
                          @foreach(App\Country::where('country_status', 1)->orderBy('country_name', 'ASC')->get() as $key => $con)
                            <option value="{{$con->id}}" {{$province->country_id == $con->id ?'selected':''}}>{{$con->country_name}}</option>
                          @endforeach
                        </select>
                    
                         
                      </div> 
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
                    <textarea class="form-control my-editor" name="province_intro" rows="6" placeholder="Enter ...">{!! old('province_intro', $province->province_intro) !!}</textarea>             
                  </div>
                  <div class="form-group">
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Status</label>&nbsp;
                        <label style="font-weight:400;"> <input type="radio" name="status" value="1" {{$province->province_status == 1 ? 'checked':''}}>Publish</label>&nbsp;&nbsp;
                        <label style="font-weight: 400;"> <input type="radio" name="status" value="0" {{$province->province_status == 0 ? 'checked':''}}>UnPublish</label>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Website</label>&nbsp;
                        <label style="font-weight:400;"> <input type="radio" name="web" value="1" {{$province->web == 1 ? 'checked':''}}>Yes</label>&nbsp;&nbsp;
                        <label style="font-weight: 400;"> <input type="radio" name="web" value="0" {{$province->web == 0 ? 'checked':''}}>No</label>
                      </div> 
                    </div>
                  </div>             
                </div>
              </section>
              <section class="col-lg-3 connectedSortable"><br>
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div id="wrap-feature-image" style="position:relative;" class="{{$province->province_photo ? 'open-img':''}}">
                      <input type="hidden" name="user_id" value="{{$province->user_id}}">
                      <input type="hidden" name="image" id="data-img" value="{{$province->province_photo}}">
                      <img id="feature-img" src="{{Content::urlthumbnail($province->province_photo, $province->user_id)}}" style="width:100%;display:none;margin-bottom:12px;" class="btnUploadFiles" data-type="single-img" data-toggle="modal" data-target="#myUpload">
                      <i id="removeImage" class="fa fa-remove (alias)" title="Remove picture" style="display: none;"></i>
                    </div>
                    <a href="#uploadfile" class="btnUploadFiles" data-type="single-img" data-toggle="modal" data-target="#myUpload">Set Feature Image</a>
                  </div>
                  <div class="panel-footer">Feature Image</div>
                </div>
                <div class="panel panel-default">
                  <div class="panel-body" style="padding: 8px;">
                    <div id="wrap-gallery-image" style="position:relative;">
                    <?php 
                      $gallery = explode("|", rtrim($province->province_picture, "|"));
                    ?>
                      <ul class="list-ustyled">
                        @if($gallery[0])                        
                          @foreach($gallery as $key=>$img)
                            <li data-url="{{$img}}"><i class="removegallery fa fa-remove (alias)" title="Remove picture" style="display:none;"></i>
                              <input type="hidden" name="gallery[]" value="{{$img}}">  
                              <img src="{{Content::urlthumbnail($img, $province->user_id)}}" style='width:100%;'/></li>
                          @endforeach
                        @endif
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
