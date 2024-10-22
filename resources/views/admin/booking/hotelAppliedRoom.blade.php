@extends('layout.backend')
@section('title', 'Room Applied Hotel Booking')
<?php $active = 'booked/project';
$subactive ='booked/hotel';
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
          <div class="col-lg-12"><h3 class="border"><small>Room Applied for</small> <strong>{{$hotel->supplier_name}}</strong> <small>Hotel</small>, <strong style="font-size: 14px;">CheckIn:{{Content::dateformat($project->book_checkin)}}, CheckOut:{{Content::dateformat($project->book_checkout)}}</strong></h3></div>
          <form method="POST" action="{{route('bookingAppliedroom')}}">
            {{csrf_field()}}
            <input type="hidden" name="bookId" value="{{$project->id}}">
            <input type="hidden" name="projectNo" value="{{$project->book_project}}">
            <input type="hidden" name="hotelId" value="{{$hotel->id}}">
            <input type="hidden" name="option" value="{{$project->book_option}}">
            <input type="hidden" name="book_option" value="{{$project->book_quot_hotel_option}}">
            <input type="hidden" name="book_day" id="book_day" value="{{$project->book_day}}">
            <input type="hidden" name="book_checkin" id="book_checkin" value="{{$project->book_checkin}}">
            <input type="hidden" name="book_checkout" id="book_checkout" value="{{$project->book_checkout}}">
            <section class="col-lg-12">
              <table class="table" style="margin-bottom: 0px !important;">
                <thead>
                  <tr>
                    <th style="width:11%;">Room Type</th>
                    <th style="width: 13%;">Room Category</th>
                    <th style="width: 11%;">No. of Room</th>
                    <th class="text-center">Selling Price</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Net Price</th>
                    <th class="text-center">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($hotel->room as $key => $room)
                 
                    <?php 
                    // dd($room);
                      $rate = App\RoomRate::where(['supplier_id'=>$hotel->id, 'room_id'=>$room->id])
                          // ->whereBetween('start_date',[$project->book_checkin, $project->book_checkout])
                          ->whereDate('start_date','<=', $project->book_checkin)
                           ->whereDate('end_date','>=', $project->book_checkout)
                          ->first();
                         // dd($project->book_checkin,$rate);
                    // dd($rate,$project->book_checkin,$room->id,$hotel->id);
                    ?>
                    <tr>
                      <td class="container_room" style="padding-right:10px; vertical-align: middle;">
                        <label class="container-CheckBox" style="margin-bottom: 0px;">{{$room->name}}
                          <input type="checkbox" id="checkRoom" class="checkRoom" name="roomtype[]" value="{{$room->id}}" > 
                          <span class="checkmark hidden-print" ></span>
                        </label>
                      </td>
                      <td colspan="6">
                        <table class="table" style="margin-bottom: 0px;">
                          @foreach(App\RoomCategory::whereIn('id',[1,2,3,4,5])->get() as $key => $cat)
                             <?php
                            // dd($rate);
                            // $selling_price  = 0;
                            // $net_price = 0;
                            //$rate=[];
                            // dd($cat);
                             if (!isset($rate)) {
                                  $selling_price=0;
                                  $net_price=0;
                                }
                              elseif ($cat->id == 1) {
                               
                                $selling_price=$rate->ssingle;
                                $net_price=$rate->nsingle;
                              }elseif ($cat->id == 2) {
                                $selling_price = $rate->stwin;# != null ? $rate['stwin']: 0;
                                $net_price = $rate->ntwin;# != null ? $rate['ntwin'] : 0;
                              }elseif($cat->id == 3){
                                $selling_price = $rate->sdbl_price;# != null ? $rate->sdbl_price: 0;
                                $net_price = $rate->ndbl_price; # != null ? $rate->ndbl_price: 0;
                              }elseif($cat->id == 4){
                                $selling_price = $rate->sextra; # != null ? $rate['sextra']: 0;
                                $net_price = $rate->nextra;# != null ? $rate['nextra']: 0;
                              }else {
                                $selling_price = $rate->schexbed;# != null ? $rate['schexbed']: 0;
                                $net_price = $rate->nchexbed;# != null ? $rate['nchexbed']: 0;
                              }

                            ?>
                            <tr>
                              <td class="container_category" style="width: 13%;">
                                <label class="container-CheckBox" style="margin-bottom: 0px;">{{$cat->name}}
                                  <input type="checkbox" id="roomCat" class="checkRoom" name="roomCat{{$room->id}}[]" value="{{$cat->id}}" data-selling="{{$selling_price}}" data-net="{{$net_price}}">
                                  <span class="checkmark hidden-print" ></span>
                                </label>
                            
                              </td>
                              <td style="width:13%;" class="container_room_no">
                                <select class="form-control input-sm " id="no_of_room" name="no_of_room{{$room->id}}_{{$cat->id}}" >
                                  <option value="">0</option>
                                  @for($n=1; $n <= 29; $n++)
                                  <option value="{{$n}}">{{$n}}</option>
                                  @endfor
                                </select>
                              </td>
                              <td style="width:24%;" class="text-right container_selling_price">
                                <input type="hidden" name="price_{{$room->id}}_{{$cat->id}}" id="room_price">
                                <span>00.0</span>
                              </td>
                              <td style="width:16%;" class="text-right container_selling_amount">
                                <input type="hidden" name="sell_amount_{{$room->id}}_{{$cat->id}}" id="selling_amount" value="00.0">
                                <span>00.0</span>
                              </td>
                              <td style="width:18%;" class="text-right container_net_price">
                                <input type="hidden" name="nprice_{{$room->id}}_{{$cat->id}}" id="room_net_price">
                                <span>00.0</span>
                              </td>
                              <td style="width:21%;" class="text-right container_net_amount">
                                <input type="hidden" name="net_amount_{{$room->id}}_{{$cat->id}}" id="net_amount" value="00.0">
                                <span>00.0</span>
                              </td>
                            </tr>
                          @endforeach 
                        </table>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <hr>
              <div class="col-md-6 col-xs-6 col-md-offset-1" style="padding-left: 33px;">
                <textarea class="form-control" rows="5" name="remark" cols="5" placeholder="Enter remark...!"></textarea>
              </div>
              <div class="col-md-5 col-xs-6">
                <input type="submit" class="btn btn-success btn-flat btn-sm" id="confirm-booking" disabled="true" value="Confirm Booking">
              </div>
              <div class="clearfix"></div>
              <br>
              <!-- <hr style="border-bottom: 1px solid #f4f4f5;"> -->
              <div class="col-md-12">
                <h4>Hotel Booking Result for project: <strong>{{$project->book_project}}</strong></h4>
                <table class="table">
                  <tr>
                      <th>Hotel</th>
                      <th>CheckIn - CheckOut</th>
                      <th>Room</th>
                      @foreach(App\RoomCategory::whereIn('id',['1','2','3','4','5'])->get() as $cat)
                      <th class="text-center">{{$cat->name}}</th>
                      @endforeach
                      <th class="text-right">Status</th>
                  </tr>
                  <tbody>
                  <?php 
                  $hbooked = App\HotelBooked::where(['hotel_id'=>$hotel->id, 'book_id'=>$project->id])->orderBy('room_id', 'ASC'); 
                  ?>
                    @foreach($hbooked->get() as $bhotel)
                    <tr>
                      <td>{{{$bhotel->hotel->supplier_name or ''}}}</td>
                      <td style="font-size: 12px;">{{Content::dateformat($bhotel->checkin)}} -> {{Content::dateformat($bhotel->checkout)}}</td>
                      <td>{{{$bhotel->room->name or ''}}}</td>
                      <?php $status = "<label class='icon-list ic_status'></label>"; ?>
                      <td class="text-center">{!! $bhotel->ssingle != 0 ? "$status":"" !!}</td>
                      <td class="text-center">{!! $bhotel->stwin != 0 ? "$status":"" !!}</td>
                      <td class="text-center">{!! $bhotel->sdouble != 0 ? "$status":"" !!}</td>
                      <td class="text-center">{!! $bhotel->sextra != 0 ? "$status":"" !!}</td>
                      <td class="text-center">{!! $bhotel->schextra != 0 ? "$status":"" !!}</td>
                      <td class="text-right">
                        <a href="#" data-id="{{$bhotel->id}}" data-remark="{{$bhotel->remark}}" class="btn btn-primary btn-xs BtnEdit" data-toggle="modal" data-target="#myModal">Add Remark</a>
                        <!--<a target="_blank" href="{{route('hVoucher', ['project'=>$bhotel->project_number, 'bhotelid'=> $bhotel->id, 'bookid'=> $project->id, 'type'=>'hotel-voucher'])}}" title="Hotel Voucher">-->
                        <!--  <label class="icon-list ic_inclusion" style="margin: -4px 0px;"></label>-->
                        <!--</a>-->
                        <!--&nbsp;-->
                        <!-- <a target="_blank" href="{{route('hVoucher', ['project'=>$bhotel->project_number, 'bhotelid'=> $bhotel->id, 'bookid'=> $project->id, 'type'=> 'hotel-booking-form'])}}" title="Hotel Booking Form">-->
                        <!--  <label  class="icon-list ic_invoice_drop" style="margin: -4px 0px;"></label>-->
                        <!--</a>    &nbsp;-->
                        
                        <a target="_blank" href="{{route('hVoucher', ['project'=>$bhotel->project_number, 'bhotelid'=> $bhotel->id, 'bookid'=> $project->id, 'type'=>'hotel-voucher' ,'checkin'=> $bhotel->checkin, 'checkout'=> $bhotel->checkout])}}" title="Hotel Voucher">
                        <label class="icon-list ic_inclusion" style="margin: -4px 0px;"></label>
                        </a>
                        &nbsp;
                        <a target="_blank" href="{{route('hVoucher', ['project'=>$bhotel->project_number, 'bhotelid'=> $bhotel->id, 'bookid'=> $project->id, 'type'=> 'hotel-booking-form','checkin'=> $bhotel->checkin, 'checkout'=> $bhotel->checkout])}}" title="Hotel Booking Form">
                         <label  class="icon-list ic_invoice_drop" style="margin: -4px 0px;"></label>
                        </a>    &nbsp;
                        <a href="javascript:void(0)" class="RemoveHotelRate" data-type="book_hotelrate" data-id="{{$bhotel->id}}" title="Delete this Hotel Rate">
                            <label class="icon-list ic_remove" style="margin: -4px 0px;"></label>
                          </a>   
                      </td>
                    </tr>
                    @endforeach
                    <tr>
                      <td colspan="9" class="text-right">
                        <strong class="totalsize">Hotel Total: {{number_format($hbooked->sum('sell_amount'),2)}} {{Content::currency()}}</strong>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <br><br>
            </section>
          </form>
        </div>
      </section>
    </div>  
  </div>


<div class="modal" id="myModal" role="dialog" data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-md">
    <form method="POST" action="{{route('addHotelRemark')}}">
      <div class="modal-content">       
        <input type="hidden" name="bhotelId" id="seviceid"> 
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add Remark</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}} 
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Remark</label>
                <textarea class="form-control" rows="4" id="Reremark" name="remark" placeholder="Remark here..."></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer" style="text-align: center;">
              <button type="submit" class="btn btn-success btn-flat btn-sm">Save</button>
              <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Close</a>
          </div>
        </div>     
      </div>   
    </form>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $(".BtnEdit").on('click', function(){
      $("#seviceid").val($(this).data('id'));
      $("#Reremark").val($(this).data('remark'))
    })
  })
</script>
@endsection
