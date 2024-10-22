@extends('layout.backend')
@section('title', 'Entrance Assignment')
<?php
  $active = 'restaurant/menu'; 
  $subactive = 'entrance/service';
  use App\component\Content;
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
		            <h3 class="border" style="text-transform:capitalize;">Booking Entrance Fees for Project No. <b>{{$project->project_number}} </b> 
				 	@if($project->project_status == 2)
						@if(\Auth::user()->role_id == 2)
							<span class="fa fa-angle-double-right"></span> <a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModal">Add Entrance Fees</a> 
						@endif
					@else  
				   <span class="fa fa-angle-double-right"></span> <a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModal">Add Entrance Fees</a>
		         	@endif   
					 </h3>
		            <table class="datatable table table-hover table-striped">
			            <thead>
			                <tr>
			                  	<th width="100px">Start Date</th>
			                  	<th>Entrance Fees</th>
			                  	<th>Pax</th> 
			                  	<th class="text-right">Price {{Content::currency()}}</th>
			                  	<th class="text-right">Amount</th>
			                  	<th class="text-right">Price {{Content::currency(1)}}</th>
			                  	<th class="text-right">Amount</th>
			                  	<th class="text-center" width="100px">Status</th>
			                </tr>
			            </thead>
				            <tbody>
			                @foreach($booking as $ent)
							 
			                <tr>
			                  	<td>{{Content::dateformat($ent->start_date)}}</td>
				                <td>{{{$ent->entrance->name or ''}}}</td>
				                <td>{{$ent->book_pax}}</td> 
				                <td class="text-right">{{Content::money($ent->price)}}</td>
				                <td class="text-right">{{Content::money($ent->amount)}}</td>
				                <td class="text-right">{{Content::money($ent->kprice)}}</td>
			                  	<td class="text-right">{{Content::money($ent->kamount)}}</td>
			                  	<td class="text-right">
			                  		<!-- $journal = AccountJournal::where(['book_id'=>$ent->id, '']) -->
			                  		<?php	 
									 $EntJournal = App\AccountJournal::where(['business_id'=>55,'project_number'=>$project->project_number, 'book_id'=>$ent->id, 'type'=>1, 'status'=>1])->first(); ?>
			                  		@if($ent['supplier_id'] > 0)
			                  			<a target="_blank" href="{{route('opsVoucher',['type'=>'entrance', 'project'=>$ent->project_number,'supplier'=>$ent->id])}}" title="View Service Voucher">
				                        	<label class="icon-list ic_inclusion"></label>
						                </a>
						                <a target="_blank" href="{{route('opsReservation', ['type'=>'entrance', 'project'=>$ent->project_number,'supplier'=>$ent->id])}}" title="View Reservation Form">
					                        <label class="icon-list ic_invoice_drop"></label>
					                    </a> 
			                  		@endif

			                  		@if($EntJournal == Null)
					                    <!-- <span style="position: relative;top:-5px;" class="btnEditTran" data-type="entrance_fee" data-country="{{$ent->country_id}}" data-province="{{$ent->province_id}}" data-restmenu="{{{ $ent->entrance->id or ''}}}"  data-bookpax="{{ $ent->book_pax}}" data-supplier="{{$ent['supplier']}}" data-price="{{$ent->price}}" data-kprice="{{$ent->kprice}}" data-bookdate="{{$ent->start_date}}"  data-remark="{{$ent->remark}}" data-id="{{$ent->id}}" data-toggle="modal" data-target="#myModal">
					                      <i style="padding:1px 2px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i>
					                    </span> -->
										<a target="_blank" href="{{route('editoperation', ['type'=>'entrance', 'id'=>$ent->id , 'project_no'=>$ent->project_number])}}" title="Edit Entrance Fee">
                                			<label class="icon-list ic_edit"></label>
                             			</a>&nbsp;
								        <a href="javascript:void(0)" class="RemoveHotelRate" data-type="apply_entrance" data-id="{{$ent->id}}" title="Delete this ">
				                          <label class="icon-list ic_remove"></label>
				                        </a>
				                    @else
				                    	<span title="Project have been posted. can't edit" style="border-radius: 50px;border: solid 1px #795548; padding: 0px 6px;">Posted</span>
			                        @endif
				                </td>                     
			                </tr>
			                @endforeach
			            </tbody>
		            </table>
		        </section>
		    </div>
	    </section>
	</div>
</div>
<!-- entrabce fee create start -->
<div class="modal in" id="myModal" role="dialog" data-backdrop="static" data-keyboard="true">
	<div class="modal-dialog modal-lg">
	    <form method="POST" action="{{route('assignEntrance')}}">
	      <div class="modal-content">        
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title"><strong id="form_title">Entrance Fees Assignment</strong></h4>
	        </div>
	        <div class="modal-body">
	          {{csrf_field()}}    
	          	<input type="hidden" name="restId" id="tour_id">
	          	<input type="hidden" name="project_number" id="project_number" value="{{$project->project_number}}">
		        <div class="row">
		        	<div class="col-md-12 col-xs-12">
			            <div class="form-group">
			                <label>Start Date</label> 
							<input type="date" id="start_date" name="start_date" class="form-control book_date" placeholder="Start Date" >	
			            	<!-- <input type="text" name="star
							t_date" class="form-control book_date" placeholder="Start Date" value="{{date('Y-m-d')}}">	 -->
			            </div> 
		            </div>		           
		            <div class="col-md-6 col-xs-6">
		              <div class="form-group">
		                <label>Country <span style="color:#b12f1f;">*</span></label> 
		                <select class="form-control country" id="country" name="country" data-type="entrance"
		                data-pro_of_bus_id="entrance_fee_type" data-locat="data" data-title="6" required>
		                    <option value="">--country--</option>
			                @foreach(App\Country::getEntranCon() as $con)
			                    <option value="{{$con->id}}">{{$con->country_name}}</option>
			                @endforeach
		                </select>
		              </div>  
		            </div>
		            <div class="col-md-6 col-xs-6">
		              <div class="form-group">
		                <label>City Name <span style="color:#b12f1f;">*</span></label>
		                <select class="form-control city" name="city" data-type="entrance_fee" id="dropdown-entrance" required>
		                  <option value="">City</option>
		                  @foreach(App\Province::getEntranPro(\Auth::user()->country_id) as $pro)
		                    <option value="{{$pro->id}}">{{$pro->province_name}}</option>
		                  @endforeach 
		                </select>
		              </div> 
		            </div>
		      
		            <div class="col-md-6">
		               <div class="form-group">
			                <label>Entrance Fees </label>
			               	<select class="form-control rest_menu" name="rest_menu" id="dropdown-entrance_fee">
			               		<option>Select Entrance</option>
			               		@foreach(App\Entrance::where('status', 1)->orderBy('name', 'ASC')->get() as $rm)
			               		<option value="{{$rm->id}}" data-price="{{$rm->price}}" data-kprice="{{$rm->kprice}}">{{$rm->name}}</option>
			               		@endforeach
			               	</select>

			            </div>
		            </div>    

		             <!-- <div class="col-md-6">
			            <div class="form-group">
			                <label>Transportation<span style="color:#b12f1f;">*</span></label>
			               	<select class="form-control transportation" id="dropdown-transport_service" name="transportation"></select>
			            </div>
		            </div>       -->
		            <div class="col-md-6 col-xs-6">
		              <div class="form-group">
		                <label>Pax No.</label>
		                <input type="number" name="pax" id="pax" class="form-control text-center pax">
		              </div>
		            </div>
		            <div class="col-md-3 col-xs-6">
		              <div class="form-group">
		                <label>Price {{Content::currency()}}</label>
		                <input type="text" name="price" id="price" class="form-control editprice" placeholder="00.0" readonly>
		              </div>
		            </div>
		            <div class="col-md-3 col-xs-6">
		              <div class="form-group">
		                <label>Price {{Content::currency(1)}}</label>
		                <input type="text" name="kprice" id="kprice" class="form-control editprice" placeholder="00.0" readonly>
		              </div>
		            </div>
		            <div class="col-md-12 col-xs-12">
		              <div class="form-group">
		                <label>Remark</label>
		                <textarea class="form-control remark" id="remark" name="remark" rows="5" placeholder="Remark..."></textarea>
		              </div>
		            </div>
		        </div>
	        </div>
	        <div class="modal-footer" style="text-align: center;">
	          <button type="submit" class="btn btn-success btn-flat btn-sm" >Publish</button>
	          <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Cancel</a>
	        </div>
	      </div>      
	    </form>
	</div>
</div>
<!-- entrance fee create end -->


<script type="text/javascript">
  $(document).ready(function(){

     $(".datatable").DataTable();
  });
</script>
  @include('admin.include.datepicker')
@endsection
