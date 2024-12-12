@extends('layout.backend')
@section('title', 'Transport Service')
<?php
  $active = 'restaurant/menu'; 
  $subactive ='transport/service';
  use App\component\Content;
?>
@section('content')
<style type="text/css">
  .obs-wrapper-search ul li{
    padding: 0px;
  }
</style>
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <div class="row">
        <section class="col-lg-12 connectedSortable">
          <h3 class="border">Transport Services <span class="fa fa-angle-double-right"></span> <a href="#" data-toggle="modal" data-target="#myModal" class="btn btn-default btn-sm" id="btnCreateTransport">Add Transport Service</a></h3>
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
                  <th class="text-center">Supplier</th>
                  <th>Province</th>
                  <th class="text-center" width="80px">Status</th>
                </tr>
              </thead> 
              <tbody>
                @foreach($tranService as $tran)
                <tr>
                  <td>{{$tran->title}}</td>
                  <td width="120px" class="text-center">
                    <?php $title = ""; ?>
                    @foreach($tran->supplier_transport as $sup)
                      <?php $title .= $sup->supplier_name; ?>
                    @endforeach
                    @if($tran->supplier_transport->count() > 0)
                      <label class="badge" data-toggle="tooltip" title="{!! $title !!}">{{$tran->supplier_transport->count()}}</label>
                    @endif
                  </td> 

                  <td>{{{$tran->province->province_name or ''}}}</td>
                  <td class="text-right">       
                    @if($tran->supplier_transport->count() > 0)
                      <a style="position: relative; top: -5px;" class="btnEditVehicle" data-id="{{$tran->id}}" data-type="addVehicle" data-toggle="modal" data-target="#myModalVehicle" title="Add & View Vehicle">
                        <i style="padding:1px 2px;" class="btn btn-primary btn-xs fa fa-list-alt"></i>
                      </a>&nbsp;
                    @endif
                    <button class="btnTranUpdate" data-id="{{$tran->id}}" style="padding:0px; border:none; top: -5px; position: relative;" data-toggle="modal" data-target="#myModal">
                      <i style="padding:1px 2px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i>
                    </button>&nbsp;
                    <a href="javascript:void(0)" class="RemoveHotelRate" data-type="transport_service" data-id="{{$tran->id}}" title="Remove this menu ?">
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
<script type="text/javascript">
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
  });
</script>
<div class="modal in" id="myModal" role="dialog" data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-lg">    
    <form id="form_submittransportService" method="POST" action="{{route('addtranService')}}">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong id="form_title">Add Transport Service</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
          <input type="hidden" name="eid" id="eid">
          <div class="row">
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Country <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control country" id="country" name="country" data-type="country" data-title="7" required>
                    <option value="">--Choose--</option>
                  @foreach(App\Country::where('country_status', 1)->whereHas('tour')->orderBy('country_name')->get() as $con)
                    <option value="{{$con->id}}">{{$con->country_name}}</option>
                  @endforeach
                </select>
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>City Name <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control province_transport" name="city" data-type="transport_service" id="dropdown-country" required>
                </select>
              </div> 
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="service_name" id="service_name" class="form-control" placeholder="Service Name">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Transportation<span style="color:#b12f1f;">*</span></label>
                <div class="btn-group" style="display: block;">
                  <button type="button" class="form-control " data-toggle="dropdown" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
                   <span class="pull-left">Choose</span><span class="pull-right"><i class="caret"></i></span>
                  </button>  
                  <div class="obs-wrapper-search">
                    <div class="">
                      <input type="text" data-type="transport_service" data-url="{{route('getFilter')}}" id="data_filter" class="form-control" autofocus>
                    </div>
                    <ul class="dropdown-data" style="width: 100%;" id="dropdown-transport_service" style="min-height: 250px;">
                      <div class="clearfix"></div>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success btn-flat btn-sm" id="btnSubmitTran">Publish</button>
          <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Cancel</a>
        </div>
      </div>      
    </form>
  </div>
</div>
<!-- add vehicle form -->
<div class="modal in" id="myModalVehicle" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-lg">    
    <form id="form_submitVehicle" method="POST" action="{{route('addVehicle')}}">
      {{csrf_field()}}
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <div class="modal-title col-md-4 col-xs-4 text-right"><strong id="form_title" style="font-size: 1.3vw; ">Add Vehicle For Supplier</strong></div>
          <div class="col-md-6 col-xs-6">            
            <select class="form-control input-sm" id="addVehicleTransport" name="supplier" required="">
            </select>
            <input type="hidden" name="transport_id" id="transport_id">
          </div>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-11">
              <div class="row">
                <div class="col-md-5 col-xs-4">
                  <div class="form-group">
                    <label>Vehicle Name <span style="color:#b12f1f;">*</span></label> 
                    <input type="text" name="vehicle" id="title" class="form-control input-sm" required placeholder="Vehicle Name">
                  </div> 
                  <input type="hidden" name="eid" id="languid" class="clearValue" >
                </div>
                <div class="col-md-3 col-xs-4">
                  <div class="form-group">
                    <label>Price {{Content::currency()}}</label> 
                    <input type="text" name="price" id="price" class="form-control input-sm number_only" placeholder="00.0">
                  </div> 
                </div>
                <div class="col-md-3 col-xs-4">
                  <div class="form-group">
                    <label>Price {{Content::currency(1)}}</label> 
                    <input type="text" name="kprice" id="kprice" class="form-control input-sm number_only" placeholder="00.0">
                  </div> 
                </div>
            
                <div class="col-md-1">        
                  <button style="position: relative;top: 22px;right: 6px;" type="submit" class="btn btn-primary btn-sm" id=""  data-url="{{route('addLanguage')}}" value="Add">
                    <i class="fa fa-plus-circle"></i>&nbsp;&nbsp;
                    <span id="btnAddLanguage">Add</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th>Vihecle</th>
                <th width="130px">Price {{Content::currency()}}</th>
                <th width="130px">Price {{Content::currency(1)}}</th>
                <th width="100px" class="text-center">Status</th>
              </tr>
            </thead>
            <tbody id="TransportDataList">
             
            </tbody>
          </table>          
        </div>
        <div class="modal-footer">
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
