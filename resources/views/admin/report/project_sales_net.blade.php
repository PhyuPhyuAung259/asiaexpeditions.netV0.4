@extends('layout.backend')
@section('title', 'Project '.$type)
<?php 
	use App\component\Content;
	$user = App\User::find($project->check_by);
?>
@section('content')
<div class="container">
	<div class="col-lg-12">
    @include('admin.report.project_header')	
		<div class="pull-left">
			<div><b>CRD:</b> {{Content::dateformat($project->project_date)}}, 
				@if($project->project_check )
				Project No. <b>{{$project->project_number}}</b> is Already checked by <b>{{{ $user->fullname or ''}}}</b> at {{Content::dateformat($project->project_check_date)}},
				@endif
				<b>Revised Date</b> {{Content::dateformat($project->project_revise)}}
			</div> 
		</div>
		<div class="pull-right hidden-print">
			<a href="javascript:void(0)" onclick="window.print();"><span class="fa fa-print btn btn-primary"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
		</div><div class="clearfix"></div>
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
						<td width=100px" style="vertical-align: top;"><h5><b>{{date('d M Y', strtotime($book->book_checkin))}}</b></h5>{{date('l', strtotime($book->book_checkin))}}</td>
						<td style="vertical-align: top;">					
						@if(!empty($book->book_checkin) && !empty($book->book_project))
							<?php 
								$getBook = \App\Booking::where(['book_checkin'=>$book->book_checkin, 'book_project'=>$book->book_project, 'book_status'=> 1])->orderBy('hotel_id','ASC')->orderBy("book_checkin", "DESC")->get();
							?>
							@foreach($getBook as $prow)
								<?php 
									$bhotel = App\HotelBooked::where(['book_id'=> $prow->id, 'project_number'=> $prow->book_project])->first();
									$bcruise = App\CruiseBooked::where(['book_id'=> $prow->id, 'project_number'=> $prow->book_project])->first();
								?>
								<table style="width: 100%;">
									<tr>
										@if(isset($prow->tour->tour_name))
										<td>
											<div><b>{{{ $prow->tour->tour_name or '' }}}</b>, </div>
											{!!$prow->tour->tour_desc!!}
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
											<table style="width:100%;">
												@if($tour->tour_picture || $tour->tour_photo)
												<tr>
													
													<td>
														<div class="row">
															@if($tour->tour_photo)
																<div class="col-sm-4 col-xs-4" style="padding-right:4px;">
																	<div class="form-group">
																		<img src="/storage/{{$tour->tour_photo}}" style="width: 100%;" />
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
										
										@if(isset($prow->hotel->supplier_name))
										<td><b>{{{ $prow->hotel->supplier_name or '' }}},Room: {{{ $bhotel->room->name or '' }}}, CheckIn: {{{ $bhotel->checkin or ''}}} - CheckOut: {{{ $bhotel->checkout or '' }}}</b></td>
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
	@if($booking->count() > 0)
		<h4><strong style="text-transform: capitalize;">Program Summary</strong></h4>
		<?php 
			$tourBook = \App\Booking::tourBook($book->book_project); 
		?>
		@if(!empty($book->book_project))		
			<div><strong style="text-transform: uppercase;">EXCURSION OVERVIEW</strong></div>
			<table class="table" id="roomrate">
				<tr style="background-color:#f4f4f4;">
					<th width="100px">Date</th>
					<th width="110px">City</th>
					<th >Title</th>					
				</tr>			
				@foreach($tourBook->get() as $tour)
					<?php 				
					$pro = \App\Province::find($tour->province_id); ?>
					<tr>
						<td>{{Content::dateformat($tour->book_checkin)}}</td>
						<td>{{{ $pro->province_name or ''}}}</td>
						<td>{{$tour->tour_name}}</td>					
					</tr>				
				@endforeach
			</table>
		@endif
		<!-- End tour -->
		<?php 
		$hotelBook = \App\HotelBooked::where('project_number', $book->book_project)->orderBy("checkin", 'ASC');
		?>
		@if($hotelBook->count() > 0)
			<div><strong style="text-transform: uppercase;">HOTEL OVERVIEW</strong></div>
			<table class="table" id="roomrate">
				<tr style="background-color:#f4f4f4;">
					<th>Hotel</th>
					<th>Checkin - Checkout</th>
					<th>Room</th>
					<td>No. of Room</td>
					<th class="text-center">Nights</th>
					@foreach(App\RoomCategory::whereIn('id',[1,2,3,4,5])->orderBy('id', 'ASC')->get() as $cat)
	                <th class="text-right">{{$cat->name}}</th>
	                @endforeach	            
				</tr>

				@foreach($hotelBook->get() as $hotel)				
				<tr>
					<td>{{{ $hotel->hotel->supplier_name or ''}}}</td>
					<td>{{{ $hotel->checkin or ''}}} - {{{ $hotel->checkout or ''}}}</td>
					<td>{{{ $hotel->room->name or ''}}}</td>
					<td class="text-center">{{$hotel->no_of_room}}</td>
					<td class="text-center">{{ $hotel->book_day}}</td>				
					<td class="text-right">{!! ($hotel->ssingle== 0 ? '' : '<span style="position: static;" class="fa fa-check-square check"></span>') !!} </td>
					<td class="text-right">{!! ($hotel->stwin== 0 ? '' : '<span style="position: static;" class="fa fa-check-square check"></span>') !!} </td>
					<td class="text-right">{!! ($hotel->sdouble== 0 ? '' : '<span style="position: static;" class="fa fa-check-square check"></span>') !!}</td>
					<td class="text-right">{!! ($hotel->sextra== 0 ? '' : '<span style="position: static;" class="fa fa-check-square check"></span>') !!} </td>
					<td class="text-right">{!! ($hotel->schextra== 0 ? '' : '<span style="position: static;" class="fa fa-check-square check"></span>') !!}</td>
				</tr>
				@endforeach
			</table>
		@endif
		
		<?php 
			$flightBook = App\Booking::flightBook($book->book_project);
		?>
		@if($flightBook->count() > 0)
			<div><strong style="text-transform: uppercase;">Flight OVERVIEW</strong></div>
			<table class="table" id="roomrate">
				<tr style="background-color:#f4f4f4;">
					<th width="100px">Date</th>
					<th>Flight From <i class="fa fa-space-shuttle"></i>  To</th>
					<th>Flight No.</th>
					<th>Flight Dep <i class="fa fa-long-arrow-right"></i> Arr</th>
					<th>Ticketing Agent</th>					
				</tr>
				@foreach($flightBook->get() as $fl)			
					<?php 
					$flprice = App\Supplier::find($fl->book_agent);
					?>			
					<tr>
						<td>{{Content::dateformat($fl->book_checkin)}}</td>
						<td>{{$fl->flight_from}} <i class="fa fa-space-shuttle"></i>  {{$fl->flight_to}}</td>
						<td>{{$fl->flightno}}</td>
						<td>{{$fl->dep_time}} <i class="fa fa-long-arrow-right"></i> {{$fl->arr_time}}</td>
						<td>{{{ $flprice->supplier_name or ''}}}</td>
						<td class="text-center">{{$fl->book_pax}}</td>
					</tr>				
				@endforeach
			</table>
		@endif
		<!-- end flight  -->
		<?php 
			$golfBook = App\Booking::golfBook($book->book_project);
		?>
		@if($golfBook->count() > 0)
			<div><strong style="text-transform: uppercase;">GOFL COURSES OVERVIEW</strong></div>
			<table class="table" id="roomrate">
				<tr style="background-color:#f4f4f4;">
					<th width="100px">Date</th>
					<th>Golf</th>
					<th>Tee Time</th>
					<th>Golf Service</th>
					<th class="text-center">Pax</th>
				</tr>
				@foreach($golfBook->get() as $gf)			
					<?php 
					$gsv = App\GolfMenu::find($gf->program_id);
					?>	
					<tr>
						<td>{{Content::dateformat($gf->book_checkin)}}</td>
						<td>{{$gf->supplier_name}}</td>
						<td>{{$gf->book_golf_time}}</td>
						<td>{{{ $gsv->name or ''}}}</td>
						<td class="text-center">{{$gf->book_pax}}</td>
					</tr>
				@endforeach
			</table>
		@endif
		<!-- end golf  -->		
		<?php 
			$cruiseBook = App\CruiseBooked::where('project_number', $book->book_project);
		?>
		@if($cruiseBook->count() >0)			
			<div><strong style="text-transform: uppercase;">River Cruise OVERVIEW</strong></div>
			<table class="table" id="roomrate">
				<tr style="background-color:#f4f4f4;">
					<th width="170px;">Date</th>
					<th>River Cruise</th>
					<th>Program</th>
					<th>Room</th>
					<th>Night</th>
					<th>No. Cabin</th>
					@foreach(App\RoomCategory::whereIn('id',[1,2,3,4,5])->orderBy('id', 'ASC')->get() as $cat)
	                <th class="text-right">{{$cat->name}}</th>
	                @endforeach
				</tr>
				@foreach($cruiseBook->get() as $crp)			
					<?php 
					$pcr = App\CrProgram::find($crp->program_id);
					$rcr = App\RoomCategory::find($crp->room_id);
					?>	
					<tr>
						<td>{{Content::dateformat($crp->checkin)}} - {{ Content::dateformat($crp->checkout)}} </td>
						<td>{{$crp->cruise->supplier_name}}</td>
						<td>{{{ $pcr->program_name or ''}}}</td>
						<td>{{{ $rcr->name or ''}}}</td>					
						<td class="text-center">{{$crp->book_day}}</td>
						<td class="text-center">{{$crp->cabin_pax}}</td>									
						<td class="text-right">{!! ($crp->ssingle == 0 ? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!}</td>
						<td class="text-right">{!! ($crp->stwin == 0 ? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!}</td>
						<td class="text-right">{!! ($crp->sdouble == 0 ? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!}</td>
						<td class="text-right">{!! ($crp->sextra == 0 ? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!}</td>
						<td class="text-right">{!! ($crp->schextra == 0 ? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!}</td>
					</tr>		
				@endforeach
			</table>
		@endif
		<h4 class="text-right">
			<?php 
			$getGrandTotal = $cruiseBook->sum('net_amount') + $golfBook->sum('book_namount') + $flightBook->sum('book_namount') + $hotelBook->sum('net_amount') + $tourBook->sum('book_namount');
			 ?>
			<h4 class="text-right"><strong>Grand Total: {{ Content::marginRate($getGrandTotal, $project->margin_rate )}} {{Content::currency()}}</strong></h4>
		</h4>
		<?php
	        $Included=App\Service::where('service_cat',1)->whereIn('id',$servicedata)->get();
	        $Excluded=App\Service::where('service_cat',0)->whereIn('id',$servicedata)->get();
      	?>
		<div class="table-responsive">
			@if($Included->count() > 0 || $Excluded->count() > 0)
	        <table class='table'>
	          <thead>
	            <tr class="text-left">
	              <td style="width: 50%;"><strong><?php echo ($Included) ? "Service Included":"";?></strong></td>
	              <td style="width: 50%;"><strong><?php echo ($Excluded) ? "Service Excluded":"";?></strong></td>
	            </tr>
	          </thead>
	          <tbody>
		        <tr> 
		            <td style="vertical-align:top;">
		            	<ul class="list-unstyled">
	                    @foreach($Included as $room)
	                        <li>- {{$room->service_name}}</li>
	                    @endforeach
	                    </ul>
		            </td>
		            <td style="vertical-align:top;">
		            	<ul class="list-unstyled">
	                    @foreach($Excluded as $room)
	                        <li>- {{$room->service_name}}</li>
	                    @endforeach
	                    </ul>
		            </td>
	          	</tr>
	          </tbody>
	        </table>
	        @endif
	    </div>
	    
	    	@include('admin.report.project_hotel_booking')
	    
	@endif
  	</div>
 </div>
@endsection
