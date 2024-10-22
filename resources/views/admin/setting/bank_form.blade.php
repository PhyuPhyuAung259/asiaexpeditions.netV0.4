@extends('layout.backend')
@section('title', 'Bank Info')
<?php 
  $active = 'setting-options'; 
  $subactive ='bank';
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
        <div class="col-lg-12"><h3 class="border text-center">Bank Infomation</h3></div>
        <form method="POST" action="{{route('addBankInfo')}}" enctype="multipart/form-data"> 
          {{csrf_field()}}
          <section class="col-lg-8 col-lg-offset-2">
            <input type="hidden" name="ebank_id" value="{{{ $bank->id or ''}}}">
            <div class="col-md-12 col-xs-12">
              <div class="form-group {{$errors->has('title')?'has-error has-feedback':''}}">
                <label>Bank Name<span style="color:#b12f1f;">*</span></label> 
                <input type="text" class="form-control" name="name" placeholder="Company Title" value="{{{ $bank->name or ''}}}" required=""> 
              </div> 
            </div>            
            <div class="col-md-12 col-xs-12">
              <div class="form-group {{$errors->has('desc')?'has-error has-feedback':''}}">
                <label>Bank Details</label> 
                <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
               <textarea class="form-control my-editor" name="details" rows="8" placeholder="Enter ...">{!! $bank->details or '' !!}</textarea>
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <?php 
                  if (isset($bank->status) && $bank->status == 1 ) {
                      $check = "checked";
                      $uncheck = "";
                  }else{
                    $check = "";
                    $uncheck = "checked";
                  }

                 ?>
                <label>Status</label>&nbsp;
                <label style="font-weight:400;"> <input type="radio" name="status" value="1" {{$check}}>Publish</label>&nbsp;&nbsp;
                <label style="font-weight: 400;"><input type="radio" name="status" value="0" {{$uncheck}}>UnPublish</label>
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

@endsection
