@extends('layout.backend')
@section('title', 'Edit Room Rate')
<?php 
  $active = 'supplier/hotels';
  $subactive ='hotel/hotelrate'; 
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
            <h4 class="border"><span style="color: #f39c12; font-size: 22px;" class="fa fa-hotel (alias)"></span> <b>{{{ $room->name or '' }}} Room For {{{ $hotel->supplier_name or ''}}} Hotel</b> <small>( currency USD Price )</small></h4>
              <input type="hidden" name="hotelId" id="hotelId" value="{{{ $hotel->id or ''}}}">
              <input type="hidden" name="roomId" id="roomId" value="{{{ $room->id or ''}}}">
              <input type="hidden" id="updateRoomRate" value="{{route('updateRoomRate')}}">
              {{csrf_field()}}
              <table class="table table-hover table-striped" id="hotel-rate">
                <thead>
                  <tr>                     
                    <th colspan="2" class="text-center" style="width: 20%;">From <span class="fa  fa-long-arrow-right" style="top: 1px; position: relative;"></span> To</th>
                    @foreach(App\RoomCategory::where('status',1)->orderBy('id', 'ASC')->get() as $cat)
                    <th class="text-center" title="{{$cat->name}}" >{{$cat->category_iso}}</th>
                    @endforeach
                    <th width="100" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($edithotelRate as $key=> $rate)
                  <tr>
                    <td colspan="2">    
                      <div class="input-group">
                          <input type="text" name="fromdate[]" class="form-control input-sm from_date" id="from_date_{{$key}}" value="{{$rate->start_date}}" readonly>
                          <div class="input-group-addon">to</div>
                          <input type="text" name="todate[]" class="form-control input-sm to_date" id="to_date_{{$key}}" value="{{$rate->end_date}}" readonly>
                      </div>
                    </td>
                    <td><input type="text" class="number_only form-control input-sm text-center ssingle" name="ssingle[]" value="{{$rate->ssingle}}"></td>
                    <td><input type="text" class="number_only form-control input-sm text-center stwin" name="stwin[]" value="{{$rate->stwin}}"></td> 
                    <td><input type="text" class="number_only form-control input-sm text-center sdouble" name="sdouble[]" value="{{$rate->sdbl_price}}"></td> 
                    <td><input type="text" class="number_only form-control input-sm text-center sextra" name="sextra[]" value="{{$rate->sextra}}"></td>
                    <td><input type="text" class="number_only form-control input-sm text-center schextra" name="schextra[]" value="{{$rate->schexbed}}"></td>
                    <td><input type="text" class="number_only form-control input-sm text-center nsingle" name="nsingle[]" value="{{$rate->nsingle}}"></td>
                    <td><input type="text" class="number_only form-control input-sm text-center ntwin" name="ntwin[]" value="{{$rate->ntwin}}"></td>
                    <td><input type="text" class="number_only form-control input-sm text-center ndouble" name="ndouble[]" value="{{$rate->ndbl_price}}"></td> 
                    <td><input type="text" class="number_only form-control input-sm text-center nextra" name="nextra[]" value="{{$rate->nextra}}"></td>
                    <td><input type="text" class="number_only form-control input-sm text-center nchextra" name="nchextra[]" value="{{$rate->nchexbed}}"></td>          
                    <td>
                      <span class="btn btn-success btn-xs BtnHotelRate" data-id="{{$rate->id}}">Save</span>
                      &nbsp;&nbsp;
                      <span title="Make sure if you wish to remove this" class="btn btn-danger btn-xs RemoveHotelRate" data-id="{{$rate->id}}" data-type="roomRate"><i class="fa fa-minus-circle" disabled="disabled"></i> </span>
                    </td>
                  </tr>
                  <script type="text/javascript">
                      $(function(){
                          var nowTemp = new Date();
                          var formatdate = "yyyy-mm-dd";
                          var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
                          var checkin = $("#from_date_{{$key}}").datepicker({
                              format: formatdate,
                              onRender: function(date) {
                                  return date.valueOf() < now.valueOf() ? "" : "";
                              }
                          }).on("changeDate", function(ev) {
                            if (ev.date.valueOf() > checkout.date.valueOf()) {
                              var newDate = new Date(ev.date)
                              newDate.setDate(newDate.getDate() + 1);
                              checkout.setValue(newDate);
                            }
                            checkin.hide();
                            $("#to_date_{{$key}}")[0].focus();
                          }).data("datepicker");
                          var checkout = $("#to_date_{{$key}}").datepicker({
                              format: formatdate,
                              onRender: function(date) {
                                  return date.valueOf() < checkin.date.valueOf() ? "disabled" : "";
                              }
                          }).on("changeDate", function(ev) {
                            checkout.hide();
                          }).data("datepicker");
                      });
                  </script>
                  @endforeach                    
                </tbody>
              </table>            
        </section>
      </div>
    </section>
  </div>  
</div>

@include('admin.include.datepicker')
@endsection


