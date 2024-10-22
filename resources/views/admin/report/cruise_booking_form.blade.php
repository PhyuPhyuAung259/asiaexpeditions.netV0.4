@extends('layout.backend')
@section('title', ' Booking Form')
<?php 
use App\component\Content;
 ?>
@section('content')
	<div class="col-lg-12">
		<h3 class="text-center"><strong class="btborder" style="text-transform:uppercase;">Booking Form</strong></h3>
		<?php 
		$comadd = \App\Company::where('country_id', \Auth::user()->country_id)->first();
		?>
		<table class="table" style="margin-bottom: 0px;">
			<tr>
				<td style="border-top: none; vertical-align: bottom;">
					<div><b>File / Project No.</b>: {{$project->project_fileno ? $project->project_prefix.'-'.$project->project_fileno : $project->project_number }}</div>
					<div><b>Issue Date</b>: {{Date('d-M-Y')}}</div>
				</td>
				<td style="border-top: none" class="text-right">
					<img src="{{url('/')}}/img/{{$comadd->logo}}" width="135px">
				</td>
			</tr>
		</table>
		<div class="col-md-12">			
			<div class="pull-right hidden-print">
				<a href="javascript:void(0)" onclick="window.print();"><span class="fa fa-print btn btn-primary"></span></a>
			</div>
		</div>
		<table class="table" style="width: 100%;" >
			<tr>				
				<td style="border-top: none" colspan="2">
					<div class="well" style="padding: 4px;margin-bottom: 0px; background-color: #ddd0;">
						<address style="margin-bottom: 0px;">
							<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;{{{ $bcruise->cruise->supplier_name or ''}}}<br>
						 	<i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;{{{ $bcruise->cruise->supplier_address or ''}}}<br>
							<i class="glyphicon glyphicon-phone-alt"></i>&nbsp;&nbsp;{{{ $bcruise->cruise->supplier_phone or ''}}} / {{{ $bcruise->cruise->supplier_phone2 or ''}}}<br>
							<i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;{{{ $bcruise->cruise->supplier_email or ''}}}<br>
						</address>
					</div>
				</td>
			</tr>
			<tr>
				<td style="border-top: none" colspan="2"><p>Dear Reservation Team,<br>
					Could you please kindly book and confirm the following reservation?</p>
				</td>
			</tr>
			<tr>
				<td style="border-top: none" colspan="2">					
					<table class="table table-padding">
						<tr>
							<td width="230px" class="text-right hotel_booking"><strong>Client Name</strong>:</td><td class="text-left">{{$project->project_client}}</td>
							<td class="text-right"><strong>Room Type</strong>:</td><td class="text-left">{{{ $bcruise->room->name or ''}}}</td>
						</tr>
						<tr>
							<td class="text-right"><strong>Check-In</strong>:</td><td class="text-left">{{Content::dateformat($booking->book_checkin)}}</td>
							<td class="text-right"><strong>Check-Out</strong>:</td><td class="text-left">{{Content::dateformat($booking->book_checkout)}}</td></tr>
						<tr>
							<td class="text-right"><strong>Number of Room {{{$bcruise->category->name or ''}}}</strong>:</td><td class="text-left">{{$bcruise->cabin_pax}}</td>
							<td class="text-right"><strong>Number Of Pax</strong>:</td><td class="text-left">{{$booking->book_pax}}</td>
						</tr>
						<tr>
							<td class="text-right"><strong>Days / Nights</strong>:</td><td class="text-left">{{ $bcruise->book_day}}</td>
							<td></td><td></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="border-top: none" >
					<div class="well" style="padding: 1px;margin-bottom: 0px; background-color: #ddd0;">
						<address style="margin-bottom: 0px;">
							<strong>Remark:</strong>
							{{$bcruise->remark}}
						</address>
					</div>
				</td>
			</tr>
			<tr>
				<td style="border-top: none">
					<p>Thank you very much and we look forward to receiving your confirmation soon. Should you need any further information, please do not hesitate to contact us.<br>
					With Best Regards,<br>
					Operation Department</p>
					{!! $comadd->address !!}
				</td>
			</tr>
		</table>		
  	</div>
@endsection
