@extends('layout.backend')
@section('title', 'Reportation')
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
		           	<div class="col-md-12">
		           		<h3 style="text-transform:capitalize; color: #3c8dbc;"><i class="fa fa-user-md" style="font-size: 21px;"></i> Suppliers Reportation </h3>
		           	</div>
		            <form method="GET" action="">
		            	<div class="row">
		            		<?php $getBusType = App\Business::where(['status'=>1,'web'=>0])->whereNotIn('id', [4,51,5,10,13,12,9])->whereHas('supplier', function($query){
		            			$query->where('supplier_status',1);
		            		})->orderBy('name')->get(); ?>
	                        <div class="col-md-3"> 
		                        <select class="form-control" name="business" id="business">   
		                        	@foreach($getBusType as $key => $bus)
		                        		<?php $busid = isset($bus_id) ? $bus_id : 0; ?>
		                        		<option value="{{$bus->id}}" {{$busid == $bus->id ? 'selected' : ''}}>{{$bus->name}}</option>
		                        	@endforeach
		                        </select>
	                        </div>
	                        <div class="col-md-3">
		                        <!--<div class="form-group">-->
		                        <!--    <input type="text" name="supplier" class="form-control" value="{{$supp['supplier_name'] or ''}}" placeholder="Supplier Name Search..." >		           	-->
		                        <!--</div>-->
		                        
								<select class="col-md-2 form-control input-sm  " name="supplier" id="supplier"  >
									<option value="0">Choose Supplier Name</option>
								</select>
	                      
	                        </div>
	                        <div class="col-md-2">
		                        <div class="form-group">
		                            <input type="text" name="start_date" class="form-control text-center" id="from_date" value="{{{$start_date or '' }}}"  placeholder="Date From" readonly>
		                        </div>
	                        </div>
	                        <div class="col-md-2">
		                        <div class="form-group">
		                            <input type="text" name="end_date" class="form-control text-center" id="to_date" value="{{{$end_date or ''}}}" placeholder="Date To" readonly>
		                        </div>
	                        </div>
	                        <div class="col-md-1">
		                        <div class="form-group">
		                            <div class="pull-left">
		                              <button type="submit" class="btn btn-primary " id="btnSearchJournal">Search</button>
		                            </div>
		                        </div>
	                        </div> 
                        </div>
                    </form>
                    <hr style="border: none;border-top: solid #3c8dbc; margin-top: 0px;">
                    <div class="col-md-12">
                    	@if(isset($bookeds) && $bookeds != Null)
			    			<span class="pull-right">
			    				<div class="dropup">
								  	<button class="btn btn-primary btn-acc btn-xs dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								    Download <span class="caret"></span>
								  	</button>
									<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
									    <li><a href="#" class="ok_download" data-type="pdf"><i class="fa fa-file-pdf-o"></i>PDF</a></li>
									    <li><a href="#" class="ok_download" data-type="excel"><i class="fa fa-file-excel-o"></i> Excel</a></li>
									</ul>
								</div>
							</span>
						@endif
		    		
			           	@if($bus_id == 1)
			           		@include('admin.report.supplier_booked.hotelbooked')
			           	@elseif($bus_id == 2)
			           		@include('admin.report.supplier_booked.restaurantbooked')
			           	@elseif($bus_id == 6)
			           		@include('admin.report.supplier_booked.guidebooked')
			           	@elseif($bus_id == 7)
			           		@include('admin.report.supplier_booked.transportbooked')
			           	@elseif($bus_id == 29)
			           		@include('admin.report.supplier_booked.golfbooked')
			           	@elseif($bus_id == 37)
			           		@include('admin.report.supplier_booked.ticketingbooked')
			           	@endif
			        </div>
		        </section>
		    </div>
	    </section>
	</div>
</div>

<script type="text/javascript" src="{{asset('js/jquery.table2excel.min.js')}}"></script>
<script type="text/javascript">
	$(".ok_download").click(function(){
		var type = $(this).data('type');
		if (type == 'excel') {
			$(".excel-sheed").table2excel({
				exclude: ".noExl",
				name: "Supplier booked report by {{Content::dateformat($start_date)}} - {{Content::dateformat($end_date)}}",
				filename: "Supplier booked report by {{Content::dateformat($start_date)}} - {{Content::dateformat($end_date)}}",
				fileext: ".xls",
				exclude_img: true,
				exclude_links: true,
				exclude_inputs: true						
			});
			return false;
		}
	});
	$(document).ready(function(){
    
      $('#business').change(function() {
        var bus_id = $(this).val();
          $.ajax({
            url: '/get_sup_name/' + bus_id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var sup_info = $('#supplier');
                sup_info.empty();
                if (response.length === 0) {
                    sup_info.append('<option value="0">No available</option>');
                } else {
					sup_info.append('<option value="0">Choose Supplier Name</option>');
                    $.each(response, function(key, value) {
                        sup_info.append('<option value="' + value.id + '">' +
                            value.supplier_name + '</option>');
                    });
                } 
            }
          }); 
                    
      });
    });
</script>


@include('admin.include.datepicker')
@endsection
