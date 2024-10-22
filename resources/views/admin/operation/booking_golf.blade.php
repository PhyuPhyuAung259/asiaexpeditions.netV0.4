@extends('layout.backend')
@section('title', 'Golf Assignment')
<?php
  $active = 'restaurant/menu'; 
  $subactive = 'transport/service';
  use App\component\Content;
 // dd($project,$booking);
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
	<div class="content-wrapper">
	    <section class="content"> 
		    <div class="row">
		      	@include('admin.include.message')
		        <section class="col-lg-12 connectedSortable">
		          <h3 class="border" style="text-transform:capitalize;">Booking Golf for Project No. <b>{{$project->project_number}} </b></h3>
		            <table class="datatable table table-hover table-striped">
		              <thead>
		                <tr>
		                 	<th width="100px">Date</th>
							<th>Golf</th>
							<th>Tee Time</th>
							<th>Golf Service</th>
							<th class="text-center">Pax</th>
							<th class="text-right">Price {{Content::currency()}}</th>
							<th class="text-right">Amount {{Content::currency()}}</th>
							<th class="text-right">Price {{Content::currency(1)}}</th>
							<th class="text-right">Amount {{Content::currency(1)}}</th>
							<th class="text-center">Status</th>
		                </tr>
		              </thead>
		              <tbody>
		              @foreach($booking as $gf)			
						<?php 
						$gsv = App\GolfMenu::find($gf->program_id);
						?>	
							<tr>
								<td>{{Content::dateformat($gf->book_checkin)}}</td>
								<td>{{$gf->supplier_name}}</td>
								<td>{{$gf->book_golf_time}}</td>
								<td>{{{ $gsv->name or ''}}}</td>
								<td class="text-center">{{$gf->book_pax}}</td>			
								<td class="text-right">{{Content::money($gf->book_nprice)}}</td>
								<td class="text-right">
									{{
									$gf->book_namount > 0 ? Content::money($gf->book_namount) : Content::money($gf->book_nprice * $gf->book_pax)
									}}
								</td>
								<td class="text-right">{{Content::money($gf->book_kprice)}}</td>
								<td class="text-right">
									{{ $gf->book_kamount > 0 ? Content::money($gf->book_kamount) : Content::money($gf->book_kprice * $gf->book_pax) }}
								</td>
							    @if($project->project_status == 2)
										@if(\Auth::user()->role_id == 2)
											<td class="text-center">
												<button class="btnEditTran" style="padding:0px;border:none;" data-id="{{$gf->id}}" data-toggle="modal" data-target="#myModal">
												<i style="padding:1px 2px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i></button>
											</td>
										@else
											<td></td>
										@endif
								@else 
								<td class="text-center"><button class="btnEditTran" style="padding:0px;border:none;" data-id="{{$gf->id}}" data-toggle="modal" data-target="#myModal">
		                      	<i style="padding:1px 2px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i>
		                    </button> </td> 	
								@endif
							</tr>
						@endforeach
		              </tbody>
		            </table>
		        </section>
		    </div>
	    </section>
	</div>
</div>

<div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="true">
	<div class="modal-dialog modal-md">
	    <form method="POST" action="{{route('updateTeetime')}}">
		    <div class="modal-content">        
		        <div class="modal-header" >
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title"><strong id="form_title">What time you want to play</strong></h4>
		        </div>
		        <div class="modal-body">
		          	{{csrf_field()}}    
		          	<input type="hidden" name="bookid" id="tour_id" value="">
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
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@endsection
