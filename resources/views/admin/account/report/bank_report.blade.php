
@extends('layout.backend')
@section('title', 'Preview Of '. $bank->name)
<?php 
  use App\component\Content;
  $comadd = \App\Company::find(1);
?>

@section('content')
<div class="container">
  @include('admin.report.headerReport') 
    <h4 class="text-left">
        <div class="pull-left"><b>{{{$bank->name or ''}}}</b>, As of {{date('d, F Y')}} </div> 
    </h4>
    <br>
    <form method="GET" action="">
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
    <div class="spacing" style="margin-bottom: 20px;"></div>
    <table class="table">
      @if($bankTransactionPreview->count() > 0 )
        <tr>
          <th width="90px" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Date</b></th>  
          <th style="border-top: none;border-bottom: 1px solid #ddd;">Description</th>
          <th class="text-right" width="110px" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Deposit</b></th>   
          <th class="text-right" width="110px" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Withdraw</b></th>
          <th class="text-right" style="border-top: none;border-bottom: 1px solid #ddd;">Balance {{Content::currency()}}</th>
          <th class="text-right" width="110px" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Deposit {{Content::currency(1)}} </b></th>   
          <th class="text-right" width="110px" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Withdraw {{Content::currency(1)}}</b></th>
          <th class="text-right" style="border-top: none;border-bottom: 1px solid #ddd;" width="100px">Balance {{Content::currency(1)}}</th>
          <th style="border-top: none;border-bottom: 1px solid #ddd;" class="text-right" width="85px">Ex-Rate-Info</th>
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
    <br>
</div>
@include('admin.include.datepicker')
@endsection
