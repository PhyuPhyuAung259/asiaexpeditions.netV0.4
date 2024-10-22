<?php  use App\component\Content;?>
<table class="table" id="roomrate">
	<thead style="background-color: rgb(245, 245, 245); font-weight: 600;">
		<tr>
			<td style="padding: 8px;">Menu</td>
			<td style="padding: 8px;">Price US</td>	
			<td style="padding: 8px;">Price Kyat</td>	
		</tr>
	</thead>
	<tbody>
		<?php $data = App\RestaurantMenu::where('supplier_id', $supplier->id)->get(); ?>
		@foreach($data as $key => $rest)
			<tr style="border: 1px solid #eee;">
				<td style="padding: 8px;">{{$rest->title}}</td>
				<td style="padding: 8px;">{{$rest->price}} <span class="pcolor">{{Content::currency()}}</span></td>
				<td style="padding: 8px;">{{$rest->kprice}} <span class="pcolor">{{Content::currency(1)}}</span></td>
			</tr>
		@endforeach
	</tbody>
</table>
<table class="table" id="roomrate">
	<thead style="background-color: rgb(245, 245, 245); font-weight: 600;">
		<tr>
			<td style="padding: 8px;">Info</td>
			<td style="padding: 8px;">Remark</td>	
			<td style="padding: 8px;">Descriptions</td>	
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="padding: 8px; width: 25%;">	
				<address>
					<b>P/H :</b> {{ $supplier->supplier_phone}}/{{$supplier->supplier_phone2}}<br>
					<b>Email :</b> {{$supplier->supplier_email}}<br>
					<b>Address :</b> {{$supplier->supplier_address}}<br>
					<b>Website :</b> {{$supplier->supplier_website}}</p>
				</address>
			</td>
			<td style="padding: 8px; width: 30%;">
				{{$supplier->supplier_remark}}
			</td>
			<td style="padding: 8px; width: 45%;">
				{!!$supplier->supplier_intro!!}
			</td>
		</tr>
	</tbody>	
</table>