@extends('layout.backend')
@section('title', isset($supplier->business->name)? $supplier->business->name:'Supplier')
<?php
  $active = isset($business) ? 'supplier/'.$business->slug: 'suppliers';
  $subactive ='supplier/add/new';
  use App\component\Content;
  $countryId = $supplier->country_id != "" ? $supplier->country_id : Auth::user()->country_id;
?>
@section('content')
  <div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">
      <section class="content"> 
        <div class="row">
          @include('admin.include.message')
          <div class="col-lg-12"><h3 class="border">{{{ $supplier->business->name }}} Management</h3></div>
          <form method="POST" action="{{route('udpateSupplier')}}"  enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" name="eid" value="{{$supplier->id}}">
            <section class="col-lg-9 connectedSortable">
              <div class="card">                                
                <div class="row">
                  <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label>Supplier name<span style="color:#b12f1f;">*</span></label> 
                      <input autofocus type="text" placeholder="Tour Name" class="form-control" name="title" required value="{{$supplier->supplier_name}}">
                    </div> 
                  </div>        
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Country <span style="color:#b12f1f;">*</span></label> 
                      <select class="form-control country" name="country" data-type="country" required>
                        @foreach(App\Country::where('country_status', 1)->orderBy('country_name')->get() as $con)
                          <option value="{{$con->id}}" {{$supplier->country_id == $con->id ? 'selected':''}}>{{$con->country_name}}</option>
                        @endforeach
                      </select>
                    </div> 
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>City <span style="color:#b12f1f;">*</span></label> 
                      <select class="form-control" name="city" id="dropdown-country" required>
                        @foreach(App\Province::where(['province_status'=> 1, 'country_id'=> $countryId ])->orderBy('province_name')->get() as $pro)
                          <option value="{{$pro->id}}" {{$supplier->province_id == $pro->id ? 'selected':''}}>{{$pro->province_name}}</option>
                        @endforeach
                      </select>
                    </div> 
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Business Type <span style="color:#b12f1f;">*</span></label>
                      <select class="form-control" name="business_type">
                        <option value="0">--Select--</option>
                      @foreach(App\Business::where(['category_id'=>0, 'status'=>1])->orderBy('name', 'ASC')->get() as $key=>$cat)
                        <option value="{{$cat->id}}" {{$supplier->business_id == $cat->id ? 'selected':''}}>{{$cat->name}}</option>
                      @endforeach
                      </select>
                    </div>
                  </div>                 
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Contact Name<span style="color:#b12f1f;">*</span></label>
                      <input type="text" value="{{$supplier->supplier_contact_name}}" name="contact_name" class="form-control" placeholder="Contact Person">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Phone 1<span style="color:#b12f1f;">*</span></label> 
                      <input type="text" value="{{$supplier->supplier_phone}}" name="phone_one" class="form-control" placeholder="Ex:+855 123 456 789" required>
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Phone 2</label>
                      <input type="text" value="{{$supplier->supplier_phone2}}" name="phone_two" class="form-control" placeholder="Ex:+855 123 456 789">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Bank Name</label>
                      <input type="text" name="bankname" class="form-control" placeholder="ABA or Wing">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Bank Account</label>
                      <input type="text" name="bankacc" class="form-control" placeholder="0123654789">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Bank QR Scan</label>
                      <input type="file" name="scan_img" class="form-control"/>
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Fax Number</label>
                      <input type="text" value="{{$supplier->supplier_fax}}" name="fax_number" class="form-control" placeholder="Ex:+855 123 456 789">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Email Address 1 <span style="color:#b12f1f;">*</span></label>
                      <input type="eamil" value="{{$supplier->supplier_email}}" name="email_one" class="form-control" placeholder="example@gmail.com">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Email Address 2</label>
                      <input type="email" value="{{$supplier->supplier_email2}}" name="email_two" class="form-control" placeholder="example@gmail.com">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Website</label>
                      <input type="text" value="{{$supplier->supplier_website}}" name="website" class="form-control" placeholder="{{config('app.url_add')}}">
                    </div>
                  </div>
                </div>  
                <div class="form-group">
                  <label>Address</label>
                  <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
                  <textarea class="form-control" name="address" rows="6" placeholder="Enter ...">{!! old('tour_remark', $supplier->supplier_address) !!}</textarea>             
                </div>
                <div class="form-group">
                  <label>Remarks</label>
                  <textarea class="form-control" name="remark" rows="6" placeholder="Enter ...">{!! old('tour_hight', $supplier->supplier_remark) !!}</textarea>             
                </div>
                <div class="form-group">
                  <label>Description</label>
                  <textarea class="form-control my-editor" name="desc" rows="6" placeholder="Enter ...">{!! old('tour_desc', $supplier->supplier_intro) !!}</textarea>             
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
                  <div id="wrap-feature-image" style="position:relative;" class="{{$supplier->supplier_photo ? 'open-img':''}}">
                    <input type="hidden" name="image" id="data-img" value="{{$supplier->supplier_photo}}">
                    <img id="feature-img" src="/storage/{{$supplier->supplier_photo}}" style="width:100%;display:none;margin-bottom:12px;" class="btnUploadFiles" data-type="single-img" data-toggle="modal" data-target="#myUpload">
                    <i id="removeImage" class="fa fa-remove (alias)" title="Remove picture" style="display: none;"></i>
                  </div>
                  <a href="#uploadfile" class="btnUploadFiles" data-type="single-img" data-toggle="modal" data-target="#myUpload">Set Feature Image</a>
                </div>
                <div class="panel-footer">Supplier Logo</div>
              </div>
              <div class="panel panel-default">
                <div class="panel-body" style="padding: 8px;">
                  <div id="wrap-gallery-image" style="position:relative;">
                    <?php 
                    $gallery = explode("|", rtrim($supplier->supplier_picture, "|"));
                    ?>
                    <ul class="list-ustyled">
                      @if( $gallery[0] )
                        @foreach($gallery as $key=>$img)
                          <li data-url="{{$img}}"><i class="removegallery fa fa-remove (alias)" title="Remove picture" style="display:none;"></i><input type="hidden" name="gallery[]" value="{{$img}}"><img src="/storage/{{$img}}" style='width:100%;' /></li>
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
