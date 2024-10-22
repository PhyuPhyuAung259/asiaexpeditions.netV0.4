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
				<td style="border-top: none;" width="186px">
					<img src="{{Storage::url('avata/'.$comadd->logo) }}" style="width: 100%;">
				</td>
				<td class="text-left" style="border-top: none;">
					<address>
						{!!$comadd->address!!}
					</address>
				</td>
				<td style="border-top: none;" class="text-center">
					<h3 style="text-transform: uppercase;">Service Voucher</h3>
					<div><b>File / Project No.</b>: {{$project->project_fileno ? $project->project_prefix.'-'.$project->project_fileno : $project->project_number }}</div>
					<div><b>Issue Date</b>: {{Date('d-M-Y')}}</div>
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
				<td style="border-top: none; padding: 0px;" colspan="2">
					<div  style="padding: 8px;margin-bottom: 0px; background-color: #ddd0;">
						<address style="margin-bottom: 0px;">
							<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;{{$supplier['supplier_name'] }}<br>
						 	<i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;{{$supplier['supplier_address'] }}<br>
							<i class="glyphicon glyphicon-phone-alt"></i>&nbsp;&nbsp;{{ $supplier['supplier_phone']}} / {{ $supplier['supplier_phone2']}}<br>
							<i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;{{$supplier['supplier_email']}}<br>
						</address>
					</div>
				</td>
			</tr>
			<tr>
				<td style="border-top:none; padding: 0px;" colspan="2" >	
					<table class="table table-padding" style="width: 100%;">
						<tr>
							<td class="text-right hotel_booking" style="width: 190px;">Client Name:</td><th class="text-left">{{$project->project_client}}</th>
							<td class="text-right">Start Date:</td><td class="text-left">{{Content::dateformat($bops->start_date)}}</td>
						</tr>
						<tr>
							<td class="text-right hotel_booking">Service Name:</td><th class="text-left">{{{ $bops->entrance->name  or ''}}}</th>
							<td class="text-right">Pax No.:</td><th class="text-left">{{$bops->book_pax}}</th>
						</tr>
						@if($bops->remark)
							<tr>
								<td class="text-right hotel_booking">Remark:</td>
								<td colspan="3">
									<div  style="padding: 1px;margin-bottom: 0px; background-color: #ddd0;">{{$bops->remark}}</div>
								</td>
							</tr>
						@endif
						<tr>
							<td colspan="1" class="text-right">Confirmed By:<br>...........................</td>
							<td colspan="3" class="text-right">Signed By:<br>...........................</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>		
  	</div>
@endsection
