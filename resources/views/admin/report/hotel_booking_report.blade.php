
@extends('layout.backend')
@section('title', 'Hotel booking report')
<?php 
$total_no_of_booked_night=0;
$total_price=0;
?>
@section('content')
	<div class="col-lg-12">
    <table style="margin-top:10px;">
     
        @if(!empty($hotel))
          <?php  $hotel_info= DB::table('suppliers')->where('id', $hotelid)->first(); ?>
          <tr>
            <tr>
              <td>Hotel Name &nbsp;&nbsp; : &nbsp;&nbsp; {{$hotel_info->supplier_name}}</td>
            </tr>
            <tr>
              <td>Contact Name &nbsp;&nbsp;:&nbsp;&nbsp; {{{$hotel_info->supplier_contact_name or ''}}}</td>
            </tr>
            <tr>
              <td>Phone &nbsp;&nbsp;:&nbsp;&nbsp; {{{$hotel_info->supplier_phone or ''}}}</td>
            </tr>
            <tr>
              <td>Email &nbsp;&nbsp;:&nbsp;&nbsp; {{{$hotel_info->supplier_email or ''}}}</td>
            </tr>
            <tr>
              <td>Address &nbsp;&nbsp;:&nbsp;&nbsp; {{{$hotel_info->supplier_address or ''}}}</td>
            </tr> 
            <tr>
              <td>Website &nbsp;&nbsp;:&nbsp;&nbsp; {{{$hotel_info->supplier_website or ''}}}</td>
            </tr>
          </tr>
        @endif
    </table>
    <div class="pull-right hidden-print">
      <a href="javascript:void(0)" onclick="window.print();"><span class="fa fa-print btn btn-primary"> Print</span></a>&nbsp;&nbsp;&nbsp;&nbsp;
		</div>

		<div class="text-center mt-5 "> <span class="pull-left"> <h4>From {{$startDate or ''}} : To {{$endDate or ''}} </h4></span> <h3> <strong class="btborder " style="text-transform: uppercase;"> {{$hotel_info->supplier_name}} Booking Report</strong></h3></div>
    <table id="firstDt" class=" datatable table-hover table-striped table-bordered dt-responsive" cellspacing="0" width="100%" >
      <thead>
        <tr>
          <th rowspan="2">Client Infomation</th>
          <th rowspan="2">Agent Name</th>
          <th colspan="11">Booking Details</th>
        </tr>
        <tr>
          <th>Check In</th>
          <th>Check Out</th>
          <th>Room</th>
          <th>No of Room</th>
          <th>Booked Night</th>
          <th>Net single</th>
          <th>Net Double</th>
          <th>Net Twin</th>
          <th>Net Extra</th>
          <th>Net Chextra</th>
          <th>Net Amount</th>
        </tr>
      </thead>
      <tbody>
        @if(!empty($hotel))
        @foreach($hotel as $hotels)
        <?php $hotelbooked= DB::table("hotelb")->where(['book_id'=>$hotels->id,'hotel_id'=>$hotels->hotel_id])->get();
     
        ?>
        @foreach($hotelbooked as $hotelb)
          @if(!empty($hotelb->project_number))
            <?php $client_info= DB::table("project")->where('project_number', $hotelb->project_number)->first(); ?>
                <tr>
                  <td>
                    Project Number &nbsp;&nbsp; : &nbsp;&nbsp; {{{$client_info->project_fileno or ''}}} <br>
                    Client Name &nbsp;&nbsp; : &nbsp;&nbsp; {{{$client_info->project_client or ''}}} <br>
                  </td>
                  <td><?php $agent=DB::table("suppliers")
                                  ->where('id',$client_info->supplier_id)
                                  ->first();?>
                                  {{{$agent->supplier_name or ''}}}</td>
                  <td>{{{$hotelb->checkin or ''}}}</td>
                  <td>{{{$hotelb->checkout or ''}}}</td>
                  <td><?php $room= DB::table("room")->where('id', $hotelb->room_id)->first(); ?>{{{ $room->name or ''}}}</td>
                  <td>{{{$hotelb->no_of_room or ''}}}</td>
                  <td>{{{$hotelb->book_day or ''}}}</td>
                  <td>{{{$hotelb->nsingle or ''}}}</td>
                  <td>{{{$hotelb->ndouble or ''}}}</td>
                  <td>{{{$hotelb->ntwin or ''}}}</td>
                  <td>{{{$hotelb->nextra or ''}}}</td>
                  <td>{{{$hotelb->nchextra or ''}}}</td>
                  <td>{{{$hotelb->net_amount or ''}}}</td>
                  <?php 
                    $total_no_of_booked_night=$total_no_of_booked_night+ ($hotelb->book_day * $hotelb->no_of_room) ;
                    $total_price=$total_price + $hotelb->net_amount;
                  ?>
                  
                </tr>
            @endif
        @endforeach
       
          @endforeach
        @endif
        <tr>
            <td colspan="6" class="text-right"> <strong> Total number of booked night : </strong></td> 
            <td><strong>{{$total_no_of_booked_night}}</strong></td>
            <td colspan="5"  class="text-right"><strong>Total Amount :  </strong></td> 
            <td><strong>{{$total_price}}</strong></td>
        </tr>
      </tbody>
     
    </table>
   



  </div>
  	@include('admin.include.datepicker')
@endsection
