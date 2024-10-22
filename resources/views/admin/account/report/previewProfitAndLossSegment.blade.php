<?php $title = "Profit & Loss for File No: ".$project['project_prefix']."-".$project['project_fileno']; ?>
@extends('layout.backend')
@section('title', $title)
<?php 
	use App\component\Content;
	$comadd = \App\Company::find(1);
	$amount = "Amount";
	$invoice = "Invoice";
?>
@section('content')
<div class="container">
	@include('admin.report.headerReport')		
	
	
	<div class="clearfix"></div><br>
	<table class="table tableExcel">
		<tr>
			<td style="border-top: none; padding: 2px;" colspan="3" class="text-center"><h4><strong style="font-size: 16px; text-transform:capitalize; color: #07c;">{{$title}}</strong></h4></td>
		</tr>
		<tr>
			<td style="border-top: none; padding: 2px;" class="text-right" colspan="2">Travelling Date:</td>
			<td style="border-top: none; padding: 2px;" class="text-left"><b>{{Content::dateformat($project['project_start'])}} -> {{Content::dateformat($project['project_end'])}}</b></td>
		</tr>
		
		<tr>
			<td style="border-top: none;  padding: 2px;" class="text-right" colspan="2">Client Name:</td><td style="border-top:none; padding: 2px;" class="text-left"><b>{{$project['project_client']}}</b></td>
		</tr>
		<tr>
			<td style="border-top: none; padding: 2px;" colspan="2" class="text-right" >Agent Name: </td>
			<td style="border-top: none; padding: 2px;"><b>{{$project->supplier['supplier_name']}}</b></td>
		</tr>
		<tr>
			<td style="border-top: none; padding: 2px;" colspan="2" class="text-right">Pax Number: </td>
			<td style="border-top: none; padding: 2px;"><b>{{$project->project_pax}} Pax</b></td>
		</tr>		
		<tr>
			<td style="border-top: none; padding: 2px;" class="hidden-print" colspan="3">
				<div class="pull-right hidden-print">
				   	<a href="javascript:void(0)" class="myConvert hidden-print"><span class=" btn btn-primary btn-acc btn-xs"><i class="fa fa-download"></i> Download In Excel</span></a>&nbsp;
				    <span onclick="window.print()" class="hidden-print btn btn-xs btn-primary btn-acc"><i class="fa fa-print"></i> Print</span>
				</div>
			</td>
		</tr>
	  	<tr>
	  		<td width="320px" style="padding:0px 8px 1px 0px; vertical-align: bottom; border-top: none;">Account</td>
	  		<td style="padding:0px 8px 1px 0px; border-top: none;">Segment </td>
	  		<td style="padding:0px 8px 1px 0px; border-top: none;" class="text-right">Total Dollar</td>
	  	</tr>
	  	<?php $CostOfSaleTotal = 0; $reVenueTotal=0; $otherExpense=0?>
  		@foreach($journals as $key => $jour)
  			<tr>
	  			<th style="border-bottom:solid 1px #9E9E9E; padding-left: 0px; color:#07C; text-transform: uppercase;" colspan="3">{{$jour->account_name}} </th>
  			</tr>
	  		<?php 
	  			$transactions = \App\AccountTransaction::where(['account_type_id'=>$jour->account_type_id, 'project_id'=>$project->id, 'status'=>1])->groupBy('account_name_id')->orderBy('created_at', 'DESC')->get(); 
	  			$TotalAmt = 0; ?>
	  		@foreach($transactions as $kay => $tran)
		  		<?php 
					$reVenue = App\AccountTransaction::where(["project_id"=>$project->id,'status'=>1, 'account_type_id'=>$jour->account_type_id, 'account_name_id'=>$tran->account_name_id])->whereIn('account_type_id',[8,9]);
				 
					$CostOfSale = App\AccountTransaction::where(["project_id"=>$project->id, 'status'=>1, 'account_type_id'=>$jour->account_type_id, 'account_name_id'=>$tran->account_name_id])->whereNotIn('account_type_id',[8,9]);
					// $CostOfSaleTotal = 0;
					// $reVenueOrReven = 0;
		  			if ($jour->account_type_id == 8 || $jour->account_type_id == 9) {
		  				$reVenueAmt = ($reVenue->sum('debit') + $reVenue->sum('ex_rate_converted'));
		  				$TotalAmt = $TotalAmt + $reVenueAmt;
		  				$reVenueTotal = $reVenueTotal + $reVenueAmt;
						$total = $reVenueAmt;
					}elseif ($jour->account_type_id == 12) {
						$otherExpense = $otherExpense + $CostOfSaleAmt;
					
		  			}else{
		  				$CostOfSaleAmt = ($CostOfSale->sum('credit') + $CostOfSale->sum('ex_rate_converted'));
		  				$TotalAmt = $TotalAmt + $CostOfSaleAmt;
		  				$CostOfSaleTotal = $CostOfSaleTotal + $CostOfSaleAmt;
		  				$total = $CostOfSaleAmt;
		  				// $CostOfSaleTotal = $CostOfSaleTotal + $CostOfSaleAmt;
		  			}
		  		 ?>
	  			<tr>
		  			<td>&nbsp;&nbsp;{{$tran->account_name['account_code']}}-{{$tran->account_name['account_name']}} </td>
		  			<td>
		  				@if($project)
		  					<a title="Preview Journal Entry" target="_blank" href="{{route('getJournalReport', ['journal_entry'=>$tran->project['project_number']])}}">{{$project['project_prefix']}}-{{$project['project_fileno']}}</a>
		  				@endif
		  			</td>
		  			<td class="text-right">{{number_format($total, 2)}}</td>
	  			</tr>

	  		

	  		@endforeach
	  		<tr><th colspan="3" class="text-right">Total {{$jour->account_name}} : {{number_format($TotalAmt,2)}}</th></tr>
			@if ($jour->account_type_id == 9) 
  			<tr><td style="border:none;" class="text-right" colspan="3">Total Revenue: {{number_format($reVenueTotal, 2)}}</td></tr>
  			@elseif ($jour->account_type_id == 10)
  			<tr><td style="border:none;" class="text-right" colspan="3">Total Cost Of Sale: {{number_format($CostOfSaleTotal, 2)}}</td></tr>
  			@elseif ($jour->account_type_id == 12)
  			<tr><td style="border:none;" class="text-right" colspan="3">Total Expense: {{number_format($otherExpense, 2)}}</td></tr>
  			@endif
  		@endforeach
  		<?php $grandTotal = ($reVenueTotal - $CostOfSaleTotal); ?>
	  	<tr><th colspan="3" class="text-right" style="color:#07C"><h3>Gross Profit : {{number_format($grandTotal, 2)}}</h3></th></tr>
	</table>
</div>
<script type="text/javascript">
  	$(document).ready(function(){
      	$(".myConvert").click(function(){
          if(confirm('Do you to export in excel?')){
            $(".tableExcel").table2excel({
              exclude: ".noExl",
              name: "Profit & Loss",
              filename: "{{$title}}",
              fileext: ".xls",
              exclude_img: true,
              exclude_links: true,
              exclude_inputs: true
            });
            return false;
          }else{
            return false;
          }
      	});
  	});
</script>
<script type="text/javascript" src="{{asset('js/jquery.table2excel.min.js')}}"></script>

@include('admin.include.datepicker')
@endsection
