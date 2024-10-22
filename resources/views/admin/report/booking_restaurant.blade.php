@extends('layout.backend')
@section('title', 'Restaurant Assignment')
<?php
  $active = 'service'; 
  $subactive = 'restaurant/service';
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
		           <h3 class="border" style="text-transform:capitalize;">Booking Restaurant for Project No. <b>{{$project->project_number}} </b> <span class="fa fa-angle-double-right"></span> <a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModal">Add Restaurant</a></h3>
		            <table class="datatable table table-hover table-striped">
			            <thead>
			                <tr>
			                  <th width="100px">Start Date</th>
			                  <th>Restuarant Name</th>
			                  <th>Menu</th>
			                  <th>Pax</th>
			                  <th style="width: 70px;">Price {{Content::currency()}}</th>
			                  <th style="width: 70px;">Amount</th>
			                  <th style="width: 70px;">Price {{Content::currency(1)}}</th>
			                  <th style="width: 70px;">Amount</th>
			                  <th class="text-center">Status</th>
			                </tr>
			            </thead>
			            <tbody>
			                @foreach($booking as $rest)
			                <tr>
			                  	<td>{{Content::dateformat($rest->start_date)}}</td>
				                <td>{{{$rest->supplier->supplier_name or ''}}}</td>         
				                <td>{{{$rest->rest_menu->title or ''}}}</td>
				                <td>{{$rest->book_pax}}</td>
				                <td class="text-right">{{Content::money($rest->price)}}</td>
				                <td class="text-right">{{Content::money($rest->amount)}}</td>
				                <td class="text-right">{{Content::money($rest->kprice)}}</td>
			                  	<td class="text-right">{{Content::money($rest->kamount)}}</td>
			                  	<td class="text-right">                      
				                    <span class="btnEditTran" data-type="rest" data-country="{{$rest->country_id}}" data-province="{{$rest->province_id}}" data-restname="{{{$rest->supplier->id or ''}}}" data-restmenu="{{{$rest->rest_menu->id or ''}}}" data-bookpax="{{ $rest->book_pax}}" data-price="{{$rest->price}}" data-kprice="{{$rest->kprice}}" data-bookdate="{{$rest->start_date}}" data-bookingdate="{{$rest->book_date}}" data-remark="{{$rest->remark}}" data-id="{{$rest->id}}" data-toggle="modal" data-target="#myModal">
				                      <i style="padding:1px 2px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i>
				                    </span>  
				                    <a target="_blank" href="{{route('restBooking',['project'=> $rest->project_number,'restid'=> $rest->id])}}" title="View Restaurant Booking">
				                        <img src="#" class="icon-list ic_inclusion">
				                    </a>
				                    <a target="_blank" href="{{route('restVoucher',['project'=> $rest->project_number,'restid'=> $rest->id])}}" title="View Restaurant Voucher">
				                        <img src="#" class="icon-list ic_invoice_drop">
				                    </a> 
							        <a href="javascript:void(0)" class="RemoveHotelRate" data-type="apply_rest" data-id="{{$rest->id}}" title="Delete this ">
			                          <img src="#" class="icon-list ic_remove">
			                        </a>
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

<div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="true">
	<div class="modal-dialog modal-lg">
	    <form method="POST" action="{{route('assignRestuarant')}}">
	      <div class="modal-content">        
	        <div class="modal-header" style="padding: 5px 13px;">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title"><strong id="form_title">Restaurant Booking</strong></h4>
	        </div>
	        <div class="modal-body">
	          {{csrf_field()}}    
	          	<input type="hidden" name="restId" id="tour_id">
	          	<input type="hidden" name="project_number" id="project_number" value="{{$project->project_number}}">
		        <div class="row">
		        	<div class="col-md-6 col-xs-6">
			            <div class="form-group">
			                <label>Start Date</label> 
			            	<input type="text" name="start_date" class="form-control book_date start_date" placeholder="Start Date">	
			            </div> 
		            </div>
		            <div class="col-md-6 col-xs-6">
			            <div class="form-group">
			                <label>Booking Date</label> 
			            	<input type="text" name="book_date" class="form-control book_date booking_date" placeholder="Booking Date">	
			            </div> 
		            </div>
		            <div class="col-md-6 col-xs-6">
		              <div class="form-group">
		                <label>Country <span style="color:#b12f1f;">*</span></label> 
		                <select class="form-control country" id="country" name="country" data-type="country" required>
		                    <option value="">Country</option>
		                  @foreach(App\Country::where('country_status', 1)->orderBy('country_name')->get() as $con)
		                    <option value="{{$con->id}}">{{$con->country_name}}</option>
		                  @endforeach
		                </select>
		              </div> 
		            </div>
		            <div class="col-md-6 col-xs-6">
		              <div class="form-group">
		                <label>City Name <span style="color:#b12f1f;">*</span></label> 
		                <select class="form-control province" name="city" data-type="rest_name" id="dropdown-data" required>
		                  <option value="">City</option>
		                  @foreach(App\Province::where(['province_status'=> 1])->orderBy('province_name')->get() as $pro)
		                    <option value="{{$pro->id}}">{{$pro->province_name}}</option>
		                  @endforeach
		                </select>
		              </div> 
		            </div>
		        </div>
		        <div class="row">
		            <div class="col-md-6">
		              <div class="form-group">
		                <label>Restaurant Name</label>
		               	<select class="form-control rest_name" name="rest_name" data-type="rest_menu" id="dropdown-transervice">
		               		<option>Select Service</option>
		               		@foreach(App\Supplier::where(['business_id'=> 2 , 'supplier_status'=> 1])->orderBy('supplier_name', 'ASC')->get() as $rest)
		               		<option value="{{$rest->id}}">{{$rest->supplier_name}}</option>
		               		@endforeach
		               	</select>
		              </div>
		            </div>
		            <div class="col-md-6 col-xs-12">
			            <div class="form-group">
			                <label>Menu </label>
			               	<select class="form-control rest_menu" name="rest_menu" id="dropdown-rest_menu">
			               		<option>Select Menu</option>
			               		@foreach(App\RestaurantMenu::whereNotIn('title', [''])->get() as $rm)
			               		<option value="{{$rm->id}}" data-price="{{$rm->price}}" data-kprice="{{$rm->kprice}}">{{$rm->title}}</option>
			               		@endforeach
			               	</select>
			            </div>
		            </div>
		            <div class="col-md-6 col-xs-6">
		              <div class="form-group">
		                <label>Pax No.</label>
		                <input type="number" name="pax" id="pax" class="form-control text-center">
		              </div>
		            </div>
		            <div class="col-md-3 col-xs-3">
		              <div class="form-group">
		                <label>Price {{Content::currency()}}</label>
		                <input type="text" name="price" id="price" class="form-control editprice" placeholder="00.0" readonly>
		              </div>
		            </div>
		            <div class="col-md-3 col-xs-3">
		              <div class="form-group">
		                <label>Price {{Content::currency(1)}}</label>
		                <input type="text" name="kprice" id="kprice" class="form-control editprice" placeholder="00.0" readonly>
		              </div>
		            </div>
		            <div class="col-md-12 col-xs-12">
		              <div class="form-group">
		                <label>Remark</label>
		                <textarea class="form-control" id="remark" name="remark" rows="5" placeholder="Remark..."></textarea>
		              </div>
		            </div>
		        </div>
	        </div>
	        <div class="modal-footer" style="padding: 5px 13px;">
	          <button type="submit" class="btn btn-success btn-flat btn-sm" >Publish</button>
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
  @include('admin.include.datepicker')
@endsection
