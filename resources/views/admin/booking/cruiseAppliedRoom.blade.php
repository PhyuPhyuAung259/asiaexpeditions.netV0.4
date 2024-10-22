@extends('layout.backend')
@section('title', 'Room Applied Cruise Booking')
<?php $active = 'booked/project';
$subactive ='booked/cruise';
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
          <div class="col-lg-12"><h3 class="border"><span style="color: #f39c12; font-size: 22px;" class="fa fa-ship"></span> <b>{{$rcProgram->program_name}}</b> of <strong>{{$cruise->supplier_name}}</strong> River Cruise</h3></div>
          <form method="POST" action="{{route('crbgAppliedroom')}}">
            {{csrf_field()}}
            <input type="hidden" name="bookId" value="{{$project->id}}">
            <input type="hidden" name="projectNo" value="{{$project->book_project}}">
            <input type="hidden" name="cruiseId" value="{{$cruise->id}}">
            <input type="hidden" name="program_id" value="{{$rcProgram->id}}">
            <input type="hidden" name="option" value="{{$project->book_option}}">
            <input type="hidden" name="book_day" id="book_day" value="{{$project->book_day}}">
            <input type="hidden" name="book_checkin" id="book_checkin" value="{{$project->book_checkin}}">
            <input type="hidden" name="book_checkout" id="book_checkout" value="{{$project->book_checkout}}">
            <section class="col-lg-12">
              <table class="table" style="margin-bottom: 0px !important;">
                <thead>
                  <tr>
                    <th style="width:15%;">Room Type</th>
                    <th style="width:13%;">Room Category</th>
                    <th style="width:11%;">No. of Room</th>
                    <th class="text-center">Selling Price</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Net Price</th>
                    <th class="text-center">Amount</th>
                  </tr>
                </thead>
                <tbody>          
                @foreach($rcProgram->crCabin as $cabin)    
                <?php 
                $rcprice = App\CrPrice::where(['supplier_id'=> $cruise->id, 'cabin_id'=>$cabin->pivot->cr_cabin_id, 'program_id'=> $cabin->pivot->cr_program_id ])
                    ->whereDate('start_date','<=', $project->book_checkin)
                    ->whereDate('end_date','>=', $project->book_checkout)->first();
                // ->whereBetween('start_date', [$project->book_checkin, $project->book_checkout])->first();
                ?>
                  <tr>
                    <td class="container_room" style="padding-right:10px; vertical-align: middle;">
                        <label class="container-CheckBox" style="margin-bottom: 0px;">{{$cabin->name}}
                            <input type="checkbox" id="checkRoom" class="checkRoom" name="roomtype[]" value="{{$cabin->id}}"> <span class="checkmark hidden-print"></span>
                        </label>
                    </td>
                    <td colspan="6">
                      <table class="table" style="margin-bottom: 0px;">
                        @foreach(App\RoomCategory::whereIn('id',['1','2','3','4','5'])->get() as $key => $cat)
                          <?php
                            // if ($cat->id == 1) {
                            //   $selling_price = $rcprice['ssingle_price'];
                            // }elseif ($cat->id == 2) {
                            //   $selling_price = $rcprice['stwn_price'];
                            // }elseif($cat->id == 3){
                            //   $selling_price = $rcprice['sdbl_price'];
                            // }elseif($cat->id == 4){
                            //   $selling_price = $rcprice['sextra_price'];
                            // }else {
                            //   $selling_price = $rcprice['schextra_price'];
                            // }

                            // if ($cat->id == 1) {
                            //   $net_price = $rcprice['nsingle_price'];
                            // }elseif ($cat->id == 2) {
                            //   $net_price = $rcprice['ntwn_price'];
                            // }elseif($cat->id == 3){
                            //   $net_price = $rcprice['ndbl_price'];
                            // }elseif($cat->id == 4){
                            //   $net_price = $rcprice['nextra_price'];
                            // }else {
                            //   $net_price = $rcprice['nchextra_price'];
                            // }  
                            
                             if (!isset($rcprice)) {
                              $selling_price=0;
                              $net_price=0;
                            }
                            elseif ($cat->id == 1) {
                              $selling_price = $rcprice['ssingle_price'];
                              $net_price = $rcprice['nsingle_price'];
                            }elseif ($cat->id == 2) {
                              $selling_price = $rcprice['stwn_price'];
                              $net_price = $rcprice['ntwn_price'];
                            }elseif($cat->id == 3){
                              $selling_price = $rcprice['sdbl_price'];
                              $net_price = $rcprice['ndbl_price'];
                            }elseif($cat->id == 4){
                              $selling_price = $rcprice['sextra_price'];
                              $net_price = $rcprice['nextra_price'];
                            }else {
                              $selling_price = $rcprice['schextra_price'];
                              $net_price = $rcprice['nchextra_price'];
                            }

                          ?>
                          <tr>
                            <td class="container_category" style="width: 15%;">                              
                               <label class="container-CheckBox" style="margin-bottom: 0px;">{{$cat->name}}
                                  <input type="checkbox" id="roomCat" class="checkRoom" name="roomCat{{$cabin->id}}[]" value="{{$cat->id}}" data-selling="{{$selling_price}}"  data-net="{{$net_price}}">
                                  <span class="checkmark hidden-print"></span>
                              </label>
                            </td>
                            <td style="width:13%;" class="container_room_no">
                              <select class="form-control input-sm " id="no_of_room" name="cabin_pax{{$cabin->id}}_{{$cat->id}}">
                                <option value="">0</option>
                                @for($n=1; $n<= 29; $n++)
                                <option value="{{$n}}">{{$n}}</option>
                                @endfor
                              </select>
                            </td>
                            <td style="width:24%;" class="text-right container_selling_price">
                              <input type="hidden" name="price_{{$cabin->id}}_{{$cat->id}}" id="room_price">
                              <span>00.0</span>
                            </td>
                            <td style="width:16%;" class="text-right container_selling_amount">
                              <input type="hidden" name="sell_amount_{{$cabin->id}}_{{$cat->id}}" id="selling_amount" value="00.0">
                              <span>00.0</span>
                            </td>
                            <td style="width:18%;" class="text-right container_net_price">
                              <input type="hidden" name="nprice_{{$cabin->id}}_{{$cat->id}}" id="room_net_price">
                              <span>00.0</span>
                            </td>
                            <td style="width:21%;" class="text-right container_net_amount">
                              <input type="hidden" name="net_amount_{{$cabin->id}}_{{$cat->id}}" id="net_amount" value="00.0">
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
              <div class="col-md-6 col-xs-6 col-md-offset-2" style="padding-left: 33px;">
                <textarea class="form-control" rows="5" name="remark" cols="5" placeholder="Enter remark...!"></textarea>
              </div>
              <div class="col-md-4 col-xs-6">
                <input type="submit" class="btn btn-success btn-flat btn-sm" id="confirm-booking" disabled="true" value="Confirm Booking">
              </div>
              <div class="clearfix"></div>
              <br>
              <!-- <hr style="border-bottom: 1px solid #f4f4f5;"> -->
               <div class="col-md-12">
                <h4>Cruise Booking Result for project: <strong>{{$project->book_project}}</strong></h4>
                <table class="table">
                  <tr>
                      <th>Cruise Name</th>
                      <th>CheckIn - CheckOut</th>
                      <th>Cruise Program</th>
                      <th>Room</th>
                      @foreach(App\RoomCategory::whereIn('id',['1','2','3','4','5'])->get() as $cat)
                      <th class="text-center">{{$cat->name}}</th>
                      @endforeach
                      <th class="text-right">Status</th>
                  </tr>
                  <tbody>
                  <?php 
                  $crbooked = App\CruiseBooked::where(['cruise_id'=>$cruise->id, 'book_id'=>$project->id])->orderBy('room_id', 'ASC'); 
                  ?>
                    @foreach($crbooked->get() as $cr)
                    <tr>
                      <td>{{{$cr->cruise->supplier_name or ''}}}</td>
                      <td style="font-size: 12px;">{{Content::dateformat($cr->checkin)}} -> {{Content::dateformat($cr->checkout)}}</td>
                      <td>{{{$cr->program->program_name or ''}}}</td>
                      <td>{{{$cr->room->name or ''}}}</td>
                      <?php $status = "<label class='icon-list ic_status'></label>"; ?>
                      <td class="text-center">{!! $cr->ssingle  != 0 ? $status : "" !!}</td>
                      <td class="text-center">{!! $cr->stwin    != 0 ? $status : "" !!}</td>
                      <td class="text-center">{!! $cr->sdouble  !=0 ? $status  : "" !!}</td>
                      <td class="text-center">{!! $cr->sextra   != 0 ? $status : "" !!}</td>
                      <td class="text-center">{!! $cr->schextra != 0 ? $status : "" !!}</td>
                      <td class="text-right">
                        <a target="_blank" href="{{route('hVoucher', ['project'=>$cr->project_number, 'bhotelid'=> $cr->id, 'bookid'=> $project->id, 'type'=>'cruise-voucher'])}}" title="Cruise Voucher">
                          <label class="icon-list ic_inclusion"></label>
                        </a>
                        &nbsp;
                         <a target="_blank" href="{{route('hVoucher', ['project'=>$cr->project_number, 'bhotelid'=> $cr->id, 'bookid'=> $project->id, 'type'=> 'cruise-booking-form'])}}" title="Cruise Booking Form">
                          <label class="icon-list ic_invoice_drop"></label>
                        </a>
                      </td>
                    </tr>
                    @endforeach
                    <tr>
                      <td colspan="10" class="text-right">
                        <strong class="totalsize">Cruise Total: {{number_format($crbooked->sum('sell_amount'),2)}} {{Content::currency()}}</strong>
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
@endsection
