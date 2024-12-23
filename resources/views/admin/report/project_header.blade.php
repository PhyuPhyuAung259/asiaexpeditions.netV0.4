<?php $comadd = \App\Company::find(1); ?>
@if(isset($project))
<table class="table" style="margin-bottom: 0px;">
	<tr>
		<td style="border-top: none; border-bottom: 1px solid #f4f4f4; padding-bottom: 0px;">
			
			<div>File/ Project : <b>{{{ $project->project_prefix or ''}}}-{{ isset($project->project_fileno) ? $project->project_fileno: $project->project_number}} </b></div>
			<div>Pax Number : <b>{{{ $project->project_pax or ''}}}</b></div>
			<div>Client Name/s : <b>{{{ $project->project_client or ''}}}</b></div>
			<div>Travelling Date : <b>{{date("d-M-Y", strtotime($project->project_start))}} - {{date("d-M-Y", strtotime($project->project_end))}}</b></div>
			<div>
				@if($project->flightArr)
					<span>Flight No./Arrival : <b>{{{ $project->flightArr->flightno or ''}}} - {{{ $project->flightArr->arr_time or '' }}}</b></span>, 
				@endif
				@if($project->flightDep)
				<span>Flight No./Departure : <b>{{{ $project->flightDep->flightno or ''}}} - {{{ $project->flightDep->dep_time or '' }}}</b></span>
				@endif
			</div>
			<div>
				<span>Travel Consultant :<b>{{$project->project_book_consultant}}</b></span>, 
				<span>Reference No.: <b>{{$project->project_book_ref}}</b></span>
			</div>
		</td>
		<td class="text-right" width="150px" style="border-top: none; border-bottom: 1px solid #f4f4f4; padding-bottom: 0px;">
			<img src="{{url('storage/avata/'. $comadd['logo'])}}" style="width: 100%;">
		</td>
	</tr>
</table> 
<div class="pull-right hidden-print" id="preview_layout" style="color: #009688; cursor: pointer;">
	<input type="hidden" name="preview-type" value="standard">
	<p>Wide View <i class="fa fa-reply"></i></p>
</div>
<div class="clearfix"></div>
@endif