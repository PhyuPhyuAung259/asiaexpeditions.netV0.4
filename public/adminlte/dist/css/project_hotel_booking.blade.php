<?php 
use App\component\Content;
?>
<h5 class="text-center"><strong>HOTEL INFORMATION ON YOUR PROGRAM</strong></h5>
<table class="table">
	<?php 
	$hotelReport = \App\HotelBooked::where('project_number', $book->book_project)->groupBy('hotel_id')->orderBy('hotel_id', 'DESC');
	?>
	@foreach($hotelReport->get() as $hotel)
	<?php 
		$sup = App\Supplier::find($hotel->hotel_id);
	?>
	<tr>
		<td width="330px;"><img src="{{Content::urlthumbnail($hotel->supplier_photo)}}" ></td>
		<td style="vertical-align:top;">
			<h5 style="text-transform: capitalize;"><strong>{{ $sup->supplier_name }} | <a href="javascript:void(0)">{{{ $sup->province->province_name or ''}}}</a>, <a href="javascript:void(0)">{{{ $sup->country->country_name or ''}}}</a> </strong></h5>
			{!! str_limit($sup->supplier_intro,1350) !!}
		</td>
	</tr>
	@endforeach
</table>