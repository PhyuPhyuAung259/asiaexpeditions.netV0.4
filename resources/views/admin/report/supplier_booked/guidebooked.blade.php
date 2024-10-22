<?php use App\component\Content; ?>
<table class="table table-hover table-bordered excel-sheed">
	@if(isset($bookeds) && $bookeds != Null)
	    <thead>
            <tr><th colspan="8" align="left"><font color="#1991d6">{{$supp['supplier_name']}}</font> From {{Content::dateformat($start_date)}} -> {{Content::dateformat($end_date)}}</th></tr>
	        <tr style="background-color: #ddd">
	          	<td width="86">File No.</td>
	          	<td>Client Name</td>
	          	<td>Start Date</td>
                <td>Service Name</td>
	          	<td>Language</td>
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
                    <td>{{Content::dateformat($sub->book['book_checkin'])}}</td>                    
                    <td>{{$sub->service['title']}}</td>
                    <td>{{$sub->language['name']}}</td>
                    <td class="text-right">{{Content::money($sub->price)}}</td>
                    <td class="text-right">{{Content::money($sub->kprice)}}</td>
                </tr>
            @endforeach      
        </tbody>
        <tfoot>
        	<tr style="background-color:#f4f4f4;border: solid 1px #ddd; color: darkgreen;">
        		<td colspan="6" align="right" bgcolor="#ddd">
                    @if($bookeds->sum('price'))
                        <font color="#1991d6">
                            Grand Total : {{Content::money($bookeds->sum('price'))}}  {{Content::currency()}}
                        </font>
                    @endif
                </td>
                <td colspan="2" bgcolor="#ddd" align="right">
                    @if($bookeds->sum('kprice'))
                        <font color="#1991d6">
                            Grand Total : {{Content::money($bookeds->sum('kprice'))}}  {{Content::currency(1)}}
                        </font>
                    @endif
                </td>
        	</tr>
        </tfoot>
    @else
    	<tfoot>
        	<tr>
        		<td bgcolor="#ddd" colspan="12" align="right">Record Not found...</td>
        	</tr>
        </tfoot>	
    @endif
</table>