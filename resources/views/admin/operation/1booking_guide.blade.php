@extends('layout.backend')
@section('title', 'Guide Assignment')
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
		          <h3 class="border" style="text-transform:capitalize;">Booking Guide for Project No. <b>{{$project->project_number}} </b></h3>
		            <table class="datatable table table-hover table-striped">
		              <thead>
		                <tr>
		                  <th width="60px">StartDate</th>
		                  <!-- <th style="width: 9%;">City</th> -->
		                  <th>Tour</th>
		                  <th>Service</th>
		                  <th>Guide</th>
		                  <th>Phone</th>
		                  <th>Language</th>
		                  <th style="width: 70px;">Price {{Content::currency()}}</th>
		                  <th style="width: 70px;">Price {{Content::currency(1)}}</th>
		                  <th class="text-center">Status</th>
		                </tr>
		              </thead>
		              <tbody>
		                @foreach($booking as $tran)
				            <?php
				            	$pro = App\Province::find($tran->province_id);
				            	$bg  = App\BookGuide::where(['project_number'=>$tran->book_project, 'book_id'=>$tran->id])->first();
				            	$JournalGuide = \App\AccountJournal::where(['supplier_id'=>$bg['supplier_id'], 'business_id'=>6,'project_number'=>$project->project_number, 'book_id'=>$tran->id, 'type'=>1 ,'status'=>1])->first();
				            	?>
				                <tr>
									<td>{{Content::dateformat($tran->book_checkin)}}</td>
									<!-- <td>{{$pro->province_name}}</td>          -->
									<td>{{$tran->tour_name}} <span class="badge">{{$pro->province_name}}</span></td>     
									<td>{{{ $bg->service->title or '' }}}</td>
									<td>{{{ $bg->supplier->supplier_name or ''}}} </td>
									<td>
										@if(isset($bg->supplier->phone) || isset($bg->supplier->phone2))
											{{$bg->supplier->phone}} {{ $bg->supplier->phone2}}
										@else
											@if(!empty($bg->supplier_id) )
												{{{$bg->phone or ''}}}
											
											@endif
										@endif
									</td> 
									<td>{{{$bg->language->name or ''}}}</td>
									<td class="text-right">{{ isset($bg->price)?Content::money($bg->price):''}}</td>
									<td class="text-right">{{ isset($bg->price)? Content::money($bg->kprice):''}}</td>
									<td class="text-right">      
										@if($JournalGuide == Null)
											@if(isset($bg->id))
											<span class="btn btn-acc btn-xs btnRefresh" title="Clear this service" data-type="clear-guide" data-id="{{{$bg->id or ''}}}"><i class="fa fa-refresh"></i></span>
											@endif     
											<button class="btnEditTran"  style="padding:0px;border:none;"  
												data-type="apply_guide"
												data-restmenu="{{{$bg->service_id or ''}}}" 
												data-transport="{{{$bg->supplier_id or ''}}}" 
												data-language="{{{$bg->language_id or ''}}}" 
												data-country="{{{$pro->country_id or ''}}}" 
												data-province="{{{$pro->id or ''}}}"  
												data-price="{{{$bg->price or ''}}}" 
												data-kprice="{{{$bg->kprice or ''}}}" 
												data-phone="{{{$bg->phone or ''}}}"  
												data-pro_of_bus_id="6" 
												data-id="{{$tran->id}}" data-toggle="modal" data-target="#myModal">
											  <i style="padding:1px 2px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i>
											</button> 
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

<div class="modal in" id="myModal" role="dialog" data-backdrop="static" data-keyboard="true">
	<div class="modal-dialog modal-lg">
	    <form method="POST" action="{{route('assignGuide')}}">
		    <div class="modal-content">        
		        <div class="modal-header">
		          	<button type="button" class="close" data-dismiss="modal">&times;</button>
		          	<h4 class="modal-title"><strong id="form_title">Guide Assignment</strong></h4>
		        </div>
		        <div class="modal-body">
		          	{{csrf_field()}}    
		          	<input type="hidden" name="bookid" id="tour_id">
	 	          	<input type="hidden" name="project_number" id="project_number" value="{{$project->project_number}}">
			        <div class="row">
			            <div class="col-md-6 col-xs-6">
			              <div class="form-group">
			                <label>Country <span style="color:#b12f1f;">*</span></label> 
			                <select class="form-control country" id="country" name="country" data-type="country" data-locat="guide_data" data-pro_of_bus_id="6" data-title="6" required>
			                    <option value="">--Choose--</option>
				                @foreach(App\Country::getGuideCon() as $con)
				                    <option value="{{$con->id}}">{{$con->country_name}}</option>
				                @endforeach
			                </select>
			              </div> 
			            </div>
			            <div class="col-md-6 col-xs-6">
			              <div class="form-group">
			                <label>City Name <span style="color:#b12f1f;">*</span></label> 
			                <select class="form-control province" name="city" data-type="apply_guide" id="dropdown-country"  required>
			                  <option value="">--choose--</option>			                  
			                </select>
			              </div> 
			            </div>
			        </div>
			        <div class="row">
			            <div class="col-md-6 col-xs-6 ">
			              <div class="form-group">
			                <label>Service Name</label>
			               	<select class="form-control tran_name" name="tran_name" data-type="apply_language"  id="dropdown-apply_guide">
			               	</select>
			              </div>
			            </div>
			            
			            <div class="col-md-6 col-xs-6">
				            <div class="form-group">
				                <label>Language</label>
				               	<select class="form-control language" name="language" id="dropdown-rest_menu" data-type="language-supplier">
				               		<option>Choose Language</option>
				               		
				               	</select>
				            </div>
			            </div>
			            <div class="col-md-3 col-xs-6 ">
			              	<div class="form-group">
				                <label>Guide Name</label>
				               	<select class="form-control guide_name" name="guide_name" data-type="guide_name" id="dropdown-language-data">
				               		<option>No Guide</option>			               	
				               	</select>
				            </div>
			            </div>
			            <div class="col-md-3 col-xs-6">
			            	<div class="form-group">
			            		<label>Telephone</label>
			            		<input type="text" name="phone" id="phone" class="form-control" placeholder="(+855) 123 456 789" >
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
			        </div>
		        </div>
		        <div class="modal-footer" style="text-align: center !important;">
		          	<button type="submit" class="btn btn-success btn-flat btn-sm" >Publish</button>
		         	<a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Close</a>
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
