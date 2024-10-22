<?php  use App\component\Content;

?>
@if($sub_type=="with Price")
<table class="table" id="roomrate">
	<thead style="background-color: rgb(245, 245, 245); font-weight: 600;">
		<tr>
			<td style="padding: 8px;">Name</td>
			<td style="padding: 8px;">Transport Service</td>
			<td style="padding: 8px;">Price US</td>	
			<td style="padding: 8px;">Price Kyat</td>	
		</tr>
	</thead>
	<tbody>
		<?php $data = App\TransportMenu::where('supplier_id', $supplier->id)->get();?>
		@foreach($data as $key => $tran)
			<?php $transervice = App\TransportService::where('id', $tran->transport_id)->first();?> 
			@if(!empty($transervice->title))
			<tr style="border-bottom: 3px solid #black;">
				<td style="padding: 8px;">{{$tran->name}}</td>
				<td style="padding: 8px;"><?php $transervice = App\TransportService::where('id', $tran->transport_id)->first();?> {{{$transervice->title or ''}}} </td>
				<td style="padding: 8px;">{{$tran->price}} <span class="pcolor">{{Content::currency()}}</span></td>
				<td style="padding: 8px;">{{$tran->kprice}} <span class="pcolor">{{Content::currency(1)}}</span></td>
			</tr>
			@endif
		@endforeach
	</tbody>
</table>
@elseif($sub_type=="without Price")
<table class="table" id="roomrate">
	<thead style="background-color: rgb(245, 245, 245); font-weight: 600;">
		<tr>
		
			<td style="padding: 8px;">Transport Service</td>
			
		</tr>
	</thead>
	<tbody>
	<?php $data = DB::table('supplier_transport_service')->where('supplier_id', $supplier->id)->get();?>
		@foreach($data as $key => $tran)
			<?php $transervice = App\TransportService::where('id', $tran->transport_service_id)->first();?> 
			@if(!empty($transervice->title))
			<tr style="border-bottom: 3px solid #black;">
				<td style="padding: 8px;">{{{$transervice->title}}} </td>
			</tr>
			@endif
		@endforeach
	</tbody>
</table>
@endif
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