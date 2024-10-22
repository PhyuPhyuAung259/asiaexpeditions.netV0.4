@extends('layout.backend')
@section('title', 'Transportation Assignment')
<?php
  $active = 'restaurant/menu'; 
  $subactive = 'transport/service';
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
		          <h3 class="border" style="text-transform:capitalize;">Booking Transportation for Project No. <b>{{$project->project_number}} </b></h3>
		          	<div class="table-responsive">
		          		<table class="datatable table table-hover table-striped">
				            <thead>
				                <tr>
				                  <th width="70px">Start Date</th>
				                  <!-- <th width="67px">City</th> -->
				                  <th>Tour Name</th>
				                  <th>Service</th>
				                  <th>Vehicle</th>
				                  <th style="width:77px;s">Driver</th>
				                  <th>Phone</th>
				                  <th title="Meeting / PickUp Time">MT/PKTime</th>
				                  <th title="Flight Number & Time ">FlightNo./Time</th>
				                  <th style="width: 70px;">Price </th>
				                  <th style="width: 70px;">Price {{Content::currency(1)}}</th>
				                  <th class="text-center" style="width:75px;">Status</th>
				                </tr>
				            </thead>
				            <tbody>
				                @foreach($booking as $tran)
						            <?php 
							            $pro   = App\Province::find($tran->province_id);
							            $btran = App\BookTransport::where(['project_number'=>$tran->book_project,'book_id'=>$tran->id])->first();
							            $TranJournal = App\AccountJournal::where(['supplier_id'=>$btran['transport_id'], 'business_id'=>7,'project_number'=>$project->project_number, 'book_id'=>$tran->id, 'type'=>1, 'status'=>1])->first();
						            ?>
					                <tr>
					                  <td>{{Content::dateformat($tran->book_checkin)}}</td>
					                  <!-- <td></td>          -->
					                  <td>{{$tran->tour_name}} <span class="badge">{{$pro->province_name}}</span></td>          
					                  <td>{{{$btran->service->title or ''}}}</td>
					                  <td>{{{$btran->vehicle->name or ''}}}</td>
					                  <td>{{{$btran->driver->driver_name or ''}}}</td>
					                  <td>
					                  	@if(isset($btran->transport->phone) || isset($btran->transport->phone2))
					                  		{{$btran->transport->phone}} {{ $btran->transport->phone2}}
					                  	@else
					                  		{{{$btran->transport_phone or ''}}}
					                  	@endif
					                  </td> 
					                  <td>{{{ $btran->pickup_time or ''}}}</td>
					                  <td>{{{$btran->flightno or ''}}}</td>
					                  <td class="text-right">{{ isset($btran->price)?Content::money($btran->price):''}}</td>
					                  <td class="text-right">{{ isset($btran->kprice)? Content::money($btran->kprice):''}}</td>
					                  <td class="text-right">     
					                  	@if($TranJournal == Null)    
						                  	@if(isset($btran->id))
												<span class="btn btn-acc btn-xs btnRefresh" title="Clear this service" data-type="clear-transport" data-id="{{{$btran->id or ''}}}"><i class="fa fa-refresh"></i></span>
											@endif      
						                    <label title="Edit Service" class="btnEditTran"   
						                    	data-label="{{$tran->tour_name}}" data-type="vehicle" data-restmenu="{{{$btran->service_id or ''}}}" 
						                    	data-restname="{{{$btran->transport_id or ''}}}" data-vehicle="{{{$btran->vehicle_id or ''}}}" 
						                    	data-country="{{{$btran->country_id or ''}}}" data-province="{{{$btran->province_id or ''}}}" 
						                    	data-price="{{{$btran->price or ''}}}" data-kprice="{{{$btran->kprice or ''}}}" 
						                    	data-phone="{{{$btran->phone or ''}}}" data-id="{{$tran->id}}" data-toggle="modal" data-target="#myModal">
						                      <i style="font-size:17px; color: #03A9F4;" class="btn btn-xs fa fa-pencil-square-o"></i>
						                    </label>
						                @else
						                	<span title="Project have been posted. can't edit" style="border-radius: 50px;border: solid 1px #795548; padding: 0px 6px;">Posted</span>
					                   	@endif 
					                    @if(isset($btran->id))
										<a target="_blank" href="{{route('getBookingVoucher', [$tran->book_project, $tran->id])}}" title="View Restaurant Booking">
							               	<label class="fa fa-list-alt btn btn-xs" style="font-size:17px; color: #527686;"></label>
							            </a>   
							            @endif
							            
					                  </td>                     
					                </tr>
				                @endforeach
				            </tbody>
		            	</table>
		          	</div>
		        </section>
		    </div>
	    </section>
	</div>
</div>

<div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="true">
	<div class="modal-dialog modal-lg">
	    <form method="POST" action="{{route('assignTransport')}}">
	      <div class="modal-content">        
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title"><strong id="form_title"> Transportation Assignment</strong> <strong id="title_of_tour"></strong></h4>
	        </div>
	        <div class="modal-body">
	          {{csrf_field()}}    
	          <input type="hidden" name="bookid" id="tour_id">
	          <input type="hidden" name="project_number" id="project_number" value="{{$project->project_number}}">
		        <div class="row">
		            <div class="col-md-4 col-xs-6">
		              <div class="form-group">
		                <label>Country <span style="color:#b12f1f;">*</span></label> 
		                <select class="form-control country" id="country" name="country" data-type="booking_transport" data-locat="driver_data"required>
		                    <option value="">--Choose--</option>
		                  @foreach(App\Country::Where('country_status',1)->wherehas('transportservice')->orderBy('country_name')->get() as $con)
		                    <option value="{{$con->id}}">{{$con->country_name}}</option>
		                  @endforeach
		                </select>
		              </div> 
		            </div>
		            <div class="col-md-4 col-xs-6">
		              <div class="form-group">
		                <label>Transportation</label>
		               	<select class="form-control transport" name="transport"  id="dropdown-booking_transport" data-type="booking_driver">
		               		<option>--Choose--</option>
		               		@foreach(App\Supplier::where(['business_id'=> 7, 'supplier_status'=>1])->orderBy('supplier_name', 'ASC')->get() as $sup)
		               		<option value="{{$sup->id}}" data-phone="{{$sup->supplier_phone}}"  data-phone2="{{$sup->supplier_phone2}}">{{$sup->supplier_name}}</option>
		               		@endforeach
		               	</select>
		              </div>
		            </div>
		            <div class="col-md-4 col-xs-6">
		              <div class="form-group">
		                <label>Service Name</label>
		               	<select class="form-control tran_service" name="tran_name" id="dropdown-transervice" data-type="vehicle">
		               		
		               	</select>
		              </div>
		            </div>
		            <div class="col-md-4 col-xs-6">
			            <div class="form-group">
			                <label>Vehicle</label>
			               	<select class="form-control vehicle" name="vehicle" id="dropdown-vehicle">
			               	</select>
			            </div>
		            </div>
		            <div class="col-md-4 col-xs-3">
		              <div class="form-group">
		                <label>Price {{Content::currency()}}</label>
		                <input type="text" name="price" id="price" class="form-control editprice" placeholder="00.0" readonly>
		              </div>
		            </div>
		            <div class="col-md-4 col-xs-3">
		              <div class="form-group">
		                <label>Price {{Content::currency(1)}}</label>
		                <input type="text" name="kprice" id="kprice" class="form-control editprice" placeholder="00.0" readonly>
		              </div>
		            </div>       
		           
		            <div class="col-md-4 col-xs-6">
		            	<div class="form-group">
		            		<label><br></label>
		            		<input type="text" name="phone1" id="phone" class="form-control" placeholder="(+855) 123 456 789" >
		            	</div>
		            </div>
		            <div class="col-md-2 col-xs-6">
		            	<div class="form-group">
		            		<label>Driver Name</label>
		               		<select class="form-control driver_name" name="driver_name"  id="dropdown-booking_driver">
		               		</select>
		            	</div>
		            </div>
		            <div class="col-md-2 col-xs-6">
		            	<div class="form-group">
		            		<label><br></label>
		            		<input type="text" name="phone2" id="phone2" class="form-control" placeholder="(+855) 123 456 789" >
		            	</div>
		            </div>
		            <div class="col-md-2 col-xs-6">
		            	<div class="form-group">
		            		<label>Meeting/PickUpTime</label>
		               		<input type="text" name="pickup_time" id="pickup_time" class="form-control" placeholder="Meeting / Pick Up Time" >
		            	</div>
		            </div>
		            <div class="col-md-2 col-xs-6">
		            	<div class="form-group">
		            		<label>Flight No./time</label>
		               		<input type="text" name="flightno" id="flightno" class="form-control" placeholder="Flight No./time">
		            	</div>
		            </div>

		            <div class="col-md-12 col-xs-12">
		            	<div class="form-group">
		            		<label>Remarks</label>
		               		<textarea class="form-control" rows="7" name="remark" placeholder="Type Remark here..."></textarea>
		            	</div>
		            </div>
		        </div>

	        </div>
	        <div class="modal-footer">
	          <button type="submit" class="btn btn-success btn-flat btn-sm" >Save</button>
	          <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Close</a>
	        </div>
	      </div>      
	    </form>
	</div>
</div>
<script type="text/javascript">
  	$(document).ready(function(){
  		$(document).on("click", ".btnEditTran", function(){
  			$("#title_of_tour").text("For " + $(this).data('label'));
  		});
  		// $("#title_of_tour")$("#tour_id").val()
  		// alert("")
     $(".datatable").DataTable();
  	});
</script>
@endsection
