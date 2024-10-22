@extends('layout.backend')
@section('title', 'Add Driver')
<?php
  $active = 'service'; 
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
        <section class="col-lg-12 connectedSortable">
          <h3 class="border">Driver List <span class="fa fa-angle-double-right"></span> <a href="#" data-toggle="modal" data-target="#myModal" class="btn btn-default btn-sm" id="btnCreateTransport">Add New Driver</a></h3>
            <table class="datatable table table-hover table-striped">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Transport Company</th>
                  <th>Country</th>
                  <th>Province</th>
                  <th class="text-center">Status</th>
                </tr> 
              </thead>
              <tbody>
                @foreach($driver as $dr)
                <tr>
                  <td>{{$dr->driver_name}}</td>
                  <td>{{{$dr->transport->supplier_name or ''}}}</td>
                  <td>{{{$dr->country->country_name or ''}}}</td>
                  <td>{{{$dr->province->province_name or ''}}}</td>
                  <td class="text-right">
                    <a href="#" class="tranEditMenu"  data-title="{{$dr->driver_name}}"  data-id="{{$dr->id}}" data-supplier="{{$dr->tour_id}}" data-country="{{{$dr->country->id or ''}}}" data-city="{{{$dr->province->id or ''}}}" data-toggle="modal" data-target="#myModal">
                      <i style="padding:1px 2px; position: relative;top:-5px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i>
                    </a>&nbsp;
                    <!-- <a href="javascript:void(0)" class="RemoveHotelRate" data-type="guide_service" data-id="{{$dr->id}}" title="Remove this menu ?">
                      <label src="#" class="icon-list ic-trash"></label>
                    </a> -->

                    {!! Content::DelUserRole("Remove this Driver  ?", "guide_service", $dr->id, $dr->user_id ) !!}    
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
          <h4 class="modal-title"><strong>Driver Name</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
          <input type="hidden" name="eid" id="eid">
          <div class="row">
            <div class="col-md-4 col-xs-6">
              <div class="form-group">
                <label>Country <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control country" id="country" name="country" data-type="country" required>
                    <option value="">Country</option>
                  @foreach(App\Country::where('country_status', 1)->orderBy('country_name')->get() as $con)
                    <option value="{{$con->id}}">{{$con->country_name}}</option>
                  @endforeach
                </select>
              </div> 
            </div>
            <div class="col-md-4 col-xs-6">
              <div class="form-group">
                <label>City Name <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control province" name="city" data-type="transport_service" id="dropdown-data" required>
                  <option value="">City</option>
                  @foreach(App\Province::where(['province_status'=> 1])->orderBy('province_name')->get() as $pro)
                    <option value="{{$pro->id}}">{{$pro->province_name}}</option>
                  @endforeach
                </select>
              </div> 
            </div>
            <div class="col-md-4 col-xs-6">
              <div class="form-group text-center">
                <i class="fa fa-user" style="font-size: 7em; font-size: 7em;padding: 0px 19px;border: solid;color: #ddd;border-radius: 6px;"></i>
              </div> 
            </div>
            <div class="col-md-4 col-xs-6">
              <div class="form-group">
                <label>Transport Name <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control province" name="city" data-type="transport_service" id="dropdown-data" required>
                  <option value="">City</option>
                  @foreach(App\Province::where(['province_status'=> 1])->orderBy('province_name')->get() as $pro)
                    <option value="{{$pro->id}}">{{$pro->province_name}}</option>
                  @endforeach
                </select>
              </div> 
            </div>
         
            <div class="col-md-4">
              <div class="form-group">
                <label>Phone </label>
                <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone +855 123 456 789">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Phone 2</label>
                <input type="text" name="phone2" id="phone2" class="form-control" placeholder="Phone +855 123 456 789">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Email Addres 1</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Ex: virak@sia-expeditions.com">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Email Addres 2</label>
                  <input type="email" name="email2" id="email2" class="form-control" placeholder="Ex: virak@sia-expeditions.com">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Driver Info</label>
                <textarea class="form-control" rows="5" placeholder="Enter here...!"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Driver Remark</label>
                <textarea class="form-control" rows="5" placeholder="Enter here...!"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
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
    <form id="form_submitGlanguage" method="post" action="{{route('addLanguage')}}">
      {{csrf_field()}}
      <div class="modal-content">        
        <div class="modal-header" style="padding: 5px 13px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong id="form_lan_title">Add Language</strong></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 col-xs-4">
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
            <div class="col-md-1 col-xs-4" style="padding-left: 0px;">
              <label><label></label></label>
              <button type="submit" class="btn btn-primary btn-sm" id="btnSaveLange" data-url="{{route('addLanguage')}}" value="Add">
                <i class="fa fa-plus-circle"></i>&nbsp;&nbsp;
                <span id="btnAddLanguage">Add</span>
              </button>
            </div>
          </div>
          <table class="table table-hover table-striped" id="tableLanuage">
            <thead>
              <tr>
                <th>Langauge</th>
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
@endsection
