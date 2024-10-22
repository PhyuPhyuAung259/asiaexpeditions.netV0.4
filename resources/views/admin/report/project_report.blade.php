@extends('layout.backend')
@section('title', 'Project '.$type)
<?php 
use App\component\Content;
$user = App\User::find($project->check_by);
?>
@section('content')

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
	                <div id="container" >
                        @include('include.editor')
                        <div class="editor1" style="resize:both; overflow:auto;max-width:100%;min-width: 100%;" class="titletou" contenteditable="true"  data-text="Enter comment....">
                        </div>
                        <textarea class="form-control my-editor" name="tourBook_desc" id="_desc" rows="7" placeholder="Tour Detials" style="display: none;"></textarea>
                    </div> 
	                <!-- script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
	               	<textarea class="form-control my-editor" name="tourBook_desc" id="tourBook_desc" rows="7" placeholder="Tour Detials"></textarea -->
	              </div>
	            </div>
	          </div>
	          <div class="modal-footer" style="text-align: center">
	              <button type="submit" class="btn btn-success btn-flat btn-sm Book_desc">Save</button>
	              <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Close</a>
	          </div>
	        </div>     
	      </div>   
	    </form>
	</div>
</div>


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
						<td width="100px" style="vertical-align: top;"><h5><b>{{date('d M Y', strtotime($book->book_checkin))}}</b></h5>{{date('l', strtotime($book->book_checkin))}}</td>
						<td style="vertical-align: top;">					
						@if(!empty($book->book_checkin) && !empty($book->book_project))
							<?php 
								$getBook = \App\Booking::where(['book_checkin'=>$book->book_checkin, 'book_project'=>$book->book_project, 'book_status'=> 1])->whereNotIn('book_pax', ["", "Null",0])->orderBy('hotel_id','ASC')->orderBy("book_checkin", "DESC")->get();
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
												<div><h4> <b>{{{ $prow->tour->tour_name or '' }}}</b>, </h4> </div>
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
																$gallery = [];
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
												<div><b style="text-transform: capitalize;">Golf : {{{$prow->golf->supplier_name or ''}}},   {{{$prow->golf_service->name or ''}}}, Pax: {{$prow->book_pax}}, Tee Time: {{$prow->book_golf_time}}</b> </div>									
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
	@if($booking->count() > 0)
		<h4><strong style="text-transform: capitalize;">Program Summary</strong></h4>
		<?php $tourBook = \App\Booking::tourDetailsBook($book->book_project); ?>
		@if(!empty($book->book_project))		
			<div><strong style="text-transform: uppercase;">EXCURSION OVERVIEW</strong></div>
			<table class="table" id="roomrate">
				<tr style="background-color:#f4f4f4;">
					<th width="100px">Date</th>
					<th>City</th>
					<th>Title</th>
					@if($type == "details" || $type == "details-net")
						<th class="text-center">Pax</th>
						<th class="text-right">Price</th>
						<th class="text-right">Amount</th>
					@endif
				</tr>			
				@foreach($tourBook->get() as $tour)
					<?php 
					
					$pro = \App\Province::find($tour->province_id); ?>
					<tr>
					
						<td>{{Content::dateformat($tour->book_checkin)}}</td>
						<td>{{{ $pro->province_name or ''}}}</td>
						<td>{{$tour->tour_name}}</td>
					
						@if($type == "details")
							<td class="text-center">{{$tour->book_pax}}</td>
							<?php $tour_price = \App\TourPrice::where(['tour_id'=>$tour->tour_id,'pax_no'=>$tour->book_pax])->first();
							?>
							<td class="text-right">{{{$tour_price->sprice or ""}}}</td>
							<td class="text-right">{{$tour->book_amount}}</td>
						@endif
					
						@if($type == "details-net")
							<td class="text-center">{{$tour->book_pax}}</td>
							<td class="text-right">{{Content::money($tour->book_nprice)}}</td>
							<td class="text-right">{{Content::money($tour->book_namount)}}</td>
						@endif
					</tr>				
				@endforeach
				@if($type == "details")
				<tr>
					<td colspan="6" class="text-right"><h5><strong>Tour Sub Total: {{Content::money($tourBook->sum('book_amount'))}} {{Content::currency()}} </strong></h5></td>
				</tr>
				@endif
				@if($type == "details-net")
				<tr>
					<td colspan="6" class="text-right"><h5><strong>Tour Sub Total: {{Content::money($tourBook->sum('book_namount'))}} {{Content::currency()}} </strong></h5></td>
				</tr>
				@endif
			</table>
		@endif 
		<!-- End tour -->
		<?php $hotelBook = App\HotelBooked::where(['project_number'=>$book->book_project, 'status'=> 1])->orderBy("checkin", 'ASC');?>
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
	                @if($type == "details" || $type == "details-net")
	                <th class="text-right">Hotel Amount</th>
	                @endif
				</tr>

				@foreach($hotelBook->get() as $hotel)				
				<tr>
					<td>{{{ $hotel->hotel->supplier_name or ''}}}</td>
					<td>{{{ $hotel->checkin or ''}}} - {{{ $hotel->checkout or ''}}}</td>
					<td>{{{ $hotel->room->name or ''}}}</td>
					<td class="text-center">{{$hotel->no_of_room}}</td>
					<td class="text-center">{{ $hotel->book_day}}</td>
					@if($type == 'details-net')
						<td class="text-right">{{Content::money($hotel->nsingle)}}</td>
						<td class="text-right">{{Content::money($hotel->ntwin)}}</td>
						<td class="text-right">{{Content::money($hotel->ndouble)}}</td>
						<td class="text-right">{{Content::money($hotel->nextra)}}</td>
						<td class="text-right">{{Content::money($hotel->nchextra)}}</td>
						<td class="text-right">{{Content::money($hotel->net_amount)}}</td>
					@else
						<td class="text-right">{!! $type == 'details' ? Content::money($hotel->ssingle) : ($hotel->ssingle== 0? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!} </td>
						<td class="text-right">{!! $type == 'details' ? Content::money($hotel->stwin) : ($hotel->stwin== 0? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!} </td>
						<td class="text-right">{!! $type == 'details' ? Content::money($hotel->sdouble) : ($hotel->sdouble== 0? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!}</td>
						<td class="text-right">{!! $type == 'details' ? Content::money($hotel->sextra) : ($hotel->sextra== 0? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!} </td>
						<td class="text-right">{!! $type == 'details' ? Content::money($hotel->schextra) : ($hotel->schextra== 0? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!}</td>
						@if($type == "details")
						<td class="text-right">{{Content::money($hotel->sell_amount)}}</td>
						@endif
					@endif
				</tr>
				@endforeach
				@if($type == "details")
				<tr>
					<td colspan="12" class="text-right"><h5><strong>Hotel Sub Total: {{Content::money($hotelBook->sum('sell_amount'))}} {{Content::currency()}} </strong></h5></td>
				</tr>
				@endif
				@if($type == "details-net")
				<tr>
					<td colspan="12" class="text-right"><h5><strong>Hotel Sub Total: {{Content::money($hotelBook->sum('net_amount'))}} {{Content::currency()}} </strong></h5></td>
				</tr>
				@endif
			</table>
		@endif
		
		<?php $flightBook = App\Booking::flightBook($book->book_project);?>
		@if($flightBook->count() > 0)
			<div><strong style="text-transform: uppercase;">Flight OVERVIEW</strong></div>
			<table class="table" id="roomrate">
				<tr style="background-color:#f4f4f4;">
					<th width="100px">Date</th>
					<th>Flight From <i class="fa fa-space-shuttle"></i>  To</th>
					<th>Flight No.</th>
					<th>Flight Dep <i class="fa fa-long-arrow-right"></i> Arr</th>
					<!-- <th>Flight Arr</th> -->
					@if($type != "details" && $type != "sales")
					<th>Ticketing Agent</th>
					@endif
					<th class="text-center">Pax</th>
					@if($type == "details" || $type == "details-net")
						<th class="text-right">Price {{Content::currency()}}</th>
						<th class="text-right">Amount {{Content::currency()}}</th>
					@endif
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
					<!-- <td></td>					 -->
					@if($type != "details" && $type != "sales")
					<td>{{{ $flprice->supplier_name or ''}}}</td>
					@endif
					<td class="text-center">{{$fl->book_pax}}</td>
					@if($type == "details")
					<th class="text-right">{{Content::money($fl->book_price)}}</th>
					<th class="text-right">{{Content::money($fl->book_amount)}}</th>
					@endif
					@if($type == "details-net")
					<th class="text-right">{{Content::money($fl->book_nprice)}}</th>
					<th class="text-right">{{Content::money($fl->book_namount)}}</th>
					@endif
				</tr>				
				@endforeach				
				@if($type == "details")
				<tr>
					<td colspan="11" class="text-right"><h5><strong>Flight Sub Total: {{Content::money($flightBook->sum('book_amount'))}} {{Content::currency()}}</strong></h5></td>
				</tr>
				@endif

				@if($type == "details-net")
				<tr>
					<td colspan="11" class="text-right"><h5><strong>Flight Sub Total: {{Content::money($flightBook->sum('book_namount'))}} {{Content::currency()}}</strong></h5></td>
				</tr>
				@endif
			</table>
		@endif
		<!-- end flight  -->
		
		<?php $golfBook = App\Booking::golfBook($book->book_project);?>
		@if($golfBook->get()->count() > 0)
			<div><strong style="text-transform: uppercase;">GOLF COURSE OVERVIEW</strong></div>
			<table class="table" id="roomrate">
				<tr style="background-color:#f4f4f4;">
					<th width="100px">Date</th>
					<th>Golf</th>
					<th>Tee Time</th>
					<th>Golf Service</th>
					<th class="text-center">Pax</th>
					@if($type == "details" || $type == "details-net")
					<th class="text-right">Price {{Content::currency()}}</th>
					<th class="text-right">Price {{Content::currency()}}</th>
					@endif
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
					@if($type == "details")					
					<td class="text-right">{{Content::money($gf->book_price)}}</td>
					<td class="text-right">{{Content::money($gf->book_amount)}}</td>
					@endif
					@if($type == "details-net")					
					<td class="text-right">{{Content::money($gf->book_nprice)}}</td>
					<td class="text-right">{{Content::money($gf->book_namount)}}</td>
					@endif
				</tr>
				@endforeach
				@if($type == "details")
				<tr>
					<td colspan="9" class="text-right"><h5><strong>Golf Sub Total: {{Content::money($golfBook->sum('book_amount'))}} {{Content::currency()}}</strong></h5></td>
				</tr>
				@endif
				@if($type == "details-net")
				<tr>
					<td colspan="9" class="text-right"><h5><strong>Golf Sub Total: {{Content::money($golfBook->sum('book_namount'))}} {{Content::currency()}}</strong></h5></td>
				</tr>
				@endif
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
					@if($type == 'details-net')
						<td class="text-right">{{Content::money($crp->nsingle)}}</td>
						<td class="text-right">{{Content::money($crp->ntwin)}}</td>
						<td class="text-right">{{Content::money($crp->ndouble)}}</td>
						<td class="text-right">{{Content::money($crp->nextra)}}</td>
						<td class="text-right">{{Content::money($crp->nchextra)}}</td>						
					@else					
					<td class="text-right">{!! $type == 'details' ? Content::money($crp->ssingle) : ($crp->ssingle== 0? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!}</td>
					<td class="text-right">{!! $type == 'details' ? Content::money($crp->stwin) : ($crp->stwin== 0? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!}</td>
					<td class="text-right">{!! $type == 'details' ? Content::money($crp->sdouble) : ($crp->sdouble== 0? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!}</td>
					<td class="text-right">{!! $type == 'details' ? Content::money($crp->sextra) : ($crp->sextra== 0? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!}</td>
					<td class="text-right">{!! $type == 'details' ? Content::money($crp->schextra) : ($crp->schextra== 0? '':'<span style="position: static;" class="fa fa-check-square check"></span>') !!}</td>
					@endif
				</tr>	
				@endforeach		
					@if($type == "details")
					<tr>
						<td colspan="12" class="text-right"><h5><strong>River Cruise Sub Total: {{Content::money($cruiseBook->sum('sell_amount'))}} {{Content::currency()}} </strong></h5></td>
					</tr>
					@endif
					@if($type == "details-net")
					<tr>
						<td colspan="12" class="text-right"><h5><strong>River Cruise Sub Total: {{Content::money($cruiseBook->sum('net_amount'))}} {{Content::currency()}} </strong></h5></td>
					</tr>
					@endif
				
			</table>
		@endif
		<!-- End cruise  -->
		@if($type == "details")
		<h4 class="text-right"><strong>Grand Total: {{ Content::money($cruiseBook->sum('sell_amount') + $golfBook->sum('book_amount') + $flightBook->sum('book_amount') + $hotelBook->sum('sell_amount') + $tourBook->sum('book_amount'))}} {{Content::currency()}}</strong></h4>
		@endif

		<!-- net pricee -->
		@if($type == "details-net")
		<h4 class="text-right"><strong>Grand Total: {{ Content::money($cruiseBook->sum('net_amount') + $golfBook->sum('book_namount') + $flightBook->sum('book_namount') + $hotelBook->sum('net_amount') + $tourBook->sum('book_namount'))}} {{Content::currency()}}</strong></h4>
		@endif
		<!-- <div class="col-md-12"> -->
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
	    @if($type == "details" || $type == "sales")
	    	@include('admin.report.project_hotel_booking')
	    @endif
	@endif
  	</div>
 </div>



@include('admin.include.editor')

 <script type="text/javascript">
 	$(document).ready(function(){
 		$(document).on("click", ".btnEditTour", function(){
 			// $("#tourBook_desc").val($(this).data('desc'));
 			$("#tour_id").val($(this).data('id'));
 			$("#tour_name").text($(this).data('title'));
 			$('.editor1').html($(this).data('desc'));
 			// tinyMCE.activeEditor.setContent($(this).data('desc'));
 		});
 		$('.Book_desc').on('click',function(){
 			var gettext = $(document).find('.editor1').html();
 			$('#_desc').val(gettext);
 		});
 	});
 </script>
@endsection
