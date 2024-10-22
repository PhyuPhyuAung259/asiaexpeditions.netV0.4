@extends('layout.backend')
@section('title', ' Booking Form')
<?php 
use App\component\Content;
	$comId = Auth::user()->company_id ? Auth::user()->company_id: 1;
	$comadd = \App\Company::find($comId);
 ?>
@section('content')

	<div class="container">
		<h3 class="text-center"><strong class="btborder" style="text-transform:uppercase;">Booking Form</strong></h3> 
		<table class="table" style="margin-bottom: 0px;">
			<tr>
				<td style="border-top: none; vertical-align: bottom;">
					<div><b>File / Project No.</b>: {{$project->project_fileno ? $project->project_prefix.'-'.$project->project_fileno : $project->project_number }}</div>
					<div><b>Issue Date</b>: {{Date('d-M-Y')}}</div>
				</td>
				<td style="border-top: none;" width="200px">
					<img src="{{Storage::url('avata/')}}{{ $comadd->logo }}" style="width: 100%;">
				</td>
			</tr>
		</table>
		
		<div class="pull-right hidden-print" id="preview_layout" style="color: #009688; cursor: pointer;">
			<input type="hidden" name="preview-type" value="standard">
			<p>Wide View <i class="fa fa-reply"></i></p>
		</div>
		<div class="clearfix"></div>
		<div class="col-md-12">			
			<div class="pull-right hidden-print">
				<a href="javascript:void(0)" onclick="window.print();"><span class="fa fa-print btn btn-primary"></span></a>
			</div>
		</div>
		<table class="table" style="width: 100%;" >
			<tr>				
				<td style="border-top: none" colspan="6">
					<div class="well" style="padding: 4px;margin-bottom: 0px; background-color: #ddd0;">
						<address style="margin-bottom: 0px;">
							<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;{{{ $bhotel->hotel->supplier_name or ''}}}<br>
						 	<i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;{{{ $bhotel->hotel->supplier_address or ''}}}<br>
							<i class="glyphicon glyphicon-phone-alt"></i>&nbsp;&nbsp;{{{ $bhotel->hotel->supplier_phone or ''}}} / {{{ $bhotel->hotel->supplier_phone2 or ''}}}<br>
							<i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;{{{ $bhotel->hotel->supplier_email or ''}}}<br>
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
				<th class="text-right" style="width: 22%;">Client Name</th>
				<td class="text-left" style="width: 18%;">{{$project['project_client']}}</td>
				<th class="text-right" style="width: 22%;">No. of Pax:</th>
				<td class="text-left" style="width: 18%;">{{$project['project_pax']}}</td>
			</tr>
			<tr>
				<th class="text-right" style="width: 22%;">Check-In:</th>
				<td class="ftext-left" style="width: 18%;">{{Content::dateformat($bhotel['checkin'])}}</td>
				<th class="text-right" style="width: 22%;">Check-Out:</th>
				<td class="ftext-left" style="width: 18%;">{{Content::dateformat($bhotel['checkout'])}}</td>
			</tr>
			@if($hotel)
				@foreach($hotel as $key=> $bh)							
					<tr>
						<th class="text-right" style="width: 22%;">Room Type:</th>
						<td class="text-left"  style="width: 18%;">{{ $bh->room->name }}</td>
						<th class="text-right" style="width: 22%;">No. of {{{$bh->category->name or ''}}}:</th>
						<td class="text-left"  style="width: 18%;">{{ $bh->no_of_room }}</td>
					</tr>
					<tr><th colspan="3" class="text-right">No. of Nights:</th><td class="text-left">{{$bh->book_day}}</td></tr>
				@endforeach
			@endif
			<tr>
				<td colspan="6" style="border-top: none" >
					<div style="padding: 3px;margin-bottom: 0px; background-color: #ddd0; border: solid 1px #ddd;">
						<address style="margin-bottom: 0px;">
							<strong>Remark:</st	rong>
							{{$bhotel->remark}}
						</address>
					</div><br>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="border-top: none">
					<p>Thank you very much and we look forward to receiving your confirmation soon. Should you need any further information, please do not hesitate to contact us.<br>
					With Best Regards,<br>
					Operation Department</p>
					{!! $comadd->address !!}
					<br>
				</td>
			</tr>
		</table>		
  	</div>
@endsection
