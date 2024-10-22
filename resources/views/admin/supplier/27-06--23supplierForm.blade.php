@extends('layout.backend')
@section('title', isset($business) ? $business->name: 'Suppliers')
<?php
  $active = isset($business) ? 'supplier/'.$business->slug: 'suppliers';  
  $subactive ='supplier/add/new';
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
        <div class="col-lg-12"><h3 class="border">{{{$business->name or 'Supplier'}}} Management</h3></div>
        <form method="POST" action="{{route('createSupplier')}}">
            {{csrf_field()}}
            <section class="col-lg-9 connectedSortable">
              <div class="card">                                
                <div class="row">
                  <div class="col-md-6 col-xs-12">
                      <div class="form-group {{$errors->has('title') ?'has-error has-feedback':''}} ">
                      <label>Supplier name<span style="color:#b12f1f;">*</span></label> 
                      <input autofocus="" type="text" placeholder="Tour Name" class="form-control" name="title" value="{{old('title')}}" required>
                    </div> 
                  </div>        
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Country <span style="color:#b12f1f;">*</span></label> 
                      <select class="form-control country" name="country" data-type="country" required>
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
                      <label>Business Type <span style="color:#b12f1f;">*</span></label>
                      <select class="form-control" name="business_type" required>
                        <option value="">--Select--</option>
                        @foreach(App\Business::where(['category_id'=>0, 'status'=>1])->orderBy('name', 'ASC')->get() as $key=>$cat)
                            <option value="{{$cat->id}}" {{$type == $cat->slug ?'selected':''}}>{{$cat->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>                 
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group {{$errors->has('contact_name')?'has-error has-feedback':''}}">
                      <label>Contact Name<span style="color:#b12f1f;">*</span></label>
                       <input type="text" name="contact_name" class="form-control" placeholder="Contact Person" value="{{old('contact_name')}}" >
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group {{$errors->has('phone_one')?'has-error has-feedback':''}}">
                      <label>Phone 1<span style="color:#b12f1f;">*</span></label> 
                      <input type="text" name="phone_one" class="form-control" placeholder="Ex:+855 123 456 789" value="{{old('phone_one')}}"  required>
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Phone 2</label>
                      <input type="text" name="phone_two" class="form-control" placeholder="Ex:+855 123 456 789">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Fax Number</label>
                      <input type="text" name="fax_number" class="form-control" placeholder="Ex:+855 123 456 789">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group {{$errors->has('email_one')?'has-error has-feedback':''}}">
                      <label>Email Address 1 <span style="color:#b12f1f;">*</span></label>
                      <input type="eamil" name="email_one" class="form-control" placeholder="example@gmail.com" value="{{old('email_one')}}">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Email Address 2</label>
                      <input type="email" name="email_two" class="form-control" placeholder="example@gmail.com">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Website</label>
                      <input type="text" name="website" class="form-control" placeholder="{{config('app.url_add')}}">
                    </div>
                  </div>
                </div>  
                <div class="form-group">
                  <label>Address</label>
                  <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
                  <textarea class="form-control" name="address" rows="6" placeholder="Enter ...">{!! old('tour_remark') !!}</textarea>             
                </div>
                <div class="form-group">
                  <label>Remarks</label>
                  <textarea class="form-control" name="remark" rows="6" placeholder="Enter ...">{!! old('tour_hight') !!}</textarea>             
                </div>
                <div class="form-group">
                  <label>Description</label>
                  <textarea class="form-control my-editor" name="desc" rows="6" placeholder="Enter ...">{!! old('tour_desc') !!}</textarea>             
                </div>
                <div class="form-group">
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
            <section class="col-lg-3 connectedSortable"><br>
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
                <button type="submit" class="btn btn-success btn-flat btn-sm">Submit</button>&nbsp;
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
