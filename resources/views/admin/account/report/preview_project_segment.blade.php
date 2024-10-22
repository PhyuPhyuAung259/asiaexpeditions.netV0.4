
@extends('layout.backend')
@section('title', 'Cash Book ')
<?php 
  use App\component\Content;
  $comadd = \App\Company::find(1);
?>

@section('content')
<div class="col-md-12">
  <div class="col-md-12">
  @include('admin.report.headerReport') 
  <p class="pull-left" style="text-transform: capitalize;"><a href="{{Route('getCashBook')}}"><i class="fa fa-arrow-circle-o-left"></i> Back to Aged Report</a></p> 
  </div>
  <div class="clearfix"></div>
  <div class="col-md-3">
    <div class="col-md-12">
      <h4 ><strong  style=" text-transform:capitalize;">Daily Cash On <b></b></strong></h4>
    </div>
  </div>

  <br>
    <table class="table table-hover table-striped">
      <tr>                       
        <th width="60px" style="position: relative;">ProjectNo.</th>
        <th style="width: 218px;">ClientName</th>
        <th style="width: 62.8125px;">Date Start -> End</th>
        <th>Agent</th>
        <th>Revenue</th>
        <th title="Cost Of Sales ">CS-Amount</th>
        <th title="Gross Profit">G-Profit</th>
        <th title="Revenue Received">Revenue Amount</th>
        <th title="Revenue Receivable">Revenue AR</th>
        <th title="Cost Of Sales Paid">CS-Paid</th>
        <th title="Cost Of Sales Payable">CS-AP</th>
        <th class="text-center">Post</th>
      </tr>
      <tbody>
        @foreach($previewSegments as $project)
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
          <td style="position: relative;">{{$project->project_number}}</td>
          <td>{{$project->project_client}}</td>
          <td>{{Content::dateformat($project->project_start) }} -> {{{ $sup->supplier_name or ''}}}</td>
          <td class="text-right"><a href="javascript:void(0)">{{Content::money($acc_revenue)}}</a></td>
          <td class="text-right"><a href="javascript:void(0)">{{Content::money($acc_Cost_of_sales)}}</a></td>
          <td class="text-right"><a href="javascript:void(0)">{{Content::money($acc_revenue - $acc_Cost_of_sales)}}</a></td>
          <td class="text-right"><a href="javascript:void(0)">{{Content::money($acc_tran_debit)}}</a></td>
          <td class="text-right"><a href="javascript:void(0)">{{Content::money($acc_revenue - $acc_tran_debit)}}</a></td>
          <td class="text-right"><a href="javascript:void(0)">{{Content::money($acc_tran_credit)}}</a></td>
          <td class="text-right"><a href="javascript:void(0)"> {{ Content::money($acc_Cost_of_sales - $acc_tran_credit) }}</a></td>                    
          <td class="text-center">
            <a target="_blank" href="{{route('previewPosting', ['project'=>$project->project_number, 'type'=>'Posting Account'])}}" title="View to Post">
              <label style="cursor:pointer;" class="icon-list ic_ops_program"></label>
            </a>
        </tr>
        @endforeach
      </tbody>
    </table>
</div>
<div class="clearfix"></div>
<br><br><br>

<script type="text/javascript">
  $(document).ready(function(){
    $(".myConvert").click(function(){
        if(confirm('Do you to export in excel?')){
          $(".tableExcel").table2excel({
            exclude: ".noExl",
            name: "Daily Cash On ",
            filename: "Daily Cash On ",
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
@include("admin.account.accountant")
@endsection
