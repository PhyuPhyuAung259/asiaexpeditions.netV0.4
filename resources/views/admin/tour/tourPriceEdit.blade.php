@extends('layout.backend')
@section('title', 'Assing Price For Tour')
<?php $active = 'tours'; 
$subactive ='tour/create/new';
  use App\component\Content;
?>
@section('content')
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <div class="row">
        @include('admin.include.message')
        <section class="col-lg-12 connectedSortable">          
          <input type="hidden" id="urlRate" value="{{route('getRatePrice')}}"> 
          <h4 class="border"><span style="color: #f39c12; font-size: 22px;" class="fa fa-tree"></span>  
          Add Pax & Price For<b> {{{ $tour->tour_name or ''}}} </b> <small>( currency USD Price )</small></h4>
          <form action="{{route('updateTourPrice')}}" method="POST">
            <input type="hidden" name="tour_id" value="{{$tour->id}}">

            {{csrf_field()}}
            <table class="table table-hover table-striped" id="hotel-rate">
              <thead>
                <tr>                     
                  <th title="Pax No." class="text-center" style="width: 19%;">Pax Number </th>
                  <th class="text-center"> Selling Price</th>
                  <th class="text-center"> Net Price</th>                    
                  <th width="80" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                @if( $tour->pricetour != null )
                  @foreach($tour->pricetour as $key=> $tour)
                    <tr>
                      <input type="hidden" name="eid[]" value="{{$tour->id}}">
                      <td><input name="pax_no[]" value="{{$tour->pax_no}}" placeholder="Pax 1" type="text" class="form-control input-sm number_only" required ></td>
                      <td><input name="sprice[]" value="{{$tour->sprice}}" placeholder="0.00 {{Content::currency()}}" type="text" class="form-control input-sm number_only"></td>
                      <td><input name="nprice[]" value="{{$tour->nprice}}" placeholder="0.00 {{Content::currency()}}" type="text" class="form-control input-sm number_only"></td>
                      <td title="Make sure if you wish to remove this"><span class="btn btn-danger btn-xs RemoveHotelRate" data-id="{{$tour->id}}" data-type="tourPaxPrice"><i class="fa fa-minus-circle" disabled="disabled"></i> Remove</span></td>
                    </tr>   
                  @endforeach
                @endif            
              </tbody>
            </table>  
            @if( $tour->pricetour == null )
              <div class="form-group">
                <div class="col-sm-2">
                  <div class="pull-left">
                    <input type="submit" name="btnUpdate" class="btn btn-success btn-flat btn-sm" value="Confirm">
                  </div>
                  <div class="pull-left" style="padding-left: 12px;">
                    <a href="{{route('tourList')}}" class="btn btn-danger btn-flat">Cancel</a>
                  </div>
                </div>
              </div>      
              <div id="LoadingRow" style="display:none; position: absolute; margin:0% 30% 41% 46%;">
                <center><span style="font-size: 38px;" id="placeholder" class="fa fa fa-spinner fa-spin"></span></center>
              </div>
            @endif
          </form>
        </section>
      </div>
    </section>
  </div>  
<div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">    
    <form id="form_submitTourType">
      <div class="modal-content">        
        <div class="modal-header" style="padding: 5px 13px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add New Tour Type</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
          <input type="hidden" name="eid" id="eid">
          <div class="row">
            <div class="col-md-4 col-xs-6">
              <div class="form-group">
                <label>Title<span style="color:#b12f1f;">*</span></label> 
                <input type="text" placeholder="Title" class="form-control" name="title" id="title" required autofocus>
              </div> 
            </div>       
            <div class="col-md-3 col-xs-6">
              <div class="form-group">
                <label>Business IOS</label> 
                <input type="text" placeholder="IOS" class="form-control" name="bus_ios" id="bus_ios">
              </div> 
            </div>        
            <div class="col-md-2 col-xs-6">
              <div class="form-group">
                <div><label>Website</label></div>
                <label style="font-weight: 400;"><input type="radio" name="web" value="1" checked="">Yes</label>&nbsp;&nbsp;
                <label style="font-weight: 400;"><input type="radio" name="web" value="0">No</label>
              </div> 
            </div>
            <div class="col-md-3 col-xs-6">
              <div class="form-group">
                <div><label>Status</label></div>
                <label style="font-weight: 400;"><input type="radio" name="status" value="1" checked="">Publish</label>&nbsp;&nbsp;
                <label style="font-weight: 400;"><input type="radio" name="status" value="0">UnPublish</label>
              </div> 
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Keyword www.google.com (SEO)</label>
                <textarea class="form-control" rows="3" placeholder="Keyword (SEO)" name="meta_keyword" id="meta_keyword"></textarea>
              </div>
            </div>
          </div>
           <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Description </label>
                <textarea class="form-control" rows="5" placeholder="Description (SEO)" name="meta_desc" id="meta_desc"></textarea>
              </div>
            </div>
          </div>         
        </div>
        <div class="modal-footer" style="padding: 5px 13px;">
          <button type="submit" class="btn btn-success btn-flat btn-sm" >Publish</button>
          <a href="#" class="btn btn-danger btn-sm" data-dismiss="modal">Cancel</a>
        </div>
      </div>      
    </form>
  </div>
</div>
@endsection
