<?php  use App\component\Content;?>
<table class="table table-bordered" id="roomrate">
	@if($priceType  == "contract" )
		<tr>
			<tr>
				<td colspan="2">
					<address>
						<p><b>Hotel Name :</b> {{$supplier->supplier_name}}<br>
						<b>P/H :</b> {{ $supplier->supplier_phone}}/{{$supplier->supplier_phone2}}<br>
						<b>Email :</b> {{$supplier->supplier_email}}<br>
						<b>Address :</b> {{$supplier->supplier_address}}<br>
						<b>Website :</b> {{$supplier->supplier_website}}</p>
					</address>
					{!! $supplier->supplier_intro !!}</td>
			</tr>
			<td style="width: 50%; vertical-align:top;">
				<strong>Hotel Facilities</strong>
				<ul>
					@if($supplier->hotel_facility )
						@foreach($supplier->hotel_facility as $key => $hf)
						<li>{{$hf->name}}</li>
						@endforeach
					@endif
				</ul>
			</td> 
			<td style="width: 50%; vertical-align:top;">
				<strong>Hotel Info</strong>
				<ul>
					@if($supplier->hotel_category )
						@foreach($supplier->hotel_category as $key => $hf)
						<li>{{$hf->name}}</li>
						@endforeach
					@endif
				</ul>
			</td>	
			@if($supplier->supplier_term_condition)
				<tr>
					<td colspan="2">
						<strong>Terms & Conditions</strong>
						{!! $supplier->supplier_term_condition !!}</td>
				</tr>
			@endif
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
					{!! $supplier->supplier_pchild !!}</td>
			</tr>
			@endif
			@if($supplier->supplier_pcancelation)
			<tr>
				<td colspan="2">
					<strong>Cancellation Policy</strong>
					{!! $supplier->supplier_pcancelation !!}</td>
			</tr>
			@endif
			@if($supplier->supplier_ppayment)
			<tr>
				<td colspan="2">
					<strong>Payment Policy</strong>
					{!! $supplier->supplier_ppayment !!}</td>
			</tr>
			@endif
			<tr>
				<td colspan="2"> <strong>Remarks:</strong>{!! $supplier->remark !!} </td>
			</tr>
		</tr>
	@else
		<tr style="background-color: rgb(245, 245, 245);">
			<th style="padding:2px;"><span>RoomType</span></td>
			<th style="padding:2px;"><span>From Date</span>  <span class="fa fa-long-arrow-right" style="top: 1px; position: relative;"></span> <span>To Date</span></th>
			@if($priceType != "selling")
				@foreach(\App\RoomCategory::where('status',1)->orderBy('id','ASC')->get() as $cat)
				<th title="{{$cat->name}}" style="padding: 2px;" class="text-right"><span>{{$cat->name}}</span></th>
				@endforeach
			@else
				@foreach(\App\RoomCategory::where('status',1)->take(5)->orderBy('id','ASC')->get() as $cat)
				<th title="{{$cat->name}}" style="padding: 2px;" class="text-right"><span>{{$cat->name}}</span></th>
				@endforeach
			@endif
		</tr>			 
		@foreach($supplier->room as $room)
			@if(isset($fmonth) != "" && isset($tmonth) != "" && isset($year) != "")
				<?php
				$getRate = App\RoomRate::where(['supplier_id'=>$supplier->id, 'room_id'=>$room->id])
									->whereMonth('start_date', '>=', $fmonth)
									->whereMonth('start_date', '<=', $tmonth)
									->whereYear('start_date', $year)
									->orderBy('start_date','ASC')->get();
					// dd($getRate);
				?>
			@else
				<?php
				$getRate = App\RoomRate::where(['supplier_id'=>$supplier->id, 'room_id'=>$room->id])->orderBy('start_date','ASC')->get();
				?>
			@endif
			<tr>
				<td style="vertical-align: middle;" colspan="{{$getRate->count()== 0? '12':''}}" rowspan="{{$getRate->count() +1}}"><b>{{$room->name}}</b></td>
			</tr>
			@foreach($getRate as $rate)
			<tr>
				<td style="font-size:12px;" class="date-font">{{Content::dateformat($rate->start_date)}} <i class="pcolor">-></i> {{Content::dateformat($rate->end_date)}}</td>
				<td class="text-right">{{Content::money($rate->ssingle)}} <small class="pcolor">{{$rate->ssingle>0?Content::currency():''}}</small></td>
				<td class="text-right">{{Content::money($rate->stwin)}} <small class="pcolor">{{$rate->stwin>0?Content::currency():''}}</small></td>
				<td class="text-right">{{Content::money($rate->sdbl_price)}} <small class="pcolor">{{$rate->sdbl_price>0?Content::currency():''}}</small></td>
				<td class="text-right">{{Content::money($rate->sextra)}} <small class="pcolor">{{$rate->sextra>0?Content::currency():''}}</small></td>
				<td class="text-right">{{Content::money($rate->schexbed)}} <small class="pcolor">{{$rate->schexbed>0?Content::currency():''}}</small></td>
				@if($priceType != "selling" )
					<td class="text-right">{{Content::money($rate->nsingle)}} <small class="pcolor">{{$rate->nsingle>0?Content::currency():''}}</small></td>
					<td class="text-right">{{Content::money($rate->ntwin)}} <small class="pcolor">{{$rate->ntwin>0?Content::currency():''}}</small></td>
					<td class="text-right">{{Content::money($rate->ndbl_price)}} <small class="pcolor">{{$rate->ndbl_price>0?Content::currency():''}}</small></td>
					<td class="text-right">{{Content::money($rate->nextra)}} <small class="pcolor">{{$rate->nextra>0?Content::currency():''}}</small></td>
					<td class="text-right">{{Content::money($rate->nchexbed)}} <small class="pcolor">{{$rate->nchexbed>0?Content::currency():''}}</small></td>
				@endif
			</tr>
			@endforeach				
		@endforeach
		@include('admin.report.supplier_info')
	@endif	
</table>