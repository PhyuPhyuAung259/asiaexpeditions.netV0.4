@extends('layout.backend')
@section('title', "Operation Report")
<?php 
	use App\component\Content;
	$comadd = \App\Company::find(1); 
	$user = App\User::find($project->check_by);
	$Probooked = App\Booking::where(['book_project'=>$project->project_number, 'book_status'=>1, "book_option"=>0])->get();
	
	?>
@section('content')
	<div class="container">
		<div class="row">
			<form method="GET" target="_blank" action="{{route('requestReport', ['url'=> $project->project_number])}}">
			<div class="col-lg-12">
		    	@include('admin.report.project_header')	 
				<div class="pull-left">
					<p> 
						<b>CRD:</b> {{Content::dateformat($project->project_date)}}, 
						@if($project->project_check )
							Project No. <b>{{$project->project_number}}</b> is Already checked by <b>{{{ $user->fullname or ''}}}</b> at {{Content::dateformat($project->project_check_date)}},
						@endif
						<b>Revised Date</b> {{$project->project_revise}}
					</p> 
				</div>
				<div class="pull-right">
				<a class="btn btn-info" target="_blank" href="{{route('proFormEdit', ['project'=> $project->project_number])}}" title="Edit Project">
                         Edit Project
                        </a>
                @if(\Auth::user()->role_id == 2)
					@if($project->project_status==2) 
					<a class="btn btn-success" target="_blank" href="{{route('changestatus', ['projectNum'=> $project->project_number])}}" title="Confirm">Confirmed</a>
					@else
					<a class="btn btn-info" target="_blank" href="{{route('changestatus', ['projectNum'=> $project->project_number])}}" title="Confirm"> Confirm</a>
					@endif
				@elseif($project->project_status == 2)
				  	<a class="btn btn-success" title="Checked">Checked</a>
				@endif
				
				</div>
				<?php $clientByProject = App\Admin\ProjectClientName::where('project_number', $project->project_number)->get();?>
				@if($Probooked->Count() > 0 )
				<div class="pull-right hidden-print checkingAction" style="display: none;">
					<div class="row">
						<input type="submit" name="btnstatus" value="Payment Voucher" class="btn btn-primary btn-xs">&nbsp;
						<a href="#" class="myConvert"><span class=" btn btn-default btn-xs"><i class="fa fa-download"></i> Download In Excel</span></a>&nbsp;
						@if($clientByProject->count() > 0)
						<a target="_blank" href="{{route('getProjectBooked',  [$project->project_number, 'passenger-manifast'])}}"><span class=" btn btn-primary btn-xs">Passenger Manifast</span></a>&nbsp;
						@endif
						<input type="submit" name="btnstatus" title="Booking Records" value="Booking Records" class="btn btn-primary btn-xs">&nbsp;
						<input type="submit" name="btnstatus" title="Cash Advance" value="Cash Advance" class="btn btn-primary btn-xs">&nbsp;
						<input type="submit" name="btnstatus" title="Booking Form" value="Booking Form" class="btn btn-primary btn-xs">&nbsp;
						<input type="submit" name="btnstatus" title="Account Report" value="Account Report" class="btn btn-primary btn-xs">&nbsp;
						<input type="submit" name="btnstatus" title="Booking Form Without Price" value="B Form WO/P" class="btn btn-primary btn-xs">&nbsp;
						<input type="submit" name="btnstatus" title="Booking Status" value="Booking Status" class="btn btn-primary btn-xs">&nbsp;
						<input type="submit" name="btnstatus" title="Guide Fees" value="Guide Fees" class="btn btn-primary btn-xs">&nbsp;
						<a href="javascript:void(0)" onclick="window.print();"><span class="btn btn-primary btn-xs"><i class="fa fa-print"></i></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
					</div>
				</div>
				@endif<div class="clearfix"></div><br>
				
				@if($project->project_hight)
				<div class="form-group">{!! $project->project_hight !!}</div>
				@endif
				<?php $projectPdF = App\Admin\Photo::where(['project_number'=> $project->project_number])->get(); ?>
				@if($projectPdF->count() > 0)
					<ul>
						@foreach($projectPdF as $key=>$val)
						<li><a target="_blank" href="{{asset('storage/contract/projects')}}/{{$val->name}}">{{$val->original_name}}</a></li>
						@endforeach
					</ul>
				@endif
				@if($Probooked->Count() > 0 )
					<div class="hidden-print">
						<label class="container-CheckBox"> Check All to print services
						  <input type="checkbox" class="checkall" id="check_all" >
						  <span class="checkmark"></span>
						</label>
						
					</div>
				@endif

				<!-- Hotel Start  -->
				<?php $hotelBook= \App\HotelBooked::where(['project_number'=>$project->project_number])->orderBy("checkin");?>	
				<table class="table operation-sheed">
					<tr style="display: none;">
						<td colspan="10" style="border-top: none; border-bottom: 1px solid #f4f4f4; padding-bottom: 0px;">
							<p><b>File/ Project :</b> {{{ $project->project_prefix or ''}}}-{{ isset($project->project_fileno) ? $project->project_fileno: $project->project_number}}</p>
							@if($project['project_client'])
								<b>Client Name/s :</b> {{ $project['project_client'] }}
							@endif
							<p><b>Travelling Date :</b> {{date("d-M-Y", strtotime($project->project_start))}} - {{date("d-M-Y", strtotime($project->project_end))}}</p>							
							@if($project->flightArr)
								<p><b>Flight No./Arrival :</b> {{{ $project->flightArr->flightno or ''}}} - {{{ $project->flightArr->arr_time or '' }}}</p>, 
							@endif
							@if($project->flightDep)
								<p><b>Flight No./Departure :</b> {{{ $project->flightDep->flightno or ''}}} - {{{ $project->flightDep->dep_time or '' }}}</p>
							@endif
						
							@if($project->project_book_consultant)
								<p><b>Travel Consultant :</b> {{$project->project_book_consultant}}</p>, 
							@endif
							@if($project->project_book_ref)
								<p><b>Reference No.: {{$project->project_book_ref}}</b></p>
							@endif
						</td>
						<td class="text-right" width="189px" style="border-top: none; border-bottom: 1px solid #f4f4f4; padding-bottom: 0px;">
							<img src="{{url('storage/avata/'. $comadd['logo'])}}" style="width: 100%;">
						</td>
					</tr>
					@if($hotelBook->get()->count() > 0)
						<tr>
							<th style="border-top: none;">
								<div><strong style="text-transform: capitalize;">hotel OverView</strong></div>
							</th>
						</tr>
						<tr style="background-color:#f4f4f4;">
							<th width="470">Checkin - Checkout</th>
							<th width="200">Hotel</th>
							<th width="120">Room</th>
							<th width="110px" class="text-center">No. Room</th>
							<th class="text-center">Nights</th>
							<th class="text-center" width="85">Single</th>
							<th class="text-center">Twin</th>
							<th class="text-center">Double</th>
							<th class="text-center">Promotion</th>
							<th class="text-center">Extra</th>
							<th class="text-center" width="188">Ch-Extra</th>
						
							<th class="text-left" width="188">Amount</th>
							<th>Operation</th>
						</tr>
						@foreach($hotelBook->get() as $hotel)
						<?php 
							$hjnal = App\AccountJournal::where(['supplier_id'=>$hotel->hotel->id, 'business_id'=>1,'project_number'=>$project->project_number, 'book_id'=>$hotel->id,'status'=>1])->first();
						?>	
						<tr >
							<td>
								<span class="hidden-print" style="position: relative;top:2px;">
									<label class="container-CheckBox">{{Content::dateformat($hotel->checkin)}}<i class="fa fa-long-arrow-right" style="color: #72afd2"></i>{{Content::dateformat($hotel->checkout)}}
									  <input type="checkbox" class="checkall" name="checkedhotel[]" value="{{$hotel->id}}" >
									  <span class="checkmark"></span>
									</label>
								</span>
							</td>
							<td>{{{ $hotel->hotel->supplier_name or ''}}} 
								<!-- <small style="color: #9E9E9E;">{{isset($hotel->remark)? '('.$hotel->remark.')' :''}}</small> -->
							</td>
							<td>{{{ $hotel->room->name or ''}}}</td>
							<td><div class="numberOfRoom" >{{$hotel->no_of_room}}</div></td>
							<td class="text-center">{{ $hotel->book_day}}</td>
							<td class="text-right" width="85px">{{Content::money($hotel->nsingle)}}</td>
							<td class="text-right">{{Content::money($hotel->ntwin)}}</td>
							<td class="text-right">{{Content::money($hotel->ndouble)}}</td>
							<td class="text-center addOption">
								<div class="numberOfRoom" ><span {{$hjnal === null ? '' : "style=background-color:#f6f6f6;cursor:no-drop"}} title="{{$hjnal === null ? 'onClick to Add Discount Rate' : ''}}">{{{$hotel->discount  or '0'}}} </span> </div>

								<!-- @if($hjnal === null) -->
									<div class="wrapping-discount"> 
										<div>
											<div><strong>Add Promotion Rate</strong> 
												<i class="fa fa-times-circle pull-right btnClose" style="cursor: pointer;"></i></div>
											<div class="value" style="padding: 12px;">
												<input type="text" class="form-control input-sm text-center" name="discount" value="{{ $hotel->discount }}" placeholder="00.00">
												<input type="hidden" name="eid" value="{{$hotel->id}}">
												<input type="hidden" name="_token" value="{{ csrf_token() }}">
												<input type="hidden" name="book_day" value="{{$hotel->book_day}}">
												<input type="hidden" name="no_of_room" value="{{$hotel->no_of_room}}">
												
											</div>
											<div><button class="submitDiscount btn-acc btn btn-default" type="button btn-xs">Save</button></div>
										</div>
									</div>
								<!-- @endif -->
							</td>
							<td class="text-right">{{Content::money($hotel->nextra)}}</td>
							<td class="text-right" width="150px">{{Content::money($hotel->nchextra)}}</td>
							
							<td class="text-left" width="300px">{{Content::money($hotel->net_amount)}} 
								<span class="pull-right hidden-print">
									<a href="#" data-id="{{$hotel->id}}" data-remark="{{$hotel->remark}}" class="btn btn-primary btn-xs BtnEdit" data-toggle="modal" data-target="#myModal">Add Remark</a>
									<a title="Hotel Voucher" target="_blank" href="{{route('hVoucher', ['project'=>$hotel->project_number, 'bhotelid'=> $hotel->id, 'bookid'=> $hotel->book_id, 'type'=>'hotel-voucher', 'checkin'=> $hotel->checkin, 'checkout'=> $hotel->checkout])}}"><i style="font-size:16px;position: relative;" class="fa fa-newspaper-o"></i></a>&nbsp;
									<a title="Hotel Booking Form" target="_blank" href="{{route('hVoucher', ['project'=>$hotel->project_number, 'bhotelid'=> $hotel->id, 'bookid'=> $hotel->book_id, 'type'=>'hotel-booking-form', 'checkin'=> $hotel->checkin, 'checkout'=> $hotel->checkout])}}"><i class="fa fa-list-alt" style="font-size:16px;position: relative;"></i></a>&nbsp;
									<span class="changeStatus" data-type="hotel" data-id="{{$hotel->id}}" style="cursor: pointer;">
										@if($hotel->confirm == 0 || $hotel->confirm == null)
											<i class="fa fa-warning (alias)"></i>
										@else
											<i class="fa fa-check-circle"></i>
										@endif
									</span>
								</span>
							</td>
							<td>
							<!-- <a target="_blank" href="{{route('bookingEdit', ['url'=>'hotel', 'id'=>$hotel->book_id])}}" title="Edit hotel">
                                <label class="icon-list ic_edit"></label>
                              </a>&nbsp;
							  <a target="_blank" href="{{route('bapplyRoom', ['pro'=> $hotel->book_project,'hotelid'=>$hotel->hotel_id,'bookid'=> $hotel->book_id])}}" title="Apply room for this hotel" >
                              <i class="fa fa-hotel (alias)" style="font-size: 19px;color: #c38015;"></i>
                            </a>&nbsp; -->
							</td>
						 
						</tr>
						 
							 
						@endforeach
						<tr>
							 
							<td colspan="11" class="text-right"><h5><strong>Total: {{Content::money($hotelBook->sum('net_amount'))}} {{Content::currency()}} </strong></h5></td>
							<td colspan="11" class="text-right"><h5><strong>   </strong></h5></td> 
						</tr>				
					@endif
					<?php 
						$flightBook = App\Booking::flightBook($project->project_number);
					?>
					@if($flightBook->count() > 0)
						<tr>
							<td style="border-top: none;"><strong>Flight Expenses</strong></td>
						</tr>
						<tr style="background-color:#f4f4f4;">
							<th>From <i class="fa fa-space-shuttle" style="color: #72afd2;"></i> To</th>
							<th width="120px">Date</th>
							<th width="160" title="Flight Number">FlightNo.</th>
							<th width="160" title="Departure & Arrival Time"><span title="Departure">Dep</span>|<span title="Arrival">Arr</span> Time</th>
							<th colspan="2" width="119px">Agent</th>
							<th class="text-center">Seats</th>					
							<th class="text-right">Price {{Content::currency()}}</th>
							<th class="text-right">Amount</th>
							<th class="text-right">Price {{Content::currency(1)}}</th>
							<th class="text-left" width="160px">Amount</th>
						</tr>
						@foreach($flightBook->get() as $fl)
							<?php 
							$flprice = App\Supplier::find($fl->book_agent);
							?>			
							<tr>
								<td>
									<label class="container-CheckBox">{{$fl->flight_from}}<i class="fa fa-space-shuttle" style="color: #72afd2;"></i>{{$fl->flight_to}}
									  <input type="checkbox" class="checkall" name="checkedflight[]" value="{{$fl->id}}" >
									  <span class="checkmark"></span>
									</label>
								</td>
								<td>{{Content::dateformat($fl->book_checkin)}}</td>
								<!-- <td>{{$fl->flight_from}}</td>
								<td>{{$fl->flight_to}}</td> -->
								<td>{{$fl->flightno}}</td>
								<td>{{$fl->dep_time}}<i class="fa fa-long-arrow-right" style="color: #72afd2;"></i> {{$fl->arr_time}}</td>
								<td colspan="2">{{{ $flprice->supplier_name or ''}}}</td>
								<td class="text-center">{{$fl->book_pax}}</td>
								<td class="text-right">{{Content::money($fl->book_nprice)}}</td>
								<td class="text-right">{{Content::money($fl->book_namount)}}</td>
								<td class="text-right">{{Content::money($fl->book_kprice)}}</td>
								<td class="text-left">{{Content::money($fl->book_kamount)}}
									<span class="changeStatus pull-right" data-type="flight" data-id="{{$fl->id}}" style="cursor: pointer;">
										@if($fl->book_confirm == 0 || $fl->book_confirm == null)
											<i class="fa fa-warning (alias)"></i>
										@else
											<i class="fa fa-check-circle"></i>
										@endif
									</span>
								</td>
							</tr>
						@endforeach	
						<tr>
							<td colspan="11" class="text-right">
								<h5><strong>
									Total {{Content::currency()}}: {{Content::money($flightBook->sum('book_namount'))}},
									&nbsp;  
									Total {{Content::currency(1)}}: {{Content::money($flightBook->sum('book_kamount'))}}</strong>
								</h5>
							</td>
						</tr>
					@endif
					<!-- end flight  -->
					<?php 
					$golfBook = App\Booking::golfBook($project->project_number);
					?>
					@if($golfBook->count() > 0)
						<tr>
							<th style="border-top:none;" colspan="3">
								<div><strong style="text-transform:capitalize;">golf Courses Overview</strong></div>
							</th>
						</tr>
						<tr style="background-color:#f4f4f4;">
							<th>Golf</th>
							<th width="100px">Date</th>
							<th>Tee Time</th>
							<th colspan="3">Golf Service</th>
							<th class="text-center">Pax</th>
							<th class="text-right">Price {{Content::currency()}}</th>
							<th class="text-right">Amount </th>
							<th class="text-right">Price {{Content::currency(1)}}</th>
							<th class="text-center" width="160px">Amount</th>
							<th>Operation</th>
						</tr>
						@foreach($golfBook->get() as $gf)			
							<?php 
							$gsv = App\GolfMenu::find($gf->program_id);
							?>	
							<tr>
								<td>
									 
									<label class="container-CheckBox"> {{$gf->supplier_name}}
									  <input type="checkbox" class="checkall" name="checkedgolf[]" value="{{$gf->id}}" >
									  <span class="checkmark"></span>
									</label>
								</td>
								
								<td>{{Content::dateformat($gf->book_checkin)}}</td>
								<td>{{$gf->book_golf_time}}</td>
								<td colspan="3">{{{ $gsv->name or ''}}}</td>
								<td class="text-center">{{$gf->book_pax}}</td>			
								<td class="text-right">{{Content::money($gf->book_nprice)}}</td>
								<td class="text-right">{{Content::money($gf->book_namount)}}</td>
								<td class="text-right">{{Content::money($gf->book_nkprice)}}</td>
								<td class="text-left">{{Content::money($gf->book_nkamount)}}
									<span class="changeStatus pull-right hidden-print" data-type="golf" data-id="{{$gf->id}}" style="cursor: pointer;">
										@if($gf->book_confirm == 0 || $gf->book_confirm == null)
											<i class="fa fa-warning (alias)"></i>
										@else
											<i class="fa fa-check-circle"></i>
										@endif
									</span>
								</td>
								<td>
									<!-- <a class="btnEditTran" style="padding:0px;border:none;" data-id="{{$gf->id}}" data-toggle="modal" data-target="#golfModal">
										<i style="padding:1px 2px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i>
									</a>  -->
								</td>
							</tr>
						@endforeach
						<tr>
							<td colspan="11" class="text-right"><h5><strong>
								Total {{Content::currency()}}: {{Content::money($golfBook->sum('book_namount'))}},
								&nbsp;  
								Total {{Content::currency(1)}}: {{Content::money($golfBook->sum('book_nkamount'))}}</strong></h5>
							</td>
						</tr>
					@endif
					<!-- end golf  -->
					<?php $cruiseBook = App\CruiseBooked::where(['project_number'=>$project->project_number]);?>
					@if($cruiseBook->count() > 0)
						<tr><th style="border-top: none;"><div><strong style="text-transform:capitalize;">River Cruise OverView</strong></div></th></tr>
							<tr style="background-color:#f4f4f4;">
								<th width="170px;">Date</th>
								<th>River Cruise</th>
								<th>Program</th>
								<th>Room</th>
								<th style="font-size: 11px;">Night/Cabin</th>
								<th class="text-center" width="85px">Single</th>
								<th class="text-center">Twin</th>
								<th class="text-center">Double</th>
								<th class="text-center">EX-Bed</th>
								<th class="text-center" width="153px">CHE-Bed</th>
								<th class="text-left" width="160px" >Amount</th>
							</tr>
							@foreach($cruiseBook->get() as $crp)			
								<?php 
									$pcr = App\CrProgram::find($crp->program_id);
									$rcr = App\RoomCategory::find($crp->room_id);
								?>	
								<tr>
									<td>
										<label class="container-CheckBox">{{Content::dateformat($crp->checkin)}}<i class="fa fa-long-arrow-right" style="color: #72afd2;"></i>{{ Content::dateformat($crp->checkout)}}
											<input type="checkbox" class="checkall" name="checkedcruise[]" value="{{$crp->id}}" >
										  	<span class="checkmark"></span>
										</label>
									</td>
								
									<td>{{$crp->cruise->supplier_name}}</td>
									<td>{{{ $pcr->program_name or ''}}}</td>
									<td>{{{ $crp->room->name or ''}}}</td>					
									<td class="text-center">{{$crp->book_day}}/ {{$crp->cabin_pax}}</td>
									<!-- <td class="text-center"></td> -->
									<td class="text-right">{{Content::money($crp->nsingle)}}</td>
									<td class="text-right">{{Content::money($crp->ntwin)}}</td>
									<td class="text-right">{{Content::money($crp->ndouble)}}</td>
									<td class="text-right">{{Content::money($crp->nextra)}}</td>
									<td class="text-right">{{Content::money($crp->nchextra)}}</td>
									<td class="text-left">{{Content::money($crp->net_amount)}}
										<span class="pull-right hidden-print">
											<a target="_blank" href="{{route('hVoucher', ['project'=>$crp->project_number, 'bhotelid'=> $crp->id, 'bookid'=> $crp->book_id, 'type'=>'cruise-voucher'])}}"><i style="font-size:16px;position:relative;" class="fa fa-newspaper-o"></i></a>&nbsp;
											<a target="_blank" href="{{route('hVoucher', ['project'=>$crp->project_number, 'bhotelid'=> $crp->id, 'bookid'=> $crp->book_id, 'type'=>'cruise-booking-form'])}}"><i class="fa fa-list-alt" style="font-size:16px;position: relative;"></i></a>
											&nbsp;
											<span class="changeStatus pull-right" data-type="cruise" data-id="{{$crp->id}}" style="cursor: pointer;">
												@if($crp->confirm == 0 || $crp->confirm == null)
													<i class="fa fa-warning (alias)"></i>
												@else
													<i class="fa fa-check-circle"></i>
												@endif
											</span>
										</span>
									</td>
								</tr>
							@endforeach
							<tr>
								<td colspan="11" class="text-right"><h5><strong>Total {{Content::currency()}} : {{Content::money($cruiseBook->sum('net_amount'))}}</strong></h5></td>
							</tr>
					@endif
					<!-- Tour start-->
					<?php 
						$tourNetPrice = \App\Booking::tourNetPrice($project->project_number); 
						$tourTotal =0; 	
					?>
						<tr><th colspan="11" style="border-top:none;"><div><strong style="text-transform: capitalize;">Tour expenses</strong></div></th></tr>
						<tr style="background-color:#f4f4f4;">
							<th width="110px">Date</th>
							<th colspan="7">Title</th>
							<th>Pax</th>
							<th class="text-right" width="160px" >Price {{Content::currency()}}</th>
							<!-- <th class="text-right" width="160px" >Price {{Content::currency(1)}}</th> -->
							<th>Amount</th>
							<th>Operation</th>
						</tr>	
						@foreach($tourNetPrice->get() as $tour)
						<?php
							$amount=$tour->book_pax * $tour->nprice;
							$tourTotal = $tourTotal + $amount; 
						?>
							<tr>
							<td>
									<label class="container-CheckBox">{{Content::dateformat($tour->book_checkin)}}
									  	<input type="checkbox" class="checkall" name="checkedtransport[]" value="{{isset($btran->id) ? $btran->id : '' }}" >
									  	<span class="checkmark"></span>
									</label>
								</td>
								<td colspan="7">{{{$tour->tour_name or ''}}}</td>
								<td class="text-right" width="160px" >{{{$tour->book_pax or ''}}}</td>
								<td class="text-right" width="160px" >{{{$tour->nprice}}}</td>
								<!-- <td class="text-right" width="160px" >{{{$tour->nprice}}}</td> -->
								<td>{{{$amount or ''}}}</td>
								<td> 
									 <a target="_blank" href="{{route('getBookingVoucher', ['type'=>'Tour',$tour->book_project, $tour->id])}}" title="View Booking Voucher">
							               	<label class="fa fa-list-alt btn btn-xs" style="font-size:17px; color: #527686;"></label>
							            </a> 
								</td>
							</tr>
						@endforeach
						<tr>
						<td colspan="11" class="text-right">
								
									<h5><strong>Total {{Content::currency()}}: {{Content::money($tourTotal)}}</strong></h5>
								
								
							</td>
						</tr>
					<!--  Tour end-->
					<!-- Transport Start-->
					<?php 
						$tranBook = \App\Booking::tourBook($project->project_number); 
						$transportTotal = 0;
						$transportkTotal = 0;
						// dd($tranBook->get()->count());
					?>
					@if($tranBook->get()->count() != 0)
						<tr><th colspan="11" style="border-top:none;"><div><strong style="text-transform: capitalize;">transport expenses</strong></div></th></tr>
						<tr style="background-color:#f4f4f4;">
							<th width="110px">Date</th>
							<!-- <th>City</th> -->
							<th colspan="3">Title</th>
							<th colspan="2">Service</th>
							<th>Vehicle</th>
							<th>Car Company</th>
							<th width="100px;">Phone</th>
							<th class="text-right" width="160px" >Price {{Content::currency()}}</th>
							<th class="text-right" width="160px" >Price {{Content::currency(1)}}</th>
							<th>Operation</th>
						</tr>			
						@foreach($tranBook->get() as $tran)
							<?php 
								//dd("Transport",$tran);
								//$pro   = App\Province::find($tran->province_id); 
								$btran = App\BookTransport::where(['project_number'=>$tran->book_project, 'book_id'=>$tran->id])->first();
								//dd($btran);
								$price = isset($btran->price)? $btran->price:0; 
								$kprice = isset($btran->kprice)? $btran->kprice:0;
								$transportTotal = $transportTotal + $price;
								$transportkTotal = $transportkTotal + $kprice;
								if ($btran !== null && is_object($btran)) {
                                    $transportId = $btran->transport_id;
                                    $supplier= App\Supplier::where(['id'=> $transportId])->first();
                                } 
							?>
							<tr>
								<td>
									<label class="container-CheckBox">{{Content::dateformat($tran->book_checkin)}}
									  	<input type="checkbox" class="checkall" name="checkedtransport[]" value="{{isset($btran->id) ? $btran->id : '' }}" >
									  	<span class="checkmark"></span>
									</label>
								</td>
								<td colspan="3">{{ $tran->tour_name }} </td>
								<td colspan="2">{{{ $btran->service->title or ''}}}</td>
				                <td>{{{ $btran->vehicle->name or ''}}}</td>
				                <td>{{{ $supplier->supplier_name or ''}}}</td>
				                <td>{{{ $supplier->supplier_phone or ''}}}</td> 
				                <td class="text-right">{{ Content::money($price) }}</td>
			                  	<td class="text-right">{{ Content::money($kprice) }}</td>
								<td>
									<a target="_blank" href="{{route('editoperation', ['type'=>'Transport', 'id'=>$tran->id, 'project_no'=>$tran->book_project , 'tour_id'=>$tran->tour_id])}}" title="Edit Transport">
                                		<label class="icon-list ic_edit"></label>
                             		</a>&nbsp;
									 <a target="_blank" href="{{route('editoperation', ['type'=>'Transport', 'id'=>$tran->id, 'project_no'=>$tran->book_project , 'tour_id'=>$tran->tour_id, 'sub_type'=>'additional transport'])}}" title="Additional Transport">
                                			<label class="icon-list ic_book_add"></label>
                             			</a>&nbsp;
									 <a target="_blank" href="{{route('getBookingVoucher', ['type'=>'Transport',$tran->book_project, $tran->id])}}" title="View Transport Booking">
							               	<label class="fa fa-list-alt btn btn-xs" style="font-size:17px; color: #527686;"></label>
							            </a>  
								</td>
							</tr>				
						@endforeach
						<tr>
							<td colspan="10" class="text-right">
								@if($transportTotal > 0)
									<h5><strong>Total {{Content::currency()}}: {{Content::money($transportTotal)}}</strong></h5>
								@endif
								
							</td>
							<td class="text-right">
								@if($transportkTotal > 0)
									<h5><strong>Total {{Content::currency(1)}}: {{Content::money($transportkTotal)}}</strong></h5>
								@endif
							</td>
						</tr>
					@endif
					<!-- End Transport -->
					<!-- Guide Start-->
						<?php 
							$guideBook = \App\Booking::tourBook($project->project_number); 
						//	dd($guideBook->count());
							$guidTotal = 0;
							$guidkTotal = 0;
						?>
						@if($guideBook->count() != 0)
							<tr>
								<th colspan="12" style="border-top: none;"><div><strong style="text-transform: capitalize;">guide expenses</strong></div>
								</th>
							</tr>
							<tr style="background-color:#f4f4f4;">
								<th>StartDate</th>
								<!-- <th>City</th> -->
								<th colspan="3">Tour</th>
								<th colspan="2">Service</th>
								<th>Guide</th>
								<th>Phone</th>
								<th>Language</th>
								<th class="text-right">Price {{Content::currency()}}</th>
								<th class="text-right" width="160px">Price{{Content::currency(1)}}</th>
								<th>Operation</th>
							</tr>			
							@foreach($guideBook->get() as $tran)
							<?php 
								//dd("guide",$tran);
								$pro = \App\Province::find($tran->province_id);
								$bg  = App\BookGuide::where(['project_number'=>$tran->book_project,'book_id'=>$tran->id])->first(); 
								$price = isset($bg->price)?$bg->price :0;
								$guidTotal = $guidTotal + $price;
								$kprice = isset($bg->kprice)? $bg->kprice :0;
								$guidkTotal = $guidkTotal + $kprice;
							?>
							<tr>
								<td>
									<label class="container-CheckBox">{{Content::dateformat($tran->book_checkin)}}
										<input type="checkbox" class="checkall" name="checkedguide[]" value="{{isset($bg->id) ? $bg->id : ''}}">
										<span class="checkmark"></span>
									</label>
								</td> 					
								<td colspan="3">{{$tran->tour_name}} </td>     
								<td colspan="2">{{{ $bg->service->title or '' }}}</td>
								<td>{{{ $bg->supplier->supplier_name or ''}}} </td>
								<td>
									@if(isset($bg->supplier->phone) || isset($bg->supplier->phone2))
										{{$bg->supplier->phone}} {{ $bg->supplier->phone2}}
									@else
										{{{$bg->phone or ''}}}
									@endif
								</td> 
								<td>{{{$bg->language->name or ''}}}</td>
								<td class="text-right">{{Content::money($price)}}</td>
								<td class="text-right">{{Content::money($kprice)}}</td>
								<td><a target="_blank" href="{{route('editguideoperation', ['type'=>'guide', 'project_no'=>$tran->book_project, 'id'=>$tran->id , 'tour_id'=>$tran->tour_id])}}" title="Edit Guide">
                                			<label class="icon-list ic_edit"></label>
                             			</a>&nbsp;
										 <a target="_blank" href="{{route('editguideoperation', ['type'=>'guide', 'project_no'=>$tran->book_project, 'id'=>$tran->id , 'tour_id'=>$tran->tour_id, 'sub_type'=>'additional guide'])}}" title="Additional Guide">
                                			<label class="icon-list ic_book_add"></label>
                             			</a>&nbsp;
										
								</td>
							</tr>				
							@endforeach
							<tr>
								<td colspan="10" class="text-right">
									@if($guidTotal > 0 )
										<h5><strong>Total {{Content::currency()}}: {{Content::money($guidTotal)}}</strong></h5>
									@endif
								</td>
								<td class="text-right">
									@if($guidkTotal > 0)
										<h5><strong>Total {{Content::currency(1)}}: {{Content::money($guidkTotal)}}</strong></h5>
									@endif
								</td>
							</tr>
						
						@endif
					<!-- End Guide -->

					<!-- Restaurant Start-->
					<?php
					$restBook = \App\BookRestaurant::where('project_number', $project->project_number)->orderBy('start_date');?>
						@if($restBook->count() > 0)
							<tr>
								<th style="border-top: none;">
									<div><strong style="text-transform:capitalize;">restaurant expenses</strong></div>
								</th>
							</tr>
							<tr style="background-color:#f4f4f4;">
				                <th width="110px">Start Date</th>
				                <th colspan="2">Restaurant Name</th>
				                <th colspan="3">Menu</th>
				                <th>Pax</th>
				                <th class="text-right">Price {{Content::currency()}}</th>
				                <th class="text-right">Amount</th>
				                <th class="text-right">Price {{Content::currency(1)}}</th>
				                <th class="text-right">Amount</th>
								<th>Operation</th>
			                </tr>		
							@foreach($restBook->get() as $rest)
							<tr>
								<td>
									<label class="container-CheckBox">{{Content::dateformat($rest->start_date)}}
									  <input type="checkbox" class="checkall" name="checkedRest[]" value="{{$rest->id}}" >
									  <span class="checkmark"></span>
									</label>
								</td>								
				                <td colspan="2">{{{$rest->supplier->supplier_name or ''}}} </td>         
				                <td colspan="3">{{{$rest->rest_menu->title or ''}}}</td>
				                <td>{{$rest->book_pax}}</td>
				                <td class="text-right">{{Content::money($rest->price)}}</td>
				                <td class="text-right">{{Content::money($rest->amount)}}</td>
				                <td class="text-right">{{Content::money($rest->kprice)}}</td>
			                  	<td class="text-right">{{Content::money($rest->kamount)}}</td>
								<td><a target="_blank" href="{{route('editoperation', ['type'=>'restaurant', 'id'=>$rest->id , 'project_no'=>$rest->project_number])}}" title="Edit Restaurant">
                                			<label class="icon-list ic_edit"></label>
                             			</a>&nbsp;</td>
							</tr>				
							@endforeach
							<tr>
								<td colspan="10" class="text-right">
									@if($restBook->sum('amount') > 0 )
										<h5><strong>Total {{Content::currency()}}: {{Content::money($restBook->sum('amount'))}}</strong></h5>
									@endif
								</td>
								<td class="text-right">
									@if($restBook->sum('kamount') > 0)
										<h5><strong>Total {{Content::currency(1)}}: {{Content::money($restBook->sum('kamount'))}}</strong></h5>
									@endif
									
								</td>
							</tr>
						@endif
					<!-- End Restaurant -->

					<!-- Entrance Fees Start-->
					<?php 
					$EntranceBook = \App\BookEntrance::where('project_number', $project->project_number)->orderBy('start_date', 'ASC'); ?>
						@if($EntranceBook->count() > 0)
							<tr><th colspan="11" style="border-top: none;"><div><strong style="text-transform: capitalize;">entrance fees expenses</strong></div></th></tr>
							<tr style="background-color:#f4f4f4;">
								<th width="100px">Start Date</th>
			                  	<th colspan="5">Entrance Fees</th>
			                  	<th>Pax</th>
			                  	<th class="text-right">Price {{Content::currency()}}</th>
			                  	<th class="text-right">Amount</th>
			                  	<th class="text-right">Price {{Content::currency(1)}}</th>
			                  	<th class="text-right" width="160px">Amount</th>
								<th>Operation</th>
							</tr>			
							@foreach($EntranceBook->get() as $rest)
							<?php $pro = \App\Province::find($rest->province_id); ?>
							<tr>
								<td>
									<label class="container-CheckBox">{{Content::dateformat($rest->start_date)}}
									  <input type="checkbox" class="checkall" name="checkedentran[]" value="{{$rest->id}}" >
									  <span class="checkmark"></span>
									</label>
								</td>
				                <td  colspan="5">{{{$rest->entrance->name or ''}}}</td>
				                <td>{{$rest->book_pax}}</td>
				                <td class="text-right">{{Content::money($rest->price)}}</td>
				                <td class="text-right">{{Content::money($rest->amount)}}</td>
				                <td class="text-right">{{Content::money($rest->kprice)}}</td>
			                  	<td class="text-right">{{Content::money($rest->kamount)}}</td>
								<td>
									<a target="_blank" href="{{route('editoperation', ['type'=>'entrance', 'id'=>$rest->id, 'project_no'=>$rest->project_number])}}" title="Edit Entrance Fee">
                                			<label class="icon-list ic_edit"></label>
                             		</a>&nbsp;
								</td>
							</tr>				
							@endforeach
							<tr>
								<td colspan="9" class="text-right">
									@if($EntranceBook->sum('amount') > 0 )
										<h5><strong>Total {{Content::currency()}}: {{Content::money($EntranceBook->sum('amount'))}}</strong></h5>
									@endif
								</td>
								<td colspan="2" class="text-right">
									@if( $EntranceBook->sum('kamount') > 0)
										<h5><strong>Total {{Content::currency(1)}} : {{Content::money($EntranceBook->sum('kamount'))}}</strong></h5>
									@endif
								</td>
							</tr>
						@endif
					<!-- End Entrance Fees -->

					<!-- MISC Start-->
					<?php 
					$MiscBook = \App\Booking::tourDetailsBook($project->project_number); 
						$miscTotal = 0;
						$misckTotal = 0;
						?>
						@if($MiscBook->count() > 0)
							<tr>
								<th colspan="11" style="border-top: none;">
									<div><strong style="text-transform: capitalize;">MISC expenses</strong></div>
								</th>
							</tr>
							<tr style="background-color:#f4f4f4;">
								<th>Date</th>
								<!-- <th width="120px">City</th> -->
								<th colspan="10">Title</th>	
								<th>Operation</th>					
							</tr>			
							@foreach($MiscBook->get() as $tour)
								<?php 
									$pro = \App\Province::find($tour->province_id); 
									$miscService = App\BookMisc::where(['project_number'=>$tour->book_project,'book_id'=>$tour->id])->orderBy("created_at", "DESC")->get();?>
								<tr>
									<td>
					                	<label class="container-CheckBox">{{Content::dateformat($tour->book_checkin)}}
										  <input type="checkbox" class="checkall" name="checkedmisc[]" value="{{$tour->id}}" >
										  <span class="checkmark"></span>
										</label>
					                </td>
					                <td colspan="10">
					                  	<div><strong>{{$tour->tour_name}}</strong></div>
							            @if($miscService->count() > 0) 
							            	<hr style="border-top:none; border-bottom: 1px solid #ddd;padding: 5px 0px; margin-top:0px; margin-bottom: 0px;">
						                  	<div class="row "style="font-style: italic;">
							                  	<label class="col-md-4 ">
							                  		<strong class="pcolor">Service Name</strong>
							                  	</label>
							                  	<label class="col-md-1 ">
							                  		<label class="row">
							                  			<strong class="pcolor">PaxNo.</strong>
							                  		</label>
							                  	</label>
							                  	<label class="col-md-1 ">
							                  		<label class="row">
								                  		<strong class="pcolor">Price{{Content::currency()}}</strong>
								                  	</label>
							                  	</label>
							                  	<label class="col-md-1 ">
							                  		<label class="row">
								                  		<strong class="pcolor">Amount</strong>
								                  	</label>
							                  	</label>
							                  	<label class="col-md-1 ">
							                  		<label class="row">
								                  		<strong class="pcolor">Price{{Content::currency(1)}}</strong>
								                  	</label>
							                  	</label>
							                  	<label class="col-md-2 pcolor text-right">
								                  	<strong class="pcolor">Amount</strong>
							                  	</label>
												  <label class="col-md-2 pcolor text-right">
								                  	<strong class="pcolor">Operation</strong>
							                  	</label>
						                  	</div>		                
							            	@foreach($miscService as $misc)
							            	<?php 
								            	$miscTotal = $miscTotal + $misc->amount;
								            	$misckTotal = $misckTotal + $misc->kamount;
							            	?>
						                  	<div class="row">
							                  	<label class="col-md-6" style="font-weight: 400;">
							                  	    <span>{{{ $misc->servicetype->name or '' }}}</span>
							                  	</label>
							                  	<label class="col-md-1" style="font-weight: 400;">
							                  		<p>{{$misc->book_pax}}</p>
							                  	</label>
							                  	<label class="col-md-1" style="font-weight: 400;">
							                  		<p>{{Content::money($misc->price)}}</p>
							                  	</label>
							                  	<label class="col-md-1" style="font-weight: 400;">
							                  		<p>{{Content::money($misc->amount)}}</p>
							                  	</label>
							                  	<label class="col-md-1" style="font-weight: 400;">
							                  		<p>{{Content::money($misc->kprice)}}</p>
							                  	</label>
							                  	<label class="col-md-2 text-right" style="font-weight: 400;">
							                  		<span>{{Content::money($misc->kamount)}}</span> 
							                  	</label>
												  <label class="col-md-2 text-right" style="font-weight: 400;">
							                  		<span><a class="btnEditTran" data-type="apply_misc" href="#" data-id="{{$misc->id}}" data-country="{{$misc->country_id}}" data-province="{{$misc->province_id}}" data-pax="{{$misc->book_pax}}" data-restmenu="{{$misc->service_id}}" data-price="{{$misc->price}}" data-kprice="{{$misc->kprice}}" data-remark="{{$misc->remark}}" data-toggle="modal" data-target="#miscModal"><i style="font-size: 16px;" class="fa fa-pencil"></i></a></span> 
							                  	</label>
							                  	<div class="clearfix"></div>
						                  	</div>
						                  	@endforeach
						                @endif
				                  	</td>
									<td style="border-left:solid thin dark">
									<a target="_blank" href="{{route('editoperation', ['type'=>'misc', 'id'=>$tran->id, 'project_no'=>$tran->book_project , 'tour_id'=>$tran->tour_id])}}" title="Edit Misc">
								<i style="padding:1px 2px;" class="fa fa-plus-circle btn btn-info btn-xs"> </i>
                             	</a>&nbsp; 
									</td>	                                     
				                </tr>			
							@endforeach
							<tr>
								<td colspan="10" class="text-right">
									@if($miscTotal > 0)
										<h5><strong>Total {{Content::currency()}}: {{Content::money($miscTotal)}}</strong></h5>
									@endif
									
								</td>
								<td class="text-right" colspan="2">
									@if($misckTotal > 0) 
										<h5><strong>Total {{Content::currency(1)}}: {{Content::money($misckTotal)}}</strong></h5>
									@endif
								</td>
							</tr>
						@endif

				@if($Probooked->count() > 0 )
					<?php 
						$grandtotal = ($hotelBook->sum('net_amount') + $flightBook->sum('book_namount') + $golfBook->sum('book_namount') + $cruiseBook->sum('net_amount') + $restBook->sum('amount') + $EntranceBook->sum('amount')) + $transportTotal + $guidTotal + $miscTotal+$tourTotal;
						$grandktotal = ($flightBook->sum('book_kamount') + $golfBook->sum('book_nkamount') + $restBook->sum('kamount') + $EntranceBook->sum('kamount')) + $transportkTotal + $guidkTotal + $misckTotal;
					?>
						<!-- End MISC -->
						<tr>
							<th rowspan="2" colspan="5" style="border-top: none;" >
								<div class="hidden-print">
									<label class="label-control">Remark</label>
									<textarea class="form-control" rows="5" name="remark" placeholder="Type remark here...">{{$project->project_note}}</textarea>
								</div>
							</th>
							<th style="border-top: none;" colspan="6">
								<h4 class="text-right" style="font-size: 17px;">
								<strong>
								Sub Total {{Content::currency()}}: {{ number_format($grandtotal,2)}}
								&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 
								Sub Total {{Content::currency(1)}}: {{ number_format($grandktotal,2)}}
								</strong>
								</h4> 
							</th>
						</tr> 

						<tr>
							<th style="border-top: none;" colspan="11">
								<h4 class="text-right">
									<strong>
										<?php 
											$getExRate = $project->project_ex_rate> 0? $grandktotal / $project->project_ex_rate:"0";
										?>
										Ex-{{Content::currency(1)}} To {{Content::currency()}}: {{ number_format($getExRate,2) }}
										&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 
										GRAND TOTAL {{Content::currency()}}: {{ number_format(($grandtotal + $getExRate), 2)}}
									</strong>
								</h4> 
							</th>
						</tr>
						<tr>
							<td style="border-top: none;" colspan="3">Prepared by <b>.....................................................</b></td>
							<td style="border-top: none;" colspan="3">Checked by <b>.....................................................</b></td>
							<td style="border-top: none;" colspan="5" class="text-right">Approved by <b>.....................................................................</b></td>
						</tr>
					@endif
				</table>
		  	</div>
		  </form>
		</div><br><br>
	</div>
	<!-- Remark modal start -->
	<div class="modal" id="myModal" role="dialog" data-backdrop="static" data-keyboard="true">
		<div class="modal-dialog modal-md">
		  <form method="POST" action="{{route('addHotelRemark')}}">
			<div class="modal-content">       
			  <input type="hidden" name="bhotelId" id="seviceid"> 
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><strong>Add Remark</strong></h4>
			  </div>
			  <div class="modal-body">
				{{csrf_field()}} 
				<div class="row">
				  <div class="col-md-12">
					<div class="form-group">
					  <label>Remark</label>
					  <textarea class="form-control" rows="4" id="Reremark" name="remark" placeholder="Remark here..."></textarea>
					</div>
				  </div>
				</div>
				<div class="modal-footer" style="text-align: center;">
					<button type="submit" class="btn btn-success btn-flat btn-sm">Save</button>
					<a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Close</a>
				</div>
			  </div>     
			</div>   
		  </form>
		</div>
	</div>
	<!-- Remark modal end -->
	<!-- misc modal start -->
	<div class="modal fade" id="miscModal" role="dialog" data-backdrop="static" data-keyboard="true">
		<div class="modal-dialog modal-lg">
			<form method="POST" action="{{route('assignMisc')}}">
				<div class="modal-content">        
					<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><strong> Miscellaneouse Assignment</strong></h4>
					</div>
					<div class="modal-body">
					{{csrf_field()}}    
						<input type="hidden" name="bookid" id="tour_id">
						<input type="hidden" name="project_number" id="project_number" value="{{$project->project_number}}">
						<div class="row">
							<div class="col-md-12 col-xs-12">
								<div class="form-group">
									<label>Start Date</label> 
									<input type="date" name="start_date" class="form-control book_date" placeholder="Start Date"">	
								</div> 
							</div>	
							<div class="col-md-6 col-xs-6">
								<div class="form-group">
									<label>Country <span style="color:#b12f1f;">*</span></label> 
									<select class="form-control country" id="country" name="country" data-type="country" data-pro_of_bus_id="misc_type" data-locat="data" data-title="6" required>
										<option value="">--Choose--</option>
									@foreach(App\Country::countryByProject() as $con)
										<option value="{{$con->id}}">{{$con->country_name}}</option>
									@endforeach
									</select>
								</div> 
							</div>
							<div class="col-md-6 col-xs-6">
								<div class="form-group">
									<label>City Name <span style="color:#b12f1f;">*</span></label> 
									<select class="form-control province" name="city" data-type="apply_misc" id="dropdown-country"  data-title="Miscellaneouse" required>
									<option value="">--Choose--</option>
									@foreach(App\Province::where(['province_status'=> 1])->orderBy('province_name')->get() as $pro)
										<option value="{{$pro->id}}">{{$pro->province_name}}</option>
									@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-xs-6">
								<div class="form-group">
									<label>Service Type</label>
									<select class="form-control tran_name service_type" name="service_type" id="dropdown-apply_misc">
										<option>Select Service</option>
										@foreach(App\MISCService::where(['status'=> 1])->orderBy('name', 'ASC')->get() as $sv)
										<option value="{{$sv->id}}" data-price="{{$sv->price}}" data-kprice="{{ $sv->kprice }}">{{$sv->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-6 col-xs-6">
								<div class="form-group">
									<label>Pax No.</label>
									<input type="number" name="book_pax" id="book_pax" class="form-control text-center" value="1">
								</div>
							</div>
							<div class="col-md-12 col-xs-12 ">
								<strong style="color:red;">To make changes to the Service Type, you will need to select the City Name again.</strong>
							</div>
							<div class="col-md-6 col-xs-6">
								<div class="form-group">
									<label>Price {{Content::currency()}}</label>
									<input type="text" name="price" id="price" class="form-control" placeholder="00.0" >
								</div>
							</div>
							<div class="col-md-6 col-xs-6">
								<div class="form-group">
									<label>Price {{Content::currency(1)}}</label>
									<input type="text" name="kprice" id="kprice" class="form-control" placeholder="00.0" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-xs-12">
								<div class="form-group">
									<label>Remark</label>
									<textarea class="form-control" name="remark" id="remark" rows="5" placeholder="Remark here..."></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer" style="text-align: center;">
						<button type="submit" class="btn btn-success btn-flat btn-sm">Publish</button>
						<a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Cancel</a>
					</div>
				</div>      
			</form>
		</div>
	</div>
	<!-- misc modal end -->
	<!-- golf modal start -->
	<div class="modal fade" id="golfModal" role="dialog" data-backdrop="static" data-keyboard="true">
		<div class="modal-dialog modal-md">
			<form method="POST" action="{{route('updateTeetime')}}">
				<div class="modal-content">        
					<div class="modal-header" >
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><strong id="form_title">What time you want to play</strong></h4>
					</div>
					<div class="modal-body">
						{{csrf_field()}}    
						<input type="hidden" name="bookid" id="tour_id">
						<div class="row">
							<div class="col-md-4 col-xs-6">
								<div class="form-group">
									<label>Hours</label>
									<select class="form-control" name="hour">
										@for($i=12; $i>= 1; $i--)
										<option value="{{$i}}">{{$i}}</option>
										@endfor
									</select>
								</div>
							</div>
							<div class="col-md-4 col-xs-3">
								<div class="form-group">
									<label>Minute</label>
									<select class="form-control" name="minute">
										@for($i=59; $i>= 0; $i--)
											<?php $mi = $i <= 9 ? "0".$i : $i; ?>
											<option value="{{$mi}}">{{$mi}}</option>
										@endfor
								</select>
								</div>
							</div>	
							<div class="col-md-4 col-xs-3">
							<div class="form-group">
								<label>Start</label>
								<select class="form-control" name="start">
									<option value="AM">AM</option>
									<option value="PM">PM</option>
								</select>
							</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat btn-sm">Confirm</button>
					<a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Cancel</a>
					</div>
				</div>      
			</form>
		</div>
	</div>
	<!-- golf modal end -->
	  <script type="text/javascript">
		$(document).ready(function(){
		  $(".BtnEdit").on('click', function(){
			$("#seviceid").val($(this).data('id'));
			$("#Reremark").val($(this).data('remark'))
		  })
		})
	  </script>
	<script type="text/javascript" src="{{asset('js/jquery.table2excel.min.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#check_all").click(function () {
				if($("#check_all").is(':checked')){
			     	$(".checkall").prop('checked', true);
				} else {
				    $(".checkall").prop('checked', false);
				}
			});

			$(".myConvert").click(function(){
				if(confirm('Do you to export in excel?')){
					$(".operation-sheed").table2excel({
						exclude: ".noExl",
						name: "Operation Expenses {{$project->project_number}}",
						filename: "Operation Expenses {{$project->project_number}}",
						fileext: ".xls",
						exclude_img: true,
						exclude_links: true,
						exclude_inputs: true						
					});
					return false;
				}else{
					return false;
				}
			});

			$(document).on('click', '.numberOfRoom', function() {
				$(this).closest('tr').find('td .wrapping-discount').fadeIn();
				// $(this).closest('tr').find('td .wrapping-discount').fadeOut();
			});

			$(document).on("click", '.btnClose', function() {
				$(this).closest('tr').find('td .wrapping-discount').fadeOut();
			});

		});

		$(document).ready(function(){
	        $('input[type="checkbox"]').click(function(){
	        	var checkBotton = false;
	        	$(".checkall").each(function(i, v){
	        		if($(v).prop("checked") == true){
		                checkBotton = true;
		            }
	        	});

	            if(checkBotton){
	                $(".checkingAction").fadeIn();
	            }else{
	            	$(".checkingAction").fadeOut();
	            }
	        });
	    });
	</script>
@endsection
