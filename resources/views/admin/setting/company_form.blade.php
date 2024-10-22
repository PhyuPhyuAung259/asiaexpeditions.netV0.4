@extends('layout.backend')
@section('title', 'Company Info')
<?php 
  $active = 'setting-options'; 
  $subactive ='company';
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
        <div class="col-lg-12"><h3 class="border text-center">Company Information</h3></div>
        <form method="POST" action="{{route('addCompany')}}" enctype="multipart/form-data"> 
          {{csrf_field()}}
          <section class="col-lg-8 col-lg-offset-2">
            @if($cp)
            <div class="col-md-6 col-xs-6 col-md-offset-3 text-center">
                <div class="col-md-6" id="wrapper-logo">
                  @if(empty($cp->logo))
                    <i class="fa fa-user" style="font-size: 170px; font-size: 170px;border: solid #ddd 1px;padding: 1px 19px;border-radius: 5px;color: #999;"></i>
                  @else
                    <img src="/storage/avata/{{$cp->logo}}" style="width: 100%;">
                  @endif  
                </div>
                <div class="col-md-6 text-left"><br><br>
                  <div class="form-group"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalCenterTitle" >Upload Image</button></div><br>
                    <div class="form-group remove_image" style="display: {{isset($cp->logo)? 'block':'none'}}"><button data-id="{{{$cp->id or ''}}}" data-filename="{{{$cp->logo or ''}}}" id="removeLogo" type="button" class="btn btn-danger btn-sm">Remove</button></div>
                </div>
            </div>
            <input type="hidden" name="company_id" value="{{{$cp->id or ''}}}">
            @endif
            <div class="col-md-6 col-xs-6">
              <div class="form-group {{$errors->has('title')?'has-error has-feedback':''}}">
                <label>Company Name<span style="color:#b12f1f;">*</span></label> 
                <input type="text" class="form-control" name="title" placeholder="Company Title" value="{{{ $cp->title or ''}}}" required=""> 
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group {{$errors->has('name')?'has-error has-feedback':''}}">
                <label>Sub Title <span style="color:#b12f1f;">*</span></label> 
                <input type="text" class="form-control" name="name" placeholder="Company Name" value="{{{ $cp->name or ''}}}" required="">
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Country <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control country" name="country" data-type="country" required>
                  @foreach(App\Country::where('country_status', 1)->orderBy('country_name')->get() as $con)
                    <option value="{{$con->id}}" {{(isset($cp->country_id)? $cp->country_id: '') == $con->id ? 'selected':''}}>{{$con->country_name}}</option>
                  @endforeach
                </select>
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>City <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control" name="city" id="dropdown-data" required>
                  @foreach(App\Province::where(['province_status'=> 1])->orderBy('province_name')->get() as $pro)
                    <option value="{{$pro->id}}" {{(isset($cp->province_id)? $cp->province_id:'') == $pro->id ? 'selected':''}}>{{$pro->province_name}}</option>
                  @endforeach
                </select>
              </div> 
            </div>                  
            <div class="col-md-12 col-xs-12">
              <div class="form-group {{$errors->has('password')?'has-error has-feedback':''}}">
                <label>Address</label>
                 <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
                <textarea class="form-control my-editor" name="address" rows="8" placeholder="Enter ...">{!! $cp->address or '' !!}</textarea>
              </div> 
            </div>
            <div class="col-md-12 col-xs-12">
              <div class="form-group {{$errors->has('desc')?'has-error has-feedback':''}}">
                <label>Description</label> 
                <textarea class="form-control my-editor" name="desc" rows="8" placeholder="Enter ...">{!! $cp->descs or ''!!}</textarea>
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
               <?php 
                  if (isset($cp->status) && $cp->status == 1 ) {
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
              <div class="modal-footer" style="padding: 5px 13px;">
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
<!-- Modal -->
<div class="modal fade" id="exampleModalCenterTitle" role="dialog" data-backdrop="static" data-keyboard="false" data-show="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle"><strong>Upload Image</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" id="form_uploadeFileOnly" action="{{route('uploadOnlyFile')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-body">
          <input type="hidden" name="cp_id" value="{{{$_GET['cp_id'] or ''}}}">
          <p><span>User images are 140px by 140px  Use a jpg, png, or gif image, under 1MB.</span></p>
          <span>Choose file to upload</span>
          <input type="file" name="onlyFile" id="company-logo">        
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Uploade</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
