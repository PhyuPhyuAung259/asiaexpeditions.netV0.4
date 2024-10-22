@extends('layout.backend')
@section('title', $title)
<?php 
	use App\component\Content;
	$comadd = \App\Company::where('country_id', \Auth::user()->country_id)->first();
?>

@section('content')
@if($guideb->get()->count() > 0)
<?php 
	$guid = \App\BookGuide::whereIn("guide_book.id", $_GET['checkedguide'])->first();
	$guid->supplier->supplier_name;
?>
@endif
<div class="container">
	@include('admin.report.headerReport')	
	<div class="col-lg-12">
		<div class="pull-right hidden-print">
			<a href="javascript:void(0)" onclick="window.print();"><span class="fa fa-print btn btn-primary"></span></a>
		</div>		
		<h3 class="text-center"><span style="text-transform:capitalize; text-decoration:underline;"> Request for Guide Fees</span></h3><br><br>		
		<table class="table ">
			<tr><td style="border: none;"></td><td style="border: none;"><div><label> Issue Date</label>: {{ Content::dateformat(date('Y-m-d') ) }}</div></td></tr>
			<tr>
				<td style="width: 15%; border: none;" class="text-right">
					<p>Guide Name: </p>
					<p>Tour File / Project No.:</p>
					<p>Client Name: </p>
					<p>Tour Date:</p>
				</td>
				<td style="width: 85%; vertical-align: top; border: solid 1px #ddd;">
					<p>{{$guideb->get()->count() > 0 ? $guid->supplier->supplier_name : '............................................'}} </p>
					<p> {{$project->project_prefix}}-{{$project->project_fileno ? $project->project_fileno: $project->project_number}}</p>
					<p> {{{ $project->project_client or ''}}} </p>
					<p> <i>{{ Content::dateformat($project->project_start) }} - {{ Content::dateformat($project->project_end) }}</i></p>
				</td>
			</tr>
			
		</table>	


		<table class="table operation-sheed table-bordered">
		<?php $n = 1; 
			$getTotalUSd = 0;
			$getTotalkyat = 0;
		?>
			<tr class="header-row">
				<th width="10px"><b>No.</b></th>
				<th>Description</th>
				<th>Service Name</th>
				<th class="text-center">Date</th>
				<th class="text-center">Qty</th>
				<th class="text-center">{{Content::currency()}} Price </th>
				<th class="text-center">Amount {{Content::currency()}}</th>
				<th class="text-center">{{Content::currency(1)}} Price </th>
				<th class="text-center">Amount {{Content::currency(1)}}</th>
			</tr>
			@if($hotelb->get()->count() > 0)
				@foreach($hotelb->get() as $hb)
				<?php 
					$hbook = \App\Booking::find($hb->book_id);
				?>
				<tr>
					<td class="text-center">{{$n++}}</td>
					<td>{{{$hb->hotel->supplier_name or ''}}}</td>
					<td class="text-left">{{{$hb->room->name or ''}}}</td>
					<td>{{Content::dateformat($hb->checkin)}} - {{Content::dateformat($hb->checkout)}}</td>
					<td class="text-center">{{$hb->book_day}}</td>
					<td class="text-right">
						@if( $hb->nsingle > 0 )
							<?php $hprice = $hb->nsingle; ?>
						@elseif($hb->ntwin > 0)
							<?php $hprice = $hb->ntwin; ?>
						@elseif($hb->ndouble > 0)
							<?php $hprice = $hb->ndouble; ?>
						@elseif($hb->nextra > 0)
							<?php $hprice = $hb->nextra; ?>
						@else
							<?php $hprice = $hb->nchextra; ?>
						@endif
						{{Content::money($hprice)}}
					</td>
					<td class="text-right">{{Content::money($hb->net_amount)}}</td>
					<td></td>
					<td></td>
					<?php $getTotalUSd = $getTotalUSd + $hb->net_amount; ?>

				</tr>
				@endforeach
			@endif

			@if($guideb->get()->count() > 0)
				@foreach($guideb->get() as $bg)
					<?php 
						$dateb = \App\Booking::find($bg->book_id);
						$sb = \App\GuideService::find($bg->service_id);
						$langb = \App\GuideLanguage::find($bg->language_id);
					?>
					<tr>	
						<td class="text-center">{{$n++}}</td>
						<td>{{{ $sb->title or ''}}}</td>
						<td class="text-left">{{{ $langb->name or ''}}}</td>
						<td>{{ Content::dateformat($dateb->book_checkin) }}</td>
				        <td class="text-center"></td>
						<td class="text-right">{{ Content::money($bg->price) }}</td>
						<td class="text-right">{{ Content::money($bg->price) }}</td>
						<td class="text-right">{{ Content::money($bg->kprice) }}</td>
						<td class="text-right">{{ Content::money($bg->kprice) }}</td>
					</tr>
					<?php $getTotalUSd = $getTotalUSd + $bg->price; ?>
					<?php $getTotalkyat = $getTotalkyat + $bg->kprice; ?>
				@endforeach
			@endif

			@if($transportb->get()->count() >0)				
				@foreach($transportb->get() as $bk)
				<?php 				
					$dateb = \App\Booking::find($bk->book_id);
					$tranb = \App\BookTransport::find($bk->tran_id);
				?>
				<tr>	
					<td class="text-center">{{$n++}}</td>					
					<td>{{{$tranb->service->title or ''}}}</td>
			        <td class="text-left">{{{$tranb->vehicle->name or ''}}}</td>
			        <td>{{Content::dateformat($dateb->book_checkin)}}</td>
					<td class="text-center"></td>
					<td class="text-right">{{ Content::money($bk->price) }}</td>	
					<td class="text-right">{{ Content::money($bk->price) }}</td>		
					<td class="text-right">{{ Content::money($bk->kprice) }}</td>	
					<td class="text-right">{{ Content::money($bk->kprice) }}</td>		
				</tr>
				<?php $getTotalUSd = $getTotalUSd + $bk->price; ?>
				<?php $getTotalkyat = $getTotalkyat + $bk->kprice; ?>
				@endforeach
			@endif

			@if($restaurantb->get()->count() > 0)
				@foreach($restaurantb->get() as $rb)
				<tr>	
					<td class="text-center">{{$n++}}</td>
					<td>{{{$rb->supplier->supplier_name or ''}}}</td>         
				    <td>{{{$rb->rest_menu->title or ''}}}</td>
				    <td>{{Content::dateformat($rb->start_date)}}</td>
				    <td class="text-center">{{$rb->book_pax}}</td>
					<td class="text-right">{{Content::money($rb->price)}}</td>
	                <td class="text-right">{{Content::money($rb->amount)}}</td>
	                <td class="text-right">{{Content::money($rb->kprice)}}</td>
                  	<td class="text-right">{{Content::money($rb->kamount)}}</td>
				</tr>
				<?php $getTotalUSd = $getTotalUSd + $rb->amount; ?>
				<?php $getTotalkyat = $getTotalkyat + $rb->kamount; ?>
				@endforeach
			@endif

			@if($entranb->get()->count() > 0)
				@foreach($entranb->get() as $ent)
				<tr>	
					<td class="text-center">{{$n++}}</td>
					<td>{{{$ent->entrance->name or ''}}}</td>       
					<td></td>
					<td>{{Content::dateformat($ent->start_date)}}</td>
				    <td class="text-center">{{$ent->book_pax}}</td>
	                <td class="text-right">{{Content::money($ent->price)}}</td>
	                <td class="text-right">{{Content::money($ent->amount)}}</td>
	                <td class="text-right">{{Content::money($ent->kprice)}}</td>
                  	<td class="text-right">{{Content::money($ent->kamount)}}</td>
				</tr>
				<?php $getTotalUSd = $getTotalUSd + $ent->amount; ?>
				<?php $getTotalkyat = $getTotalkyat + $ent->kamount; ?>
				@endforeach
			@endif

			@if($miscb->get()->count() > 0)
				@foreach($miscb->get() as $misc)
				<?php
					$dateb = \App\Booking::find($misc->book_id);
				?>
				<tr>	
					<td class="text-center">{{$n++}}</td>
					<td >{{{$misc->servicetype->name or ''}}}</td>    
					<td></td>   
					<td>{{Content::dateformat($dateb->book_checkin)}}</td>
				    <td class="text-center">{{$misc->book_pax}}</td>
	                <td class="text-right">{{Content::money($misc->price)}}</td>
	                <td class="text-right">{{Content::money($misc->amount)}}</td>
	                <td class="text-right">{{Content::money($misc->kprice)}}</td>
                  	<td class="text-right">{{Content::money($misc->kamount)}}</td>
				</tr>
				<?php $getTotalUSd = $getTotalUSd + $misc->amount; ?>
				<?php $getTotalkyat = $getTotalkyat + $misc->kamount; ?>
				@endforeach
			@endif

			@if($flightb->get()->count() > 0)
				@foreach($flightb->get() as $fb)
				<tr>
					<td class="text-center">{{$n++}}</td>
					<td>{{{$fb->supplier->supplier_name or ''}}}</td>
					<td>{{{$fb->fagent->supplier_name or ''}}}</td>			
					<td>{{Content::dateformat($fb->book_checkin)}}</td>	
					<td class="text-center">{{$fb->book_pax}}</td>
					<td class="text-right">{{Content::money($fb->book_price)}}</td>
					<td class="text-right">{{Content::money($fb->book_namount)}}</td>
					<td class="text-right">{{Content::money($fb->book_nkprice)}}</td>
					<td class="text-right">{{Content::money($fb->book_kamount)}}</td>
				</tr>
				<?php $getTotalUSd = $getTotalUSd + $fb->book_namount; ?>
				<?php $getTotalkyat = $getTotalkyat + $fb->book_kamount; ?>
				@endforeach
			@endif

			@if($golfb->get()->count() > 0)
				@foreach($golfb->get() as $gb)
				<tr>
					<td class="text-center">{{$n++}}</td>					
					<td>{{{$gb->golf->supplier_name or ''}}}</td>
					<td >{{{$gb->golf_service->name or ''}}}</td>
					<td>{{Content::dateformat($gb->book_checkin)}}</td>
					<td class="text-center">{{$gb->book_pax}}</td>
					<td class="text-right">{{Content::money($gb->book_nprice)}}</td>
					<td class="text-right">{{Content::money($gb->book_namount)}}</td>
					<td class="text-right">{{Content::money($gb->book_nkprice)}}</td>
					<td class="text-right">{{Content::money($gb->book_nkamount)}}</td>
				</tr>
				<?php $getTotalUSd = $getTotalUSd + $gb->book_namount; ?>
				<?php $getTotalkyat = $getTotalkyat + $gb->book_kamount; ?>
				@endforeach
			@endif

			@if($cruiseb->get()->count() > 0)
				@foreach($cruiseb->get() as $cb)
					<?php 
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
					<td class="text-center">{{$n++}}</td>					
					<td>{{{$cb->program->program_name or ''}}}</td>
					<td>{{$cb->room->name}}</td>
					<td>{{Content::dateformat($cb->checkin)}} - {{Content::dateformat($cb->checkout)}}</td>
					<td class="text-center">{{$cb->book_day}}</td>
					<td class="text-right">{{Content::money($hprice)}}</td>
					<td class="text-right">{{Content::money($cb->net_amount)}}</td>
				</tr>
				<?php $getTotalUSd = $getTotalUSd + $cb->net_amount; ?>
				@endforeach
			@endif	
			
			<tr>
				<th style="border: solid #ddd 1px; background-color: #9e9e9e3b; font-size: 16px;" colspan="10" class="text-right"> 
					@if($getTotalUSd)
					<strong>{{Content::currency()}} Total: {{Content::money($getTotalUSd)}}</strong>, &nbsp;&nbsp;&nbsp; 
					@endif
					@if($getTotalkyat)
					<strong>{{Content::currency(1)}} Total: {{Content::money($getTotalkyat)}}</strong>
					@endif
				</th>
			</tr>
		</table>
		<table class="table">
			<tr>
				<td style="border-top: none;"><div> Request By ....................................</div></td>
				<td style="border-top: none;"><div>  Approved By ....................................</div></td>
				<td style="border-top: none;"><div> Account Dept ...................................</div></td>
				<td style="border-top: none;"><div> Received By ....................................</div></td>
			</tr>
		</table>
		<p><b>Remark</b>
		<p>{{$remark}}</p></p>

  	</div>
</div>
<br><br>
@endsection
