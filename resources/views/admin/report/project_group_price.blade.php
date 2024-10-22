@extends('layout.backend')
@section('title', 'Project Group Price')
<?php 
$type = "Project Group Price";
use App\component\Content;
$user = App\User::find($project->check_by);
$grandTotalSingle = 0;
$grandTotalGroup = 0;
?> 
@section('content')
<div class="container">
	<div class="col-lg-12">
    	@include('admin.report.project_header')	
    	<form target="_blank" method="GET" action="{{route('getProjectBooked', ['projectNo'=> $project->project_number, 'type'=> 'project_quotation_printing'])}}">
			<div class="pull-left">
				<div><b>CRD:</b> {{Content::dateformat($project->project_date)}}, 
					@if($project->project_check )
					Project No. <b>{{$project->project_number}}</b> is Already checked by <b>{{{ $user->fullname or ''}}}</b> at {{Content::dateformat($project->project_check_date)}},
					@endif
					<b>Revised Date</b> {{Content::dateformat($project->project_revise)}}
				</div> 
			</div>
			<div class="pull-right hidden-print">
				<button type="submit" name="preview_quot" value="preview_quot" class="btn btn-primary btn-xs">Itemized Cost </button>&nbsp;

				<button type="submit" name="preview_quot" value="package_cost" class="btn btn-primary btn-xs">Package Cost</button>&nbsp;

				<button type="submit" class="btn btn-primary btn-xs"> Create Quotation</button>&nbsp;
				<a href="javascript:void(0)" class="btn btn-xs btn btn-primary" onclick="window.print();"><span class="fa fa-print "></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
			</div><div class="clearfix"></div>
			@if($booking->count() > 0)
				@if($project->project_hight)
					<div class="form-group">{!! $project->project_hight !!}</div>
				@endif
				<h5 class="form-gorup">Project Details</h5>
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
											$bhotel = App\HotelBooked::where(['book_id'=>$prow->id, 'project_number'=>$prow->book_project, 'option'=> 1])->first();
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
													@if(!empty($prow->book_tour_details) || !empty($prow->tour->tour_desc))
														@if(isset($prow->book_tour_details))
				<a class="hidden-print btn btn-default btn-xs btnEditTour" data-toggle="modal" data-target="#myModal" href="#" data-id="{{$prow->id}}" data-title="{{{$prow->tour->tour_name or ''}}}" data-desc="{{ $prow->book_tour_details }}" >Edit</a>
														@else
														
				<a class="hidden-print btn btn-default btn-xs btnEditTour" data-toggle="modal" data-target="#myModal" href="#" data-id="{{$prow->id}}" data-title="{{{$prow->tour->tour_name or ''}}}" data-desc="{{ $prow->tour->tour_desc }}">Edit</a>
														@endif
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
													@if($type != "details")
													<table style="width: 100%;">
														@if($tour->tour_picture || $tour->tour_photo)
															<?php 
																$gallery = '';
																if($tour->tour_photo){
	        													 $gallery[] = ['thumbnail'=>$tour->tour_photo]; 
	        													}
	        													$tour_gallery = explode("|", trim($tour->tour_picture, '|'));
													        if (!empty($tour->tour_picture)) {
													            foreach ($tour_gallery as $key => $gl) {
													                $gallery[] = ['thumbnail'=>$gl];
													            }            
													        }
															?>
															<tr>
																<td>
																	<div class="row">
																		@if($gallery)
																			@foreach(array_slice($gallery, 0, 3) as $key => $pic)
																				<div class="col-sm-4 col-xs-4" style="padding-right:4px;">
																					<div class="form-group">
																						<img src="{{Content::urlthumbnail($pic['thumbnail'], $tour->user_id)}}" style="width: 100%;" />
																					</div>
																				</div>
																			@endforeach
																		@endif
																	</div>
																</td>
															</tr>
														@endif
													</table>
													@endif
												</td>
												@endif

												@if(isset($prow->golf->supplier_name))
												<td>
													<div><b style="text-transform: capitalize;">Golf : {{{$prow->golf->supplier_name or ''}}},   {{{$prow->golf_service->name or ''}}}, Pax: {{$prow->book_pax}}, Tee Time: {{$prow->book_golf_time}}</b></div>
												</td>
												@endif

												@if(isset($prow->flight->flightno))
												<td>
													<div><b>Flight : {{{ $prow->flight->flightno or '' }}},  D:{{{$prow->flight->dep_time or ''}}} - A:{{{$prow->flight->arr_time or ''}}}, {{{ $prow->flight->flight_from or '' }}} -> {{{ $prow->flight->flight_to}}} </b> </div>							
												</td>
												@endif
												
												@if(isset($prow->hotel->supplier_name))
												<td><b>{{{ $prow->hotel->supplier_name or '' }}} | {{{ $bhotel->room->name or '' }}} |  CheckIn:</b> {{{ $bhotel->checkin or ''}}} <b> - CheckOut: </b>{{{ $bhotel->checkout or '' }}}</td>
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
				<?php 
					$tourBook = App\Booking::tourBook($project->project_number, 1);
					$tourMaxPrice = App\Booking::tourMaxPrice($project->project_number);
				?>
				@if( $tourBook->get()->count() > 0)	
					<?php $NomOfTourPrice = 0; ?>	
					<div><strong style="text-transform: uppercase;">EXCURSION OVERVIEW</strong></div>
					
					<table class="table">
						<tr style="background-color:#f4f4f4;">
							<th width="100px">Date</th>
							<th>City</th>
							<th>Title</th>
							@foreach($tourMaxPrice as $key => $n)
								<th class="text-right">
									<span>{{$n}}</span>
									<span class="hidden-print" style="position: relative;top: 3.3px;"><input type="checkbox" name="group_tour_pax[]" value="{{$n}}"></span>
								</th>
							@endforeach
						</tr>		
						<?php 
							$paxTotal = array('0'=>0,'1'=>0,'2'=>0, '3'=>0, '4'=>0, '5'=>0,'6'=>0,'7'=>0, '8'=>0,'9'=>0, '10'=>0, '11'=>0, '12'=>0, '13'=>0, '14'=>0, '15'=>0, '16'=>0, '17'=>0, '18'=>0, '19'=>0, '20'=>0, '21'=>0, '22'=>0, '23'=>0, '24'=>0, '25'=>0, '26'=>0, '27'=>0, '28'=>0, '29'=>0, '30'=>0);
							
						?>
						@foreach($tourBook->get() as $key => $tour)
							<?php
								$pro 	= App\Province::find($tour->province_id); 
								$tprice = App\TourPrice::where('tour_id', $tour->tour_id)->whereNotIn('sprice', ['null', "", 0])->skip(0)->take(15)->orderBy("pax_no", "ASC")->get();
							?>
							<tr>
								<td>{{Content::dateformat($tour->book_checkin)}}</td>
								<td>{{ $pro->province_name }}</td>
								<td style="width: 399px;">{{$tour->tour_name}}</td>
								@foreach($tprice as $key=>$pr)
									<?php $paxTotal[$pr->pax_no] = $paxTotal[$pr->pax_no] + $pr->sprice; ?>
									<td class="text-right">{{Content::money($pr->sprice)}}</td>
								@endforeach
							</tr>				
						@endforeach
						<tr>
							<th colspan="3" class="text-right">Land Cost Per Person:</th>
							@foreach($tourMaxPrice as $key => $n)
								<th class="text-right">{{Content::money($paxTotal[$n])}}</th>
							@endforeach
						</tr>
						<tr>
							<th colspan="3" style="background-color: #3c8dbc26;border: solid 1px #d2d6de;" class="text-right">Group Price:</th>
							@foreach($tourMaxPrice as $key => $n)
								<th style="background-color:#3c8dbc26;border: solid 1px #d2d6de;" class="text-right">{{Content::money($paxTotal[$n] * $n)}}
								</th>
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
					->whereNotIn('book_quot_hotel_option', ['', 0, 'null'])->groupBy('book_quot_hotel_option')->get();
				?>
				@if($hotelBooked->count() > 0)
					<div><strong style="text-transform: uppercase;">HOTEL OVERVIEW</strong></div>
					@foreach($hotelBooked as $key => $bhotel)
						<?php $option = ['', 'Option 1', 'Option 2', 'Option 3', 'Option 4', 'Option 5', 'Option 6', 'Option 7','Option 8'];

						$hotelBook = App\HotelBooked::where(['project_number'=>$project->project_number, 'status'=>1, 'option'=>1, 'book_option'=>$bhotel->book_quot_hotel_option])->orderBy("checkin", "ASC");
						?>
						<table class="table" style="margin-bottom: 0; border:solid 1px #dddd">
							<tr><td bgcolor="#00ffbf" style="vertical-align: middle; padding: 0px 5px">
									<label><span class="hidden-print" style="position: relative;top: 3.3px;"><input type="checkbox" name="hotel_option[]" value="{{$bhotel->book_quot_hotel_option}}"></span>
										{{$option[$bhotel->book_quot_hotel_option] }}
							</td></tr>
							<tr >
								<td style="padding: 0px;">
									<table class="table">
										<tr >
											<th style="padding:2px"><label style="margin-bottom: 0px"></th>
											<th style="padding:2px">CheckIn -> CheckOut</th>
											<th style="padding:2px">Room Cat</th>
											<th style="padding:2px">Room Type</th>
											<th style="padding:2px" class="text-right">No. of Room||</th>
											<th style="padding:2px" class="text-left">Nights</th>
											<th style="padding:2px" class="text-left">Room Rate</th>
											<th style="padding:2px" class="text-left">Total Price</th>
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
													<td style="padding:2px">
														@if($hotel->ssingle > 0)
															{{ Content::money($hotel->ssingle) }}
														@elseif($hotel->stwin > 0)
															{{ Content::money($hotel->stwin) }}
														@elseif($hotel->sextra > 0)
															{{ Content::money($hotel->sextra) }}
														@endif
													</td>
													<td style="padding:2px">
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
											$singleSuplement = $totalsingle - ($totalTwin / 2);
											$TrippleRoomTotal = ($totalTwin + $totalExBed) / 3;
										 	$grandTotalSingle = $grandTotalSingle + $totalsingle;
										?>
									<tr>
										<td style="padding:2px" colspan="6" class="text-right">
											@if($totalsingle > 0)
												<b>Total Single: {{Content::money($totalsingle)}}</b>
											@endif
										</td>
										<td style="padding:2px" colspan="6" class="text-right">
											@if($totalsingle > 0)
												<b>Single Supplement: {{Content::money($singleSuplement)}}</b>
											@endif
										</td>

									</tr>

									<tr>
										<td style="padding:2px" colspan="6" class="text-right">
											@if($totalTwin > 0)
												<b>Total Twin: {{Content::money($totalTwin)}}</b>
											@endif
										</td>
										<td style="padding:2px" colspan="6" class="text-right">
											@if($totalTwin > 0)
												<b>Shared Twin PP: {{Content::money($totalTwin / 2)}}</b>
											@endif
										</td>
									</tr>

									<tr>
										<td style="padding:2px" colspan="6" class="text-right">
											@if($totalExBed > 0)
												<b>Total EX-Bed: {{Content::money($totalExBed)}}</b>
											@endif
										</td>

										<td style="padding:2px" colspan="6" class="text-right">
											@if($totalExBed > 0)
												<b>Tripple PP: {{Content::money($TrippleRoomTotal)}}</b>
											@endif
										</td>
									</tr>
									</table>
								</td>
							</tr>
						</table>
					@endforeach
				@endif
				
				<?php 
					$flightBook = App\Booking::flightBook($project->project_number, 1);
				?>
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
								<td>{{$fl->flight_from}} <i class="fa fa-space-shuttle" style="color: #3c8dbc;"></i> {{$fl->flight_to}}</td>
								<td>{{$fl->flightno}}</td>
								<td>{{$fl->dep_time}} <i class="fa fa-long-arrow-right" style="color: #3c8dbc;"></i> {{$fl->arr_time}}</td>
								<td class="text-center">{{$fl->book_pax}}</td>
								<td class="text-right">{{Content::money($fl->book_price)}}</td>
							</tr>				
						@endforeach				
						<tr>
							<td class="text-right" colspan="11"><h5><strong>Total Flight: {{Content::money($flightBook->sum('book_price'))}}</strong></h5></td>
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
							<td class="text-right" colspan="8"><h5><strong>Total Golf: {{Content::money($golfBook->sum('book_price'))}}</strong></h5></td>
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
							<th>Room Type</th>
							<th>CabinNo.</th>
							<th>Room Rate</th>
							<th>Total Price</th>
							
							
							<!-- @foreach(App\RoomCategory::whereIn('id',[1,2,4])->orderBy('id', 'ASC')->get() as $cat)
			                	<th class="text-right">{{$cat->name}}</th>
			                @endforeach
			                <th class="text-right">Single Total</th>
			                <th class="text-right">Twin Total</th>
			                <th class="text-right">Ex-Bed Total</th> -->
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
								<td style="padding:2px">
									@if($CrSingle > 0)
										Single 
									@elseif($CrTwin > 0)
										Twin
									@elseif($CRexBed > 0)
										EX-Bed
									@endif
								</td>
								<td class="text-center">{{$crp->book_day}}</td>
								<td class="text-center">{{$crp->cabin_pax}}</td>

								<td class="text-right">
									@if($CrSingle > 0)
										{{Content::money($crp->ssingle)}}
									@elseif($CrTwin > 0)
										{{Content::money($crp->stwin)}}
									@elseif($CRexBed > 0)
										{{Content::money($crp->sextra)}}
									@endif
								</td>
								
								<td class="text-right">
									@if($CrSingle > 0)
										{{Content::money($CrSingle)}}
									@elseif($CrTwin > 0)
										{{Content::money($CrTwin)}}
									@elseif($CRexBed > 0)
										{{Content::money($CRexBed)}}
									@endif
								</td>


							<!-- 	<td class="text-right">{{Content::money($crp->ssingle)}}</td>
								<td class="text-right">{{Content::money($crp->stwin)}}</td>
								<td class="text-right">{{Content::money($crp->sextra)}}</td>
								<td class="text-right">{{Content::money($CrSingle)}}</td>
								<td class="text-right">{{Content::money($CrTwin)}}</td>
								<td class="text-right">{{Content::money($CRexBed)}}</td> -->
							</tr>	
						@endforeach

						<?php 
							$CsingleSuplement = $totalsingleCruise - ($CrTwin / 2);
							$CTrippleRoomTotal = ($CrTwin + $CRexBed) / 3;
						 	$grandTotalSingle = $grandTotalSingle + $totalsingleCruise;

						?>

						<tr>
							<td colspan="6" class="text-right">
								@if($totalsingleCruise > 0)
									<b>Total Single: {{Content::money($totalsingleCruise)}}</b>
								@endif
							</td>
							<td colspan="6" class="text-right">
								@if($CsingleSuplement > 0)
									<b>Single Supplement: {{Content::money($CsingleSuplement)}}</b>
								@endif
							</td>

						</tr>

						<tr>
							<td colspan="6" class="text-right">
								@if($totalTwin > 0)
									<b>Total Twin: {{Content::money($totalTwin)}}</b>
								@endif
							</td>
							<td colspan="6" class="text-right">
								@if($CrTwin > 0)
									<b>Shared Twin PP: {{Content::money($CrTwin / 2)}}</b>
								@endif
							</td>
						</tr>

						<tr>
							<td colspan="6" class="text-right">
								@if($CRexBed > 0)
									<b>Total EX-Bed: {{Content::money($CRexBed)}}</b>
								@endif
							</td>

							<td style="padding:2px" colspan="6" class="text-right">
								@if($CTrippleRoomTotal > 0)
									<b>Tripple PP: {{Content::money($CTrippleRoomTotal)}}</b>
								@endif
							</td>
						</tr>		
					</table>
				@endif
			@endif
		</form>
  	

	<?php
		$hotelReport = App\HotelBooked::where(['project_number'=>$project->project_number,'status'=>1, 'option'=>1])
				
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
 </div>
<br><br>
<div class="modal" id="myModal" role="dialog" data-backdrop="static" data-keyboard="true">
	<div class="modal-dialog modal-lg">
	    <form method="POST" action="{{route('UpdateTourDesc')}}">
	      <div class="modal-content">       
	        <input type="hidden" name="tour_id" id="tour_id"> 
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" ><strong id="tour_name">Add Service</strong></h4>
	        </div>
	        <div class="modal-body">
	          {{csrf_field()}} 
	          <div class="row">
	            <div class="col-md-12">
	              <div class="form-group">
	                <label>Tour Descriptions</label>
	                <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
	               	<textarea class="form-control my-editor" name="tourBook_desc" id="tourBook_desc" rows="7" placeholder="Tour Detials"></textarea>
	              </div>
	            </div>
	          </div>
	          <div class="modal-footer" style="text-align: center">
	              <button type="submit" class="btn btn-success btn-flat btn-sm">Save</button>
	              <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Close</a>
	          </div>
	        </div>     
	      </div>   
	    </form>
	</div>
</div>
 <script type="text/javascript">
 	$(document).ready(function(){
 		$(document).on("click", ".btnEditTour", function(){
 			// $("#tourBook_desc").val($(this).data('desc'));
 			$("#tour_id").val($(this).data('id'));
 			$("#tour_name").text($(this).data('title'));
 			tinyMCE.activeEditor.setContent($(this).data('desc'));
 		});
 	});
 </script>
 @include('admin.include.editor')
@endsection

