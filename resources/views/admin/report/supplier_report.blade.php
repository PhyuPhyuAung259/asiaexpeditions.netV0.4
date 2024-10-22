<?php use App\component\Content; ?>
@extends('layout.backend')
@section('title', 'Agent Report')
@section('content')
<div class="container">
	<div class="row">@include('admin.report.headerReport')</div> 
	<div class="row text-center">
			<h2>Report By Agents By {{Content::dateformat($fromDate)}} -> {{Content::dateformat($endDate)}}</h2>
  	  	<form method="get">
  	  		<div class="col-md-7 col-md-offset-2">
	            <div class="input-group">
		            <input type="text" name="fromDate" class="form-control text-center" id="from_date" value="{{{$fromDate or '' }}}" readonly>
		            <span class="input-group-addon" id="basic-addon3">From To</span>
		            <input type="text" name="endDate" class="form-control text-center" id="to_date" value="{{{$endDate or ''}}}" readonly>
	            </div>
	        </div>
	        <div class="col-md-1"><button type="submit" class="btn btn-flat btn-acc">Search</button></div>
  	  	</form>
    </div>
    <br>
	<table class="table">
		<?php 
			$grandTotalSelling = 0;
			$grandTotalCOSales = 0;
			$grandTotalGrossPL = 0;
		?>
		@if($supplier->count() > 0)
		@foreach($supplier as $key => $sup)
			<?php 
				$projectBooked = $sup->project()->whereNotIn('project_fileno', ['', 'null', 0])->whereBetween('project_start', [$fromDate, $endDate])->orderBy('project_fileno');			
				$totalSelling = 0;
				$totalCostOfSales = 0;
				$totalGrossPL = 0;
			?>
			<tr>
				<th style="border-top: none; border-bottom: solid 1px; padding-left: 0" colspan="{{$key == 0 ? 2 : 7}}">
					<h4 style="margin-bottom: 0px;">
						<strong>{{$sup->supplier_name}}</strong> <small>[<i>No. Of Files : {{$projectBooked->count()}}</i>]</small>
						<small>[<i>Total Pax Numer: {{$projectBooked->sum("project_pax")}}</i>]</small>
					</h4>
				</th>	
				@if($key == 0 )
					<th style="border-top: none; border-bottom: solid 1px; padding-bottom: 0px">Travelling Date</th>
					<th style="border-top: none; border-bottom: solid 1px; padding-bottom: 0px">Sales</th>
					<th style="border-top: none; border-bottom: solid 1px; padding-bottom: 0px">Cost Of Sales</th>
					<th style="border-top: none; border-bottom: solid 1px; padding-bottom: 0px">Gross Profit</th>
				@endif
			</tr>
				@foreach($projectBooked->get() as $key => $pj)
					<?php 
						// get Selling Total
						$hotelBook  = App\HotelBooked::where('project_number', $pj->project_number);
						$cruiseBook = App\CruiseBooked::where('project_number', $pj->project_number);
						$tourBook   = App\Booking::tourBook($pj->project_number);
						$flightBook = App\Booking::flightBook($pj->project_number);
						$golfBook   = App\Booking::golfBook($pj->project_number);
						$grandtotal = $cruiseBook->sum('sell_amount') + $golfBook->sum('book_amount') + $flightBook->sum('book_amount') + $hotelBook->sum('sell_amount') + $tourBook->sum('book_amount');
						if (empty((int)$pj->project_selling_rate)) {
							$Project_total = $grandtotal;
						}else{
							$Project_total =  $pj->project_selling_rate;
						}

						 // get Cost of Sale Amount 
						$restBook = App\BookRestaurant::where(['project_number'=>$pj->project_number, 'status'=>1]);
						$EntranceBook = App\BookEntrance::where(['project_number'=>$pj->project_number, 'status'=>1]);
						$transportkBook = App\BookTransport::where(['project_number'=>$pj->project_number, 'status'=>1]); 
						$guidBook  = App\BookGuide::where(['project_number'=>$pj->project_number, 'status'=>1]); 
						$miscBook = App\BookMisc::where('project_number',$pj->project_number);

						$grandtotalCOS = ($hotelBook->sum('net_amount') + $flightBook->sum('book_namount') + $golfBook->sum('book_namount') + $cruiseBook->sum('net_amount') + $restBook->sum('amount') + $EntranceBook->sum('amount')) + $transportkBook->sum('price') + $guidBook->sum('price') + (int)$miscBook->sum('amount');
						$grandktotal = ($flightBook->sum('book_kamount') + $golfBook->sum('book_nkamount') + $restBook->sum('kamount') + $EntranceBook->sum('kamount')) + $transportkBook->sum('kprice') + $guidBook->sum('kprice') + $miscBook->sum('kamount');
						// Operation Total
						$getExRate = $pj->project_ex_rate > 0 ? $grandktotal / $pj->project_ex_rate: 0;
						$totalCostOfSale = $pj->project_net_price > 0 ? $pj->project_net_price : ($getExRate + $grandtotalCOS);
						// Gross Profit
						$grossPL = ($Project_total - $totalCostOfSale);
						$totalGrossPL = $totalGrossPL + $grossPL;

						$totalSelling = $totalSelling + $Project_total;
						$totalCostOfSales = $totalCostOfSales + $totalCostOfSale;

						$grandTotalSelling = $grandTotalSelling + $totalSelling;
						$grandTotalCOSales = $grandTotalCOSales + $totalCostOfSales;
						$grandTotalGrossPL = $grandTotalGrossPL + $totalGrossPL;

					?>
					<tr>
						<td style="padding-left: 8px">{{$pj->project_prefix}}-{{$pj->project_fileno}}  </td>
						<td>{{$pj->project_client}} <b> / </b>{{$pj->project_pax}} Pax</td>
						<td>{{Content::dateformat($pj->project_start) }} -> {{Content::dateformat($pj->project_end)}}</td>
						<td class="text-right">{!! $Project_total > 0 ? Content::money($Project_total) : "<span style='color: red'>".number_format($Project_total, 2)."</span>" !!}</td>
						<td class="text-right">{!! $totalCostOfSale > 0 ? Content::money($totalCostOfSale) : "<span style='color: red'>".number_format($totalCostOfSale,2)."</span>" !!}</td>
						<td class="text-right">{!! $grossPL > 0 ? Content::money($grossPL) : "<span style='color: red'>".number_format($grossPL,2)."</span>" !!}</td>
					</tr>
					
				@endforeach
				<tr>
					<th colspan="3"></th>
					<th class="text-right">{!! $totalSelling > 0 ? Content::money($totalSelling) : "<span style='color: red'>".number_format($totalSelling,2)."</span>" !!}</th>

					<th class="text-right">{!! $totalCostOfSales > 0 ? Content::money($totalCostOfSales) : "<span style='color: red'>".number_format($totalCostOfSales,2)."</span>" !!}</th>
					<th class="text-right"> {!! $totalGrossPL > 0 ? Content::money($totalGrossPL) : "<span style='color: red'>".number_format($totalGrossPL,2)."</span>" !!}</th>
				</tr>
		@endforeach
			<tr>
				<td colspan="6" class="text-right">
					<div style="padding: 8px; font-size: 18px">Total Of Sales : <b>
						{!! $grandTotalSelling > 0 ? Content::money($grandTotalSelling) : "<span style='color: red'>".number_format($grandTotalSelling,2)."</span>" !!}</b></div>
					<div style="padding: 8px; font-size: 18px">Total Cost Of Sales: <b>
						{!! $grandTotalCOSales > 0 ? Content::money($grandTotalCOSales) : "<span style='color: red'>".number_format($grandTotalCOSales,2)."</span>" !!}
					</b></div>
					<div style="padding: 8px; font-size: 18px">Total Gross Profit : <b>
						{!! $grandTotalGrossPL > 0 ? Content::money($grandTotalGrossPL) : "<span style='color: red'>".number_format($grandTotalGrossPL,2)."</span>" !!}</b></div>
				</td>
			</tr>
		@else 
			<tr>
				<td>No record found</td>
			</tr>
		@endif
	</table>
</div>
@include('admin.include.datepicker')
@endsection

