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
        <div class="col-lg-12"><h3 class="border">Account Management</h3></div>
        <form method="POST" action="{{route('updateAcc',['id'=> $acc->id])}}">
            {{csrf_field()}}
            <section class="col-lg-9 connectedSortable">
              <div class="card">                                
                <div class="row">
                  <div class="col-md-6 col-xs-12">
                      <div class="form-group {{$errors->has('title') ?'has-error has-feedback':''}} ">
                      <label>Account name<span style="color:#b12f1f;">*</span></label> 
                      <input autofocus="" type="text" placeholder="Account Name" class="form-control" name="account_name" value="{{ $acc-> account_name}}" required>
                    </div> 
                  </div>        
               
                <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Account Type <span style="color:#b12f1f;">*</span></label>
                      <!-- <select class="form-control" name="account_type" required>
                        <option value="">--Select--</option>
                        @foreach(App\AccountType::where(['status'=>1])->orderBy('account_name', 'ASC')->get() as $key=>$type)
                            <option value="{{ $type->id}}">{{$type->account_name}}</option>
                        @endforeach
                      </select> -->
                     
                 <?php $acc_type = App\AccountType::where('id',$acc->account_type_id)->first();
                 ?>
                  
                      <input type="text" name="account_type" class="form-control" value="{{$acc_type->account_name}}" readonly >

                    </div>
                  </div>                 
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group ">
                      <label>Account Code<span style="color:#b12f1f;">*</span></label>
                       <input type="text" name="account_code" class="form-control" value="{{$acc->account_code}}" >
                    </div>
                </div>
                <div class="col-md-6 col-xs-6">
                    <div class="form-group ">
                      <label>Account Description<span style="color:#b12f1f;">*</span></label>
                      <input type="text" name="desc" class="form-control" value="{{$acc->account_desc}}" >
                    </div>
                </div>
                <div class="col-md-3 col-xs-6">
                <div class="form-group ">
                    <button type="submit" class="btn btn-success btn-flat btn-sm">Submit</button>&nbsp;
                </div>
                </div>
            </section>
        </form>
      </div>
    </section>
  </div>  
</div>
@include('admin.include.windowUpload')
@include('admin.include.editor')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
            $('#country').change(function() {
                var countryId = $(this).val();

                $.ajax({
                    url: '/cities/' + countryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var citySelect = $('#city');
                        citySelect.empty();
                        if (response.length === 0) {
                            citySelect.append('<option value="">No cities available</option>');
                        } else {
                            $.each(response, function(key, value) {
                                citySelect.append('<option value="' + value.id + '">' +
                                    value.province_name + '</option>');
                            });
                        }
                    }
                });
            });
        });
</script>
@endsection
