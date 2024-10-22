@extends('layout.backend')
@section('title', 'Package Cost Based on Number of Pax')
<?php 
	use App\component\Content;
	$comId = Auth::user()->company_id ? Auth::user()->company_id: 1;
	$comadd = \App\Company::find($comId);
	$grandTotalSingle = 0;
	$grandTotalGroup = 0;	
?>
@section('content')
	<div class="container">
    @include('admin.report.project_header')	
    	<div class="pull-right hidden-print">
			<a href="javascript:void(0)" class="btn btn-xs btn btn-primary" onclick="window.print();"><span class="fa fa-print "></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
		</div><div class="clearfix"></div>
		<h3 class="text-center"><strong class="btborder" style="text-transform:uppercase;">Package Cost Based on Number of Pax</strong></h3><br>
		@if($preview_quot)
			@if($project->project_hight)
			<div class="form-group">{!! $project->project_hight !!}</div>
			@endif
			<table class="table">
				<tr>
					<th style="border-top:0px;">Date</th>
					<th style="border-top:0px;">Service</th>
				</tr>
				<tbody>
					@if($booking->count() > 0)
						@foreach($booking as $book)
						<tr>
							<td width="100px" style="vertical-align: top;"><h5><b>{{date('d M Y', strtotime($book->book_checkin))}}</b></h5>{{date('l', strtotime($book->book_checkin))}}</td>
							<td style="vertical-align: top;">					
							@if(!empty($book->book_checkin) && !empty($book->book_project))
								<?php 
									$getBook = App\Booking::where(['book_project'=>$book->book_project, 'book_status'=>1, 'book_option'=>1])
										->whereDate('book_checkin', $book->book_checkin)->orderBy("book_checkin", "DESC")->get();
								?>
								@foreach($getBook as $prow)
									<?php 
										$bhotel = App\HotelBooked::where(['book_id'=>$prow->id, 'project_number'=>$prow->book_project, 'option'=>1])->first();
										$bcruise = App\CruiseBooked::where(['book_id'=> $prow->id, 'project_number'=> $prow->book_project, 'option'=>1])->first();
									?>
									<table style="width: 100%;">
										<tr>
											@if(isset($prow->tour->tour_name))
											<td>
												<div><b>{{{ $prow->tour->tour_name or '' }}}</b>,</div>
												@if($prow->book_tour_details)
													{!! $prow->book_tour_details !!}
												@else
													{!! $prow->tour->tour_desc !!}
												@endif
												<?php $tour = App\Tour::find($prow->tour_id); ?>
												@if($tour->tour_feasility->count() > 0)
													<div><strong>Tour Facilities:</strong>
														@foreach($tour->tour_feasility as $ts)
															<span>{{$ts->service_name}}</span>,    
														@endforeach 
													</div>
												@endif<br>
												<table style="width: 100%;">
													@if($tour->tour_picture || $tour->tour_photo)
													<tr>
														<td>
															<div class="row">
																
																@if(Content::urlthumbnail($tour->tour_photo, $tour->user_id) != "/img/no_image.png")
																<div class="col-sm-4 col-xs-4" style="padding-right:4px;">
																	<div class="form-group">
																		<img src="{{Content::urlthumbnail($tour->tour_photo, $tour->user_id)}}" style="width: 100%;" />
																	</div>
																</div>
																@endif

																@if($tour->tour_picture)
																	<?php 
																	$photos = explode("|", rtrim($tour->tour_picture,'|')); ?>
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
												</table>
											</td>
											@endif

											@if(isset($prow->golf->supplier_name))
											<td>
												<div><b style="text-transform: capitalize;">Golf : {{{$prow->golf->supplier_name or ''}}},  {{{$prow->golf_service->name or ''}}}, Pax: {{$prow->book_pax}}, Tee Time: {{$prow->book_golf_time}}</b></div>
											</td>
											@endif

											@if(isset($prow->flight->flightno))
											<td>
												<div><b>Flight : {{{ $prow->flight->flightno or '' }}},  D:{{{$prow->flight->dep_time or ''}}} - A:{{{$prow->flight->arr_time or ''}}}, {{{ $prow->flight->flight_from or '' }}} -> {{{ $prow->flight->flight_to}}} </b> </div>							
											</td>
											@endif
											<?php
												$getHotelBook = App\Booking::where(['book_project'=>$book->book_project, 'book_status'=>1, 'book_option'=>1, 'hotel_id'=>$prow->hotel_id])
												->whereDate('book_checkin', $book->book_checkin)
												->whereIn('book_quot_hotel_option', $hotelCheck)
												->orderBy("book_checkin", "DESC")->get();
											?>
											@if(isset($prow->hotel->supplier_name) && $getHotelBook->count() > 0)
												@foreach($getHotelBook as $key=>$hb )
													<td><b>{{{ $hb->hotel->supplier_name or '' }}} | {{{ $bhotel->room->name or '' }}} </b><b>| Check-In:</b> {{ Content::dateformat($bhotel['checkin']) }}<b> -> Check-out:</b> {{ Content::dateformat($bhotel['checkout']) }}	</td>
												@endforeach
											@endif

											@if(isset($prow->cruise->supplier_name))
											<td><b>{{{$prow->cruise->supplier_name or '' }}}, {{{$bcruise->program->program_name or '' }}}, Room: {{{ $bcruise->room->name or '' }}}, </b></td>
											@endif
										</tr>
									</table>
								@endforeach
							@endif
							</td>
						</tr>
						@endforeach
					@endif
				</tbody>
			</table>
		@endif
			@if($project->count() > 0)
				<?php 
					$hotelBooked = App\Booking::where(['book_status'=>1, 'book_project'=>$project->project_number])
							->whereHas('hotelbooked')
							->whereIn('book_quot_hotel_option', $hotelCheck)->groupBy('book_quot_hotel_option')->get();
						$singleSuplement 	= 0;
						$TrippleRoomTotal 	= 0;
					 	$totalSinglePrice 	= 0;

						$totalsingle 	= 0;
						$totalTwin 		= 0;
						$totalExBed 	= 0;
					?>
					@if($hotelBooked->count() > 0)
						@foreach($hotelBooked as $key => $bhotel)
							<?php
								$option = ['', 'Option 1', 'Option 2', 'Option 3', 'Option 4', 'Option 5', 'Option 6', 'Option 7','Option 8'];
								$hotelBook = App\HotelBooked::where(['project_number'=>$project->project_number,'status'=>1, 'option'=>1, 'book_option'=>$bhotel->book_quot_hotel_option])->orderBy("checkin", "ASC");
							?>							
							
							@foreach($hotelBook->get() as $hotel)				
								<?php 
									$single 	= $hotel->ssingle * $hotel->book_day; 
									$twin 		= $hotel->stwin * $hotel->book_day;
									$exBed 		= $hotel->sextra * $hotel->book_day;
									$totalsingle 	= $totalsingle + $single;
									$totalTwin 		= $totalTwin + $twin;
									$totalExBed 	= $totalExBed + $exBed;
								?>
							@endforeach
							<?php 
								$singleSuplement 	= $singleSuplement + ($totalTwin / 2);
							 	$totalSinglePrice 	= $totalSinglePrice + $totalsingle;
							?>	
						@endforeach
					@endif

				<?php $flightBook = App\Booking::flightBook($project->project_number, 1) ?>
				@if($flightBook->count() > 0)
					<?php $totalSinglePrice = $totalSinglePrice + $flightBook->sum('book_price'); ?>
					<?php $singleSuplement  = $singleSuplement + $flightBook->sum('book_price'); ?>
				@endif
				<!-- end flight  -->
				<?php $golfBook = App\Booking::golfBook($project->project_number, 1) ?>
				@if($golfBook->count() > 0)
					<?php $totalSinglePrice = $totalSinglePrice + $golfBook->sum('book_price'); ?>
					<?php $singleSuplement  = $singleSuplement + $golfBook->sum('book_price'); ?>
				@endif
				<!-- end golf  -->		

				<?php $cruiseBook = App\CruiseBooked::where(['project_number'=> $project->project_number,'status'=>1,'option'=>1]); ?>
				@if($cruiseBook->count() >0)
					<?php 
						$totalsingleCruise = 0;
						$totalTwinCruise = 0;
						$totalExBedCruise = 0;
					?>
					@foreach($cruiseBook->get() as $crp)			
						<?php
							$CrSingle = $crp->ssingle * $crp->book_day; 
							$CrTwin 	= $crp->stwin * $crp->book_day;
							$CRexBed 	= $crp->sextra * $crp->book_day;
							$totalsingleCruise 	= $totalsingleCruise + $CrSingle;
							$totalTwinCruise 	= $totalTwinCruise + $CrTwin;
							$totalExBedCruise 	= $totalExBedCruise + $CRexBed;
						?>
					@endforeach

					<?php 
					$singleSuplement = $singleSuplement + ($totalTwinCruise / 2);
					$totalSinglePrice =  $totalsingleCruise; ?>
				@endif
				<!-- End cruise  -->

				<?php  	
					$tourBook = App\Booking::tourBook($project->project_number, 1);
					$tourMaxPrice = App\Booking::tourMaxPrice($project->project_number, $tourCheck);
				?>
				@if( $tourBook->get()->count() > 0 && count($tourCheck) > 1)	
					<div><strong style="text-transform: uppercase;"> EXCURSION OVERVIEW</strong></div>
					<table class="table">
						<tr style="background-color:#f4f4f4;">
							<?php $paxTotal = array('0'=>0,'1'=>0,'2'=>0, '3'=>0, '4'=>0, '5'=>0,'6'=>0,'7'=>0, '8'=>0,'9'=>0, '10'=>0, '11'=>0, '12'=>0, '13'=>0, '14'=>0, '15'=>0, '16'=>0, '17'=>0, '18'=>0, '19'=>0, '20'=>0, '21'=>0, '22'=>0, '23'=>0, '24'=>0, '25'=>0, '26'=>0, '27'=>0, '28'=>0, '29'=>0, '30'=>0);
							?>
							<th>Price Per Person In USD</th>
							@if($tourMaxPrice)
								@foreach($tourMaxPrice as $key => $val) 
									<th class="text-right">{{$val}} Pax </th>
								@endforeach
							@endif
						</tr>
						@foreach($tourBook->get() as $key => $tour)
							<?php
							
								$tprice = App\TourPrice::where('tour_id', $tour->tour_id)->whereIn('pax_no', $tourCheck)->get();
							?>
							<!-- <tr>
								<th></th> -->
								@foreach($tprice as $key=>$pr)
									<?php $paxTotal[$pr->pax_no] = $paxTotal[$pr->pax_no] + $pr->sprice?>
								@endforeach
							<!-- </tr>		 -->
						@endforeach
						<tr>
							<th class="text-right">Land Cost Per Person:</th>
							@foreach($tourMaxPrice as $key => $val) 
								<th class="text-right">									
									{{Content::money($paxTotal[$val])}}
								</th>
							@endforeach
						</tr>
						<tr>
							<th class="text-right">Package Price:</th>
							@foreach($tourMaxPrice as $key => $val) 
								<th class="text-right">				
									@if($val == 1 )					
										{{Content::money($paxTotal[$val] + $totalSinglePrice)}}
									@else
										{{Content::money($paxTotal[$val] + $singleSuplement)}}
									@endif
								</th>
							@endforeach
						</tr>

						<tr>
							<td class="text-right">
								<b>Single Supplement:</b>
							</td>
							<td class="text-right">
								@if($totalSinglePrice > 0)
									<b>{{Content::money($totalSinglePrice)}}</b>
								@endif
							</td>
						</tr>
						<tr>
							<td class="text-right"><b>Shared Twin PP:</b></td>
							<td class="text-right">
								@if($singleSuplement > 0)
									<b>{{Content::money($singleSuplement)}}</b>
								@endif
							</td>
						</tr>
					</table>
				@endif
			@endif<br/><br />
			<?php 
				$hotelReport = App\HotelBooked::where(['project_number'=>$project->project_number,'status'=>1, 'option'=>1])
				->whereIn('book_option', $hotelCheck)
				->groupBy('hotel_id')->orderBy("checkin", "ASC")->get();
			?>
			@if($hotelReport->count() >0)
				<h5 class="text-center"><strong>HOTEL INFORMATION ON YOUR PROGRAM</strong></h5>
				<table class="table">
					@foreach($hotelReport as $hotel)
						<?php $sup = App\Supplier::find($hotel->hotel_id); ?>
						<tr>
							<td width="330px;">
								<img src="{{Content::urlthumbnail($sup->supplier_photo, $sup->user_id)}}" class="img-responsive">
							</td>
							<td style="vertical-align:top;">
								<h5 style="text-transform: capitalize;"><strong>{{ $sup->supplier_name }} | <a href="javascript:void(0)">{{{ $sup->province->province_name or ''}}}</a>, <a href="javascript:void(0)">{{{ $sup->country->country_name or ''}}}</a> </strong></h5>
								{!! str_limit($sup->supplier_intro,1350) !!}
							</td>
						</tr>
					@endforeach
				</table>
			@endif
  	</div>
@endsection
