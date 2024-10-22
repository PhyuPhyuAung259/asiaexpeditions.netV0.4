@extends('layout.backend')
@section('title', 'Miscellaneous Assignment')
<?php
  	$active = 'restaurant/menu'; 
  	$subactive = 'misc/service';
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
		        <section class="col-lg-12 ">
		          	<h3 class="border" style="text-transform:capitalize;">Miscellaneouse Assignment For File Number <b>{{$project->project_number}} </b></h3>
		            <table class="datatable table table-hover table-striped">
			            <thead>
			                <tr>
			                  <th width="100px">Date</th>
			                  <th>Tour</th>		                 
			                  <th class="text-center" width="20px">Option</th>
			                </tr>
			            </thead>
		              	<tbody>
		                @foreach($booking as $tran)
		                	<?php 
			                	$pro = App\Province::find($tran->province_id);
						        $miscService = App\BookMisc::where(['project_number'=>$tran->book_project,'book_id'=>$tran->id])->orderBy('created_at', 'DESC')->get();
				        		?>
			                <tr>
				                <td>{{Content::dateformat($tran->book_checkin)}}</td>       
				                <td>
				                  	<div><strong>{{$tran->tour_name}}</strong>&nbsp; <i>[ {{$pro->province_name}} ]</i></div>
						            @if($miscService->count() > 0) 
						            	<hr  style="border-top:none; border-bottom: 1px solid #ddd;padding: 5px 0px; margin-top:0px; margin-bottom: 0px;">
					                  	<div class="row "style="font-style: italic; color: #c36f04;" >
						                  	<div class="col-md-6">
						                  		<p><strong>Service Name</strong></p>
						                  	</div>
						                  	<div class="col-md-1">
						                  		<p><strong>Pax No.</strong></p>
						                  	</div>
						                  	<div class="col-md-1">
						                  		<p><strong>Price{{Content::currency()}}</strong></p>
						                  	</div>
						                  	<div class="col-md-1">
						                  		<p><strong>Amount</strong></p>
						                  	</div>
						                  	<div class="col-md-1">
						                  		<p><strong>Price{{Content::currency(1)}}</strong></p>
						                  	</div>
						                  	<div class="col-md-2">
						                  		<p><strong>Amount</strong></p>
						                  	</div>
					                  	</div>				                
						            	@foreach($miscService as $misc)
						            		<?php $MiscJournal = App\AccountJournal::where(['business_id'=>54,'project_number'=>$project->project_number, 'book_id'=>$misc->id, 'type'=>1, 'status'=>1])->first(); ?>
						                  	<div class="row row-item">
							                  	<div class="col-md-6">
							                  		<p>{{{ $misc->servicetype->name or '' }}}</p>
							                  	</div>
							                  	<div class="col-md-1">
							                  		<p>{{$misc->book_pax}}</p>
							                  	</div>
							                  	<div class="col-md-1">
							                  		<p>{{Content::money($misc->price)}}</p>
							                  	</div>
							                  	<div class="col-md-1">
							                  		<p>{{Content::money($misc->amount)}}</p>
							                  	</div>
							                  	<div class="col-md-1">
							                  		<p>{{Content::money($misc->kprice)}}</p>
							                  	</div>
							                  	<div class="col-md-2">
							                  		<span><strong>{{Content::money($misc->kamount)}}</strong></span> 
							                  		<span class="pull-right">
							                  			@if($MiscJournal == Null)
								                  			<a class="btnEditTran" data-type="apply_misc" href="#" data-id="{{$misc->id}}" data-country="{{$misc->country_id}}" data-province="{{$misc->province_id}}" data-pax="{{$misc->book_pax}}" data-restmenu="{{$misc->service_id}}" data-price="{{$misc->price}}" data-kprice="{{$misc->kprice}}" data-remark="{{$misc->remark}}" data-toggle="modal" data-target="#myModal"><i style="font-size: 16px;" class="fa fa-pencil"></i></a>

								                  			<span class="btn btn-xs btnRefresh" title="Clear this service" data-type="clear-misc" data-id="{{$misc->id}}">
								                  				<label class="icon-list ic-trash"></label>
								                  			</span>
								                  		@else
								                  			<span title="Project have been posted. can't edit" style="border-radius: 50px;border: solid 1px #795548; padding: 0px 6px;">Posted</span>
								                  		@endif
							                  		</span>
							                  	</div>
						                  	</div>
					                  	@endforeach
					                @endif
				                </td>
				                <td class="text-center">                      
				                    <span class="btnEditTran" data-country="{{$tran->country_id}}" data-province="{{$tran->province_id}}" data-id="{{$tran->id}}" data-toggle="modal" data-target="#myModal">
				                      	<i style="padding:1px 2px;" class="fa fa-plus-circle btn btn-info btn-xs"> </i> 
				                    </span>                   
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
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@endsection
