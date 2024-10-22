
@extends('layout.backend')
@section('title', "Project Preview")
<?php 
  use App\component\Content;
  $comadd = \App\Company::find(1);
?>
@section('content')
  @include('admin.report.headerReport') 
  <div class="container">
      <table class=" table table-hover table-striped">
                  <tr>                       
                    <th>Project No.</th>
                    <th>ClientName</th>
                    <th>Start Date -> End Date</th>
                    <th>Agent</th>
                    <th>Revenue</th>
                    <th title="Cost Of Sales ">CS-Amount</th>
                    <th title="Gross Profit">G-Profit</th>
                    <th class="text-right" title="Gross Profit">( % )</th>
                    <th title="Revenue Received">Revenue Amount</th>
                    <th title="Revenue Receivable">Revenue AR</th>
                    <th title="Cost Of Sales Paid">CS-Paid</th>
                    <th title="Cost Of Sales Payable">CS-AP</th>                    
                  </tr>
                <tbody>
                  @if($project_preview->count() > 0)
                    @foreach($project_preview as $project)
                      <?php 
                        $sup = App\Supplier::find($project->supplier_id);
                        $user = App\User::find($project->UserID);
                        $con = App\Country::find($project->country_id);
                        $acc_revenue = App\AccountJournal::where(["project_id"=> $project->id, 'status'=> 1, 'type'=>2, 'account_type_id'=> 8])->sum('credit');
                        $acc_Cost_of_sales = App\AccountJournal::where(["project_id"=> $project->id, 'status'=> 1, 'type'=>1, 'account_type_id'=> 10])->sum('debit');
                        $acc_tran_credit = App\AccountTransaction::where(["project_id"=> $project->id, 'status'=> 1, 'type'=>2, 'account_type_id'=> 10])->sum('credit');
                        $acc_tran_debit = App\AccountTransaction::where(["project_id"=> $project->id, 'status'=> 1, 'type'=>1, 'account_type_id'=> 8])->sum('debit');
                      ?>
                    <tr>
                      <?php 
                      $grossProfit = $acc_revenue - $acc_Cost_of_sales; 
                      $getPercentage = $acc_revenue > 0 ? ($grossProfit / $acc_revenue) * 100 : 0;
                    ?>
                      <td>{{$project->project_number}}</td>
                      <td>{{$project->project_client}}</td>
                      <td>{{Content::dateformat($project->project_start) }} -> {{Content::dateformat($project->project_end)}}</td>
                      <td>{{{ $sup->supplier_name or ''}}}</td>
                      <td class="text-right"><a href="javascript:void(0)">{{Content::money($acc_revenue)}}</a></td>
                      <td class="text-right"><a href="javascript:void(0)">{{Content::money($acc_Cost_of_sales)}}</a></td>
                      <td class="text-right"><a href="javascript:void(0)">{{Content::money($grossProfit)}}</a></td>
                      <td class="text-right"><a href="javascript:void(0)"><b>{{Content::money($getPercentage)}} {{$getPercentage > 0 ? "%": ''}}</b></a></td>
                      <td class="text-right"><a href="javascript:void(0)" >{{Content::money($acc_tran_debit)}}</a></td>
                      <td class="text-right"><a href="javascript:void(0)" >{{Content::money($acc_revenue - $acc_tran_debit)}}</a></td>
                      <td class="text-right"><a href="javascript:void(0)" >{{Content::money($acc_tran_credit)}}</a></td>
                      <td class="text-right"><a href="javascript:void(0)" >
                        {{ Content::money($acc_Cost_of_sales - $acc_tran_credit) }}
                      </a></td>                    
                    </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>

</div>
@include('admin.include.datepicker')
@endsection
