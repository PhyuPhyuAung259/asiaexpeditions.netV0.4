@extends('layout.backend')
@section('title', 'Transport Booking Form')
<?php 
	use App\component\Content;
	$comId = Auth::user()->company_id ? Auth::user()->company_id: 1;
	$comadd = \App\Company::find($comId);
 ?>
@section('content')
	<div class="col-lg-12">
		<table class="table" style="margin-bottom: 0px;">
			<tr>
				<td style="border-top: none;" class="text-left">
					<h4 style="text-transform: uppercase;">Reservation Form</h4>
					<div><b>File / Project No.</b>: {{$project['project_prefix'] > 0 ? $project['project_prefix'].'-'.$project['project_fileno'] : $project['project_number'] }}</div>
					<div><b>Issue Date</b>: {{Date('d-M-Y')}}</div>
					<div><b>Client Name:</b>{{$project['project_client']}}</div>
					<div><b>Travelling Date:</b>{{Content::dateformat($project['project_start'])}} -  {{Content::dateformat($project['project_end'])}}</div>
				</td>
				<td style="border-top: none;" width="200px">
					<img src="{{Storage::url('avata/')}}{{ $comadd->logo }}" style="width: 100%;">
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
							<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;{{{ $btransport->transport->supplier_name or ''}}}<br>
						 	<i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;{{{ $btransport->transport->supplier_address or ''}}}<br>
							<i class="glyphicon glyphicon-phone-alt"></i>&nbsp;&nbsp;{{{ $btransport->transport->supplier_phone or ''}}} / {{{ $btransport->transport->supplier_phone2 or ''}}}<br>
							<i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;{{{ $btransport->transport->supplier_email or ''}}}<br>
						</address>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="border-top: none;">
					<p><b>Booking Date:</b> {{Content::dateformat($book->book_checkin)}}</p>
					<p>Dear Reservation Team,<br><br>
					Could you please kindly book and confirm the following reservation?</p><br>
				</td>
			</tr>
			<tr><td class=" hotel_booking" style="width: 190px;"><strong>Client Name</strong>:</td><td class="text-left">{{$project['project_client']}}</td>
			</tr>
			<tr><td><strong>Pax Number</strong>:</td><td class="text-left">{{ $project['project_pax']}}</td></tr>
			<tr><td><strong>Start Date</strong>:</td><td class="text-left">{{ Content::dateformat($book['book_checkin'])}}</td></tr>
			<tr><td class="hotel_booking"><strong>Service Name: </strong></td><td class="text-left">{{{ $btransport->service->title or ''}}}</td></tr>
			<tr><td class="hotel_booking"><strong>Vehicle : </strong>:</td><td class="text-left">{{{$btransport->vehicle->name or ''}}}</td></tr>
			<tr><td class="hotel_booking"><strong>Driver Name : </strong></td><td class="text-left">{{{$btransport->driver->driver_name or ''}}}</td></tr>
			<tr><td class="hotel_booking"><strong>Pickup TIme : </strong></td><td class="text-left">{{$btransport['pickup_time']}}</td></tr>
			<tr><td class="hotel_booking"><strong>Flight No. : </strong></td><td class="text-left">{{$btransport['flightno']}}</td></tr>
			@if($btransport['remark'])
			<tr>
				<td colspan="3"><br><br>
				<strong>Remark: </strong>
				<div class="well">{{$btransport['remark']}}</div>
				</td>
			</tr>
			@endif
			<tr>
				<td colspan="3"><br><br>
					<p>Thank you very much and we look forward to receiving your confirmation soon. Should you need any further information, please do not hesitate to contact us.<br><br>
						With Best Regards,<br><br>
						Operation Department<br><br>
						<p>{!! $comadd->address!!}</p>
					</p>
				</td>
			</tr>
		</table>
  	</div>
@endsection
