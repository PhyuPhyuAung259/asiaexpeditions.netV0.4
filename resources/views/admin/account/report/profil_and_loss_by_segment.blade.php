@extends('layout.backend')
@section('title', 'Posting Account Management')
<?php 
  $active = 'finance/journal'; 
  $subactive = 'finance/pnlbysegment';
  use App\component\Content;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <form method="POST" action="{{route('searchPnlbysegment')}}">
            {{csrf_field()}}
            <section class="col-lg-12 connectedSortable">
              <h3 class="border" style="text-transform: capitalize;">Profit and loss by segment </h3>
              <div class="col-sm-8 col-xs-12 pull-right" style="position: relative; z-index: 2;">
                <div class="col-md-3 col-xs-5">
                  <input type="hidden" value="{{{$projectNum or ''}}}" id="projectNum">
                  <input class="form-control input-sm" type="text" id="from_date" name="start_date" placeholder="Date From" value="{{{$startDate or ''}}}">
                </div>
                <div class="col-md-3 col-xs-5">
                  <input class="form-control input-sm" type="text" id="to_date" name="end_date" placeholder="Date To" value="{{{$endDate or ''}}}"> 
                </div>
                <div class="col-md-2" style="padding: 0px;">
                  <button class="btn btn-primary btn-sm" type="submit" name="search_type" value="pandl">Search</button>
                </div>    
                <div class="col-md-2" style="padding: 0px;">
                  <button class=" btn btn-primary btn-acc btn-sm" type="submit" name="printBtn" value="print">Print <i class="fa fa-print"></i></button>
                </div>           
              </div>
              <table class="datatable table table-hover table-striped">
                <thead>
                  <tr>                       
                    <th width="60px" style="position: relative; "> 
                      <span style="position: absolute; z-index: 999;"><input style=" width: 14px; height:14px; cursor: pointer;" type="checkbox" id="check_all"> </span>

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProjectNo.</th>
                    <th>ClientName</th>
                    <th style="width: 62.8125px;">Start Date</th>
                    <th style="width: 58.8125px;">End Date</th>
                    <th>Agent</th>
                    <th class="text-right">Revenue</th>
                    <th class="text-right" title="Cost Of Sales ">CS-Amount</th>
                    <th class="text-right" title="Gross Profit">G-Profit</th>
                    <th class="text-right" title="Gross Profit">( % )</th>
                    <th class="text-right" title="Revenue Received">Revenue Amount</th>
                    <th class="text-right" title="Revenue Receivable">Revenue AR</th>
                    <th class="text-right" title="Cost Of Sales Paid">CS-Paid</th>
                    <th class="text-right" title="Cost Of Sales Payable">CS-AP</th>
                    <th class="text-center">Post</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($projects as $project)
                    <?php 
                      $sup = App\Supplier::find($project->supplier_id);
                      $user = App\User::find($project->UserID);
                      $con = App\Country::find($project->country_id);
                      $acc_revenue = App\AccountJournal::where(["project_id"=> $project->project_id, 'status'=> 1, 'type'=>2, 'account_type_id'=> 8])->sum('credit');
                      $acc_Cost_of_sales = App\AccountJournal::where(["project_id"=> $project->project_id, 'status'=> 1, 'type'=>1, 'account_type_id'=> 10])->sum('debit');
                      $acc_tran_credit = App\AccountTransaction::where(["project_id"=> $project->project_id, 'status'=> 1, 'type'=>2, 'account_type_id'=> 10])->sum('credit');
                      $acc_tran_debit = App\AccountTransaction::where(["project_id"=> $project->project_id, 'status'=> 1, 'type'=>1, 'account_type_id'=> 8])->sum('debit');
                    ?>
                  <tr>
                    <td style="position: relative;">
                      <input style="width: 14px height:14px;" type="checkbox" name="checkSegment[]" value="{{$project->project_id}}" class="checkall" > {{$project->project_number}}</td>
                    <td>{{$project->project_client}}</td>
                    <td>{{Content::dateformat($project->project_start) }}</td>
                    <td>{{Content::dateformat($project->project_end)}}</td>
                    <td>{{{ $sup->supplier_name or ''}}}</td>
                    <?php 
                      $grossProfit = $acc_revenue - $acc_Cost_of_sales; 
                      $getPercentage = $acc_revenue > 0 ? ($grossProfit / $acc_revenue) * 100 : 0;
                    ?>
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
                    <td class="text-center">
                      <a target="_blank" href="{{route('previewPosting', ['project'=>$project->project_number, 'type'=>'Posting Account'])}}" title="View to Post">
                        <label style="cursor:pointer;" class="icon-list ic_ops_program"></label>
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </section> 
          </form>
        </div>
    </section>
</div>
@include('admin.include.datepicker')
<script type="text/javascript">
  $(document).ready(function(){
    $(".datatable").DataTable({
      language: {
        searchPlaceholder: "Project/File No. ClientName"
      }
    });
  });
</script>
@endsection
