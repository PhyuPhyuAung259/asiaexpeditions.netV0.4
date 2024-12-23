@extends('layout.backend')
@section('title', $tour->tour_name)
<?php  use App\component\Content;?>
@section('content')
	<div class="container">
    @include('admin.report.headerReport')
		<h3 class="text-right">
			<a class="hidden-print" href="javascript:void(0)" onclick="window.print();"><span class="fa fa-print"></span></a> &nbsp;
		</h3>
		<h4><strong>{{$tour->tour_name}}</strong></h4>
		<div><strong>Destination: {{{$tour->province->province_name or ''}}}</strong> <small><span class="fa fa-exchange"></span></small> <strong>{{$tour->tour_dest}}</strong></div>
		<strong>Itinerary</strong>
		<table class="table">
			<tbody>
				<tr>
					<td style="width: 75%; vertical-align: top;" >
						<div><strong>HightLights</strong></div>
						<p>{!! $tour->tour_intro !!}</p>
					</td>
					<td rowspan="3" style="vertical-align: top;">
						<table class="table">
							<tr>
								<th class="text-center" style="border-top: none;">Tour Pax</th>
								<th class="text-center" style="border-top: none;">Tariff Price</th>
							</tr>							
								@foreach($tour->pricetour as $pax_price)
									@if($type == "selling")
										@if($pax_price->sprice > 0)
											<tr>
												<td class="text-center">{{$pax_price->pax_no}}</td>
												<td class="text-center">{{Content::money($pax_price->sprice)}} <span class="pcolor">{{Content::currency()}}</span></td>
											</tr>
										@endif
									@else
										@if($pax_price->nprice > 0)
											<tr>
												<td class="text-center">{{$pax_price->pax_no}}</td>
												<td class="text-center">{{Content::money($pax_price->nprice)}}<span class="pcolor">{{Content::currency()}}</span></td>	
											</tr>
										@endif
									@endif
								@endforeach
						</table>
					</td>		
				</tr>	
				@if(!empty($tour->tour_desc))
				<tr>
					<td style="vertical-align: top;">
						<div><strong>Description</strong></div>
						<p>{!! $tour->tour_desc !!}</p>
					</td>
				</tr>	
				@endif
				@if(!empty($tour->tour_remark))
				<tr>					
					<td style="vertical-align: top;">
						<div><strong>Services Includes/ Excluded : </strong></div>
						<p>{!! $tour->tour_remark !!}</p>
					</td>
				</tr>
				@endif
				@if($tour->tour_feasility->count() > 0)
				<tr>					
					<td style="vertical-align: top;">
						<div><strong>Tour Feasility</strong></div>
						<ul>
							@foreach($tour->tour_feasility as $ts)
							<li>{{$ts->service_name}}</li>
							@endforeach
						</ul>
					</td>
				</tr>
				@endif
				@if($tour->tour_picture || $tour->tour_photo)
				<tr><td><strong>Galleries</strong></td></tr>
				<tr>
					<?php $photos = explode("|", rtrim($tour->tour_picture,'|')); ?>
					<td>
						<div class="row">
						@if($tour->tour_photo)
						<div class="col-sm-4 col-xs-4" style="padding-right:4px;">
							<div class="form-group">
								<img src="{{Content::urlthumbnail($tour->tour_photo, $tour->user_id)}}" style="width: 100%;" />
							</div>
						</div>
						@endif
						@if($tour->tour_picture != '')
							@foreach($photos as $key => $pic)
								@if($key <= 1)	
									<div class="col-sm-4 col-xs-4" style="padding-right:4px;">
										<div class="form-group">
											<img src="{{Content::urlthumbnail($pic, $tour->user_id)}}" style="width: 100%;" />
										</div>
									</div>
								@endif
							@endforeach
						@endif
						</div>
					</td>
				</tr>
				@endif
			</tbody>
		</table>
  	</div>
@endsection
