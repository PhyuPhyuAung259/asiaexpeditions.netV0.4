@extends('layout.backend')
@section('title', $title)
<?php 
	use App\component\Content;
	$comadd = \App\Company::where('country_id', \Auth::user()->country_id)->first();
?>

@section('content')

@if($title == "Booking Records")
	<br><br>
	<?php 
		$projectNum = ' / '.$project->project_number;
	?>
@else
	<?php 
	$projectNum = '';
	 ?>
	@include('admin.report.headerReport')
@endif

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="pull-right hidden-print">
			<a href="javascript:void(0)" onclick="window.print();"><span class="fa fa-print btn btn-primary"></span></a>
		</div>		
		<h3 class="text-center"><span style="text-transform:capitalize; text-decoration:underline;">{{$title}} On {{Content::dateformat(date("d-M-Y"))}}</span></h3><br><br>
		<table class="table table-bordered">
			<tr>
				<td style="width:50%;">
					<p><label style="width:100px; margin-bottom:0px;">File/Project No.:</label> {{$project->project_prefix}}-{{$project->project_fileno ? $project->project_fileno: $project->project_number}} {{$projectNum}} </p>
					<p><label style="width:90px; margin-bottom:0px;">Client Name:</label> {{$project->project_client}}</p>
					<p><label style="width:90px; margin-bottom:0px;">Tour Date:</label> {{Content::dateformat($project->project_start)}} - {{Content::dateformat($project->project_end)}}</p>
				</td>
				<td style="width:50%;">
					<!--<p><label style="width:106px; margin-bottom: 0px;">Agent Name:</label> {{{$project->supplier->supplier_name or ''}}}</p>-->
						<p><label style="width:106px; margin-bottom: 0px;">Reference No.:</label> {{{$project->project_book_ref or ''}}}</p>
					<p><label style="width:106px; margin-bottom: 0px;">
					Flight No./:Arrival</label>{{{$project->flightArr->flightno or ''}}} - {{{$project->flightArr->arr_time or ''}}}, &nbsp;&nbsp;<b> 
					Flight No./Departure:</b>{{{$project->flightDep->flightno or ''}}} - {{{$project->flightDep->dep_time or ''}}}</p>
				</td>
			</tr>
		</table>	
		<table class="table operation-sheed table-bordered">
			@if($hotelb->get()->count() > 0)
			<tr>
				<th style="text-transform: uppercase;"  colspan="12">Hotel </th>
			</tr>
			
			<tr class="header-row">
				<th width="175px">Checkin - Checkout</th>
				<th>Hotel</th>
				<th>Location</th>
				<th class="text-center">Room</th>
				<th class="text-center">Nights</th>
				<th class="text-center">Single </th>
				<th class="text-center">Twin</th>
				<th class="text-center">Double</th>
				<th class="text-center">EX-Bed</th>
				<th class="text-center">ChEX-Bed</th>
				<th class="text-center">Status</th>
			</tr>
				@foreach($hotelb->get() as $hb)
					<?php 
						$hbook = \App\Booking::find($hb->book_id);
					?>
					<tr>
						<td>{{Content::dateformat($hb->checkin)}} - {{Content::dateformat($hb->checkout)}}</td>
						<td>{{{$hb->hotel->supplier_name or ''}}}</td>
						<td>{{{$hbook->province->province_name or ''}}}</td>
						<td class="text-center">{{{$hb->room->name or ''}}}</td>
						<td class="text-center">{{$hb->book_day}}</td>
						<td class="text-center">{{$hb->nsingle != 0 ? $hb->no_of_room :''}}</td>
						<td class="text-center">{{$hb->ntwin != 0 ? $hb->no_of_room :''}}</td>
						<td class="text-center">{{$hb->ndouble != 0 ? $hb->no_of_room :''}}</td>
						<td class="text-center">{{$hb->nextra != 0 ? $hb->no_of_room :''}}</td>
						<td class="text-center">{{$hb->nchextra != 0 ? $hb->no_of_room :''}}</td>
						<td class="text-center"><b>{{$hb->confirm !=0? 'OK':'RQ'}}</b></td>
					</tr>
				@endforeach
			@endif

			@if($flightb->get()->count() > 0)
				<tr>
					<th style="padding-top: 20px; text-transform: uppercase;"  colspan="12">Flight </th>
				</tr>
				<tr class="header-row">
					<th>Flight Date</th>
					<th>Flight No.</th>
					<th colspan="5">From - To</th>
					<th class="text-center">Dep Time</th>
					<th class="text-center">Arr Time</th>
					<th class="text-center">Seats</th>
					<th class="text-center">Status</th>
				</tr>
				@foreach($flightb->get() as $fb)
				<tr>
					<td>{{Content::dateformat($fb->book_checkin)}}</td>
					<td>{{{$fb->flight->flightno or ''}}}</td>
					<td colspan="5">{{{$fb->flight->flight_from or ''}}} - {{{$fb->flight->flight_to or ''}}}</td>
					<td class="text-center">{{{$fb->flight->dep_time or ''}}}</td>
					<td class="text-center">{{{$fb->flight->arr_time or ''}}}</td>
					<td class="text-center">{{$fb->book_pax}}</td>
					<td class="text-center"><b>{{$fb->book_confirm !=0? 'OK':'RQ'}}</b></td>
				</tr>
				@endforeach
			@endif

			@if($golfb->get()->count() > 0)
				<tr>
					<th style="padding-top: 20px; text-transform: uppercase;"  colspan="12">Golf </th>
				</tr>
				<tr class="header-row">
					<th>Start Date</th>
					<th>Golf Name</th>
					<th colspan="6">Golf Service </th>
					<th class="text-center">Tee Time</th>
					<th class="text-center">No. of Pax</th>
					<th class="text-center">Status</th>
				</tr>
				@foreach($golfb->get() as $gb)
				<tr>
					<td>{{Content::dateformat($gb->book_checkin)}}</td>
					<td>{{{$gb->golf->supplier_name or ''}}}</td>
					<td colspan="6">{{{$gb->golf_service->name or ''}}}</td>
					<td class="text-center">{{$gb->book_golf_time}}</td>
					<td class="text-center">{{$gb->book_pax}}</td>
					<td class="text-center"><b>{{$gb->book_confirm !=0? 'OK':'RQ'}}</b></td>
				</tr>
				@endforeach
			@endif

 	
			@if($guideb->get()->count() > 0)
				<tr>
					<th  style="padding-top: 20px; text-transform: uppercase;" colspan="12">Guide </th>
				</tr>
				<tr class="header-row">
					<th>Start Date</th>
					<th>Guide Name</th>
					<th colspan="5"> Service </th>
					<th class="text-center">Language</th>
					<th class="text-center">Location</th>
					<th class="text-center">Phone</th>
					<th class="text-center">Status</th>
				</tr>
				@foreach($guideb->get() as $bg)
				<?php 
					$dateb = \App\Booking::find($bg->book_id);
					$sb = \App\GuideService::find($bg->service_id);
					$supb = \App\Supplier::find($bg->sup_id);
					$langb = \App\GuideLanguage::find($bg->language_id);
					$prob = \App\Province::find($bg->province_id);
				?>
				<tr>	
					<td>{{ isset($dateb->book_checkin) ? Content::dateformat($dateb->book_checkin) : ''}}</td>
					<td>{{{ $supb->supplier_name or '' }}} </td>
					<td <th colspan="5">{{{ $sb->title or ''}}}</td>
					<td class="text-center">{{{ $langb->name or ''}}}</td>
			        <td class="text-center">{{{ $prob->province_name or ''}}}</td>
					<td class="text-right">{{$bg->phone}}</td>
					<td class="text-center">...........</td>
				</tr>
				@endforeach
			@endif

			@if($transportb->get()->count() >0)
				<tr>
					<th  style="padding-top: 20px; text-transform: uppercase;" colspan="12">Transportation </th>
				</tr>
				<tr class="header-row">
					<th>Start Date</th>
					<th>Driver Name</th>
					<th colspan="5"> Service </th>
					<th class="text-center">Vehicle</th> 
					<th class="text-center">Location</th>
					<th class="text-center">Phone</th>
					<th class="text-center">Status</th>
				</tr>
				@foreach($transportb->get() as $bk)
				<?php 				
					$dateb = \App\Booking::find($bk->book_id);
					$driver = \App\Driver::find($bk->driver_id);
					$service = \App\TransportService::find($bk->service_id);
					$vehicle = \App\TransportMenu::find($bk->vehicle_id);
					$province = \App\Province::find($bk->province_id);
				?>
				<tr>	
					<td>{{ isset($dateb->book_checkin) ? Content::dateformat($dateb->book_checkin) : ''}}</td>
					<td>{{{ $driver->driver_name or ''}}}</td>
					<td colspan="5">{{$service->title}}</td>
			        <td class="text-center">{{{ $vehicle->name or '' }}}</td>
					<td class="text-center">{{{ $province->province_name or ''}}}</td>
					<td class="text-center">{{{ $driver->phone or ''}}}</td>		
					<td class="text-center">...........</td>			
				</tr>
				@endforeach
			@endif

			@if($cruiseb->get()->count() > 0)
				<tr>
					<th style="padding-top: 20px; text-transform: uppercase;" colspan="12">Cruise </th>
				</tr>
				<tr class="header-row">
					<th>Start Date</th>
					<th>Cruise Name</th>
					<th>Program </th>
					<th class="text-center">Room</th>
					<th class="text-center">Nights</th>
					<th class="text-center">Single</th>
					<th class="text-center">Twin</th>
					<th class="text-center">Double</th>
					<th class="text-center">EX-Bed</th>
					<th class="text-center">CHEX-Bed</th>
					<th class="text-center">Status</th>
				</tr>
				@foreach($cruiseb->get() as $cb)
				<tr> 
					<td>{{Content::dateformat($cb->checkin)}} - {{Content::dateformat($cb->checkout)}}</td>
					<td>{{{$cb->cruise->supplier_name or ''}}}</td>
					<td>{{{$cb->program->program_name or ''}}}</td>
					<td>{{$cb->room->name}}</td>
					<td class="text-center">{{$cb->book_day}}</td>
					<td class="text-center">{{$cb->nsingle != 0 ? $cb->cabin_pax :''}}</td>
					<td class="text-center">{{$cb->ntwin != 0 ? $cb->cabin_pax :''}}</td>
					<td class="text-center">{{$cb->ndouble != 0 ? $cb->cabin_pax :''}}</td>
					<td class="text-center">{{$cb->nextra != 0 ? $cb->cabin_pax :''}}</td>
					<td class="text-center">{{$cb->nchextra != 0 ? $cb->cabin_pax :''}}</td>
					<td class="text-center"><b>{{$cb->confirm !=0? 'OK':'RQ'}}</b></td>
				</tr>
				@endforeach
			@endif
			<tr>
				<td colspan="11">
					<b>Remark</b>
					<p>{{$remark}}</p>
				</td>
			</tr>
			<tr>
				<td colspan="5" style="border:none; padding-top: 12px;">Date of Open:............................</td>
				<td colspan="6" style="border:none; padding-top: 12px;" class="text-right">Signature:........................................................</td>
			</tr>
		</table>
  	</div>
</div>
@endsection
