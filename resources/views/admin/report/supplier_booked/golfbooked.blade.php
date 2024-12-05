<?php 
use App\component\Content;
$total_round=0;
?>
<table class="table table-hover table-bordered excel-sheed">
    @if(isset($bookeds) && $bookeds != Null)
        <thead>
            <tr><th colspan="8" align="left"><font color="#1991d6">{{$supp['supplier_name']}}</font> From {{Content::dateformat($start_date)}} -> {{Content::dateformat($end_date)}}</th></tr>
            <tr style="background-color: #ddd">
                <td width="86">File No.</td>
                <td>Client Name</td>
                <td>Start Date</td>
                <td>Golf Service</td>
                <td class="text-center">Pax</td>
                <td class="text-right">Price</td>
                <td class="text-right">Amount</td>
                <td class="text-right">Price {{Content::currency(1)}}</td>
                <td class="text-right">Amount {{Content::currency(1)}}</td>
            </tr>
        </thead>
        <tbody>
            @foreach($bookeds as $key => $sub )
                <?php $project = App\Project::where('project_number',$sub->book_project)->first(); 
                $gsv = App\GolfMenu::find($sub->program_id);?>
                <tr>
                    <td>{{$project['project_prefix']}}-{{$project['project_fileno']}}</td>
                    <td>{{$project['project_client']}} <small><b>x</b></small> <i>[ {{$project['project_pax']}} ]</i></td>
                    <td>{{Content::dateformat($sub->book_checkin)}}</td>                    
                    <td>{{$gsv['name']}}</td>
                    <td class="text-center">{{$sub->book_pax}}</td>
                    <td class="text-right">{{Content::money($sub->book_nprice)}}</td>
                    <td class="text-right">{{Content::money($sub->book_namount)}}</td>
                    <td class="text-right">{{Content::money($sub->book_kprice)}}</td>
                    <td class="text-right">{{Content::money($sub->book_kamount)}}</td>
                </tr>
                <?php $total_round=$total_round+ $sub->book_pax; ?>
            @endforeach      
        </tbody>
        <tfoot>
            <!--<tr style="border: solid 1px #ddd;">-->
            <!--    <td colspan="7" align="right">-->
            <!--        <font color="#1991d6">-->
            <!--            @if($bookeds->sum('book_amount'))-->
            <!--                Grand Total : {{Content::money($bookeds->sum('book_amount'))}}  {{Content::currency()}}-->
            <!--            @endif-->
            <!--        </font>-->
            <!--    </td>-->
            <!--    <td colspan="2" align="right">-->
            <!--        @if($bookeds->sum('book_kamount'))-->
            <!--        <font color="#1991d6">-->
            <!--            Grand Total : {{Content::money($bookeds->sum('book_kamount'))}}  {{Content::currency(1)}}-->
            <!--        </font>-->
            <!--        @endif-->
            <!--    </td>-->
            <!--</tr>-->
            <tr style="border: solid 1px #ddd;">
                <td colspan="5" align="right">
                    <font color="#1991d6">
                    Total Round :  {{$total_round}}
                    </font>
                 
                </td>
                <td colspan="2" align="right">
                    <font color="#1991d6">
                        @if($bookeds->sum('book_amount'))
                            Grand Total : {{Content::money($bookeds->sum('book_namount'))}}  {{Content::currency()}} 
                        @endif
                    </font>
                </td>
                <td colspan="2" align="right">
                    @if($bookeds->sum('book_kamount'))
                    <font color="#1991d6">
                        Grand Total : {{Content::money($bookeds->sum('book_kamount'))}}  {{Content::currency(1)}}
                    </font>
                    @endif
                </td>
            </tr>
        </tfoot>
    @else
        <tfoot>
            <tr>
                <td colspan="12" align="center">Record Not found...</td>
            </tr>
        </tfoot>    
    @endif
</table>