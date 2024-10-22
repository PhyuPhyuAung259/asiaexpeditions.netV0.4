@extends('layout.backend')
@section('title', 'Vehicle Type')
<?php
  $active = 'restaurant/menu'; 
  $subactive ='transport/service';
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
          <h3 class="border">Vehicle List Of {{{ $tranName->title or ''}}} <span class="fa fa-angle-double-right"></span> <a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModal" id="btnAddVehicle">Add Vehicle</a></h3>
            <table class="table table-hover table-striped">
              <thead>
                <tr>
                  <th>Vehicle Type</th>
                  <th class="text-center">Price {{Content::currency()}}</th>
                  <th class="text-center">Price {{Content::currency(1)}}</th>
                  <th class="text-center" width="80px">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($getVehicle as $veh)
                <tr>                  
                  <td>{{$veh->name}}</td>
                  <td class="text-right">{{number_format($veh->price,2)}}</td>
                  <td class="text-right">{{number_format($veh->kprice,2)}}</td>
                  <td class="text-right">                      
                    <button style="padding:0px; border:none;" class="vehicleEdit" data-id="{{$veh->id}}" data-title="{{$veh->name}}" data-price="{{$veh->price}}" data-kprice="{{$veh->kprice}}" data-toggle="modal" data-target="#myModal">
                      <i style="padding:1px 2px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i>
                    </button>
                    <a href="javascript:void(0)" class="RemoveHotelRate" data-type="vehicle" data-id="{{$veh->id}}" title="Remove this menu ?">
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

<div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog ">    
    <form id="form_submitVehicle" method="post" action="{{route('addVehicle')}}">
      <div class="modal-content">        
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong id="form_title">Add Vehicle </strong> <strong>for {{{ $tranName->title or ''}}}</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
          <input type="hidden" name="eid" id="vhid">
          <input type="hidden" name="tour_id" value="{{$vehicle}}">
          <input type="hidden" name="supplier_id" value="{{{ $tranName->tour_id or ''}}}">
          <div class="row">
            <div class="col-md-12 col-xs-12">    
              <div class="form-group">
                <label>Vehicle Type <span style="color:#b12f1f;">*</span></label> 
                <input type="text" name="vehicle" id="vehicle" class="form-control" required  placeholder="Vehicle Name">
              </div>
            </div>                 
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Price {{Content::currency()}} </label>
                <input type="text" class="form-control number_only" name="price" id="price"  placeholder="00.0">
              </div>
            </div>     
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Price {{Content::currency(1)}} </label>
                <input type="text" class="form-control number_only" name="kprice" id="kprice" placeholder="00.0">
              </div>
            </div>         
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success btn-flat btn-sm" id="btnsave">Publish</button>
            <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Cancel</a>
          </div>
        </div>  
      </div>  
    </form>
  </div>
</div>
@endsection
