@extends('layout.backend')
@section('title', 'Transport Assignment')
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
				@if($editTran==null)
				<div class="Transport">
					<div class="modal-dialog modal-lg">
						<form method="POST" action="{{route('assignTransport')}}">
						<div class="modal-content">        
							<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title"><strong id="form_title"> Transportation Assignment</strong> </h4>
							</div>
							<div class="modal-body">
							{{csrf_field()}}    
							<input type="hidden" name="book_id" id="tour_id" value="{{$btransport->id}}">
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
				@else
					<div class="Transport" >
						<div class="modal-dialog modal-lg">
							<form method="POST" action="{{route('assignTransport')}}">
							<div class="modal-content">        
								<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title"><strong id="form_title"> Transportation Assignment</strong> <strong id="title_of_tour"></strong></h4>
								</div>
								<div class="modal-body">
								{{csrf_field()}}    
								<input type="hidden" name="book_id" id="tour_id" value="{{$editTran->book_id}}">
								<input type="hidden" name="project_number" id="project_number" value="{{$editTran->project_number}}">
									<div class="row">
										<div class="col-md-12 col-xs-12">
											<div class="form-group">
												<label>Start Date</label> 
												<input type="date" id="start_date" name="start_date" class="form-control book_date" placeholder="Start Date" value="{{$editTran->start_date or ''}}" >	
											</div> 
										</div>
										<div class="col-md-4 col-xs-6">
										<div class="form-group">
											<label>Country <span style="color:#b12f1f;">*</span></label> 
											<select class="form-control country" id="country" name="country" data-type="booking_transport" data-locat="driver_data" required>
												@if($editTran->country_id !== null)
													<option value="{{$editTran->country_id}}">   <?php  $country = DB::table('country')->where('id', $editTran->country_id)->first();?> {{$country->country_name or ''}}	</option>
														@foreach(App\Country::Where('country_status',1)->wherehas('transportservice')->orderBy('country_name')->get() as $con)
															<option value="{{$con->id}}">{{$con->country_name}}</option>
														@endforeach
												@endif
											</select>           
										</div> 
										</div>
										<div class="col-md-4 col-xs-6">
										<div class="form-group">
											<label>Transportation</label>
											<select class="form-control transport" name="transport"  id="dropdown-booking_transport" data-type="booking_driver">
											@if($editTran->transport_id !== null)
													<option value="{{$editTran->transport_id}}">   <?php  $supplier = DB::table('suppliers')->where('id', $editTran->transport_id)->first();?> {{$supplier->supplier_name or ''}}	</option>
														@foreach(App\Supplier::where(['business_id'=> 7, 'supplier_status'=>1, 'country_id'=> $editTran->country_id])->orderBy('supplier_name', 'ASC')->get() as $sup)
															<option value="{{$sup->id}}">{{$sup->supplier_name}}</option>
														@endforeach
												@endif
											</select>
										</div>
										</div>
										<div class="col-md-4 col-xs-6">
										<div class="form-group">
											<label>Service Name</label>
											<select class="form-control tran_service" name="tran_name" id="dropdown-transervice" data-type="vehicle">
											@if($editTran->service_id !== null)
													<option value="{{$editTran->service_id}}">   <?php  $transervice = DB::table('transport_service')->where('id', $editTran->service_id)->first();?> {{$transervice->title or ''}}	</option>
													
														<?php
														$tran_service=DB::table('suppliers AS A')
														->join('supplier_transport_service AS B', 'A.id', '=', 'B.supplier_id')
														->join('transport_service AS C', 'B.transport_service_id', '=', 'C.id')
														->where(['A.id'=>$editTran->transport_id])
														->select('C.id', 'C.title')
														->get();
														?>
														@foreach($tran_service as $service)
															<option value="{{$service->id}}">{{$service->title}}</option>
														@endforeach
														
												@endif
											</select>
										</div>
										</div>
										<div class="col-md-4 col-xs-6">
											<div class="form-group">
												<label>Vehicle</label>
												<select class="form-control vehicle" name="vehicle" id="dropdown-vehicle">
												
														@if($editTran->vehicle_id !== null)
															<option value="{{$editTran->vehicle_id}}">   <?php  $vehicle = DB::table('transport_type')->where('id', $editTran->vehicle_id)->first();?> {{$vehicle->name or ''}}	</option>
																@foreach(App\TransportMenu::where(['transport_id'=> $editTran->service_id])->get() as $vehicle)
																dd($vehicle);
																	<option value="{{$vehicle->id}}">{{$vehicle->name}}</option>
																@endforeach
														@endif
												
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
										<div class="col-md-12 col-xs-12 ">
                                                <strong style="color:red;">To make changes to the Vehicle, you will need to select the Service Name again.</strong>
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
				@endif
            </div>
        </section>
    </div>
</div>
@include('admin.include.datepicker')
@endsection