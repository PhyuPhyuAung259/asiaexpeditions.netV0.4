<?php use App\component\Content; ?>
<table class="table table-hover table-bordered excel-sheed">
	@if(isset($bookeds) && $bookeds != Null)
	    <thead>
             <tr><th colspan="8" align="left"><font color="#1991d6">{{$supp['supplier_name']}}</font> From {{Content::dateformat($start_date)}} -> {{Content::dateformat($end_date)}}</th></tr>
	        <tr style="background-color: #ddd">
	          	<td width="86">File No.</td>
	          	<td>Client Name</td>
	          	<td>Start Date</td>
	          	<td>Menu</td>
	          	<td class="text-center">Pax</td>
	          	<td class="text-right">Price</td>
	            <td class="text-right">Amount</td>
                <td class="text-right">Price {{Content::currency(1)}}</td>
                <td class="text-right">Amount {{Content::currency(1)}}</td>

	        </tr>
	    </thead>
        <tbody>
        	@foreach($bookeds as $key => $sub )
                <?php $project = App\Project::where('project_number',$sub->project_number)->first(); ?>
                <tr>
                    <td>{{$project['project_prefix']}}-{{$project['project_fileno']}}</td>
                    <td>{{$project['project_client']}} <small><b>x</b></small> <i>[ {{$project['project_pax']}} ]</i></td>
                    <td>{{Content::dateformat($sub->start_date)}}</td>                    
                    <td>{{{$sub->rest_menu->title or ''}}}</td>
                    <td class="text-center">{{$sub->book_pax}}</td>
                    <td class="text-right">{{Content::money($sub->price)}}</td>
                    <td class="text-right">{{Content::money($sub->amount)}}</td>
                    <td class="text-right">{{Content::money($sub->kprice)}}</td>
                    <td class="text-right">{{Content::money($sub->kamount)}}</td>
                </tr>
            @endforeach      
        </tbody>
        <tfoot>
        	<tr style="border: solid 1px #ddd;">
        		<td bgcolor="#ddd" colspan="7" align="right">
                    @if($bookeds->sum('amount'))
                        <font color="#1991d6">
                            Grand Total : {{Content::money($bookeds->sum('amount'))}}  {{Content::currency()}}
                        </font>
                    @endif
                </td>
                <td bgcolor="#ddd" colspan="2" align="right">
                    @if($bookeds->sum('kamount'))
                        <font color="#1991d6">
                            Grand Total : {{Content::money($bookeds->sum('kamount'))}}  {{Content::currency(1)}}
                        </font>
                    @endif
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