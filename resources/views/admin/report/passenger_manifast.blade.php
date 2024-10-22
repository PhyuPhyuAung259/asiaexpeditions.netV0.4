@extends('layout.backend')
@section('title', 'Passenger Manifest')
<?php 
	use App\component\Content;
	$comId = Auth::user()->company_id ? Auth::user()->company_id: 1;
	$comadd = \App\Company::find($comId);
?>
@section('content')
	<div class="container">
    @include('admin.report.headerReport')		
		<div class="text-center"><strong class="btborder" style="text-transform:uppercase;">Passenger Manifest</strong></div>
		<table class="table">
			<tr>				
				<td style="border-top: none; vertical-align: top;">
					@if($clientByProject->count() > 0)
						<table class="table">
							<tr>
								<th colspan="12" style="text-transform: capitalize; border: none; background: #dddddd78"><strong>
									File No. {{$project->project_prefix}}-{{$project->project_fileno}} | Group Name: {{$project->project_client}} | Travelling Date From: {{Content::dateformat($project->project_start)}} To: {{Content::dateformat($project->project_end)}}
								</strong></th>
							</tr>
							<tr>
								<th>No.</th>
								<th>Client Name</th>
								<th>Nationality</th>
								<th>Passport Number</th>
								<th>Expired Date</th>
								<th>Date Of Birth</th>
								<th>Shared With</th>
								<th>Dietary</th>
								<th>Allergies</th>
								<th>Mobile Phone</th>
								<th>Arrival Flight</th>
								<th>Departure Flight</th>
							</tr>
							<?php $n = 1; ?>
							@foreach($clientByProject as $key=> $cl)
								
								<tr>
									<td>{{$n++}}</td>
									<td>{{$cl->client_name}}</td>
									<td>{{{ $cl->country->nationality or ''}}}</td>
									<td>{{$cl->passport}}</td>
									<td>{{ isset($cl->expired_date) ? Content::dateformat($cl->expired_date) : ''}}</td>
									<td>{{ isset($cl->date_of_birth) ? Content::dateformat($cl->date_of_birth) : ''}}</td>
									<td>{{$cl->share_with}}</td>
									<td>{{$cl->dietary}}</td>
									<td>{{$cl->allergies}}</td>
									<td>{{$cl->phone}}</td>
									<td>
										{{$cl->flight_arr}}
									</td>
									<td>
										{{$cl->flight_dep}}
									</td>
									
								</tr>
							@endforeach
						</table>
					@endif
				</td>
			</tr>
			<tr>
				<td style="border-top: none"><strong>Remark:</strong><div class="well">{!! $project->project_hight !!}</div></td>
			</tr>
			<tr>			
				<td style="border-top: none">
					Printed On {{date("d F Y")}}
				</td>
			</tr>
			
		</table>		
  	</div>
@endsection
