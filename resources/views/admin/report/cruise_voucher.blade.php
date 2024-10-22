@extends('layout.backend')
@section('title', 'Hotel Voucher')
<?php 
	use App\component\Content;
	$comadd = \App\Company::where('country_id', \Auth::user()->country_id)->first();
?>
@section('content')
	<div class="container-fluid">
    @include('admin.report.headerReport')		
		<h3 class="text-center"><strong class="btborder" style="text-transform:uppercase;">service voucher</strong></h3>
		<div class="col-md-10 col-md-offset-2">
			<div class="row">
				<div class="pull-left hotel_voucher_title" style="position: relative;bottom:-12px;left:55px">
					<strong style="font-size:16px;">File / Project No. {{$project->project_fileno ? $project->project_prefix.'-'.$project->project_fileno : $project->project_number }}</strong>
				</div>
				<div class="pull-right hidden-print">
					<a href="javascript:void(0)" onclick="window.print();"><span class="fa fa-print btn btn-primary"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
			</div>
		</div>
		<table class="table" style="width: 100%;" >
			<tr>
				<td style="border-top: none" class="text-right hotel_voucher" width="205px">
					<address style="margin-bottom: 0px;">
						Name:<br>
						Address:<br>
						Phone:<br>
						Email:
					</address>
				</td>
				<td style="border-top: none" colspan="3" >
					<div class="well" style="padding: 4px;margin-bottom: 0px; background-color: #ddd0;">
						<address style="margin-bottom: 0px;">
							{{{ $bcruise->cruise->supplier_name or ''}}}<br>
							{{{ $bcruise->cruise->supplier_address or ''}}}<br>
							{{{ $bcruise->cruise->supplier_phone or ''}}} / {{{ $bcruise->cruise->supplier_phone2 or ''}}}<br>
							{{{ $bcruise->cruise->supplier_email or ''}}}<br>
						</address>
					</div>
				</td>
			</tr>
			<tr>
				<td style="border-top: none" class="text-right" >
					<address>
						<label style="font-weight:400;margin-top: 8px;">Client Name:</label><br>
						<label style="font-weight:400;margin-top: 11px;">Check-In:</label><br>
						<label style="font-weight:400;margin-top: 10px;">No. of Room {{{$bcruise->category->name or ''}}} </label><br>
						<label style="font-weight:400;margin-top: 12px;">No. of  Nights </label>
					</address>
				</td>
				<td style="border-top: none; width: 34%;">
					<div class="well" style="padding: 1px;margin-bottom: 0px; background-color: #ddd0; border:none;">
						<address>
							<div style="margin: 0px 0px 4px 0px;" class="form-control input-sm">{{$project->project_client}}</div>
							<div style="margin: 0px 0px 4px 0px; " class="form-control input-sm">{{Content::dateformat($booking->book_checkin)}}</div>
							<div style="margin: 0px 0px 4px 0px;" class="form-control input-sm">{{$bcruise->cabin_pax}}</div>
							<div style="margin: 0px 0px 4px 0px;" class="form-control input-sm">{{$bcruise->book_day}}</div>
						</address>
					</div>
				</td>
				<td style="border-top: none; width: 12%; vertical-align: top; text-align: right;">
					<address>
						<label style="font-weight: 400; margin-top: 10px">No. of Pax:</label><br>
						<label style="font-weight: 400; margin-top: 12px;">Check-Out:</label><br>
						<label style="font-weight: 400; margin-top: 10px">Cabin Type:</label>
					</address>
				</td>
				<td style="border-top: none; width: 34%; vertical-align: top;">
					<div class="well" style="padding: 1px;margin-bottom: 0px; background-color: #ddd0; border:none;">
						<address>
							<div style="margin: 0px 0px 4px 0px;" class="form-control input-sm">{{$booking->book_pax}}</div>
							<div style="margin: 0px 0px 4px 0px;" class="form-control input-sm">{{Content::dateformat($booking->book_checkout)}}</div>
							<div style="margin: 0px 0px 4px 0px;" class="form-control input-sm">{{{ $bcruise->room->name or ''}}}</div>
						</address>
					</div>
				</td>
			</tr>
			<tr>
				<td style="border-top: none" class="text-right"><strong>Remark:</strong></td>
				<td style="border-top: none" colspan="3"><div class="form-control input-sm">{{$bcruise->remark}}</div></td>
			</tr>
			<tr><td style="border-top: none"></td></tr>
			
			<tr>
				<td style="border-top: none" ></td>
				<td style="border-top: none">
					{{$comadd->title}}
				</td>
			</tr>
			<tr>
				<td style="border-top: none" ></td>
				<!-- <td style="border-top: none" colspan="2" class="text-left"></td> -->
				<td style="border-top: none" colspan="2" class="text-left">Signed By: ........................................</td>
			</tr>
		</table>		
  	</div>
@endsection
