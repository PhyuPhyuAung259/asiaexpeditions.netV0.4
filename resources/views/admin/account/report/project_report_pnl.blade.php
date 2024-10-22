<?php 
	$projectPNo = isset($project->project_prefix) ? $project->project_prefix ."-". ($journal->project_fileNo ? $journal->project_fileNo : $journal->project_number): $journal->project_number;
?>
@extends('layout.backend')
@section('title', 'Profit & Loss For '.$projectPNo)
<?php 
	use App\component\Content;
	$comadd = \App\Company::find(1);
	$amount = "Amount";
	$invoice = "Invoice";
?>
@section('content')
<div class="container">
	@include('admin.report.headerReport')		
	<span><a href="{{ URL::previous() }}"><i class="fa fa-arrow-circle-o-left"></i> Back to Aged Report</a></span>  
	<h3 class="text-left"><strong style="font-size: 18px; text-transform:capitalize;">Profit & Loss For <b>{{$projectPNo}}</b></strong></h3>
	<div class="clearfix"></div>
	<div><p>Agent Name: <b>{{{ $project->supplier->supplier_name or ''}}}</b></p></div>
	<div class="pull-left"><p>Project File No.:<b>{{$projectPNo}}</b>, &nbsp;&nbsp;
	 Client Name: <b>{{{$project->project_client or ''}}}</b>, &nbsp;&nbsp;
	 Travelling Date:<b>{{ isset($project->project_start) ? Content::dateformat($project->project_start) : '' }}</b></p></div>
	<div class="clearfix"></div><br>
	<table class="table">
		@if(isset($journal) && $journal->count() > 0)
			<?php
				$proNo = isset($project->project_number) ? $project->project_number:'';
				$accTransactionRP = \App\AccountTransaction::where(["project_number"=> $proNo, 'type'=> 1]);
				$JournalRPInvoice = \App\AccountJournal::where(["project_number"=>$proNo, 'status'=>1]);
				$totalRP = 0;
				$totalRPk = 0;
			?>
			@if($accTransactionRP->get()->count() > 0)
				<tr>
					<th width="120px" style="border-top: none;border-bottom: 1px solid #ddd;">Date</th>
					<th width="230px" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Suppliers Entry</b></th>
					<th width="130px" class="text-left" style="border-top: none;border-bottom: 1px solid #ddd;">{{$invoice}} Date</th>
					<th class="text-left" width="400px" style="border-top: none;border-bottom: 1px solid #ddd;">Descriptions</th>
					<th class="text-right" width="190px" style="border-top: none;border-bottom: 1px solid #ddd;">Received {{$amount}} </th>
					<th class="text-right" width="180px" style="border-top: none;border-bottom: 1px solid #ddd;">Balance </th>
					<th class="text-right" width="210px" style="border-top: none;border-bottom: 1px solid #ddd;">Received {{$amount}} {{Content::currency(1)}}</th>
					<th class="text-right" width="180px" style="border-top: none;border-bottom: 1px solid #ddd;">Balance {{Content::currency(1)}}</th>
				</tr>				
				@foreach($accTransactionRP->get() as $key => $acc)
					<?php 
						$totalRPk = ($totalRPk +  $acc->kdebit); 
						$totalRP = ($totalRP + $acc->debit); ?>
					<tr>
						<td>{{Content::dateformat($acc->pay_date)}}</td>
						<td>{{{$acc->supplier->supplier_name or ''}}}</td>
						<td>{{Content::dateformat($acc->invoice_pay_date)}}</td>
						<td>{{$acc->remark}}</td>						
						<td class="text-right">{{Content::money($acc->debit)}}</td>
						<td class="text-right">{{Content::money($acc->total_amount - $acc->debit)}}</td>
						<td class="text-right">{{Content::money($acc->kdebit)}}</td>
						<td class="text-right">{{Content::money($acc->book_kamount - $acc->kdebit)}}</td>
					</tr>
				@endforeach		
				<tr>
					<td colspan="8" class="text-right" style="border-bottom: solid 1px; padding-bottom: 12px; font-weight: 700;"> 	
						{{$invoice}} {{$amount}}:<b style="color:#337ab7;">{{Content::currency()}} {{Content::money($JournalRPInvoice->sum('credit'))}} </b>, &nbsp; 
						Recieved {{$amount}}: <b style="color:#337ab7;">{{Content::currency()}} {{Content::money($totalRP)}}</b>, &nbsp;
						Balance: <b style="color:#337ab7;">{{Content::currency()}} {{Content::money($JournalRPInvoice->sum('credit') - $totalRP)}}</b>,&nbsp;&nbsp;&nbsp;&nbsp;
						{{$invoice}} {{$amount}}:<b style="color:#337ab7;">{{Content::currency(1)}} {{Content::money($JournalRPInvoice->sum('kcredit'))}}</b>, &nbsp;
						Recieved {{$amount}}:<b style="color:#337ab7;">{{Content::currency(1)}} {{Content::money($totalRPk)}}</b>, &nbsp;
						Balance:<b style="color:#337ab7;">{{Content::currency(1)}} {{Content::money($JournalRPInvoice->sum('kcredit') - $totalRPk)}} </b>
					</td>
				</tr>				
			@endif
			<!-- End receivable -->
			<?php 
				$getJournal = \App\AccountJournal::getJournalByTransaction($proNo);
				$getTotalPaid = 0;
				$getCrossProfit = 0;
				$exKyatToDol = 0;
			?>
			@if($getJournal->get()->count() > 0 )
				<tr>
					<th width="100px" style="border-top: none;border-bottom: 1px solid #ddd;">Date</th>
					<th width="250px" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Suppliers Entry</b></th>
					<th class="text-left" width="150px" style="border-top: none;border-bottom: 1px solid #ddd;">{{$invoice}} Date</th>
					<th class="text-left" width="400px" style="border-top: none;border-bottom: 1px solid #ddd;">Descriptions</th>
					<th class="text-right" style="border-top: none;border-bottom: 1px solid #ddd;" width="140px">Paid {{$amount}}</th>
					<th class="text-right" style="border-top: none;border-bottom: 1px solid #ddd;" width="140px">Balance</th>
					<th class="text-right" style="border-top: none;border-bottom: 1px solid #ddd;" width="140px">Paid {{$amount}} {{Content::currency(1)}}</th>
					<th class="text-right" style="border-top: none;border-bottom: 1px solid #ddd;" width="140px">Balance {{Content::currency(1)}}</th>
				</tr>
				@foreach($getJournal->get() as $key => $acc_journal)
					<?php
						$accTranPayment = \App\AccountTransaction::Where(["project_number"=> $acc_journal->project_number, 'journal_id'=> $acc_journal->id, 'type'=>2, 'status'=> 1])->get();
						$paidAmount = 0;
						$paidAmountk = 0;
						$paidTotal = 0;
					?>
					@foreach($accTranPayment as $key =>  $acc)
						<?php 
							$paidBalanceK = $acc->ex_rate > 0 ? ($acc->kcredit / $acc->ex_rate) :$acc->kcredit;
							$exKyatToDol = $exKyatToDol + $paidBalanceK;
							$paidAmount = ($acc->total_amount - $acc->credit); 					
							$paidAmountk = ($acc->total_kamount - $acc->kcredit); 					
							$supName = isset($acc->supplier->supplier_name)? $acc->supplier->supplier_name: '';
							if ($acc->business_id == 55) {
		                        $supName = isset($acc->ent_service->name)? $acc->ent_service->name :'';
		                    }
		                    if ($acc->business_id == 54) {
								$supName = isset($acc->misc_service->name)? $acc->misc_service->name :'';
							}
							$ptotalAmount = ($acc_journal->debit - $paidAmount);
							$ptotalAmountk = ($acc_journal->kdebit - $paidAmountk);
							$getTotalPaid = $getTotalPaid + $ptotalAmount;
							$getCrossProfit = $getCrossProfit + $ptotalAmount;
							// $getCrossProfit = $getCrossProfit + $ptotalAmountk;
						?>
						<tr>
							{{$acc->ex_rate}}
							<td>{{Content::dateformat($acc->pay_date)}}</td>
							<td>{{$supName}}</td>
							<td>{{Content::dateformat($acc->invoice_pay_date)}}</td>
							<td>{{$acc->remark}}</td>								
							<td class="text-right">{{Content::money($acc->credit)}}</td>
							<td class="text-right">{{Content::money($paidAmount)}}</td>
							<td class="text-right">{{Content::money($acc->kcredit)}}</td>
							<td class="text-right">{{Content::money($paidAmountk)}}</td>							
						</tr>					
					@endforeach
					
					@if($accTranPayment->count() > 0)
						<tr>
							<td colspan="8" class="text-right" style="border-bottom: solid 1px; padding-bottom: 12px; font-weight: 700;"> 	
								{{$invoice}} {{$amount}}: <b style="color:#337ab7;">{{Content::currency()}} {{Content::money($acc_journal->debit)}}</b>, &nbsp;
								Paid {{$amount}}: <b style="color:#337ab7;">{{Content::currency()}} {{Content::money($ptotalAmount)}} </b>, &nbsp;
								Balance: <b style="color:#337ab7;">{{Content::currency()}} {{Content::money($acc_journal->debit - $ptotalAmount)}}</b>
								,&nbsp;&nbsp;&nbsp;&nbsp;
								<!-- kyat currency -->
								{{$invoice}} {{$amount}} {{Content::currency(1)}}: <b style="color:#337ab7;">{{Content::money($acc_journal->kdebit)}}</b>&nbsp;,
								Paid {{$amount}} {{Content::currency(1)}}:<b style="color:#337ab7;"> {{Content::money($ptotalAmountk)}} </b> &nbsp;,
								Balnace {{Content::currency(1)}}: <b style="color:#337ab7;"> {{Content::money($acc_journal->kdebit - $ptotalAmountk )}}</b>
							</td>
						</tr>				
					@endif
				@endforeach			
			@endif

			<tr style="background: #3c8dbc0a;">
				<td colspan="9" class="text-right" style="padding: 0px; font-weight: 700;">
					<div class="text-right" style="padding: 11px;background: #1b648e1f;border: solid 1px #ddd;">
						<?php
							$rcvINVTotal = isset($accTranPayment) ? $accTranPayment->sum("debit"):'';
							$rcvINVTotalk = isset($accTranPayment) ? $accTranPayment->sum("kdebit"):'';
						?>
						Recieved  Total {{Content::currency()}}: <b style="color:#337ab7;">{{ Content::money($totalRP) }}</b>,  &nbsp;&nbsp;
						Paid Total {{Content::currency()}}: <b style="color:#337ab7;">{{ Content::money($rcvINVTotal)}}</b>,  &nbsp;&nbsp;
						Gross Profit {{Content::currency()}}: <b style="color:#337ab7;">{{ Content::money($totalRP - $rcvINVTotal) }}</b>
						<!-- End currency usd -->
						,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Recieved  Total {{Content::currency(1)}}:<b style="color:#337ab7;">{{ Content::money($totalRPk) }}</b>, &nbsp;&nbsp;
						Paid Total {{Content::currency(1)}}: <b style="color:#337ab7;">{{{ Content::money($rcvINVTotalk) or ''}}}</b>, &nbsp;&nbsp;
						Gross Profit {{Content::currency()}}: <b style="color:#337ab7;">{{ Content::money($exKyatToDol) }}</b>
					</div>
				</td>
			</tr>
		@endif
	</table>
</div>
<br><br><br> 
@endsection
