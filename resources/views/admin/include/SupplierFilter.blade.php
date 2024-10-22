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
	.wrapper-filter-item ul li{
		padding: 0px 10px 3px 10px;
	    border: solid 1px #ddd;
	    border-radius: 3px;
	    background-color: #dddddd2e;
	}
	.wrapper-ulist-item ul li{
		cursor: pointer;
		padding: 6px 4px;
		transition: transform 300ms cubic-bezier(0.075, 0.132, 0.165, 1);
	}
	.wrapper-ulist-item ul li:hover, .wrapper-FGlist-item ul li:hover{
	    color: #fff;
	    background-color: #337ab7;
	    border-color: #2e6da4;
	}
</style>
<?php 
	$dataLists = App\Supplier::where(['supplier_status'=>1, 'business_id'=>$bus_id])->orderBy('supplier_name');	
?>
<div class="row">
	<div class="col-md-12 col-xs-12">
	    <div class="form-group">
	        <label>{{$title}} <span style="color:#b12f1f;">*</span></label> 
			<div style="position: relative;">
			    <div class="dropdown-toggle form-control wrapper-filter-item" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative;">
			      	<span style="position: absolute;right: 11px;padding: 4px;"><i class="fa fa-sort-up"></i></span>
				    <ul id="MainUl" class="list-unstyled" style="display: flex;">
				    	@if($sup_id)
				  			<li>
				  				<input type="hidden" name="{{$field_name}}" value="{{$sup_id}}">
				  				<span>{{$name}}</span>
				  			</li>
				  		@endif
				  	</ul>
			    </div>
			    <ul class="dropdown-menu wrapper-ulist-item" style="width: 100%">
			    	<div style="padding-top: 0px; padding: 12px;">      
						<span>
							<input style="border-radius: 2px;" id="searchData" type="text" class="form-control" onkeyup="supFilter()" placeholder="Type for search..">
						</span>    
						<div style="max-height: 218px; overflow: auto;">                
							<ul id="{{$field_name}}" class="list-unstyled" style="padding-top: 5px;">
							  	@foreach($dataLists->get() as $key => $cl)                            
								    <li class="ulList" data-id="{{$cl->id}}" data-fieldname="{{$field_name}}" data-name="{{$cl->supplier_name}}">
								    	<span> {{$cl->supplier_name}}</span>
								    </li>
							    @endforeach     
							</ul>
						</div> 
						<div><a href="{{$routeMap}}"><i class="fa fa-plus"></i> Add New </a></div>
					</div>
			    </ul>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$("ul.wrapper-ulist-item ul li").on('click', function(){
			$(this).closest('ul div div ').closest('ul.wrapper-ulist-item').closest('div').find('ul#MainUl').html('<li><input type="hidden" name="'+ $(this).data('fieldname') +'" value="'+$(this).data('id')+'"><span>'+$(this).data('name')+'</span></li>');
		});

		// $(window).on('keydown', function(e){
		// 	var li = $('#list > li');
		// 	var liSelected;
		// 	if(e.which === 40){

		// 	}
		// })
	});
	

    function supFilter() {
		input = document.getElementById("searchData");
		filter= input.value.toUpperCase();
		ul = document.getElementById("{{$field_name}}");
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