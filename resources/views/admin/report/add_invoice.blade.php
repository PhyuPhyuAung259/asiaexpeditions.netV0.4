@extends('layout.backend')
@section('title', 'INVOICE')
<?php 
	use App\component\Content;
	$comadd = \App\Company::find(1);
?>
@section('content')
<div class="container">
    @include('admin.report.headerReport')		
		<h3 class="text-center"><strong class="btborder" style="text-transform:uppercase;">ADDITIONAL INVOICE</strong></h3>
		<table style="width: 100%;">
			<td style="width: 80%;">				
				<div class="well">
					<div><b>Invoice To:</b> {{ $project->supplier_id == 502 ? $project->project_client :  $project->supplier->supplier_name }}</div>
					<div><b>Address:</b> {{{ $project->supplier->supplier_address or ''}}}</div>
					<div><b>Phone:</b> {{{ $project->supplier->supplier_phone or ''}}} / {{{ $project->supplier->supplier_phone2 or ''}}}</div>
				</div>
			</td>
			<td class="text-center" valign="top">
				<h4>{{$project->project_prefix}}-{{ $project->project_fileno != null ? $project->project_fileno: $project->project_number}}</h4>
				{{date('d-M-Y')}}
			</td>
		</table>
		<div class="clearfix"></div>
		<div><b>Client Name: </b>{{$project->project_client}}, <b>Reference: </b>{{$project->project_book_ref}}, <b>Travel Consultant :</b>{{$project->project_book_consultant}}</div>
	
	<table class="table table-striped" style="border: solid 1px #c4c2c2;">
		<tr>
			<th width="10px">No.</th>
			<th style="width: 80%;" class="text-center">Descriptions</th>
			<th class="text-right">Amount</th>
		</tr>
		<tr>
			<td valign="top">1</td>
			<td>{!! $project->project_add_desc !!}</td>
			<td class="text-right"><b>{{ Content::money($project->project_add_invoice) }} {{Content::currency()}}</b></td>
		</tr>
		<tr style="background: white;">

			<td class="text-right" colspan="3"><strong style="text-transform:uppercase;">Grand Total:</strong>
				<b>{{ Content::money($project->project_add_invoice) }} {{Content::currency()}}</b>
			</td>
		</tr>
	</table>
	<table cellpadding="12" cellspacing="5" style="width: 100%;">
		<tbody>
			<td style="width: 80;">
				<?php 
					$bankId = isset($project->project_bank)? $project->project_bank:3;
					$badd = App\Bank::find($project->project_bank);
				?>
				<p><b>Your payment with:</b></p>
				{!! $badd->details or '' !!}
				<br>
				<p><b>Note:</b><br>
					Please note that all bank charges must be paid by sender. Thank you.
				</p>
			</td>
			<td class="text-center" valign="middle">
				<p>
					x x x x x <br>
					Win Zaw<br>
					Managing Director<br>
					Asia Expeditions Co.,Ltd.<br>
				</p>
			</td>
		</tbody>
	</table>
</div><br><br>
@endsection
