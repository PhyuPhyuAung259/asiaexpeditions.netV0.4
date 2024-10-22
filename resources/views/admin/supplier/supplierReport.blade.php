@extends('layout.backend')
@section('title', $supplier->supplier_name)
<?php  use App\component\Content;
	if ($type == 'hotels') {
		$title = 'hotel';
	}elseif ($type == 'golf') {
		$title = 'Golf';
	}elseif ($type == 'flights') {
		$title = 'flights';
	}elseif ($type == 'cruises') {
		$title = 'Cruises';
	}
	elseif ($type == 'restaurants') {
		$title = 'Restaurants';
	}
	elseif ($type == 'transport') {
		$title = 'Transport';
	}
	elseif ($type == 'tour-guide') {
		$title = 'Guide';
	}else{
		$title = '';
	} 

	if ($priceType == "contract") {
		$reportType = "Information";
	}else {
		$reportType = "Tariff";
	}
?>
@section('content')
	<div class="col-lg-12">
    @include('admin.report.headerReport')		
		<h3 class="text-center"><strong class="btborder" style="text-transform: uppercase;">{{$title}} {{$reportType}}</strong></h3>
		<div class="col-md-12">
			<div class="pull-left">
				<h4 style="text-transform: capitalize;"><strong>{{{ $supplier->country->country_name or '' }}} <i class="fa fa fa-angle-double-right"></i> {{{$supplier->province->province_name or ''}}} <i class="fa fa fa-angle-double-right"></i>  {{$supplier->supplier_name}} </strong></h4>
			</div>
			@if($priceType != "contract" )
				@if($type == "hotels" )
				<div class="col-md-7 pull-center hidden-print">
					<form action="/{{$currentAction}}?type={{{ $priceType or ''}}}" method="POST">
						{{csrf_field()}}
						<div class="col-md-12" style="padding-right: 0px;">
							<div class="col-md-6">
								<div class="pull-left"><label style="position:relative;top:8px;">Month</label></div>
								<div class="input-group">
									<select class="form-control input-sm" name="fmonth">
								  		<option value="">--Date--</option>
								  		<?php 
								  			$months = ["January","February","March", "April","May","June","July","August", "September","October","November", "December"];
								  		?>
								  		@foreach($months as $key => $m)
								  			<option value="{{$key+1}}" {{(isset($fmonth)?$fmonth:'') == $key+1 ?'selected':''}}>{{$m}}</option>
								  		@endforeach
								  	</select>
								    <span class="input-group-addon">From & To</span>
								    <select class="form-control input-sm" name="tmonth">
								  		<option value="">--Date--</option>
								  		
								  		@foreach($months as $key => $m)
								  			<option value="{{$key+1}}" {{(isset($tmonth)?$tmonth:'') == $key+1 ?'selected':''}}>{{$m}}</option>
								  		@endforeach
								  	</select>
								</div>
							</div>
						  	<div class="col-md-3">
							  	<div class="pull-left"><label style="position:relative;top:8px;">Year</label></div>
							  	<div class="pull-right">
								  	<select class="form-control input-sm" name="year">
								  		<?php $plusYear = date('Y', strtotime('+10 years'));  ?>
								  		<option value="">---Years---</option>
								  		@for($y = 2015; $y <= $plusYear; $y++)
								  			<option value="{{$y}}" {{(isset($year)?$year == $y:'') ? 'selected':''}}>{{$y}}</option>
								  		@endfor
								  	</select>
								</div>
								<div class="clearfix"></div>
							</div>	
							<div class="col-md-2" style="padding-left: 0px;">
								<label><br></label>
								<button class=" btn btn-default btn-sm active">Query</button>
							</div>						
						</div>
						
					</form>
				</div>
				@endif		
			@endif	
			<div class="pull-right hidden-print">
				<!-- <h3>/ -->
					<a href="javascript:void(0)" onclick="window.print();"><span class="fa fa-print btn btn-primary"> Print</span></a>&nbsp;&nbsp;&nbsp;&nbsp;
				<!-- 	<a class="hidden-print" href="{{route('getDownload',['id'=> $supplier->id])}}"><span class="fa fa-cloud-download"></span></a> -->
				<!-- </h3> -->
			</div>
		</div>
		@if($type == "hotels")
			@include('admin.report.hotel_report')
		@elseif( $type == "golf")
			@include('admin.report.golf_report')
		@elseif($type == "flights")
			@include('admin.report.flight_report')
		@elseif($type == "restaurants")
			@include('admin.report.restaurant_report')
		@elseif($type == "transport")
			@include('admin.report.transport_report')
		@elseif($type == "tour-guide")
			@include('admin.report.guide_report')
		@elseif($type == "RestaurantInfo")
			@include('admin.report.restaurant_information')
		@endif
		


  	</div>
  	@include('admin.include.datepicker')
@endsection
