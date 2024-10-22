@if(Session::get('message'))
	<div class="notify-message">
		<div class="col-md-12" >
			<div class="alert alert-dismissible fade show {{ Session::get('status') }}" role="alert" style="position: relative; padding-left: 53px;">
				<i class="fa {{Session::get('status_icon')}}" style="font-size: 37px; position:absolute; top: 5px; left: 10px;"></i>
				<div style="text-transform: capitalize;"><span>{{Session::get('message')}}</span></div>
				<p></p>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close" style=" position: absolute;right: 7px;top: 13px;">
					<span aria-hidden="true" style="font-size: 22px;padding: 1px 8px;">&times;</span>
				</button>
			</div>
		</div>
	</div>
@endif
<div class="notify-message"> </div>
<script type="text/javascript">
	// setTimeout(function(){ $(".notify-message").remove().fadeIn(900); }, 5000);
</script>