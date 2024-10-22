<?php 

use App\component\Content;

 ?>

<h4><strong style="text-transform: capitalize;">Program Summary</strong></h4>

<?php 

$tourBook = \App\Booking::tourBook($book->book_project); ?>

@if($tourBook->count() > 0)

	<div><strong style="text-transform: uppercase;">EXCURSION OVERVIEW</strong></div>

	<table class="table" id="roomrate">

		<tr style="background-color:#f4f4f4;">

			<th width="100px">Date</th>

			<th>City</th>

			<th>Title</th>

			<th class="text-center">Pax</th>

			<th class="text-right">Price</th>

			<th class="text-right">Amount</th>

		</tr>			

		@foreach($tourBook->get() as $tour)

		<?php 

		$pro = \App\Province::find($tour->province_id); ?>

		<tr>

			<td>{{Content::dateformat($tour->book_checkin)}}</td>

			<td>{{{ $pro->province_name or ''}}}</td>

			<td>{{$tour->tour_name}}</td>

			<td class="text-center">{{$tour->book_pax}}</td>

			<td class="text-right">{{Content::money($tour->book_price)}}</td>

			<td class="text-right">{{$tour->book_amount}}</td>

		</tr>

		@endforeach

		<tr>

			<td colspan="6" class="text-right"><h5><strong>Tour Sub Total: {{$tourBook->sum('book_amount')}}{{Content::currency()}} </strong></h5></td>

		</tr>

	</table>

@endif

<?php 

$hotelBook = \App\HotelBooked::where('project_number', $book->book_project);

?>

@if($hotelBook)

	<div><strong style="text-transform: uppercase;">HOTEL OVERVIEW</strong></div>

	<table class="table" id="roomrate">

		<tr style="background-color:#f4f4f4;">

			<th>Hotel</th>

			<th>Checkin - Checkout</th>

			<th>Room</th>

			<th class="text-center">Nights</th>

			@foreach(App\RoomCategory::whereIn('id',[1,2,3,4,5])->orderBy('id', 'ASC')->get() as $cat)

            <th class="text-right">{{$cat->name}}</th>

            @endforeach

		</tr>

		@foreach($hotelBook->get() as $hotel)				

		<tr>

			<td>{{{ $hotel->hotel->supplier_name or ''}}}</td>

			<td>{{{ $hotel->book->book_checkin or ''}}} - {{{ $hotel->book->book_checkout or ''}}}</td>

			<td>{{{ $hotel->room->name or ''}}}</td>

			<td class="text-center">{{ $hotel->book_day}}</td>

			{!! $hotel->ssingle > 0 ? "<td class="text-right">".Content::money($hotel->ssingle)."</td>" : '' !!} 

			{!! $hotel->stwin > 0 ? "<td class="text-right">".Content::money($hotel->stwin)."</td>" : '' !!}

			{!! $hotel->sdouble > 0 ? "<td class="text-right">".Content::money($hotel->sdouble)."</td>" : '' !!} 

			{!! $hotel->sextra > 0 ? "<td class="text-right">".Content::money($hotel->sextra)."</td>0" : '' !!}

			{!! $hotel->schextra > 0 ? "<td class="text-right">".Content::money($hotel->schextra)."</td>" : '' !!} 

		</tr>

		@endforeach

		<tr>

			<td colspan="9" class="text-right"><h5><strong>Hotel Sub Total: {{Content::money($hotelBook->sum('sell_amount'))}} {{Content::currency()}} </strong></h5></td>

		</tr>

	</table>

@endif

<?php 

$flightBook = App\Booking::flightBook($book->book_project);

?>

@if($flightBook->count() > 0)

	<div><strong style="text-transform: uppercase;">Flight OVERVIEW</strong></div>

	<table class="table" id="roomrate">

		<tr style="background-color:#f4f4f4;">

			<th width="100px">Date</th>

			<th>From</th>

			<th>To City</th>

			<th>Flight No.</th>

			<th>Flight Dep</th>

			<th>Flight Arr</th>

			<th>Ticketing Agent</th>

			<th>Pax</th>

			<th class="text-right">Price {{Content::currency()}}</th>

			<th class="text-right">Amount {{Content::currency()}}</th>

		</tr>

		@foreach($flightBook->get() as $fl)	

		<?php 

		$flprice = App\Supplier::find($fl->book_agent);

		?>			

		<tr>

			<td>{{Content::dateformat($fl->book_checkin)}}</td>

			<td>{{$fl->flight_from}}</td>

			<td>{{$fl->flight_to}}</td>

			<td>{{$fl->flightno}}</td>

			<td>{{$fl->dep_time}}</td>

			<td>{{$fl->arr_time}}</td>					

			<td>{{{ $flprice->supplier_name or ''}}}</td>

			<td class="text-center">{{$fl->book_pax}}</td>

			<th class="text-right">{{Content::money($fl->book_price)}}</th>

			<th class="text-right">{{Content::money($fl->book_amount)}}</th>

		</tr>

		@endforeach

		<tr>

			<td colspan="11" class="text-right"><h5><strong>Flight Sub Total: {{$flightBook->sum('book_amount')}} {{Content::currency()}} </strong></h5></td>

		</tr>

	</table>

@endif

<!-- end flight  -->

<?php 

$golfBook = App\Booking::golfBook($book->book_project);

?>

@if($golfBook->count() > 0)

	<div><strong style="text-transform: uppercase;">GOFL COURSES OVERVIEW</strong></div>

	<table class="table" id="roomrate">

		<tr style="background-color:#f4f4f4;">

			<th width="100px">Date</th>

			<th>Golf</th>

			<th>Tee Time</th>

			<th>Golf Service</th>

			<th class="text-center">Pax</th>

			<th class="text-right">Price {{Content::currency()}}</th>

			<th class="text-right">Price {{Content::currency()}}</th>

		</tr>

		@foreach($golfBook->get() as $gf)			

		<?php 

		$gsv = App\GolfMenu::find($gf->program_id);

		?>	

		<tr>

			<td>{{Content::dateformat($gf->book_checkin)}}</td>

			<td>{{$gf->supplier_name}}</td>

			<td>{{$gf->book_golf_time}}</td>

			<td>{{{ $gsv->name or ''}}}</td>

			<td class="text-center">{{$gf->book_pax}}</td>

			<td class="text-right">{{Content::money($gf->book_price)}}</td>

			<td class="text-right">{{Content::money($gf->book_amount)}}</td>

		</tr>

		@endforeach

		<tr>

			<td colspan="9" class="text-right"><h5><strong>Golf Sub Total: {{Content::money($golfBook->sum('book_amount'))}} {{Content::currency()}}</strong></h5></td>

		</tr>

	</table>

@endif

<!-- end golf  -->

<?php 

// $cruiseBook = CruiseBooked::golfBook($book->book_project);

$cruiseBook = App\CruiseBooked::where('project_number', $book->book_project);

?>

@if($cruiseBook->count() > 0)

	<div><strong style="text-transform: uppercase;">River Cruise OVERVIEW</strong></div>

	<table class="table" id="roomrate">

		<tr style="background-color:#f4f4f4;">

			<th >Date</th>

			<th>River Cruise</th>

			<th>Program</th>

			<th>Room</th>

			<th>Night</th>

			<th>No. Cabin</th>

			@foreach(App\RoomCategory::whereIn('id',[1,2,3,4,5])->orderBy('id', 'ASC')->get() as $cat)

            <th class="text-right">{{$cat->name}}</th>

            @endforeach



		</tr>

		@foreach($cruiseBook->get() as $crp)			

		<?php 

		$pcr = App\CrProgram::find($crp->program_id);

		$rcr = App\RoomCategory::find($crp->room_id);

		?>	

		<tr>

			<td>{{Content::dateformat($crp->checkin)}} - {{ Content::dateformat($crp->checkout)}} </td>

			<td>{{$crp->cruise->supplier_name}}</td>

			<td>{{{ $pcr->program_name or ''}}}</td>

			<td>{{{ $rcr->name or ''}}}</td>

			<td class="text-center">{{$crp->book_day}}</td>

			<td class="text-center">{{$crp->cabin_pax}}</td>

			<td class="text-right">{{Content::money($crp->ssingle)}}</td>

			<td class="text-right">{{Content::money($crp->stwin)}}</td>

			<td class="text-right">{{Content::money($crp->sdouble)}}</td>

			<td class="text-right">{{Content::money($crp->sextra)}}</td>

			<td class="text-right">{{Content::money($crp->schextra)}}</td>

		</tr>

		@endforeach

		<tr>

			<td colspan="11" class="text-right"><h5><strong>River Cruise Sub Total: {{Content::money($cruiseBook->sum('sell_amount'))}} {{Content::currency()}} </strong></h5></td>

		</tr>

	</table>

@endif

<!-- End cruise  -->

<!-- <div class="col-md-12"> -->

<div class="table-responsive">

    <table class='table'>

      <thead>

        <tr class="text-center">

          <td style="width: 50%;"><strong>Service Included</strong></td>

          <td style="width: 50%;"><strong>Service Excluded</strong></td>

        </tr>

      </thead>

      <tbody>

  	<?php

    $Included=App\Service::where('service_cat',0)->whereIn('id',$servicedata)->get();

    $Excluded=App\Service::where('service_cat',1)->whereIn('id',$servicedata)->get();

  	?>

      <tr> 

        <td style="vertical-align:top;">

        	<ul style="list-style-type:circle">

            @foreach($Included as $room)

                <li>{{$room->service_name}}</li>

            @endforeach

            </ul>

        </td>

        <td style="vertical-align:top;">

        	<ul style="list-style-type:circle">

            @foreach($Excluded as $room)

                <li>{{$room->service_name}}</li>

            @endforeach

            </ul>

        </td>

      </tr>

      </tbody>

    </table>

</div>