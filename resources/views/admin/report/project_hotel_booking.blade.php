<?php 
use App\component\Content;
$hotelReport = \App\HotelBooked::where('project_number', $book->book_project)->groupBy('hotel_id')->orderBy('checkin', 'ASC')->get();
	?>
@if($hotelReport->count() >0)
<h5 class="text-center"><strong>HOTEL INFORMATION ON YOUR PROGRAM</strong></h5>
<table class="table">
	
	@foreach($hotelReport as $hotel)
	<?php 
		$sup = App\Supplier::find($hotel->hotel_id);
	?>
	<tr>
		<td width="330px;">
			<img src="/storage/{{$sup->supplier_photo}}" class="img-responsive">
		</td>
		<td style="vertical-align:top;">
			<h5 style="text-transform: capitalize;"><strong> 
				<a target="_blank" href="{{route('supplierReport' ,['reportId' => $hotel->hotel_id,'type'=> 'hotels'])}}?type=contract" title="Preview Hotel Information">{{ $sup->supplier_name }}</a> | 
				<a href="javascript:void(0)">{{{ $sup->province->province_name or ''}}}</a></strong></h5>
			{!! str_limit($sup->supplier_intro,1350) !!}
		</td>
	</tr>
	@endforeach
</table>
@endif