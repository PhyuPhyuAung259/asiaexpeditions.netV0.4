@extends('layout.backend')
@section('title', 'Hotel Voucher')
<?php 
	use App\component\Content;
	$comId = Auth::user()->company_id ? Auth::user()->company_id: 1;
	$comadd = \App\Company::find($comId);
?>
@section('content')
<style type="text/css">
	@media print {
	  .no_of_room {
	    width: 120px !important;
	  }
	}
</style>
	<div class="container">
    @include('admin.report.headerReport')		
		<h3 class="text-center"><strong class="btborder" style="text-transform:uppercase;">service voucher</strong></h3>
		<div class="col-md-10 col-md-offset-2">
			<div class="row">
				<div class="pull-left hotel_voucher_title" style="position: relative;bottom:-12px;left:55px">
					<strong style="font-size:16px;">File / Project No. {{$project->project_fileno ? $project['project_prefix'].'-'.$project['project_fileno'] : $project->project_number }}</strong>
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
							{{{ $bhotel->hotel->supplier_name or ''}}}<br>
							{{{ $bhotel->hotel->supplier_address or ''}}}<br>
							{{{ $bhotel->hotel->supplier_phone or ''}}} / {{{ $bhotel->hotel->supplier_phone2 or ''}}}<br>
							{{{ $bhotel->hotel->supplier_email or ''}}}<br>
						</address>
					</div>
				</td>
			</tr>
			<tr>
				<td style="border-top: none; padding:2px;" class="text-right" >
					<label style="font-weight:400;margin-top: 8px;">Client Name:</label>
				</td>
				<td style="border-top: none; width: 34%; padding:2px;">
					<div style="padding: 1px;margin-bottom: 0px; background-color: #ddd0; border:none;">
						<div style="margin: 0px 0px 4px 0px;" class="form-control input-sm">{{$project['project_client']}}</div>
					</div>
				</td>
				<td style="border-top: none; width: 12%; vertical-align: top; text-align: right; padding:2px;">
					<label style="font-weight: 400; margin-top: 10px">No. of Pax:</label>
				</td>
				<td style="border-top: none; width: 34%; vertical-align: top; padding:2px;">
					<div  style="padding: 1px;margin-bottom: 0px; background-color: #ddd0; border:none;">
						<div style="margin: 0px 0px 4px 0px;" class="form-control input-sm">{{$project['project_pax']}}</div>
					</div>
				</td>
			</tr>
			<tr>
				<td style="border-top: none; padding:2px;" class="text-right" >
					<label style="font-weight:400;margin-top: 11px;">Check-In:</label><br>
				</td>
				<td style="border-top: none; width: 34%; padding:2px;">
					<div style="padding: 1px;margin-bottom: 0px; background-color: #ddd0; border:none;">
						<div style="margin: 0px 0px 4px 0px;" class="form-control input-sm">{{Content::dateformat($bhotel['checkin'])}}</div>
					</div>
				</td>
				<td style="border-top: none; width: 12%; vertical-align: top; text-align: right; padding:2px;">
					<label style="font-weight: 400; margin-top: 12px;">Check-Out:</label><br>
				</td>
				<td style="border-top: none; width: 34%; vertical-align: top; padding:2px;">
					<div  style="padding: 1px;margin-bottom: 0px; background-color: #ddd0; border:none;">
						<div style="margin: 0px 0px 4px 0px;" class="form-control input-sm">{{Content::dateformat($bhotel['checkout'])}}</div>
					</div>
				</td>
			</tr>
			@if($hotel)
				@foreach($hotel as $key=> $bh)							
					<tr>
						<td style="border-top: none; padding: 2px;" class="text-right">Room Type:</td>
						<td style="border-top: none; padding: 2px;"><div style="margin: 0px 0px 4px 0px;" class="form-control input-sm">{{ $bh->room->name }}</div></td>
						<td style="border-top:none; padding:2px;" class="text-right no_of_room">No. of {{{$bh->category->name or ''}}}:</td>
						<td style="border-top:none; padding:2px;"><div class="form-control input-sm">{{ $bh->no_of_room }}</div></td>
					</tr>
				@endforeach
			@endif
			<tr>				
				
				<td colspan="5" style="border-top: none; vertical-align: top;">
					<?php $getClientProject = \App\Admin\ProjectClientName::where('project_number', $project->project_number)->orderBy('client_name')->get(); ?>
					@if($getClientProject->count() > 0)
					<br><br>
					<table class="table">
							<tr>
								<th colspan="10" style="text-transform: capitalize; border: none; background: #dddddd78">Passenger Manifest</th>
							</tr>
							<tr>
								<th>No.</th>
								<th>Client Name/s</th>
								<th>Nationality</th>
								<th>Passport No.</th>
								<th>Date of Expiry</th>
								<th>Date Of Birth</th>
								<th>Shared With</th>
								<th>Dietary</th>
								<th>Allergies</th>
								<th>Mobile Phone</th>
							</tr>
							<?php $n=1; ?>
							@foreach($getClientProject as $key=> $cl)
								<tr><td>{{$n++}}</td>
									<td>{{$cl->client_name}}</td>
									<td>{{{ $cl->country->nationality or ''}}}</td>
									<td>{{$cl->passport}}</td>
									<td>{{Content::dateformat($cl->expired_date)}}</td>
									<td>{{Content::dateformat($cl->date_of_birth)}}</td>
									<td>{{$cl->share_with}}</td>
									<td>{{$cl->dietary}}</td>
									<td>{{$cl->allergies}}</td>
									<td>{{$cl->phone}}</td>
								</tr>
							</tr>
							@endforeach
						</table>
					@endif
				</td>
			</tr>
			<tr>
				<td style="border-top: none" colspan="4">
					<strong>Remark</strong>
					<div class="form-control input-sm">{{$bhotel->remark}}</div></td>
			</tr>
			<tr><td style="border-top: none"></td></tr>			
			<tr>
				<td style="border-top: none">
					{{$comadd->title}}
				</td>
			</tr>
			<tr>
				<td style="border-top: none" colspan="2" class="text-left">Signed By: ........................................</td>
			</tr>
		</table>		
  	</div>
  	<br>
@endsection
