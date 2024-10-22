
@extends('layout.backend')
@section('title', 'Preview Of '. $bank->name)
<?php 
  use App\component\Content;
  $comadd = \App\Company::find(1);
?>
@section('content')
<div class="container">
  @include('admin.report.headerReport') 
  <div class="col-md-12">
    <h4 class="text-left">  
        <span style="text-transform: uppercase; font-size:18px;">Total of all Banks,  As of {{date('d, F Y')}}</span>
    </h4>
  </div>
  <div class="clearfix"></div>
<table class="table">
  <tr>
    <td style="border-top: none;">
      <table class="table">
        <tr>
          <th>Bank Name</th>
          <th class="text-right">Balance {{Content::currency()}}</th>
          <th class="text-right">Balance {{Content::currency(1)}}</th>
        </tr>
        <?php 
          $usdTotal = 0;
          $kyatTotal = 0; ?>
          @foreach(App\Bank::where("country_id", 30)->orderBy("country_id", "ASC")->get() as $bk)
            <?php
            $countryBank = \App\Bank::getBankByCountry($bk->id, $bk->country_id);
              $accBalanceFrom = \App\AccountTransaction::where(['bank_id'=> $bk->id, 'status'=> 1]);
              $accBalanceTo = \App\AccountTransaction::where(['bank_to'=> $bk->id, 'status'=> 1]);
              $tdebit = $bk->id ? $accBalanceTo->sum('debit') :'';
              $tcredit = $bk->id ? $accBalanceFrom->sum('credit') :'';
              $tdebitk = $bk->id ? $accBalanceFrom->sum('kdebit') :'';
              $tcreditk = $bk->id ? $accBalanceTo->sum('kcredit') :'';
              $Balance = $accBalanceTo->sum('debit') - $accBalanceFrom->sum('credit');
              $Balancek = $accBalanceTo->sum('kdebit') - $accBalanceFrom->sum('kcredit');
              $usdTotal = $usdTotal + $Balance;
              $kyatTotal = $kyatTotal + $Balancek;
            ?>    
            <tr>
              <td>{{$bk->name}}</td>            
              <td class="text-right"> {!! $Balance >= 0 ? "<b style='color:#3c8dbc'>".Content::money($Balance)."</b>" : "<b style='color:#ee3721;'>". number_format($Balance,2)."</b>" !!}</td>
              <td class="text-right"> {!! $Balancek >= 0 ? "<b style='color:#3c8dbc'>".Content::money($Balancek)."</b>" : "<b style='color:#ee3721;'>". number_format($Balancek,2)."</b>" !!}</td>
            </tr>
           
          @endforeach
          <tr>
            <th colspan="3" class="text-right">Total USD: {!! $usdTotal >= 0 ? "<b style='color:#3c8dbc'>".Content::money($usdTotal)."</b>" : "<b style='color:#ee3721;'>". number_format($usdTotal,2)."</b>" !!},&nbsp; &nbsp; &nbsp;Total MMK:   {!! $kyatTotal >= 0 ? "<b style='color:#3c8dbc'>".Content::money($kyatTotal)."</b>" : "<b style='color:#ee3721;'>". number_format($kyatTotal,2)."</b>" !!}</th>
          </tr>
      </table>
    </td>
    <td style="vertical-align: top; border-top: none;">
      <table class="table">
        <tr>
          <th>Bank Name</th>
          <th class="text-right">Balance {{Content::currency()}}</th>
          <th class="text-right">Balance {{Content::currency(1)}}</th>
        </tr>
        <?php 
          $usdTotal = 0;
          $kyatTotal = 0; ?>
          @foreach(App\Bank::where("country_id", 122)->orderBy("country_id", "ASC")->get() as $bk)
            <?php
            $countryBank = \App\Bank::getBankByCountry($bk->id, $bk->country_id);
              $accBalanceFrom = \App\AccountTransaction::where(['bank_id'=> $bk->id, 'status'=> 1]);
              $accBalanceTo = \App\AccountTransaction::where(['bank_to'=> $bk->id, 'status'=> 1]);
              $tdebit = $bk->id ? $accBalanceTo->sum('debit') :'';
              $tcredit = $bk->id ? $accBalanceFrom->sum('credit') :'';
              $tdebitk = $bk->id ? $accBalanceFrom->sum('kdebit') :'';
              $tcreditk = $bk->id ? $accBalanceTo->sum('kcredit') :'';
              $Balance = $accBalanceTo->sum('debit') - $accBalanceFrom->sum('credit');
              $Balancek = $accBalanceTo->sum('kdebit') - $accBalanceFrom->sum('kcredit');
              $usdTotal = $usdTotal + $Balance;
              $kyatTotal = $kyatTotal + $Balancek;
            ?>    
            <tr>
              <td>{{$bk->name}}</td>            
              <td class="text-right"> {!! $Balance >= 0 ? "<b style='color:#3c8dbc'>".Content::money($Balance)."</b>" : "<b style='color:#ee3721;'>". number_format($Balance,2)."</b>" !!}</td>
              <td class="text-right"> {!! $Balancek >= 0 ? "<b style='color:#3c8dbc'>".Content::money($Balancek)."</b>" : "<b style='color:#ee3721;'>". number_format($Balancek,2)."</b>" !!}</td>
            </tr>         
          @endforeach
          <tr>
            <th colspan="3" class="text-right">Total USD: {!! $usdTotal >= 0 ? "<b style='color:#3c8dbc'>".Content::money($usdTotal)."</b>" : "<b style='color:#ee3721;'>". number_format($usdTotal,2)."</b>" !!},&nbsp;&nbsp;&nbsp;Total MMK:  {!! $kyatTotal >= 0 ? "<b style='color:#3c8dbc'>".Content::money($kyatTotal)."</b>" : "<b style='color:#ee3721;'>". number_format($kyatTotal,2)."</b>" !!}</th>
          </tr>   
      </table>
    </td>
  </tr>
</table>
    <div class="clearfix"></div>
    <h4 class="text-left">
        <div class="pull-left">Individual Bank balance for <b> {{{$bank->name or ''}}}</b>, As of {{date('d, F Y')}} </div> 
    </h4>
    <br><br><br>
    <cener>
      <form method="GET" action="" class="hidden-print">
        <input type="hidden" name="preview_bank" value="{{{$_GET['preview_bank'] or ''}}}">
        <div class="col-md-5">
          <div class="form-group">
            <label><br></label><div class="clearfix"></div>
            <div class="col-md-5">
              <input type="text" name="date_from" class="form-control input-sm" id="from_date" value="{{{$_GET['date_from'] or ''}}}" placeholder="From Date: 2019-09-27">
            </div>
            <div class="col-md-5">
              <input type="text" name="date_to" class="form-control input-sm" id="to_date" value="{{{$_GET['date_to'] or ''}}}" placeholder="To Date: 2019-10-27">
            </div>
            <div class="col-md-2" style="padding-left: 0px;">
              <button type="submit" class="btn btn-primary btn-sm btn-flat" id="btnSearchJournal">Search</button>
            </div>
          </div>
        </div> <div class="clearfix"></div>
      </form>
    </cener>
    <div class="spacing" style="margin-bottom: 20px;"></div>
  <div class="row">
    <table class="table">
      @if($bankTransactionPreview->count() > 0 )
        <tr>
          <th width="150" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Date</b></th>  
          <th style="border-top: none;border-bottom: 1px solid #ddd;">Description</th>
          <th class="text-right" width="110" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Deposit</b></th>   
          <th class="text-right" width="110" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Withdraw</b></th>
          <th class="text-right" style="border-top: none;border-bottom: 1px solid #ddd;">BL {{Content::currency()}}</th>
          <th class="text-right" width="100" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Deposit {{Content::currency(1)}} </b></th>   
          <th class="text-right" width="100" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Withdraw {{Content::currency(1)}}</b></th>
          <th class="text-right" style="border-top: none;border-bottom: 1px solid #ddd;" width="100">BL {{Content::currency(1)}}</th>
          <th style="border-top: none;border-bottom: 1px solid #ddd;" class="text-right" width="90">Ex-Rate-Info</th>
        </tr>
          <?php 
            $FixedBankId = isset($_GET['preview_bank']) ? $_GET['preview_bank']:'';
            $usdBalance = 0;
            $localBalance = 0;
          ?>
          @foreach($bankTransactionPreview as $key => $bn)
            <?php 
              $tdebit = $bn->bank_to == $FixedBankId ? $bn->debit :'';
              $tcredit = $bn->bank_id == $FixedBankId ? $bn->credit :'';
              $tdebitk = $bn->bank_to == $FixedBankId ?  $bn->kdebit :'';
              $tcreditk = $bn->bank_id == $FixedBankId ?  $bn->kcredit :'';

              $usdBalance = $usdBalance + $tdebit - $tcredit;
              $localBalance = $localBalance + $tdebitk - $tcreditk;
            ?>
            <tr>                
              <td>{{ Content::dateformat($bn->pay_date) }}</td>
              <td>{{$bn->remark}}</td>
              <td class="text-right"> {{ Content::money($tdebit) }}</td>
              <td class="text-right"> {{ Content::money($tcredit) }}</td>
              <td class="text-right"> {!! $usdBalance >= 0 ? "<b style='color:#3c8dbc'>".Content::money($usdBalance)."</b>" : "<b style='color:#ee3721;'>". number_format($usdBalance,2)."</b>" !!}</td>
              <td class="text-right"> {{ Content::money($tdebitk) }}</td>
              <td class="text-right"> {{ Content::money($tcreditk) }}</td>
              <td class="text-right"> {!! $localBalance >= 0 ? "<b style='color:#3c8dbc'>".Content::money($localBalance)."</b>" : "<b style='color:#ee3721;'>". number_format($localBalance,2)."</b>" !!}</td>
              <td class="text-right">Ex-{{ $bn->ex_rate }}</td>
            </tr>
          @endforeach
      @endif
    </table>  
  </div>
    <br>
</div>
@include('admin.include.datepicker')
@endsection
