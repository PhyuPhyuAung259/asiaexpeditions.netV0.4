@extends('layout.backend')
@section('title', 'Profit & Loss')
<?php
  use App\component\Content;
  $comadd = \App\Company::find(1);
?>
@section('content')
<div class="container">
  @include('admin.report.headerReport') 
  @if(isset($_GET['reportid']))
    <span><a href="{{route('getAccountReport')}}"><i class="fa fa-arrow-circle-o-left"></i> Back to Aged Report</a></span>  
  @endif
  <h3 class="text-left"><div class="pull-left" style="text-transform:capitalize;"> <b>{{Content::dateformat($from_date)}}</b></div></h3>
  <table class="table">
      @if($getAccByTran->count() > 0)
        <?php 
            $usdTotal = 0;
            $kyatTotal = 0;
        ?>
        @foreach($getAccByTran as $acc)
          <tr style="background-color: #3c8dbc0a;">
            <th style="border-top: none;border-bottom: 1px solid #ddd;"><b>{{$acc->type_name}}</b></th> 
            <th style="border-top: none;border-bottom: 1px solid #ddd;"><b>Balance {{Content::currency()}}</b></th> 
            <th style="border-top: none;border-bottom: 1px solid #ddd;"><b>Balance {{Content::currency()}}</b></th> 
          </tr>
          <?php 
            $getAccTransaction = \App\AccountTransaction::where(['account_name_id'=> $acc->nameID])->get();
          ?>
          @if($getAccTransaction->count() > 0)
            @foreach($getAccTransaction as $key => $acc_name)
              <?php 
                $getSubAmount = \App\AccountTransaction::where(['supplier_id'=> $acc_name->supplier_id]);
              ?>
              <tr>
                <td style="padding-left: 16px;">
                  <a href="{{route('getAccountReport', ['reportid' => $acc_name->account_name_id])}}">{{{ $acc_name->account_name->account_name or ''}}}</a></td>
                <td>{{Content::money($getSubAmount->sum('credit'))}}</td>
                <td>{{Content::money($getSubAmount->sum('kcredit'))}}</td>
              </tr>
            @endforeach 
          @endif
        @endforeach   
     @else 
        <tr><td colspan="10" class="text-center" style="color: #999;"> Result Not Found !</td></tr>
     @endif
  </table>
</div>
<div class="clearfix"></div>
<br><br><br><br><br><br>
@include('admin.include.datepicker')
@endsection
