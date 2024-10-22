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
          <form method="POST" action="{{route('updateTour')}}">
              {{csrf_field()}}
              <input type="hidden" name="eid" value="{{$tour->id}}">
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
                        <select class="form-control country" name="country" data-type="country" data-locat="data" required>
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
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Tour Type <span style="color:#b12f1f;">*</span></label>
                         <div class="btn-group" style="display: block;">
                            <button type="button" class="form-control " data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" data-backdrop="static" data-keyboard="false" role="dialog" data-backdrop="static" data-keyboard="false">
                             <span class="pull-left"> Tour Type </span><span class="pull-right"><i class="caret"></i></span>
                            </button>  
                            <div class="obs-wrapper-search">
                              <div class="">
                                <input type="text" data-url="{{route('getFilter')}}" id="data_filter" class="form-control" >
                              </div>
                              <ul class="dropdown-data" style="width: 100%;" id="Show_date">
                                @foreach(App\Business::where(['category_id'=>1, 'status'=>1])->orderBy('name', 'ASC')->get() as $key=>$cat)
                                <li>
                                  <div class="checkbox" style="margin:0px">
                                    <input id="checkid{{$key}}" type="checkbox" name="type[]" value="{{$cat->id}}" {{in_array($cat->id, explode(',', $dataId)) ? 'checked':''}}> 
                                    <label style="position: relative;top: 5px;" for="checkid{{$key}}">{{$cat->name}}</label>
                                  </div>
                                </li>                           
                                @endforeach
                                <div class="clearfix"></div>
                              </ul>
                            </div>
                          </div>
                      </div>
                    </div>                 
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Destination<span style="color:#b12f1f;">*</span></label>
                         <input type="text" name="destination" class="form-control" placeholder="Destination" required value="{{$tour->tour_dest}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Days/Nights<span style="color:#b12f1f;">*</span></label> 
                        <input type="text" name="daynight" class="form-control" placeholder="Days/Nights" required value="{{$tour->tour_daynight}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Price <small>({{Content::currency()}})</small></label> 
                        <input type="text" name="tour_price" class="form-control number_only" placeholder="00.00 {{Content::currency()}}" value="{{$tour->tour_price}}" required>
                      </div>
                    </div>
                  </div>  
                  <div class="form-group">
                    <label>Service Included/Exluded</label>
                    <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
                    <textarea class="form-control my-editor" name="tour_remark" rows="6" placeholder="Enter ...">{!! old('tour_remark', $tour->tour_remark) !!}</textarea>             
                  </div>
                  <div class="form-group">
                    <label>Hightlights</label>
                    <textarea class="form-control my-editor" name="tour_hight" rows="6" placeholder="Enter ...">{!! old('tour_hight', $tour->tour_intro) !!}</textarea>             
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control my-editor" name="tour_desc" rows="6" placeholder="Enter ...">{!! old('tour_desc', $tour->tour_desc) !!}</textarea>             
                  </div>
                  <div class="form-group">
                    <div class="col-md-2 col-xs-6">
                      <div class="form-group">
                        <label>Website</label>&nbsp;
                        <label style="font-weight: 400;"> <input type="radio" name="web" value="1"{{$tour->web == 1 ? 'checked':''}}>Yes</label>&nbsp;&nbsp;
                        <label style="font-weight: 400;"> <input type="radio" name="web" value="0" {{$tour->web == 1 ? '':'checked'}}>No</label>
                      </div> 
                    </div>
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
              <section class="col-lg-3 connectedSortable">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div id="wrap-feature-image" style="position:relative;" class="{{$tour->tour_photo ? 'open-img':''}}">
                      <input type="hidden" name="user_id" value="{{$tour->user_id}}">
                      <input type="hidden" name="image" id="data-img" value="{{$tour->tour_photo}}">
                      <img id="feature-img" src="/storage/{{$tour->tour_photo}}" style="width:100%;display:none;margin-bottom:12px;" class="btnUploadFiles" data-type="single-img" data-toggle="modal" data-target="#myUpload">
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
                      $gallery = explode("|", rtrim($tour->tour_picture, "|"));
                    ?>
                      <ul class="list-ustyled">
                        @if(!empty($tour->tour_picture))                        
                          @foreach($gallery as $key=>$img)
                            <li data-url="{{$img}}"><i class="removegallery fa fa-remove (alias)" title="Remove picture" style="display:none;"></i>
                              <input type="hidden" name="gallery[]" value="{{$img}}">  
                              <img src="/storage/{{$img}}" style='width:100%;'/></li>
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
                  <button type="submit" class="btn btn-success btn-flat btn-sm">Submit</button>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Tours Facilities</strong></div>
                    <div class="panel-body scrolTourFeasility" style="padding: 8px; max-height: 277px; ">
                      <ul class="list-unstyled">
                        @foreach(\App\Service::where('service_cat',0)->orderBy('service_name', 'ASC')->get() as $sv)
                          <li>
                            <div class="checkMebox">
                              <label>
                                <span style="position:relative; top:4px;"> 
                                  <i class="fa {{in_array($sv->id, explode(',', $datatour)) ? 'fa fa-check-square-o':'fa-square-o'}}"></i>
                                  <input type="checkbox" name="tour_feasilitiy[]" value="{{$sv->id}}"{{in_array($sv->id, explode(',', $datatour)) ? 'checked':''}}>&nbsp;
                                </span>
                                <span>{{$sv->service_name}}</span>
                              </label>
                            </div>
                          </li>
                        @endforeach
                      </ul>
                    </div>
                    <div class="panel-footer"></div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Accommodation</strong></div>
                    <div class="panel-body scrolTourAccommodation" style="padding: 8px; max-height: 277px; overflow: auto;">
                      <ul class="list-unstyled">
                        @foreach(\App\Supplier::where(['business_id'=>1, 'supplier_status'=>1, 'country_id'=> $tour->country_id])->orderBy('supplier_name', 'ASC')->get() as $sup)
                          <li>
                            <div class="checkMebox">
                              <label>
                                <span style="position:relative; top:4px;"> 
                                  <i class="fa {{in_array($sup->id, explode(',', $tourAccommodation)) ? 'fa fa-check-square-o':'fa-square-o'}}"></i>
                                  <input type="checkbox" name="tour_supplier[]" value="{{$sup->id}}"{{in_array($sup->id, explode(',', $tourAccommodation)) ? 'checked':''}}>&nbsp;
                                </span>
                                <span>{{$sup->supplier_name}}</span>
                              </label>
                            </div>
                          </li>
                        @endforeach
                      </ul>
                    </div>
                    <div class="panel-footer"></div>
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
