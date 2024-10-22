<?php  use App\component\Content;

?>
@if($sub_type=="with Price")
<table class="table" id="roomrate">
	<thead style="background-color: rgb(245, 245, 245); font-weight: 600;">
		<tr>
		
			<td style="padding: 8px;">Guide Service</td>
			<td style="padding: 8px;">Language</td>
			<td style="padding: 8px;">Price US</td>	
            
		</tr>
	</thead>
	<tbody>
		<?php $data = DB::table('guide_language_supplier')->where('supplier_id', $supplier->id)->get();?>
		@foreach($data as $key => $guide_lang)
        <?php
            $guide=DB::table('guide_language')->where('id', $guide_lang->guide_language_id)->first();
            $guide_service=DB::table('guide_service')->where('id', $guide->guide_service_id)->first();
         
        ?>
       
			<tr style="border-bottom: 3px solid #black;">
				<td style="padding: 8px;"> {{{$guide_service->title or ''}}} </td>
                <td style="padding: 8px;"> {{{$guide->name or ''}}} </td>
				<td style="padding: 8px;">{{{$guide->price or ''}}} <span class="pcolor">{{Content::currency()}}</span></td>
				
			</tr>
		@endforeach
	</tbody>
</table>
@elseif($sub_type=="without Price")
<table class="table" id="roomrate">
	<thead style="background-color: rgb(245, 245, 245); font-weight: 600;">
		<tr>
		
			<td style="padding: 8px;">Guide Service</td>
			<td style="padding: 8px;">Language</td>	
		
		</tr>
	</thead>
	<tbody>
		<?php $data = DB::table('guide_language_supplier')->where('supplier_id', $supplier->id)->get();?>
		@foreach($data as $key => $guide_lang)
        <?php
            $guide=DB::table('guide_language')->where('id', $guide_lang->guide_language_id)->first();
            $guide_service=DB::table('guide_service')->where('id', $guide->guide_service_id)->first();
          
        ?>
      
			<tr style="border-bottom: 3px solid #black;">
				<td style="padding: 8px;"> {{{$guide_service->title or ''}}} </td>
                <td style="padding: 8px;"> {{{$guide->name or ''}}} </td>
				
			</tr>
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