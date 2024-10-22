<div class="col-sm-8 col-xs-12 pull-right">
	<div class="col-md-3">
		<input type="hidden" value="{{{$projectNum or ''}}}" id="projectNum">
		<input class="form-control input-sm" type="text" id="from_date" name="start_date" value="{{{$startDate or ''}}}"> 
	</div>
	<div class="col-md-3">
		<input class="form-control input-sm" type="text" id="to_date" name="end_date" value="{{{$endDate or ''}}}"> 
	</div>
	<div class="col-md-2" style="padding: 0px;"> 
		<button class="btn btn-primary btn-sm" type="submit">Search</button>
	</div>
	@if(\Auth::user()->role_id == 2 )
		<div class="col-md-1" style="padding: 0px;">
			<a href="{{route('createPaymentLink')}}" class="btn btn-primary btn-sm">Create Payment Link</a>
		</div>
    @endif
</div>
<script type="text/javascript">
	$(document).ready(function(){
	    $(".datatable").DataTable({
	      language: { searchPlaceholder: "Project File/No./ClientName"}
	    });
	});
</script>