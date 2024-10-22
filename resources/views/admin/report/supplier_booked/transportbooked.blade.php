<?php use App\component\Content; ?>
<table class="table table-hover table-bordered excel-sheed">
	@if(isset($bookeds) && $bookeds != Null)
	    <thead>
            <tr><td colspan="8" align="left"><font color="#1991d6">{{$supp['supplier_name']}}</font> From {{Content::dateformat($start_date)}} -> {{Content::dateformat($end_date)}}</td></tr>
	        <tr style="background-color: #ddd">
	          	<td width="86">File No.</td>
	          	<td>Client Name</td>
	          	<td>Start Date</td>
                <td>Service Name</td>
                <td>Vehicle</td>
	          	<td>Driver</td>
                <td class="text-right">Price {{Content::currency()}}</td>
                <td class="text-right">Amount {{Content::currency()}}</td>
	        </tr>
	    </thead>
        <tbody>
        	@foreach($bookeds as $key => $sub )
                <?php $project = App\Project::where('project_number',$sub->project_number)->first(); ?>
                <tr>
                    <td>{{$project['project_prefix']}}-{{$project['project_fileno']}}</td>
                    <td>{{$project['project_client']}} <small><b>x</b></small> <i>[ {{$project['project_pax']}} ]</i></td>
                    <td>{{Content::dateformat($sub->book['book_checkin'])}}</td>
                    <td>{{$sub->service['title']}}</td>
                    <td>{{$sub->vehicle['name']}}</td>
                    <td>{{$sub->driver['driver_name']}}</td>
                    <td class="text-right">{{Content::money($sub->price)}}</td>
                    <td class="text-right">{{Content::money($sub->kprice)}}</td>
                </tr>
            @endforeach      
        </tbody>
        <tfoot>
        	<tr style="border: solid 1px #ddd;">
        		<td bgcolor="#ddd" colspan="7" align="right" >
                    <font color="#1991d6">
                        @if($bookeds->sum('price'))
                            Grand Total : {{Content::money($bookeds->sum('price'))}} {{Content::currency()}}
                        @endif
                    </font>
                </td>
                <td bgcolor="#ddd" colspan="2" align="right">
                    <font color="#1991d6">
                        @if($bookeds->sum('kprice'))
                            Grand Total : {{Content::money($bookeds->sum('kprice'))}} {{Content::currency(1)}}
                        @endif
                    </font>
                </td>
        	</tr>
        </tfoot>
    @else
    	<tfoot>
        	<tr>
        		<td bgcolor="#ddd" colspan="12" class="text-center">Record Not found...</td>
        	</tr>
        </tfoot>	
    @endif
</table>