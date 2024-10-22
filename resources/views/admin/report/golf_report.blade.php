<?php  use App\component\Content;?>
<table class="table" id="roomrate">
	<tr style="background-color: rgb(245, 245, 245);">
		<th style="padding: 2px;"><span>Service Name</span></td>
		<th style="padding: 2px;"><span>Selling Price</span></th>		
		<th style="padding: 2px;"><span>Net Price</span></th>		
		<th style="padding: 2px;"><span>Selling Kyat</span></th>		
		<th style="padding: 2px;"><span>Net Kyat</span></th>		
	</tr>		
	@foreach(App\GolfMenu::where('supplier_id', $supplier->id)->orderBy('nprice')->get() as $key => $golf)	
		<tr>
			<td style="width: 50%;">{{$golf->name}}</td>
			<td>{{$golf->price}} <span class="pcolor">{{Content::currency()}}</span></td>
			<td>{{$golf->nprice}} <span class="pcolor">{{Content::currency()}}</span></td>
			<td>{{$golf->kprice}} <span class="pcolor">{{Content::currency(1)}}</span></td>
			<td>{{$golf->kprice}} <span class="pcolor">{{Content::currency(1)}}</span></td>
		</tr>
	@endforeach
	@include('admin.report.supplier_info')
</table>