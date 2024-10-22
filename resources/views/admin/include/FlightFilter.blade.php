<style type="text/css">
	.selectAuto{
		padding-top: 0px;
	    padding: 12px;
	    background-color: #fff;
	    border: 1px solid #0000001f;
	    border-radius: 4px;
	    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
	    box-shadow: 0 1px 1px rgba(0,0,0,.05);
	}

	.wrapper-filter-item ul li i{
		cursor: pointer;
	}
	.wrapper-filter-item ul li{
		padding: 0px 6px 0px 6px;
	    border: solid 1px #ddd;
	    border-radius: 3px;
	    color: white;
	    /*background-color: #1c97f8;*/
	        background-color: #337ab7;
    border-color: #2e6da4;
	}
	.wrapper-ulist-item ul li{
		padding: 6px 4px;
		transition: transform 300ms cubic-bezier(0.075, 0.132, 0.165, 1);
	}
	.wrapper-ulist-item ul li:hover{
	    color: #fff;
	    background-color: #337ab7;
	    border-color: #2e6da4;
	}
</style>
<?php 
	$flschedule = App\FlightSchedule::where('flight_status',1)->orderBy('flightno');
	$routeMap = route('createFlightSchedule');
?>
<div class="row">
	<div class="col-md-6 col-xs-6">
	    <div class="form-group">
	        <label>ARR Time</label> 
			<div style="position: relative;">
			    <div class="dropdown-toggle form-control wrapper-filter-item" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative;" {{{$permission or ''}}} >
			      	<span style="position: absolute;right: 11px;padding: 4px;"><i class="fa fa-sort-up"></i></span>
				    <ul id="MainUl" class="list-unstyled" style="display: flex;">
				    	<li>@if($arr_id)
				  			@if( !isset($_GET['ref']))
			  				<i class="bfligt-remove  fa fa-remove (alias)"></i> 
			  				@endif <input type="hidden" name="arr_time" value="{{$arr_id}}"><span>{{$arr_name}}</span></li>
				  		@endif
				  	</ul>
			    </div>
			    @if( !isset($_GET['ref']))
				    <ul class="dropdown-menu wrapper-ulist-item" style="width: 100%">
				    	<div style="padding-top: 0px; padding: 12px;">      
							<span>
								<input style="border-radius: 20px;" id="searchArr" type="text" class="form-control" onkeyup="filterArr()" placeholder="Type for search..">
							</span>    
							<div style="max-height: 218px; overflow: auto;">                
								<ul id="arr_time" class="list-unstyled" style="padding-top: 5px;">
								  	@foreach($flschedule->get() as $key => $cl)                            
									    <li class="ulList" data-id="{{$cl->id}}" data-fieldname="arr_time"
									    	data-name="{{$cl->flightno .'-'. $cl->arr_time}}"> <span>{{$cl->flightno}} - A:{{$cl->arr_time}}</span></li>
								    @endforeach     
								</ul>
							</div> 
							<div><a href="{{$routeMap}}"><i class="fa fa-plus"></i> Add New </a></div>
						</div>
				    </ul>
			    @endif
			</div>
		</div>
	</div>

	<div class="col-md-6 col-xs-6">
	    <div class="form-group">
	        <label>DEP Time</label> 
			<div style="position: relative;">
			    <div class="dropdown-toggle form-control wrapper-filter-item" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative;" {{{$permission or ''}}}>
			      	<span style="position: absolute;right: 11px;padding: 4px;"><i class="fa fa-sort-up"></i></span>
				    <ul id="MainUl" class="list-unstyled" style="display: flex;">
				    	@if($dep_id)
				  			<li>@if( !isset($_GET['ref']))
				  				<i class="bfligt-remove  fa fa-remove (alias)"></i> 
				  				@endif <input type="hidden" name="dep_time" value="{{$dep_id}}"><span>{{$dep_name}}</span></li>
				  		@endif
				  	</ul>
			    </div>
			    @if( !isset($_GET['ref']))
			    <ul class="dropdown-menu wrapper-ulist-item" style="width: 100%">
			    	<div style="padding-top: 0px; padding: 12px;">      
						<span>
							<input style="border-radius: 20px;" id="searchDep" type="text" class="form-control" onkeyup="filterDep()" placeholder="Type for search..">
						</span>    
						<div style="max-height: 218px; overflow: auto;">                
							<ul id="dep_time" class="list-unstyled" style="padding-top: 5px;">
							  	@foreach($flschedule->get() as $key => $cl)                            
								    <li class="ulList" data-id="{{$cl->id}}" data-fieldname="dep_time"
								    	data-name="{{$cl->flightno .'-'. $cl->dep_time}}">
								    	<span>{{$cl->flightno}} - D:{{$cl->dep_time}}</span>
								    </li>								    
							    @endforeach     
							</ul>
						</div> 
						<div><a href="{{$routeMap}}"><i class="fa fa-plus"></i> Add New </a></div>
					</div>
			    </ul>
				@endif
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$("ul.wrapper-ulist-item ul li").on('click', function(){
			$(this).closest('ul div div ').closest('ul.wrapper-ulist-item').closest('div').find('ul#MainUl').html('<li style="position:relative"> <i class="bfligt-remove  fa fa-remove (alias)"></i> <input type="hidden" name="'+ $(this).data('fieldname') +'" value="'+$(this).data('id')+'"><span>'+$(this).data('name')+'</span></li>');
		});
		
		$(document).on('click', ".bfligt-remove",  function(){
			$(this).closest('li').remove();
		});
	});

    function filterArr() {
		input = document.getElementById("searchArr");
		filter = input.value.toUpperCase();
		ul = document.getElementById("arr_time");
		li = ul.getElementsByTagName("li");
		for (i = 0; i < li.length; i++) {
			a = li[i];
			if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
				li[i].style.display = "";
			} else {
				li[i].style.display = "none";
			}
		}		
    }

    function filterDep() {
		input = document.getElementById("searchDep");
		filter = input.value.toUpperCase();
		ul = document.getElementById("dep_time");
		li = ul.getElementsByTagName("li");
		for (i = 0; i < li.length; i++) {
			a = li[i];
			if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
				li[i].style.display = "";
			} else {
				li[i].style.display = "none";
			}
		}		
    }
</script>