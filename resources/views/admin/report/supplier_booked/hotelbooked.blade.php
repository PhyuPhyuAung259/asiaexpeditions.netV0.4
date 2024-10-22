<?php use App\component\Content; ?>
<table class="table table-hover table-bordered excel-sheed">
	@if(isset($bookeds) && $bookeds != Null)
	    <thead>
            <tr><th colspan="13" align="left"><font color="#1991d6">{{$supp['supplier_name']}}</font>  From {{Content::dateformat($start_date)}} -> {{Content::dateformat($end_date)}}</th></tr>
	        <tr style="background-color: #ddd">
	          	<th width="86">File No.</th>
	          	<th>Client Name</th>
	          	<th>Checkin <i class="fa fa-long-arrow-right" style="color: #72afd2"></i> Checkout</th>
	          	<th>Room Type</th>
	          	<th class="text-center" width="99">No.Of Room</th>
	          	<th class="text-center">Nights</th>
                <th align="center">Total of Nights</th>
	          	@foreach(App\RoomCategory::take(5)->orderBy('id', 'ASC')->get() as $key => $cat)
	                <th class="text-center" title="{{$cat->name}}" style="text-transform: capitalize;">{{substr($cat->key_name, 1)}}</th>
	            @endforeach
	            <th class="text-right">Amount</th>
	        </tr>
	    </thead>
    
        <tbody>
        	@foreach($bookeds as $key => $sub )
        		<?php $project = App\Project::where('project_number',$sub->project_number)->first(); ?>
        		<tr>
        			<td>{{$project['project_prefix']}}-{{$project['project_fileno']}}</td>
        			<td>{{$project['project_client']}} <small><b>x</b></small> <i>[ {{$project['project_pax']}} ]</i></td>
        			<td>{{Content::dateformat($sub->checkin)}} <i class="fa fa-long-arrow-right" style="color: #72afd2"></i> {{Content::dateformat($sub->checkout)}}</td>
        			<td>{{{$sub->room->name or ''}}}</td>
        			<td class="text-center">{{$sub->no_of_room}}</td>
                    <td class="text-center">{{$sub->book_day}}</td>
                    <td align="center">{{$sub->no_of_room * $sub->book_day}}</td>
        			<td class="text-center">{{Content::money($sub->ssingle)}}</td>
        			<td class="text-center">{{Content::money($sub->stwin)}}</td>
        			<td class="text-center">{{Content::money($sub->sdouble)}}</td>
        			<td class="text-center">{{Content::money($sub->sexbed)}}</td>
        			<td class="text-center">{{Content::money($sub->nchextra)}}</td>
        			<td class="text-right">{{Content::money($sub->sell_amount)}}</td>
        		</tr>
        	@endforeach        
        </tbody>
        <tfoot>
        	<tr>
        		<td colspan="13"  bgcolor="#ddd" align="right" style="border: solid 1px #ddd;, ">
                    <font color="#1991d6">
                        <strong>Grand Total : {{Content::money($bookeds->sum('sell_amount'))}}  {{Content::currency()}}</strong>
                    </font>
                </td>
        	</tr>
        </tfoot>
    @else
    	<tfoot>
        	<tr>
        		<td bgcolor="#ddd" align="center" colspan="12">Record Not found</td>
        	</tr>
        </tfoot>	
    @endif
</table>