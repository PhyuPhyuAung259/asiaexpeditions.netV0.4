@extends('layout.backend')
@section('title', 'Resturant Service Voucher')
<?php 
use App\component\Content;
$comId = Auth::user()->company_id ? Auth::user()->company_id: 1;
$comadd = \App\Company::find($comId);
 ?>
@section('content')
	<div class="container">		
		<table class="table" style="margin-bottom: 0px;">
			<tr>				
				<td style="border-top: none;" width="200px">
					<img src="{{Storage::url('avata/')}}{{ $comadd->logo }}" style="width: 100%;">
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
		
		<table class="table" style="width: 100%;" >
			<tr>				
				<td style="border-top: none" colspan="2">
					<div class="well" style="padding: 4px;margin-bottom: 0px; background-color: #ddd0;">
						<address style="margin-bottom: 0px;">
							<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;{{{ $restb->supplier->supplier_name or ''}}}<br>
						 	<i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;{{{ $restb->supplier->supplier_address or ''}}}<br>
							<i class="glyphicon glyphicon-phone-alt"></i>&nbsp;&nbsp;{{{ $restb->supplier->supplier_phone or ''}}} / {{{ $restb->supplier->supplier_phone2 or ''}}}<br>
							<i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;{{{ $restb->supplier->supplier_email or ''}}}<br>
						</address>
					</div>
				</td>
			</tr>
			<tr>
				<td style="border-top:none;" colspan="2" >	
					<div class="container">				
						<table class=" table-padding" style="width: 100%;">
							<tr>
								<td class="text-right hotel_booking" style="width: 190px;"><strong>Client Name</strong>:</td><td class="text-left">{{$project->project_client}}</td>
								<td class="text-right"><strong>Start Date</strong>:</td><td class="text-left">{{ $restb->start_date}}</td>
							</tr>
							<tr>
								<td class="text-right hotel_booking"><strong>Resturant Menu</strong>:</td><td class="text-left">{{{ $restb->rest_menu->title or ''}}}</td>
								<td class="text-right"><strong>Pax No.</strong>:</td><td class="text-left">{{$restb->book_pax}}</td>
							</tr>
							<tr>
								<td class="text-right hotel_booking"><strong>Remark</strong>:</td>
								<td colspan="3">
									<div style="padding: 1px;margin-bottom: 0px; background-color: #ddd0;">{{$restb->remark}}</div>
								</td>
							</tr>
							<tr>
								<td colspan="1" class="text-right"><strong>Confirmed By</strong>:<br>...........................</td>
								<td colspan="3" class="text-right"><strong>Signed By</strong>:<br>...........................</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>		
  	</div>
@endsection
