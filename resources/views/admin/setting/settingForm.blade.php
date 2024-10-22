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
        <div class="col-lg-12"><h3 class="border text-center">Setting </h3></div>
        <form method="POST" action="{{route('updateSetting', ['setID'=> $setting->id])}}" enctype="multipart/form-data"> 
          {{csrf_field()}}
          <section class="col-lg-8 col-lg-offset-2">
            <div class="col-md-12 col-xs-12">
              <div class="form-group {{$errors->has('title')?'has-error has-feedback':''}}">
                <label>Title<span style="color:#b12f1f;">*</span></label> 
                <input type="text" class="form-control" name="title" placeholder="Company Title" value="{{{ $setting->title or ''}}}" required=""> 
              </div> 
            </div>
                          
            <div class="col-md-12 col-xs-12">
              <div class="form-group {{$errors->has('password')?'has-error has-feedback':''}}">
                <label>Description</label>
                 <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
                <textarea class="form-control my-editor" name="details" rows="8" placeholder="Enter ...">{!! $setting->details or '' !!}</textarea>
              </div> 
            </div>
           
            <div class="col-md-12 col-xs-12">
               <?php 
                  if (isset($setting->status) && $setting->status == 1 ) {
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
                <button type="submit" class="btn btn-success btn-flat btn-sm">Publish</button>
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
