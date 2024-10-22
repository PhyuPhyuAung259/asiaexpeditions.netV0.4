@extends('layout.backend')
@section('title', 'Room Apply Hotels')
<?php $active = 'supplier/hotels'; 
  $subactive ='hotel/room';
  use App\component\Content;
?>

@section('content')
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <div class="row">
        <section class="col-lg-12 connectedSortable">
          <input type="hidden" id="urlRate" value="{{route('getRatePrice')}}"> 
            <h4 class="border"><span style="color: #f39c12; font-size: 22px;" class="fa fa-hotel (alias)"></span> <b>{{{ $room->name or '' }}} Room For {{{ $hotel->supplier_name or ''}}} Hotel</b> <small>( currency USD Price )</small></h4>
            <form action="{{route('addRoomRate')}}" method="POST">
              <input type="hidden" name="hotelId" value="{{{ $hotel->id or ''}}}">
              <input type="hidden" name="roomId" value="{{{ $room->id or ''}}}">
              {{csrf_field()}}
              <table class="table table-hover table-striped" id="hotel-rate">
                <thead>
                  <tr>                     
                    <th title="Rate Price From Date to Date" colspan="2" class="text-center" style="width: 19%;">From <span class="fa  fa-long-arrow-right" style="top: 1px; position: relative;"></span> To</th>
                    @foreach(App\RoomCategory::where('status',1)->orderBy('id', 'ASC')->get() as $cat)
                    <th class="text-center" title="{{$cat->name}}" >{{$cat->category_iso}}</th>
                    @endforeach
                    <th width="80" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="2">                   
                      <div class="input-group">
                        <input type="text" name="fromdate[]" id="from_date" class="form-control input-sm" required="" placeholder="2018-04-25"><div class="input-group-addon">to</div>
                        <input type="text" name="todate[]" id="to_date" class="form-control input-sm" required="" placeholder="2018-06-25">
                      </div>
                    </td> 
                    @foreach(\App\RoomCategory::where('status',1)->orderBy('id', 'ASC')->get() as $cat)    
                    <td><input type="text" class="number_only form-control input-sm text-center" name="{{$cat->key_name}}[]" placeholder="0.00"></td>
                    @endforeach
                    <td><span class="btn btn-info btn-xs addHotelRate" data-type="hotelrate"><i class="fa fa-plus-circle"></i> Add new</span></td>
                  </tr>               
                </tbody>
              </table>  
              <div class="form-group">
                <div class="col-sm-2">
                  <div class="pull-left">
                    <input type="submit" name="btnUpdate" class="btn btn-success btn-flat btn-sm" value="Confirm">
                  </div>
                  <div class="pull-left" style="padding-left: 12px;">
                    <a href="{{route('getRoomApplied')}}" class="btn btn-danger btn-flat btn-sm"> Cancel</a>
                  </div>
                </div>
              </div>      
              <div id="LoadingRow" style="display:none; position: absolute; margin:0% 30% 41% 46%;">
                <center><span style="font-size: 38px;" id="placeholder" class="fa fa fa-spinner fa-spin"></span></center>
              </div>  
            </form>
        </section>
      </div>
    </section>
  </div>  
@include('admin.include.datepicker')
@endsection
