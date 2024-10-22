@extends('layout.backend')
@section('title', 'Profit & Loss')
<?php
  use App\component\Content;
  $comadd = \App\Company::find(1);
?>
@section('content')
<div class="container-fluid">
  @include('admin.report.headerReport') 
  
  <div class="col-md-3 col-xs-12">
    <div class="form-group">
    <a href="{{ URL::previous() }}"><i class="fa fa-arrow-circle-o-left"></i> Back to Aged Report</a></div> 
  </div>
  <div class="col-md-9 col-xs-12">
    <form method="GET" action="" class="hidden-print">      
      <input type="hidden" name="journal" value="{{{$_GET['journal']}}}">
        @if(\Auth::user()->role_id == 2)
          <div class="col-md-4">
            <div class="form-group">
              <select class="form-control input-sm" name="country" data-type="sup_by_bus">
                <option value="">Choose Location</option>
                @foreach(App\Country::LocalPayment() as $con)
                  <option value="{{$con->id}}" {{isset($_GET['country']) && $_GET['country']  == $con->id ? 'selected' : ''  }}>{{$con->country_name}}</option>
                @endforeach
              </select>
            </div>
          </div>
        @endif
        <div class="col-md-3">
          <div class="form-group">
            <input type="text" name="date_from" class="form-control input-sm" id="from_date" value="{{{$from_date or ''}}}" placeholder="From Date: 2019-09-27">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <input type="text" name="date_to" class="form-control input-sm" id="to_date" value="{{{$to_date or ''}}}" placeholder="To Date: 2019-10-27">
          </div>
        </div>
        <div class="col-md-1">
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-sm btn-flat" >Search</button>
          </div>  
        </div>
    </form>
  </div>
  <div class="clearfix"></div>
  <div class="text-center"><h3 style="text-transform:capitalize;">Profit & Loss</h3> <h4>Period: {{Content::dateformat($from_date)}} To {{Content::dateformat($to_date)}}</h4></div>

  <table class="table">
      @if($getJournalReport->count() > 0)
        @foreach($getJournalReport as $key => $acc)
          @if($key == 0 )
            <tr style="background-color: #3c8dbc0a;">
              <th style="border-bottom: solid 1px #607d8b73; border-top: solid 1px #607d8b73;"><b>{{$acc->account_name}}</b></th> 
              <th style="border-bottom: solid 1px #607d8b73; border-top: solid 1px #607d8b73;" class="text-right"><b>Balance {{Content::currency()}}</b></th> 
              <th style="border-bottom: solid 1px #607d8b73; border-top: solid 1px #607d8b73;" class="text-right"><b>Balance {{Content::currency(1)}}</b></th> 
            </tr>
          @else
            <tr style="background-color: #3c8dbc0a;">
              <th colspan="3" style="border-bottom: solid 1px #607d8b73;"><b>{{$acc->account_name}}</b></th>
            </tr>
          @endif
          <?php 
            $getAccTransaction = \App\AccountJournal::where(['account_type_id'=> $acc->account_type_id, 'country_id' => $acc->country_id ])->groupBy('account_name_id')->get();
            $usdTotal = 0;
            $kyatTotal = 0;
          ?>
          @if($getAccTransaction->count() > 0)
          <?php $n = 1; ?>
            @foreach($getAccTransaction as $key => $acc_name)
              <?php 
                $supAmount = \App\AccountJournal::where(['account_name_id'=> $acc_name->account_name_id]);
                $usdbalance = $supAmount->sum('credit') > 0 ? $supAmount->sum('credit') : $supAmount->sum('debit');
                $kyatbalance = $supAmount->sum('kcredit') > 0 ? $supAmount->sum('kcredit') : $supAmount->sum('kdebit');
                $usdTotal = $usdTotal + $usdbalance;
                $kyatTotal = $kyatTotal + $kyatbalance;
              ?>
              <tr>
                <td> 
                  {{$n++}} &nbsp;
                  <a href="{{route('getAccountReport', ['reportid' => $acc_name->account_name_id])}}">{{{ $acc_name->account_name->account_name or ''}}}</a></td>
                <td class="text-right"><a href="javascript::void(0)">{{ Content::money($usdbalance) }}</a></td>
                <td class="text-right"><a href="javascript::void(0)">{{ Content::money($kyatbalance) }}</a></td>
              </tr>
            @endforeach
              <tr>
                <th style="border-bottom: solid 1px #607d8b73;border-top: solid 1px #607d8b73; padding-bottom: 13px;"></th>
                <th class="text-right" style="border-bottom: solid 1px #607d8b73; border-top: solid 1px #607d8b73; padding-bottom: 13px;"><strong>{{ Content::money($usdTotal) }}</strong></th>
                <th class="text-right" style="border-bottom: solid 1px #607d8b73; border-top: solid 1px #607d8b73; padding-bottom: 13px;"><strong>{{ Content::money($kyatTotal) }}</strong></th>
              </tr>
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
