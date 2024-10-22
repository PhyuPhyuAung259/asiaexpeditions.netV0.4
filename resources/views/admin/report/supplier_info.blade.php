<tr>
	<td colspan="5"><br>
		<address>
			<p><b style="text-transform: capitalize;">{{$type}} Name :</b> {{$supplier->supplier_name}}<br>
			<b>P/H :</b> {{ $supplier->supplier_phone}}/{{$supplier->supplier_phone2}}<br>
			<b>Email :</b> {{$supplier->supplier_email}}<br>
			<b>Address :</b> {{$supplier->supplier_address}}<br>
			<b>Website :</b> {{$supplier->supplier_website}}</p>
		</address>
	</td>
	<?php $getClient = \App\Admin\Photo::where(['supplier_id'=> $supplier->id])->get(); ?>
	@if($getClient->count() > 0)
		<td colspan="6">
			<div><strong>Hotel Contract</strong></div>
			<?php  $pdfPolicies = explode('/', rtrim($supplier->supplier_contract, "/")); ?>				
			<ul>
				@foreach($getClient as $key=>$val)
				<li><a target="_blank" href="{{asset('storage/contract/hotels')}}/{{$val->name}}">{{$val->original_name}}</a></li>
				@endforeach
			</ul>
		</td>
	@endif
	<td colspan="5"><br>
		{!! $supplier->remak !!}		
	</td>
</tr>