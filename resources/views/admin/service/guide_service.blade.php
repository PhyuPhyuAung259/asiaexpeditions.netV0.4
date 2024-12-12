@extends('layout.backend')
@section('title', 'Guide Service')
<?php
  $active = 'restaurant/menu'; 
  $subactive ='guide/service';
  use App\component\Content;
?> 
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <div class="row">
        @include('admin.include.message')
        <section class="col-lg-12 connectedSortable">
          <h3 class="border">Guide Services List <span class="fa fa-angle-double-right"></span> <a href="#" data-toggle="modal" data-target="#myModal" class="btn btn-default btn-sm" id="btnCreateTransport">Add New Service</a></h3>
            <form action="" method="">
              <div class="col-sm-2 col-xs-6 pull-right" style="text-align: right; position: relative; z-index: 2;">
                <label class="location">
                  <select class="form-control input-sm locationchange" name="locat">
                    @foreach(\App\Country::where(['country_status'=>1])->whereHas('supplier')->orderBy('country_name')->get() as $loc)
                      <option value="{{$loc->id}}" {{$locat == $loc->id ? 'selected':''}}>{{$loc->country_name}}</option>
                    @endforeach
                  </select>
                </label>
              </div>
            </form>
            <table class="datatable table table-hover table-striped">
              <thead>
                <tr>
                  <th>Title</th> 
                  <th>Code</th> 
                  <th>Province</th>
                  <th class="text-center">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($guide as $tran)
                <tr>
                  <td>{{$tran->title}}</td>
                  <td>{{$tran->code}}</td>
                  <td>{{{$tran->province->province_name or ''}}}</td>
                  <td class="text-right">
                    <a data-toggle="modal" data-target="#myModalLanguage" href="#" class="ViewLang" data-con="{{$tran->country_id}}"  data-title="{{$tran->title}}" data-id="{{$tran->id}}" title="{{$tran->language->count()}} Language click to privew details">
                      <i style="padding:1px 2px;" class="btn btn-primary btn-xs a fa fa-list-alt"></i>
                    </a>&nbsp;
                    <button class="tranEditMenu" data-code="{{$tran->code}}" data-title="{{$tran->title}}" style="padding:0px; border:none;" data-id="{{$tran->id}}" data-supplier="{{$tran->tour_id}}" data-country="{{{$tran->country->id or ''}}}" data-city="{{{$tran->province->id or ''}}}" data-toggle="modal" data-target="#myModal" title="click to update">
                      <i style="padding:1px 2px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i>
                    </button>&nbsp;
                    <a style="position: relative;top: 5px;" href="javascript:void(0)" class="RemoveHotelRate" data-type="guide_service" data-id="{{$tran->id}}" title="Remove this item?">
                      <label class="icon-list ic-trash"></label>
                    </a>
                  </td>                     
                </tr>
                @endforeach
              </tbody>
            </table>
        </section>
      </div>
    </section>
  </div>
</div>
<div class="modal fade" id="myModal" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-lg">    
    <form id="form_submitGuideService" method="POST" action="{{route('addGuideService')}}">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Guide Service</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
          <input type="hidden" name="eid" id="eid">
          <div class="row">
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Country <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control country" id="country" name="country" data-type="country_guide" required>
                    <option value="">--Choose--</option>
                    @foreach(App\Country::where('country_status', 1)->whereHas('supplier', function($query) {
                    $query->where(["supplier_status"=>1, 'business_id'=>6]);
                  })->orderBy('country_name')->get() as $con)
                    <option value="{{$con->id}}">{{$con->country_name}}</option>
                  @endforeach
                </select>
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>City Name <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control province" name="city" data-type="guide" id="dropdown-country_guide" required>
                  <option value="">--Choose--</option>
                  @foreach(App\Province::where(['province_status'=> 1])->orderBy('province_name')->get() as $pro)
                    <option value="{{$pro->id}}">{{$pro->province_name}}</option>
                  @endforeach
                </select>
              </div> 
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Guide Name</label>
                <select class="form-control " name="guide_language"  id="dropdown-guide" data-type="driver">
                    <option>--Choose--</option>
                    @foreach(App\Supplier::where(['business_id'=> 6, 'supplier_status'=>1, 'country_id'=> \Auth::user()->country_id])->orderBy('supplier_name', 'ASC')->get() as $sup)
                    <option value="{{$sup->id}}" data-phone="{{$sup->supplier_phone}}"  data-phone2="{{$sup->supplier_phone2}}">{{$sup->supplier_name}}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="service_name" id="service_name" class="form-control" placeholder="Service Name">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Service Code</label>
                <input type="text" name="service_code" id="service_code" class="form-control" placeholder="Service Code">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" >
          <button type="submit" class="btn btn-success btn-flat btn-sm" id="btnaddTransport">Publish</button>
          <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Cancel</a>
        </div>
      </div>      
    </form>
  </div>
</div>

<!-- list all language -->
<div class="modal fade" id="myModalLanguage" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-lg">
    <form id="form_submitGlanguage" method="POST" action="{{route('addLanguage')}}">
      {{csrf_field()}}
      <div class="modal-content"> 
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong id="form_lan_title">Add Language</strong></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-11">
              <div class="row">
                <div class="col-md-2 col-xs-4">
                  <div class="form-group">
                    <label>Country<span style="color:#b12f1f;">*</span></label> 
                    <select class="form-control country input-sm" id="country-guide" name="country" data-type="guide_by_country" required>
                        <option value="">--country--</option>
                      @foreach(App\Booking::countryByBooking() as $con)
                        <option value="{{$con->id}}">{{$con->country_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4 col-xs-4">
                  <div class="form-group">
                    <label>Choose Supplier<span style="color:#b12f1f;">*</span></label>
                    <div class="btn-group" style="display: block;">
                      <button type="button" class="form-control input-sm" data-toggle="dropdown" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
                       <span class="pull-left">Choose Supplier</span><span class="pull-right"><i class="caret"></i></span>
                      </button>  
                      <div class="obs-wrapper-search" style="padding: 0px;">
                        <div class="">
                          <input type="text" data-type="guide_by_country" data-url="{{route('getFilter')}}" id="data_filter" class="form-control" autofocus>
                        </div>
                        <ul class="dropdown-data" style="width: 100%; padding: 0px 4px;" id="dropdown-guide_by_country">
                          <div class="clearfix"></div>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-2 col-xs-4">
                  <div class="form-group">
                    <label>Language <span style="color:#b12f1f;">*</span></label> 
                    <input type="text" name="title" id="title" class="form-control input-sm" required placeholder="Language">
                  </div> 
                  <input type="hidden" name="languid" id="languid" class="clearValue">
                  <input type="hidden" name="service" id="service">
                </div>
                <div class="col-md-2 col-xs-4">
                  <div class="form-group">
                    <label>Price {{Content::currency()}}</label> 
                    <input type="text" name="price" id="price" class="form-control input-sm number_only" placeholder="00.0">
                  </div> 
                </div>
                <div class="col-md-2 col-xs-4">
                  <div class="form-group">
                    <label>Price {{Content::currency(1)}}</label> 
                    <input type="text" name="kprice" id="kprice" class="form-control input-sm number_only" placeholder="00.0">
                  </div> 
                </div>
              </div>
            </div>
            <div class="col-md-1">
              <div class="row">        
                <button style="position: relative;top: 22px;right: 6px;" type="submit" class="btn btn-primary btn-sm" id="btnSaveLange"  data-url="{{route('addLanguage')}}" value="Add">
                  <i class="fa fa-plus-circle"></i>&nbsp;&nbsp;
                  <span id="btnAddLanguage">Add</span>
                </button>
              </div>
            </div>
          </div>
          <table class="table table-hover table-striped" id="tableLanuage">
            <thead>
              <tr>
                <th>Langauge</th>
                <th>No. Of Supplier</th>
                <th width="130px">Price {{Content::currency()}}</th>
                <th width="130px">Price {{Content::currency(1)}}</th>
                <th width="100px" class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>          
        </div>
      </div>      
    </form>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $(".datatable").DataTable();
  });
</script>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>

@endsection
