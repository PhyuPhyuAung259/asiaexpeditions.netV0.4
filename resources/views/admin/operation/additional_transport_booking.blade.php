@extends('layout.backend')
@section('title', 'Transport Additional Assignment')
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
				 
				<div class="Transport">
					<div class="modal-dialog modal-lg">
						<form method="POST" action="{{route('assignTransport')}}">
						<div class="modal-content">        
							<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title"><strong id="form_title"> Transportation Additional Assignment</strong> </h4>
							</div>
							<div class="modal-body">
							{{csrf_field()}}    
                            <input type="hidden" name="tour_id" id="tour_id" value="{{$btransport->tour_id}}">
							<input type="hidden" name="project_number" id="project_number" value="{{$btransport->book_project}}">
								<div class="row">
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label>Start Date</label> 
											<input type="date" id="start_date" name="start_date" class="form-control book_date" placeholder="Start Date" value="{{$btransport->book_checkin}}">	
										</div> 
									</div>
									<div class="col-md-4 col-xs-6">
									<div class="form-group">
										<label>Country <span style="color:#b12f1f;">*</span></label> 
										<select class="form-control country" id="country" name="country" data-type="booking_transport" data-locat="driver_data" required>
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
				
            </div>
        </section>
    </div>
</div>
@include('admin.include.datepicker')
@endsection