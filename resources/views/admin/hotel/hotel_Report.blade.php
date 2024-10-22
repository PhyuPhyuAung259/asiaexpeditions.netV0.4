<?php  use App\component\Content;?>
@extends('layout.backend')
@section('content')
<div class="container">
    @include('admin.report.headerReport')		
	<a href="javascript:void(0)" class="pull-right" onclick="window.print();"><span class="btn btn-primary btn-xs"><i class="fa fa-print"></i></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
	<table class="table table-bordered" id="roomrate">
		@foreach($suppliers as $key => $supplier)
				<tr>
					<td colspan="2" style="position: relative;top: 25px">
						<address style="background: linear-gradient(90deg, rgb(255, 255, 255) 0%, rgb(252, 252, 255) 35%, rgb(229, 229, 229) 100%);">
							<p><b>Hotel Name :</b> {{$supplier->supplier_name}}<br>
							<b>P/H :</b> {{ $supplier->supplier_phone}}/{{$supplier->supplier_phone2}}<br>
							<b>Email :</b> {{$supplier->supplier_email}}<br>
							<b>Address :</b> {{$supplier->supplier_address}}<br>
							<b>Website :</b> {{$supplier->supplier_website}}</p>
						</address>
						{!! $supplier->supplier_intro !!}
					</td>
				</tr>
				<tr>
					<td style="width: 50%; vertical-align:top;">
						@if($supplier->hotel_facility )
							<strong>Hotel Facilities</strong>
							<ul>
								@foreach($supplier->hotel_facility as $key => $hf)
								<li>{{$hf->name}}</li>
								@endforeach
							</ul>
						@endif
					</td> 
					<td style="width: 50%; vertical-align:top;">
						@if($supplier->hotel_category )
							<strong>Hotel Info</strong>
							<ul>
								@foreach($supplier->hotel_category as $key => $hf)
								<li>{{$hf->name}}</li>
								@endforeach
							</ul>
						@endif
					</td>	
				</tr>
				@if($supplier->supplier_pgroup)
					<tr>
						<td colspan="2">
							<strong>Group Policy</strong>
							{!! $supplier->supplier_pgroup !!}</td>
					</tr>
				@endif
				@if($supplier->supplier_pchild)
				<tr>
					<td colspan="2">
						<strong>Child Policy</strong>
						{!! $supplier->supplier_pchild !!}
					</td>
				</tr>
				@endif
				@if($supplier->supplier_pcancelation)
				<tr>
					<td colspan="2">
						<strong>Canceltion Policy</strong>
						{!! $supplier->supplier_pcancelation !!}
					</td>
				</tr>
				@endif
				@if($supplier->supplier_ppayment)
				<tr>
					<td colspan="2">
						<strong>Payment Policy</strong>
						{!! $supplier->supplier_ppayment !!}
					</td>
				</tr>
				@endif
				
				@if($supplier->remak)
					<tr>
						<td colspan="2"> <strong>Remarks:</strong>{!! $supplier->remark !!} </td>
					</tr>
				@endif
		@endforeach
	</table>
</div>
@endsection