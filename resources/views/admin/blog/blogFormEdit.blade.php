@extends('layout.backend')
@section('title', 'Tour Edit')
<?php $active = 'tours'; 
  $subactive ='tour/create/new';
  use App\component\Content;
  $countryId = isset($tour->country_id) ? $tour->country_id : Auth::user()->country_id;
?>
@section('content')
  <div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">
      <section class="content"> 
        <div class="row">
          @include('admin.include.message')
          <div class="col-lg-12"><h3 class="border">Tour Management</h3></div>
          <form method="POST" action="{{route('blogupdate', ['id'=> $tour->id])}}">
              {{csrf_field()}}
              <section class="col-lg-9 connectedSortable">
                <div class="card">                                
                  <div class="row">
                    <div class="col-md-6 col-xs-12">
                      <div class="form-group">
                        <label>Tour name<span style="color:#b12f1f;">*</span></label> 
                        <input type="text" placeholder="Tour Name" class="form-control" name="title" value="{{$tour->tour_name}}" required >
                      </div> 
                    </div>        
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Country <span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control country" name="country" data-type="country"  data-locat="data"  required>
                          <option>--Choose--</option>
                          @foreach(App\Country::where('country_status', 1)->orderBy('country_name')->get() as $con)
                            <option value="{{$con->id}}" {{$tour->country_id == $con->id ? 'selected':''}}>{{$con->country_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>City <span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control" name="city" id="dropdown-data" required>
                          @foreach(App\Province::where(['province_status'=> 1,'country_id' =>$countryId])->orderBy('province_name')->get() as $pro)
                            <option value="{{$pro->id}}" {{$tour->province_id == $pro->id ? 'selected':''}}>{{$pro->province_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div>
                  </div>  
                 
                  <div class="form-group">
                    <label>Description</label>
                    <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
                    <textarea class="form-control my-editor" name="tour_desc" rows="6" placeholder="Enter ...">{!! old('tour_desc', $tour->tour_desc) !!}</textarea>             
                  </div>
                  <div class="form-group">
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Status</label>&nbsp;
                        <label style="font-weight:400;"> <input type="radio" name="status" value="1" {{$tour->tour_status == 1 ? 'checked':''}}>Publish</label>&nbsp;&nbsp;
                        <label style="font-weight: 400;"> <input type="radio" name="status" value="0" {{$tour->tour_status == 1 ? '':'checked'}}>UnPublish</label>
                      </div> 
                    </div>
                  </div>             
                </div>
              </section>
              <section class="col-lg-3 connectedSortable"><br>
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div id="wrap-feature-image" style="position:relative;" class="{{$tour->tour_photo ? 'open-img':''}}">
                      <input type="hidden" name="user_id" value="{{$tour->user_id}}">
                      <input type="hidden" name="image" id="data-img" value="{{$tour->tour_photo}}">
                      <img id="feature-img" src="{{Content::urlthumbnail($tour->tour_photo, $tour->user_id)}}" style="width:100%;display:none;margin-bottom:12px;" class="btnUploadFiles" data-type="single-img" data-toggle="modal" data-target="#myUpload">
                      <i id="removeImage" class="fa fa-remove (alias)" title="Remove picture" style="display: none;"></i>
                    </div>
                    <a href="#uploadfile" class="btnUploadFiles" data-type="single-img" data-toggle="modal" data-target="#myUpload">Set Feature Image</a>
                  </div>
                  <div class="panel-footer">Feature Image</div>
                </div>
                <div class="form-group text-center"> 
                  <button type="submit" class="btn btn-success btn-flat btn-sm">Submit</button>
                </div>
                <div class="panel panel-default">
                  <div class="panel-body" style="padding: 8px;">
                    <div id="wrap-gallery-image" style="position:relative;">
                      <?php 
                        $gallery = explode("|", rtrim($tour->tour_picture, "|"));
                      ?>
                      <ul class="list-ustyled">
                        @if(!empty($tour->tour_picture))                        
                          @foreach($gallery as $key=>$img)
                            <li data-url="{{$img}}"><i class="removegallery fa fa-remove (alias)" title="Remove picture" style="display:none;"></i>
                              <input type="hidden" name="gallery[]" value="{{$img}}">  
                              <img src="{{Content::urlthumbnail($img, $tour->user_id)}}" style='width:100%;'/></li>
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
              </section>
          </form>
        </div>
      </section>
    </div>  
  </div>
  @include('admin.include.windowUpload')
  @include('admin.include.editor')
@endsection
