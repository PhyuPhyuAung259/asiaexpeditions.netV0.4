@extends('layout.backend')
@section('title', 'Create Tour')
<?php $active = 'tours'; 
  $subactive ='tour/create/new';
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
          <div class="col-lg-12"><h3 class="border">Tour Management</h3></div>
          <form method="POST" action="{{route('tourCreate')}}">
              {{csrf_field()}}
              <section class="col-lg-9 connectedSortable">
                <div class="card">                                
                  <div class="row">
                    <div class="col-md-6 col-xs-12">
                      <div class="form-group">
                        <label>Tour name<span style="color:#b12f1f;">*</span></label> 
                        <input type="text" placeholder="Tour Name" class="form-control" name="title" required>
                      </div> 
                    </div>        
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Country <span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control country" name="country" data-type="country" data-method="tour_accommodation" required>
                          @foreach(App\Country::where('country_status', 1)->orderBy('country_name')->get() as $con)
                            <option value="{{$con->id}}" {{Auth::user()->country_id == $con->id ? 'selected':''}}>{{$con->country_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>City <span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control" name="city" id="dropdown-data" required>
                          @foreach(App\Province::where(['province_status'=> 1, 'country_id'=> $countryId ])->orderBy('province_name')->get() as $pro)
                            <option value="{{$pro->id}}">{{$pro->province_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                          <label>Tour Type <span style="color:#b12f1f;">*</span></label>
                          <div class="btn-group" style="display: block;">
                            <button type="button" class="form-control " data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" data-backdrop="static" data-keyboard="false" role="dialog" data-backdrop="static" data-keyboard="false">
                              <span class="pull-left">Tour Type </span>
                              <span class="pull-right"><i class="caret"></i></span>
                            </button>  
                            <div class="obs-wrapper-search">
                              <div class="">
                                <input type="text" data-url="{{route('getFilter')}}" id="search" onkeyup="myFunction()" class="form-control" >
                              </div>
                              <ul class="dropdown-data" id="myUL" style="width: 100%;" >
                                @foreach(App\Business::where(['category_id'=>1, 'status'=>1])->orderBy('name', 'ASC')->get() as $key=>$cat)
                                  <li class="list" style="padding: 0px 0px;">
                                    <div class="checkbox" style="margin: 0px">
                                      <input id="checkid{{$key}}" type="checkbox" name="type[]" value="{{$cat->id}}">
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
                          <input type="text" name="destination" class="form-control" placeholder="Destination" >
                      </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Days/Nights<span style="color:#b12f1f;">*</span></label> 
                        <input type="text" name="daynight" class="form-control" placeholder="Days/Nights" >
                      </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Price <small>({{Content::currency()}})</small></label> 
                        <input type="text" name="tour_price" class="form-control number_only" placeholder="00.00 {{Content::currency()}}">
                      </div>
                    </div>
                  </div>  
                  <div class="form-group">
                    <label>Service Included/Exluded</label>
                    <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
                    <textarea class="form-control my-editor" name="tour_remark" rows="6" placeholder="Enter ...">{!! old('tour_remark') !!}</textarea>             
                  </div>
                  <div class="form-group">
                    <label>Hightlights</label>
                    <textarea class="form-control my-editor" name="tour_hight" rows="6" placeholder="Enter ...">{!! old('tour_hight') !!}</textarea>             
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control my-editor" name="tour_desc" rows="6" placeholder="Enter ...">{!! old('tour_desc') !!}</textarea>             
                  </div>
                  <div class="form-group">
                    <div class="col-md-2 col-xs-6">
                      <div class="form-group">
                        <label>Website</label>&nbsp;
                        <label style="font-weight: 400;"> <input type="radio" name="web" value="1" checked="">Yes</label>&nbsp;&nbsp;
                        <label style="font-weight: 400;"> <input type="radio" name="web" value="0">No</label>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Status</label>&nbsp;
                        <label style="font-weight:400;"> <input type="radio" name="status" value="1" checked="">Publish</label>&nbsp;&nbsp;
                        <label style="font-weight: 400;"> <input type="radio" name="status" value="0">UnPublish</label>
                      </div> 
                    </div>
                  </div>             
                </div>
              </section>
              <section class="col-lg-3 connectedSortable">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div id="wrap-feature-image" style="position:relative;">
                      <input type="hidden" name="image" id="data-img">
                      <img id="feature-img" src="#" style="width:100%;display:none;margin-bottom:12px;" class="btnUploadFiles" data-type="single-img" data-toggle="modal" data-target="#myUpload">
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
                  <a href="{{route('tourList')}}" class="btn btn-default btn-flat btn-sm">Cancel</a>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Tours Facilities</strong></div>
                    <div class="panel-body scrolTourFeasility" style="padding: 8px; max-height: 277px;">
                      <ul class="list-unstyled">
                        @foreach(\App\Service::where('service_cat',0)->orderBy('service_name', 'ASC')->get() as $sv)
                          <li>
                            <div class="checkMebox">
                              <label>
                                <span style="position: relative;top: 4px;"> 
                                  <i class="fa fa-square-o "></i>
                                  <input type="checkbox" name="tour_feasilitiy[]" value="{{$sv->id}}">&nbsp;
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
                        @foreach(\App\Supplier::where(['business_id'=>1, 'supplier_status'=>1, 'country_id'=> Auth::user()->country_id])->orderBy('supplier_name', 'ASC')->get() as $sup)
                          <li>
                            <div class="checkMebox">
                              <label>
                                <span style="position: relative;top: 4px;"> 
                                  <i class="fa fa-square-o "></i>
                                  <input type="checkbox" name="tour_supplier[]" value="{{$sup->id}}">&nbsp;
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
  <script>
    function myFunction() {
      input = document.getElementById("search");
      filter = input.value.toUpperCase();
      ul = document.getElementById("myUL");
      li = ul.getElementsByTagName("li");
      for (i = 0; i < li.length; i++) {
        a = li[i];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
          li[i].style.display = "";
        } else {
          li[i].style.display = "none";
        }
      }
    }
  </script>
@endsection
