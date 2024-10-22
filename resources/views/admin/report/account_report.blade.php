<?php 
	use App\component\Content;
	$comadd = \App\Company::where('country_id', \Auth::user()->country_id)->first();
	if ($title == "Query") {
		$title = "Request Cash Advance for Entrance Fee & Expenses";
	}else{
		$title = $title;
	}
	$user= App\User::find($project->UserID);
	 
?>
@extends('layout.backend')
@section('title', $title)

@section('content')

<div class="container">
	<div class="col-lg-12">
		<div class="pull-right hidden-print">
			<a href="javascript:void(0)" onclick="window.print();"><span class="fa fa-print btn btn-primary"></span></a>
		</div>		
		<h3 class="text-center"><span style="text-transform:capitalize; text-decoration:underline; font-weight: 700;">{{$title}}</span></h3><br><br>
		<table class="table table-bordered">
			<tr>
				<td style="width:50%;">
					<p><label style="width:120px; margin-bottom:0px;">File No.:</label> {{$project->project_prefix}}-{{$project->project_fileno ? $project->project_fileno: $project->project_number}} </p>
					<p><label style="width:90px; margin-bottom:0px;">Client Name:</label> {{$project->project_client}}</p>
					<p><label style="width:90px; margin-bottom:0px;">Tour Date:</label> {{Content::dateformat($project->project_start)}} - {{Content::dateformat($project->project_end)}}</p>
				</td>
				<td style="width:50%;">
					<p><label style="width:106px; margin-bottom: 0px;">Agent Name:</label> {{{$project->supplier->supplier_name or ''}}}</p>
					<p><label style="width:106px; margin-bottom: 0px;">Reference No.:</label> {{$project->project_book_ref}}</p>
					
					<!-- <p><label style="width:106px; margin-bottom: 0px;">Flight No./Arrival: {{{$project->flightArr->flightno or ''}}} - {{{$project->flightDep->arr_time or ''}}}</label> , &nbsp;&nbsp;  <b>Flight No./Departure:</b>{{{$project->flightDep->flightno or ''}}} - {{{$project->flightDep->dep_time or ''}}}</p> -->
				</td>
			</tr>
		</table>	

		@if($title == "B Form WO/P")
			<table class="table operation-sheed table-bordered">
				<tr class="header-row">
					<th width="10px">No.</th>
					<th>Description</th>
					 
					<th>Service Name</th>
					<th>Date</th>
					<th width="40px" class="text-center">Qty</th>
				</tr>
				<?php 
					$n = 0;
				?>
				@if($hotelb->get()->count() > 0)
					@foreach($hotelb->get() as $hb)
					<tr>
						<td class="text-center">{{$n++}}</td>
						<td>{{{$hb->hotel->supplier_name or ''}}}</td>
						<td>{{{$hb->room->name or ''}}}</td>
						<td>{{Content::dateformat($hb->checkin)}} - {{Content::dateformat($hb->checkout)}}</td>
						<td class="text-center">{{$hb->book_day}}</td>					
					</tr>
					@endforeach
				@endif

				@if($flightb->get()->count() > 0)
					@foreach($flightb->get() as $fb)
					<?php 
						$n++;
					?>
					<tr>
						<td class="text-center">{{$n}}</td>
						<td>{{{$fb->supplier->supplier_name or ''}}}</td>
						<td>{{{$fb->fagent->supplier_name or ''}}}</td>				
						<td>{{Content::dateformat($fb->book_checkin)}}</td>
						<td class="text-center">{{$fb->book_pax}}</td>
					</tr>
					@endforeach
				@endif

				@if($golfb->get()->count() > 0)
					@foreach($golfb->get() as $gb)
					<?php $n++; ?>
					<tr>
						<td class="text-center">{{$n}}</td>
						<td>{{{$gb->golf->supplier_name or ''}}}</td>
						<td >{{{$gb->golf_service->name or ''}}}</td>
						<td>{{Content::dateformat($gb->book_checkin)}}</td>
						<td class="text-center">{{$gb->book_pax}}</td>					
					</tr>
					@endforeach
				@endif

				@if($cruiseb->get()->count() > 0)
					@foreach($cruiseb->get() as $cb)
					<?php $n++;?>
					<tr>
						<td class="text-center">{{$n}}</td>					
						<td>{{{$cb->cruise->supplier_name or ''}}}</td>
						<td>{{{$cb->program->program_name or ''}}}</td>
						<td>{{Content::dateformat($cb->checkin)}} - {{Content::dateformat($cb->checkout)}}</td>
						<td class="text-center">{{$cb->book_day}}</td>
					</tr>
					@endforeach
				@endif

				@if($transportb->get()->count() >0)
					@foreach($transportb->get() as $bk)
					<?php 
						$n++; 
						$dateb = \App\Booking::find($bk->book_id);
					?>
					<tr>	
						<td class="text-center">{{$n}}</td>
						<td>{{{$bk->service->title or ''}}}</td>
				        <td>{{{$bk->vehicle->name or ''}}}</td>
						<td>{{Content::dateformat($dateb->book_checkin)}}</td>
						<td class="text-center"></td>					
					</tr>
					@endforeach
				@endif

				@if($guideb->get()->count() > 0)
					@foreach($guideb->get() as $bg)
					<?php 
						$n++; 
						$dateb = \App\Booking::find($bg->book_id);
					?>
					<tr>	
						<td class="text-center">{{$n}}</td>					
						<td>{{{$bg->service->title or ''}}}</td>
				        <td>{{{$bg->language->name or ''}}}</td>
				        <td>{{Content::dateformat($dateb->book_checkin)}}</td>
						<td></td>
					</tr>
					@endforeach
				@endif

				@if($restaurantb->get()->count() > 0)
					@foreach($restaurantb->get() as $rb)
					<?php $n++; ?>
					<tr>	
						<td class="text-center">{{$n}}</td>
						<td>{{{$rb->supplier->supplier_name or ''}}}</td>         
					    <td>{{{$rb->rest_menu->title or ''}}}</td>
					    <td>{{Content::dateformat($rb->start_date)}}</td>
					    <td class="text-center">{{$rb->book_pax}}</td>
					</tr>
					@endforeach
				@endif

				@if($entranb->get()->count() > 0)
					@foreach($entranb->get() as $rb)
					<?php $n++; ?>
					<tr>	
						<td class="text-center">{{$n}}</td>
						<td>{{{$rb->entrance->name or ''}}}</td>       
						<td></td>
						<td>{{Content::dateformat($rb->start_date)}}</td>
					    <td class="text-center">{{$rb->book_pax}}</td>
					</tr>
					@endforeach
				@endif

				@if($miscb->get()->count() > 0)
					@foreach($miscb->get() as $misc)
					<?php $n++; 
						$dateb = \App\Booking::find($misc->book_id);
					?>
					<tr>	
						<td class="text-center">{{$n}}</td>
						<td>{{{$misc->servicetype->name or ''}}}</td>    
						<td></td>   
						<td>{{Content::dateformat($dateb->book_checkin)}}</td>
					    <td class="text-center">{{$misc->book_pax}}</td>
					</tr>
					@endforeach
				@endif
				<tr>
					<td colspan="2" style="border:none;">Request By:{{{ $user->fullname}}}</td>
					<td colspan="" style="border:none;">Reviewed By:............................</td>
					<td colspan="" style="border:none;">Confirmed By:............................</td>
				</tr>
				<tr><td style="border: none;"></td></tr>
				<tr>
					<td colspan="5" style="border: none;">
						<b>Remark</b>
						<p>{{$remark}}</p>
					</td>
				</tr>
			</table>

		@else
			<table class="table operation-sheed table-bordered">
				<tr class="header-row">
					<th width="10px">No.</th>
					<th>Date</th>
					<th>Description</th>
					<th>Supplier Name</th>
					<th>Service Name</th>
					<th>Tee Time</th>
					<th class="text-center">No of Pax</th>
					<th class="text-center">Price</th>
					<th class="text-center">Amount</th>
					<!-- <th class="text-center">Price</th>
					<th class="text-center">Amount</th> -->
				</tr>
				<?php 
					$n = 0;
				?>
				@if($hotelb->get()->count() > 0)
					@foreach($hotelb->get() as $hb)
					<?php 
						$hbook = \App\Booking::find($hb->book_id);
						$n++;
						if($hb->discount != 0){
							$hprice=$hb->discount;
						}
						elseif (!empty($hb->nsingle) && $hb->nsingle != 0) {
							$hprice = $hb->nsingle;
						}elseif (!empty($hb->ntwin) && $hb->ntwin != 0) {
							$hprice = $hb->ntwin;
						}elseif(!empty($hb->ndouble) && $hb->ndouble != 0){
							$hprice = $hb->ndouble;
						}elseif (!empty($hb->nextra) && $hb->nextra != 0) {
							$hprice = $hb->nextra;
						}else{
							$hprice = $hb->nchextra;
						}					
					?>
					<tr>
						<td class="text-center">{{$n}}</td>
						<td>{{Content::dateformat($hb->checkin)}} - {{Content::dateformat($hb->checkout)}}</td>
						<td>{{{$hb->hotel->supplier_name or ''}}}</td>
						<td>{{{$hb->hotel->supplier_name or ''}}}</td>
						<td>{{{$hb->room->name or ''}}}</td>
						<td></td>
						<td class="text-center">{{$hb->book_day}}</td>
						<td class="text-right">{{$hprice}}  {{Content::currency()}}</td>
						<td class="text-right">{{$hb->net_amount or $hb->net-kamount}}</td>
					
					</tr>
					@endforeach
				@endif

				@if($flightb->get()->count() > 0)
					@foreach($flightb->get() as $fb)
					<?php 
					//dd($fb);
						$n++;
					?>
					<tr>
						<td class="text-center">{{$n}}</td>
						<td>{{Content::dateformat($fb->book_checkin)}}</td>
						<td>{{{$fb->supplier->supplier_name or ''}}}</td>
						<td>{{{$fb->fagent->supplier_name or ''}}}</td>
						<td></td>	
						<td></td>			
						<td class="text-center">{{$fb->book_pax}}</td>
						<td class="text-right">
							@if(!empty($fb->book_nprice))
								{{$fb->book_nprice}}  {{Content::currency()}}
							@else
								{{$fb->book_kprice}}  {{Content::currency(1)}}
							@endif
						<td class="text-right">{{Content::money($fb->book_namount)}}</td>
						
					</tr>
					@endforeach
				@endif

				@if($golfb->get()->count() > 0)
					@foreach($golfb->get() as $gb)
					<?php $n++; ?>
					<tr>
						<td class="text-center">{{$n}}</td>
						<td>{{Content::dateformat($gb->book_checkin)}}</td>
						<td>{{{$gb->golf->supplier_name or ''}}}</td>
						<td>{{{$gb->golf->supplier_name or ''}}}</td>
						<td >{{{$gb->golf_service->name or ''}}}</td>
						 
						<td class="text-center">{{$gb->book_golf_time}}</td>
						<td class="text-center">{{$gb->book_pax}}</td>
						<td class="text-right">@if(!empty($gb->book_price))
								{{$gb->book_nprice}}  {{Content::currency()}}
							@else
								{{$gb->book_nkprice}}  {{Content::currency(1)}}
							@endif</td>
						<td class="text-right">{{Content::money($gb->book_namount)}}</td>
						 
					</tr>
					@endforeach
				@endif

				@if($cruiseb->get()->count() > 0)
					@foreach($cruiseb->get() as $cb)
					<?php 
						$n++;
						if (!empty($cb->nsingle) && $cb->nsingle != 0) {
							$hprice = $cb->nsingle;
						}elseif (!empty($cb->ntwin) && $cb->ntwin != 0) {
							$hprice = $cb->ntwin;
						}elseif(!empty($cb->ndouble) && $cb->ndouble != 0){
							$hprice = $cb->ndouble;
						}elseif (!empty($cb->nextra) && $cb->nextra != 0) {
							$hprice = $cb->nextra;
						}else{
							$hprice = $cb->nchextra;
						}		
					?>
					<tr>
						<td class="text-center">{{$n}}</td>
						<td>{{Content::dateformat($cb->checkin)}} - {{Content::dateformat($cb->checkout)}}</td>
						<td>{{{$cb->cruise->supplier_name or ''}}}</td>
						<td>{{{$cb->cruise->supplier_name or ''}}}</td>
						<td>{{{$cb->program->program_name or ''}}}</td>
						<td></td>
						<td class="text-center">{{$cb->book_day}}</td>
						<td class="text-right">{{Content::money($hprice)}}</td>
						<td class="text-right">{{Content::money($cb->net_amount)}}</td>
						
					</tr>
					@endforeach
				@endif
 
				@if($transportb->get()->count() >0)
					@foreach($transportb->get() as $bk)
					<?php 
						$n++; 
						$dateb = \App\Booking::find($bk->book_id);
						$price = isset($bk->price)? $bk->price:0; 
						$kprice = isset($bk->kprice)? $bk->kprice:0;
						$service = \App\TransportService::find($bk->service_id);
						$vehicle = \App\TransportMenu::find($bk->vehicle_id);
					?>
					<tr>	
						<td class="text-center">{{$n}}</td>
						<td>{{Content::dateformat($dateb->book_checkin)}}</td>
						<td>{{{$service->title or ''}}}</td>
						<td><?php $supplier=\App\Supplier::where('id',$bk->transport_id)->first();?>{{{$supplier->supplier_name or ''}}}</td>
				        <td>{{{$vehicle->name or ''}}}</td>
						<td class="text-center"></td>
						<td></td>
						<td class="text-right">
						@if(!empty($price))
							{{$price}}  {{Content::currency()}}
					
						@endif	
						</td>
						<td class="text-right">
						@if(!empty($price))
							{{$price}}  {{Content::currency()}}
					
						@endif	
						</td>
						
					</tr>
					@endforeach
				@endif

				@if($guideb->get()->count() > 0)
					@foreach($guideb->get() as $bg)
					<?php 
						$n++; 
						$dateb = \App\Booking::find($bg->book_id);
						$price = isset($bg->price)?$bg->price :0;
						$kprice = isset($bg->kprice)? $bg->kprice :0;
						$sb = \App\GuideService::find($bg->service_id);
						$supb = \App\Supplier::find($bg->sup_id);
						$langb = \App\GuideLanguage::find($bg->language_id);
					?>
					<tr>	
						<td class="text-center">{{$n}}</td>
						<td>{{Content::dateformat($dateb->book_checkin)}}</td>
						<td>{{{$sb->title or ''}}}</td>
						<td>{{{$supb->supplier_name or ''}}}</td>
				        <td>{{{$langb->name or ''}}}</td>
						<td></td>
						<td></td>
						<td class="text-right">{{Content::money($price)}}</td>
						<td class="text-right">{{Content::money($price)}}</td>
						<td class="text-right">{{Content::money($kprice)}}</td>
						<td class="text-right">{{Content::money($kprice)}}</td>
					</tr>
					@endforeach
				@endif

				@if($restaurantb->get()->count() > 0)
					@foreach($restaurantb->get() as $rb)
					<?php $n++; ?>
					<tr>	
						<td class="text-center">{{$n}}</td>
						<td>{{Content::dateformat($rb->start_date)}}</td>
						<td>{{{$rb->supplier->supplier_name or ''}}}</td> 
						<td>{{{$rb->supplier->supplier_name or ''}}}</td>         
					    <td>{{{$rb->rest_menu->title or ''}}}</td>
						<td></td>
					    <td class="text-center">{{$rb->book_pax}}</td>
						<td class="text-right">{{Content::money($rb->price)}}</td>
		                <td class="text-right">{{Content::money($rb->amount)}}</td>
		                <td class="text-right">{{Content::money($rb->kprice)}}</td>
	                  	<td class="text-right">{{Content::money($rb->kamount)}}</td>
					</tr>
					@endforeach
				@endif

				@if($entranb->get()->count() > 0)
					@foreach($entranb->get() as $rb)
					<?php $n++; ?>
					<tr>	
						<td class="text-center">{{$n}}</td>
						<td>{{Content::dateformat($rb->start_date)}}</td>
						<td>{{{$rb->entrance->name or ''}}}</td>   
						<td></td>    
						<td></td>
						<td></td>
					    <td class="text-center">{{$rb->book_pax}}</td>
		                <td class="text-right">{{Content::money($rb->price)}}</td>
		                <td class="text-right">{{Content::money($rb->amount)}}</td>
		                <td class="text-right">{{Content::money($rb->kprice)}}</td>
	                  	<td class="text-right">{{Content::money($rb->kamount)}}</td>
					</tr>
					@endforeach
				@endif

				@if($miscb->get()->count() > 0)
					@foreach($miscb->get() as $misc)
					<?php $n++; 
						$dateb = \App\Booking::find($misc->book_id);
					?>
					<tr>	
						<td class="text-center">{{$n}}</td>
						<td>{{Content::dateformat($dateb->book_checkin)}}</td>
						<td >{{{$misc->servicetype->name or ''}}}</td>    
						<td></td>  
						<td></td> 
						<td></td>
					    <td class="text-center">{{$misc->book_pax}}</td>
		                <td class="text-right">{{Content::money($misc->price)}}</td>
		                <td class="text-right">{{Content::money($misc->amount)}}</td>
		                <td class="text-right">{{Content::money($misc->kprice)}}</td>
	                  	<td class="text-right">{{Content::money($misc->kamount)}}</td>
					</tr>
					@endforeach
				@endif
				<tr>
					<td colspan="5" class="text-right" style="border-bottom: none; border-bottom: none;" ></td>
					<td colspan="4" class="text-right" style="border-bottom: none; border-bottom: none;">
						<?php 
							$grandtotal = $hotelb->sum('net_amount') + $flightb->sum('book_namount') + $golfb->sum('book_namount') + 
									$cruiseb->sum('net_amount') + $transportb->sum('price') + $guideb->sum('price') + 
									$restaurantb->sum('amount') + $entranb->sum('amount') + 
									$miscb->sum('amount');
							?>
										<h4><b>Total {{Content::currency()}}: {{Content::money($grandtotal)}}</b></h4>
									</td>
									<!-- <td colspan="2" class="text-right" style="border-bottom: none; border-bottom: none;">
						<?php 
						$ktotal = $flightb->sum('book_nkamount') + $transportb->sum('kprice') + 
									$golfb->sum('book_nkamount') + $guideb->sum('kamount') + 
									$restaurantb->sum('kamount') + $entranb->sum('kamount') + 
									$miscb->sum('kamount'); 
						?>
						<h4><b>Total {{Content::currency(1)}}: {{Content::money($ktotal)}}</b></h4>
					</td> -->
				</tr>
				<tr>
					<td colspan="3" style="border:none;">Request By:............................</td>
					<td colspan="3" style="border:none;">Reviewed By:............................</td>
					<td colspan="3" style="border:none;">Confirmeds By:............................</td>
				</tr>
				<tr><td style="border: none;"></td></tr>
				<tr>
					<td colspan="9" style="border: none;">
						<b>Remark</b>
						<p>{{$remark}}</p>
					</td>
				</tr>
			</table>
		@endif
  	</div>
</div>
@endsection
