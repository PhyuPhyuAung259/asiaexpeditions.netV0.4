<?php  use App\component\Content;?>
<style type="text/css">
	@media print{
		.dep_and_arr{
			width: 12% !important;
		}
		.flightno{
			width: 15% !important;
		}

	}
</style>


	<table width="100%" border="1" bordercolor="#dddddd" cellpadding="0" cellspacing="0" id="roomrate">
	<tr style="background-color: rgb(245, 245, 245);">
		<th style="padding: 2px; width:7%;" class="flightno"><span>Flight No.</span></td>
		<th style="padding: 2px; width:8%;" class="dep_and_arr"><span>DEP & ARr Time</span></th>
		<th style="padding: 2px; width: 11%;"><span>From</span></th>		
		<th style="padding: 2px; width: 11%;"><span>To</span></th>		
		<th style="padding: 2px;"><span>Days</span></th>		
		<th style="padding: 2px;" class="text-center"><span>Ticketing Agent</span></th>		
	</tr>
	@foreach(\App\FlightSchedule::where('supplier_id', $supplier->id)->orderBy('flightno', 'ASC')->get() as $key=>$fy)
	<?php 
	$textcl = ($key % 2 == 1 ? '#ecf2f6': 'rgba(245, 245, 245, 0.26)');
	 ?>
	<tr style="background-color: {{$textcl}}">
		<td><strong>{{$fy->flightno}}</strong></td>
		<td class="pcolor" style="font-size: 12px;">{{$fy->dep_time}} -> {{$fy->arr_time}}</td>
		<td>{{$fy->flight_from}}</td>
		<td>{{$fy->flight_to}}</td>
		<td style="width: 16%;">
		@foreach($fy->weekday as $day)
            <span>{{substr($day->days_name,0,3)}}</span>, 
        @endforeach 
		</td>
		<td>
			@if($fy->flightagent->count() > 0)
			<table class="table table-hover table-condensed table-bordered table-striped" style="margin-bottom:0px;">
				<tr>
					<th><small>Name</small></th>
					<th class="text-right"><small>Oneway</small></th>
					<th class="text-right"><small>Return</small></th>
					<th class="text-right"><small>Oneway Net</small></th>
					<th class="text-right"><small>Return Net</small></th>
					<th class="text-right"><small>Oneway {{Content::currency(1)}}</small></th>
					<th class="text-right"><small>Return {{Content::currency(1)}}</small></th>
				</tr>	
				<body>		
				@foreach($fy->flightagent as $fprice)
					<tr>						
						<td>{{$fprice->supplier_name}}</td>
						<td class="text-right pcolor">{{$fprice->pivot->oneway_price}}</td>
						<td class="text-right pcolor">{{$fprice->pivot->return_price}}</td>	
						<td class="text-right pcolor">{{$fprice->pivot->oneway_nprice}}</td>
						<td class="text-right pcolor">{{$fprice->pivot->return_nprice}}</td>
						<td class="text-right pcolor">{{$fprice->pivot->oneway_kprice}}</td>
						<td class="text-right pcolor">{{$fprice->pivot->return_kprice}}</td>
					</tr>
				@endforeach
				</body>
			</table>
			@endif
		</td>
	</tr>
	@endforeach
	@include('admin.report.supplier_info')
</table>