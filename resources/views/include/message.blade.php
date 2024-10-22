@if(Session::has('message'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
	<small>{{Session::get('message')}}</small>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	    <span aria-hidden="true">&times;</span>
	</button>
</div>
@endif