<?php use App\component\Content; ?>
<table class="table table-hover table-bordered excel-sheed">
    <!-- {{$bookeds}} -->
	@if(isset($bookeds) && $bookeds->count() > 0 )
	    <thead>
            <tr><th colspan="8" align="left"><font color="#1991d6">{{$supp['supplier_name']}}</font> From {{Content::dateformat($start_date)}} -> {{Content::dateformat($end_date)}}</th></tr>
	        <tr style="background-color: #ddd">
	          	<td width="86">File No.</td>
	          	<td>Client Name</td>
	          	<td>Date</td>
	          	<td>Flight No.</td>
                <td>Departure -> Arrival Time</td>
	          	<td>Destination</td>
	          	<td class="text-center">Seats</td>
	          	<td class="text-right">Price</td>
	            <td class="text-right">Amount</td>
	        </tr>
	    </thead>
        <tbody>
        	@foreach($bookeds as $key => $sub)        		
                <tr>
        			<td>{{$sub->project['project_prefix']}}-{{$sub->project['project_fileno']}}</td>
        			<td>{{$sub->project['project_client']}} <small><b>x</b></small> <i>[ {{$sub->project['project_pax']}} ]</i></td>
        			<td>{{Content::dateformat($sub->book_checkin)}}</td>
        			<td>{{{$sub->flight['flightno'] or ''}}}</td>
                    <td>{{{$sub->flight['dep_time'] or ''}}} -> {{{$sub->flight['arr_time'] or ''}}}</td>
                    <td>{{{$sub->flight['flight_from'] or ''}}} <i class="fa fa-long-arrow-right" style="color: #72afd2"></i> {{{$sub->flight['flight_to'] or ''}}} </td>
        			<td class="text-center">{{$sub->book_pax}}</td>
                    <td class="text-right">{{Content::money($sub->book_price)}}</td>
                    <td class="text-right">{{Content::money($sub->book_amount)}}</td>
                </tr>
        	@endforeach        
        </tbody>
        <tfoot>
        	<tr>
        		<td colspan="11" bgcolor="#ddd" align="right" style="border: solid 1px #ddd;">
                    <font color="#1991d6">
                        Grand Total : {{Content::money($bookeds->sum('book_amount'))}}  {{Content::currency()}}
                    </font>
                </td>
        	</tr>
        </tfoot>
    @else
    	<tfoot>
        	<tr>
        		<td bgcolor="#ddd" colspan="12" align="center">Record Not found...</td>
        	</tr>
        </tfoot>	
    @endif
</table>