@extends('layout.backend')
@section('title', 'Project Quotation')
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
		<h3 class="text-center"><strong class="btborder" style="text-transform:uppercase;">Proposal</strong></h3><br>
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
												<div><b>{{{ $prow->tour->tour_name or '' }}}</b>, </div>
												@if($prow->book_tour_details)
													{!! $prow->book_tour_details !!}
												@else
													{!! $prow->tour->tour_desc !!}
												@endif
												<?php										
												$tour = \App\Tour::find($prow->tour_id);
												?>
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
												<div><b style="text-transform: capitalize;">Golf : {{{$prow->golf->supplier_name or ''}}},   {{{$prow->golf_service->name or ''}}}, Pax: {{$prow->book_pax}}, Tee Time: {{$prow->book_golf_time}}</b> </div>									
											</td>
											@endif

											@if(isset($prow->flight->flightno))
											<td>
												<div><b>Flight : {{{ $prow->flight->flightno or '' }}},  D:{{{$prow->flight->dep_time or ''}}} - A:{{{$prow->flight->arr_time or ''}}}, {{{ $prow->flight->flight_from or '' }}} -> {{{ $prow->flight->flight_to}}} </b> </div>							
											</td>
											@endif
											<?php
												$getHotelBook = App\Booking::where(['book_project'=>$book->book_project, 'book_status'=>1, 'book_option'=>1, 'hotel_id'=>$prow->hotel_id])
											->whereDate('book_checkin', $book->book_checkin)->whereIn('book_quot_hotel_option', $hotelCheck) ->orderBy("book_checkin", "DESC")->get();
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
				<?php  $tourBook = App\Booking::tourBook($project->project_number, 1);?>
				@if( $tourBook->get()->count() > 0)	
					<div><strong style="text-transform: uppercase;">EXCURSION OVERVIEW</strong></div>
					<table class="table">
						<tr style="background-color:#f4f4f4;">
							<th width="100px">Date</th>
							<th>City</th>
							<th>Title </th>
							<?php $paxTotal = array('0'=>0,'1'=>0,'2'=>0, '3'=>0, '4'=>0, '5'=>0,'6'=>0,'7'=>0, '8'=>0,'9'=>0, '10'=>0, '11'=>0, '12'=>0, '13'=>0, '14'=>0, '15'=>0, '16'=>0, '17'=>0, '18'=>0, '19'=>0, '20'=>0, '21'=>0, '22'=>0, '23'=>0, '24'=>0, '25'=>0, '26'=>0, '27'=>0, '28'=>0, '29'=>0, '30'=>0);
							?>
							@if($tourCheck)
								@foreach($tourCheck as $key => $val) 
									<th class="text-right">{{$val}} Pax </th>
								@endforeach
							@endif
						</tr>
						@foreach($tourBook->get() as $key => $tour)
							<?php
								$pro 	= App\Province::find($tour->province_id); 
								$tprice = App\TourPrice::where('tour_id', $tour->tour_id)->whereIn('pax_no', $tourCheck)->get();
							?>
							<tr>
								<td>{{Content::dateformat($tour->book_checkin)}}</td>
								<td>{{ $pro->province_name }}</td>
								<td>{{$tour->tour_name}}</td>
								<?php $i = 0; ?>
								@foreach($tprice as $key=>$pr)
									<?php 
										$i++;
										$paxTotal[$pr->pax_no] = $paxTotal[$pr->pax_no] + $pr->sprice; ?>
									<td class="text-right">{{Content::money($pr->sprice)}}</td>
								@endforeach
							</tr>				
						@endforeach
						<tr>
							<th colspan="3" class="text-right">Land Cost Per Person:</th>
							@foreach($tourCheck as $key => $val) 
								<th class="text-right">{{Content::money($paxTotal[$val])}}</th>
							@endforeach
						</tr>
						
						<?php $grandTotalSingle = $grandTotalSingle + $paxTotal[1]; ?>
					</table>
				@endif

				<?php 
				$hotelBooked = App\Booking::where(['book_status'=>1, 'book_project'=>$project->project_number])
					->WhereHas('hotelbooked', function($query){
						$query->where("status", 1);
					})
					->whereIn('book_quot_hotel_option', $hotelCheck)->groupBy('book_quot_hotel_option')->get();
					$hotelOPtion = isset($_GET['hotel_option']) ? $_GET['hotel_option'] : [];

				?>
				
				@if($hotelBooked->count() > 0)
					<div><strong style="text-transform: uppercase;">HOTEL OVERVIEW</strong></div>
					@foreach($hotelBooked as $key => $bhotel)
						<?php
							$option = ['', 'Option 1', 'Option 2', 'Option 3', 'Option 4', 'Option 5', 'Option 6', 'Option 7','Option 8'];
						$hotelBook = App\HotelBooked::where(['project_number'=>$project->project_number,'status'=>1, 'option'=>1, 'book_option'=>$bhotel->book_quot_hotel_option])->orderBy("checkin", "ASC");
						
						?>
						<table class="table">
							<tr>
								<td bgcolor="#00ffbf" style="vertical-align: middle; padding: 0px 5px">
								<label><span class="hidden-print" style="position: relative;top: 3.3px;">
								<input type="checkbox" name="hotel_option[]" value="{{$bhotel->book_quot_hotel_option}}" {{in_array($bhotel->book_quot_hotel_option, $hotelOPtion) ? 'checked' : ''}}></span>
								{{$option[$bhotel->book_quot_hotel_option]}}
								</td>
							</tr>
							<tr>
								<td style="padding: 0px;">
									<table class="table">
										<tr >
											<th style="padding:2px"><label style="margin-bottom: 0px"></th>
											<th style="padding:2px">CheckIn -> CheckOut</th>
											<th style="padding:2px">Room Cat</th>
											<th style="padding:2px">Room Type</th>
											<th style="padding:2px" class="text-center">No. of Room </th>
											<th style="padding:2px" class="text-center">Nights</th>
											<th style="padding:2px" class="text-right">Room Rate</th>
											<th style="padding:2px" class="text-right">Total Price</th>
							             
										</tr>
											<?php 
												$totalsingle = 0;
												$totalTwin = 0;
												$totalExBed = 0;
											?>
											@foreach($hotelBook->get() as $hotel)				
												<tr>
													<?php 
														$single = $hotel->ssingle * $hotel->book_day; 
														$twin = $hotel->stwin * $hotel->book_day;
														$exBed = $hotel->sextra * $hotel->book_day;
														$totalsingle = $totalsingle + $single;
														$totalTwin = $totalTwin + $twin;
														$totalExBed = $totalExBed + $exBed;
													?>
													<td style="padding:2px">{{{ $hotel->hotel->supplier_name or ''}}}</td>
													<td style="padding:2px">{{{ $hotel->checkin or ''}}} - {{{ $hotel->checkout or ''}}}</td>
													<td style="padding:2px">{{{ $hotel->room->name or ''}}}</td>
													<td style="padding:2px">
														@if($single > 0)
															Single 
														@elseif($twin > 0)
															Twin
														@elseif($exBed > 0)
															EX-Bed
														@endif
													</td>
													<td style="padding:2px" class="text-center">{{$hotel->no_of_room}}</td>
													<td style="padding:2px" class="text-center">{{ $hotel->book_day}}</td>
													<td style="padding:2px" class="text-right">
														@if($hotel->ssingle > 0)
															{{ Content::money($hotel->ssingle) }}
														@elseif($hotel->stwin > 0)
															{{ Content::money($hotel->stwin) }}
														@elseif($hotel->sextra > 0)
															{{ Content::money($hotel->sextra) }}
														@endif
													</td>
													<td style="padding:2px" class="text-right">
														@if($single > 0)
															{{ Content::money($single) }}
														@elseif($twin > 0)
															{{ Content::money($twin) }}
														@elseif($exBed > 0)
															{{ Content::money($exBed) }}
														@endif
													</td>
												</tr>
											@endforeach
										<?php 
											$singleRoomTotal = $totalsingle - ($totalTwin / 2);
											$twinRoomTotal = ($totalTwin + $totalExBed) / 3;
										 	$grandTotalSingle = $grandTotalSingle + $totalsingle;
										?>
										<tr>
											<td style="padding:2px" colspan="6" class="text-right">
												@if($totalsingle)
													<b>Total Single: {{Content::money($totalsingle)}}</b>
												@endif
											</td>
											<td style="padding:2px" class="text-right"><b>
												@if($totalTwin)
													Total Twin PP: {{Content::money($totalTwin / 2)}}</b>
												@endif
											</td>
											<td style="padding:2px" class="text-right"><b>
												@if($twinRoomTotal)
													Tripple PP: {{Content::money($twinRoomTotal)}}</b>
												@endif
											</td>
										</tr>	
										<tr>
											<th style="padding:2px" colspan="6" class="text-right">
												@if($singleRoomTotal > 0)
													Single Supplement: {{Content::money($singleRoomTotal)}}
												@endif
											</th>
											<th style="padding:2px" class="text-right">
												@if($totalExBed > 0)
													EX-Bed Total: {{Content::money($totalExBed)}}
												@endif							
											</th>
											<th style="padding:2px" class="text-right"></th>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					@endforeach
				@endif

				<?php $flightBook = App\Booking::flightBook($project->project_number, 1); ?>
				@if($flightBook->count() > 0)
					<div><strong style="text-transform: uppercase;">Flight OVERVIEW</strong></div>
					<table class="table">
						<tr style="background-color:#f4f4f4;">
							<th width="100px">Date</th>
							<th>Flight From <i class="fa fa-space-shuttle" style="color: #3c8dbc;"></i>  To</th>
							<th>Flight No.</th>
							<th>Flight Dep <i class="fa fa-long-arrow-right" style="color: #3c8dbc"></i> Arr</th>
							<th class="text-center">Pax</th>
							<th class="text-right">Unit {{Content::currency()}}</th>
						</tr>
						@foreach($flightBook->get() as $fl)			
							<?php 
							$flprice = App\Supplier::find($fl->book_agent);
							$grandTotalSingle = $grandTotalSingle + $fl->book_price; 
							?>
							<tr>
								<td>{{Content::dateformat($fl->book_checkin)}}</td>
								<td>{{$fl->flight_from}} <i class="fa fa-space-shuttle" style="color: #3c8dbc;"></i>  {{$fl->flight_to}}</td>
								<td>{{$fl->flightno}}</td>
								<td>{{$fl->dep_time}} <i class="fa fa-long-arrow-right" style="color: #3c8dbc;"></i> {{$fl->arr_time}}</td>
								<td class="text-center">{{$fl->book_pax}}</td>
								<td class="text-right">{{Content::money($fl->book_price)}}</td>
							</tr>				
						@endforeach				
						<tr>
							<td class="text-right" colspan="10">
								@if($flightBook->sum('book_price'))
									<h5><b>Total Flight: {{Content::money($flightBook->sum('book_price'))}}</b></h5>
								@endif
							</td>
						</tr>
					</table>
				@endif
				<!-- end flight  -->
				<?php 
					$golfBook = App\Booking::golfBook($project->project_number, 1);
				?>
				@if($golfBook->count() > 0)
					<div><strong style="text-transform: uppercase;">GOFL COURSES OVERVIEW</strong></div>
					<table class="table">
						<tr style="background-color:#f4f4f4;">
							<th width="100px">Date</th>
							<th>Golf</th>
							<th>Tee Time</th>
							<th>Golf Service</th>
							<th class="text-center">Pax</th>
							<th class="text-right">Unit {{Content::currency()}}</th>
						</tr>
						@foreach($golfBook->get() as $gf)			
							<?php $gsv = App\GolfMenu::find($gf->program_id);?>	
							<?php $grandTotalSingle = $grandTotalSingle + $gf->book_price; ?>
							<tr>
								<td>{{Content::dateformat($gf->book_checkin)}}</td>
								<td>{{$gf->supplier_name}}</td>
								<td>{{$gf->book_golf_time}}</td>
								<td>{{{ $gsv->name or ''}}}</td>
								<td class="text-center">{{$gf->book_pax}}</td>
								<td class="text-right">{{Content::money($gf->book_price)}}</td>
							</tr>
						@endforeach				
						<tr>
							<td class="text-right" colspan="8">
								@if($golfBook->sum('book_price') > 0)
								<h5><b>Total Golf: {{Content::money($golfBook->sum('book_price'))}}</b></h5>
								@endif
							</td>
						</tr>					
					</table>
				@endif
				<!-- end golf  -->		
				<?php $cruiseBook = App\CruiseBooked::where(['project_number'=> $project->project_number,'status'=>1,'option'=>1]); ?>
				@if($cruiseBook->count() >0)			
					<div><strong style="text-transform: uppercase;">River Cruise OVERVIEW</strong></div>
					<table class="table">
						<tr style="background-color:#f4f4f4;">
							<th>River Cruise</th>
							<th width="170px;">Start-End Date</th>
							<th>Program</th>
							<th>Night</th>
							<th>CabinNo.</th>
							@foreach(App\RoomCategory::whereIn('id',[1,2,4])->orderBy('id', 'ASC')->get() as $cat)
			                	<th class="text-right">{{$cat->name}}</th>
			                @endforeach
			                <th class="text-right">Single Total</th>
			                <th class="text-right">Twin Total</th>
			                <th class="text-right">Ex-Bed Total</th>
						</tr>
						<?php 
							$totalsingleCruise = 0;
							$totalTwinCruise = 0;
							$totalExBedCruise = 0;
						?>
						@foreach($cruiseBook->get() as $crp)			
							<?php
								$pcr = App\CrProgram::find($crp->program_id);
								$rcr = App\RoomCategory::find($crp->room_id);
								$CrSingle = $crp->ssingle * $crp->book_day; 
								$CrTwin 	= $crp->stwin * $crp->book_day;
								$CRexBed 	= $crp->sextra * $crp->book_day;
								$totalsingleCruise = $totalsingleCruise + $CrSingle;
								$totalTwinCruise = $totalTwinCruise + $CrTwin;
								$totalExBedCruise = $totalExBedCruise + $CRexBed;
							?>
							<tr>
								<td>{{$crp->cruise->supplier_name}}</td>
								<td>{{Content::dateformat($crp->checkin)}} - {{ Content::dateformat($crp->checkout)}}</td>
								<td>{{{ $pcr->program_name or ''}}}</td>
								<td class="text-center">{{$crp->book_day}}</td>
								<td class="text-center">{{$crp->cabin_pax}}</td>
								<td class="text-right">{{Content::money($crp->ssingle)}}</td>
								<td class="text-right">{{Content::money($crp->stwin)}}</td>
								<td class="text-right">{{Content::money($crp->sextra)}}</td>
								<td class="text-right">{{Content::money($CrSingle)}}</td>
								<td class="text-right">{{Content::money($CrTwin)}}</td>
								<td class="text-right">{{Content::money($CRexBed)}}</td>
							</tr>	
						@endforeach
						<tr>
							<?php $trippleRoom = (($totalTwinCruise / 2) + $totalExBedCruise) / 3; ?>
							<?php $grandTotalSingle = $grandTotalSingle + $totalsingleCruise; ?>
							<td class="text-right" colspan="7">
								@if($totalsingleCruise > 0)
									<b>Total Single: {{Content::money($totalsingleCruise)}}</b>
								@endif
							</td>
							<td class="text-right" colspan="2">
								@if($totalTwinCruise > 0)
									<b>Total Twin PP: {{Content::money($totalTwinCruise / 2)}}</b>
								@endif
							</td>
							<td class="text-right" colspan="2">
								@if($trippleRoom > 0)
									<b>Tripple PP: {{Content::money($trippleRoom)}}</b>
								@endif
							</td>
						</tr>	
						<tr>
							<td class="text-right" colspan="9">
								<?php $singleSuplement = ($totalsingleCruise - ($totalTwinCruise / 2)); ?>
								@if($singleSuplement > 0)
									<b>Single Supplement: {{Content::money($singleSuplement)}}</b>
								@endif
							</td>
							<td class="text-right" colspan="2">
								@if($totalExBedCruise > 0)
									<b>	Triple Room:{{Content::money($trippleRoom)}}</b>
								@endif
							</td>						
						</tr>			
					</table>
				@endif
			@endif


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
