@extends('layout.backend')
@section('title', 'Edit Cabin')
<?php $active = 'supplier/cruises'; 
$subactive = 'cruise/applied/cabin';

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
            <h4 class="border"><span style="color: #f39c12; font-size: 22px;" class="fa fa-ship"></span> Apply price for <b>{{{ $crcabin->name or '' }}}</b> Cabin of   &nbsp;<b>{{ $crpro->program_name or ''}} River Cruise</b> <small>( currency USD Price )</small></h4>
            <form action="{{route('updateCabinprice')}}" method="POST">
              {{csrf_field()}}
              <table class="table table-hover table-striped" id="hotel-rate">
                <thead>
                  <tr>                     
                    <th colspan="2" class="text-center" style="width: 20%;">From <span class="fa  fa-long-arrow-right" style="top: 1px; position: relative;"></span> To</th>
                    @foreach(App\RoomCategory::where('status',1)->orderBy('id', 'ASC')->get() as $cat)
                    <th class="text-center" title="{{$cat->name}}" >{{$cat->category_iso}}</th>
                    @endforeach
                    <th width="80" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($getCrprice as $key=> $rate)
                  <tr>
                    <td colspan="2">    
                      <input type="hidden" name="eid[]" value="{{$rate->id}}">
                      <div class="input-group">
                          <input type="text" name="fromdate[]" class="form-control input-sm" id="from_date_{{$key}}" value="{{$rate->start_date}}" placeholder="2018-04-08" readonly>
                          <div class="input-group-addon">to</div>
                          <input type="text" name="todate[]" class="form-control input-sm" id="to_date_{{$key}}" value="{{$rate->end_date}}" placeholder="2018-05-08" readonly>
                      </div>
                    </td>
                    <td><input type="text" class="number_only form-control input-sm text-center" name="ssingle[]" value="{{$rate->ssingle_price}}"></td>
                    <td><input type="text" class="number_only form-control input-sm text-center" name="stwin[]" value="{{$rate->stwn_price}}"></td> 
                    <td><input type="text" class="number_only form-control input-sm text-center" name="sdouble[]" value="{{$rate->sdbl_price}}"></td> 
                    <td><input type="text" class="number_only form-control input-sm text-center" name="sextra[]" value="{{$rate->sextra_price}}"></td>
                    <td><input type="text" class="number_only form-control input-sm text-center" name="schextra[]" value="{{$rate->schextra_price}}"></td>
                    <td><input type="text" class="number_only form-control input-sm text-center" name="nsingle[]" value="{{$rate->nsingle_price}}"></td>
                    <td><input type="text" class="number_only form-control input-sm text-center" name="ntwin[]" value="{{$rate->ntwn_price}}"></td>
                    <td><input type="text" class="number_only form-control input-sm text-center" name="ndouble[]" value="{{$rate->ndbl_price}}"></td>
                    <td><input type="text" class="number_only form-control input-sm text-center" name="nextra[]" value="{{$rate->nextra_price}}"></td>
                    <td><input type="text" class="number_only form-control input-sm text-center" name="nchextra[]" value="{{$rate->nchextra_price}}"></td>                  
                    <td title="Make sure if you wish to remove this"><span class="btn btn-danger btn-xs RemoveHotelRate" data-id="{{$rate->id}}" data-type="cruise-cabin-price"><i class="fa fa-minus-circle" disabled="disabled"></i> Remove</span></td>
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
              @if($getCrprice->count() > 0)
              <div class="form-group">
                <div class="col-sm-2">
                  <div class="pull-left">
                    <input type="submit" name="btnUpdate" class="btn btn-success btn-flat btn-sm" value="Update">
                  </div>
                  <div class="pull-left" style="padding-left: 12px;">
                    <a href="{{route('getCabin')}}" class="btn btn-danger btn-flat btn-sm">Back</a>
                  </div>
                </div>
              </div>    
              @endif     
            </form>    
        </section>
      </div>
    </section>
  </div>  
</div>

@include('admin.include.datepicker')
@endsection
