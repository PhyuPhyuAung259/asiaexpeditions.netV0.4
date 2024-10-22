@extends('layout.backend')
@section('title', $title)
<?php 
	use App\component\Content;
	$comId = Auth::user()->company_id ? Auth::user()->company_id: 1;
	$comadd = \App\Company::find($comId);
 ?>
@section('content')
	<div class="container">
		<table class="table" style="margin-bottom: 0px;">
			<tr>
				<td style="border-top: none;" class="text-left">
					<h4 style="text-transform: uppercase;">Reservation Form</h4>
					<div><b>File / Project No.</b>: {{$project->project_fileno ? $project->project_prefix.'-'.$project->project_fileno : $project->project_number }}</div>
					<div><b>Issue Date</b>: {{Date('d-M-Y')}}</div>
					<div><b>Client Name:</b>{{$project->project_client}}</div>	
					<div><b>Travelling Date:</b>{{Content::dateformat($project->project_start)}} -  {{Content::dateformat($project->project_end)}}</div>
				</td>
				<td style="border-top: none;" width="186px">
					<img src="{{Storage::url('avata/')}}{{ $comadd->logo }}" style="width: 100%;">
				</td>
			</tr>
		</table>
		<div class="col-md-12">			
			<div class="pull-right hidden-print">
				<a href="javascript:void(0)" onclick="window.print();"><span class="fa fa-print btn btn-primary"></span></a>
			</div>
		</div>
		
		<table class="table table-bordered" style="width: 100%;" >
			<tr>				
				<td style="border-top: none" colspan="2">
					<div class="well" style="padding: 4px;margin-bottom: 0px; background-color: #ddd0;">
						<address style="margin-bottom: 0px;">
							<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;{{$supplier['supplier_name']}}<br>
						 	<i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;{{$supplier['supplier_address']}}<br>
							<i class="glyphicon glyphicon-phone-alt"></i>&nbsp;&nbsp;{{$supplier['supplier_phone'] }} / {{$supplier['supplier_phone2']}}<br>
							<i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;{{$supplier->supplier_email}}<br>
						</address>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="border-top: none;">
					<p><b>Booking Date:</b> {{Content::dateformat($bops->book_date)}}</p>
					<p>Dear Reservation Team,<br>
					Could you please kindly book and confirm the following reservation?</p>
				</td>
			</tr>
			<tr>
				<td class="text-right hotel_booking" style="width: 190px;">Client Name:</td><td class="text-left">{{$project->project_client}}</td>
			</tr>
			<tr>
				<td class="text-right">Pax Number:</td><td class="text-left">{{ $bops->book_pax}}</td>
			</tr>
			<tr>
				<td class="text-right">Start Date:</td><td class="text-left">{{Content::dateformat($bops->start_date)}}</td>
			</tr>
			<tr>
				<td class="text-right hotel_booking">Service Name:</td><td class="text-left">{{{ $bops->entrance->name  or ''}}}</td>
			</tr>
			@if($bops->remark)
				<tr>
					<td class="text-right hotel_booking">Remark:</td><td class="text-left">{{$bops->remark}}</td>
				</tr>
			@endif
			<tr>
				<td colspan="3">
					<p>Thank you very much and we look forward to receiving your confirmation soon. Should you need any further information, please do not hesitate to contact us.<br>
						With Best Regards,<br>
						Operation Department<br>
						<p>{!! $comadd->address!!}</p>
					</p>
				</td>
			</tr>
		</table>
  	</div>
@endsection
