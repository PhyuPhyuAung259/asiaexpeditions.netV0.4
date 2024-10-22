@extends('layout.backend')
@section('title', 'Expense Report')
<?php
  use App\component\Content;
  $comadd = \App\Company::find(1);
?>
@section('content')
<div class="container">
  @include('admin.report.headerReport') 
  @if(isset($_GET['reportid']))
    <span><a href="{{ URL::previous() }}"><i class="fa fa-arrow-circle-o-left"></i> Back to Aged Report</a></span>  
  @endif
  <div style="text-transform:capitalize; text-align: center;"> <h4>Invoice <br> <b> {{{ $accountName->account_name or ''}}}<br></b> {{date("F-d-Y")}}</b></h4> </div>
  <table class="table">
    <tr style="background-color: #3c8dbc0a;">
      <th style="border-top: none;border-bottom: 1px solid #ddd;"><b>Date</b></th> 
      <th class="text-right" style="border-top: none;border-bottom: 1px solid #ddd;"> Paid {{Content::currency()}}</th>
      <th  class="text-right" style="border-top: none;border-bottom: 1px solid #ddd;"> Paid {{Content::currency(1)}}</th> 
    </tr>
      @if($viewReportID->count() > 0)
        <?php 
            $usdTotal = 0;
            $kyatTotal = 0;
        ?>
        @foreach($viewReportID as $acc_rp)
            <?php 
              $usdbalance = $acc_rp->credit > 0 ? $acc_rp->credit : $acc_rp->debit;
              $kyatbalance = $acc_rp->kcredit > 0 ? $acc_rp->kcredit : $acc_rp->kdebit;
            ?>
          <tr>
            <td style="padding-left: 16px;">
              <a href="javascript::void(0)">{{{ $acc_rp->supplier->supplier_name or '' }}}</a></td>
            <td class="text-right">{{ Content::money($usdbalance) }}</td>
            <td class="text-right">{{ Content::money($kyatbalance) }}</td>
          </tr>
        @endforeach  
        <tr>
          <th style="border-bottom: solid 1px; padding-bottom: 12px; font-weight: 700;">Total</th>
          <th style="border-bottom: solid 1px; padding-bottom: 12px; font-weight: 700;" class="text-right">{{Content::money($usdTotal)}}</th>
          <th style="border-bottom: solid 1px; padding-bottom: 12px; font-weight: 700;" class="text-right">{{Content::money($kyatTotal)}}</th>
        </tr>
     @else 
        <tr><td colspan="10" class="text-center" style="color: #999;"> Result Not Found !</td></tr>
     @endif
  </table>  

</div>
<div class="clearfix"></div>
<br><br><br><br><br><br>
@include('admin.include.datepicker')
@endsection
