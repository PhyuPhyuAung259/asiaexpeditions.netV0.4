@extends('layout.backend')
@section('title', 'Trial Balance')
<?php 
  $active = 'finance/journal'; 
  $subactive = 'finance/trial-balance ';
  use App\component\Content;
  $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
?>
@section('content')
  <div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">
      <section class="content"> 
        @include('admin.include.message')
          <h3 class="border text-center" style="color:black;text-transform: capitalize;font-weight:bold;">Trial Balance As On {{Content::dateformat($period)}}</h3>
          <div class="container ">
              <div class="col-md-8 col-md-offset-2">
                <div id="search_form" style="border: solid #d2d6de 1px; position: relative; background: #e8f1ff;margin: 12px 0px;border-radius: 3px;" class="alert alert-dismissible fade in" role="alert"> 
                  <br>
                  <form method="GET" action="" class="hidden-print">
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-4 col-xs-6 {{\Auth::user()->role_id != 2 ? 'hidden': ''}}">
                      <div class="form-group">
                        <select class="form-control location" name="country" data-type="sup_by_bus" required="">
                          <option value="">Location</option>
                          @foreach(App\Country::LocalPayment() as $con)
                            <option value="{{$con->id}}" {{{ $conId == $con->id ? 'selected':''}}}> {{ $con->country_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <input type="text" name="date" class="form-control book_date" value="{{{$period or ''}}}" readonly>
                      </div>
                    </div>                   
                    <div class="col-md-1">
                      <div class="form-group">
                        <div class="pull-left">
                          <button type="submit" class="btn btn-primary btn-md" id="btnSearchJournal">Search</button>
                        </div>
                      </div>
                    </div> 
                  </form>
                  <div class="clearfix"></div>
                </div>  
              </div>
              @if(isset($account_type) && $account_type->count() > 0)
                <div style="background-color: white; border-radius: 3px; border: solid 1px #ddd; padding: 25px; ">
                  <table class="table">
                    <tr style="color: #21919f;">
                      <th style="border-top: none;">Account Title</th>
                      <th class="text-right" style="border-top: none;">Debit {{Content::currency()}}</th>
                      <th class="text-right" style="border-top: none;">Credit {{Content::currency()}}</th>
                    </tr>
                    <?php 
                      $getToTalDebit = 0;
                      $getToTalCredit = 0;
                      $getToTalDebitKyat = 0;
                      $getToTalCreditKyat = 0;
                      $TotalCash = 0;
                      $totalAP = 0;
                    ?>
                    @foreach($account_type as $key => $acc)                    
                      <?php 
                        $totalJouran  = \App\AccountJournal::where(['account_type_id'=>$acc->id, 'country_id'=>$conId, 'status'=>1])
                                ->whereDate('entry_date',$period);
                        $totalTransaction = \App\AccountTransaction::where(['account_type_id'=>$acc->id, 'country_id'=> $conId, 'status'=> 1])
                                ->whereDate('pay_date',$period);
                        
                        $totalDebit  = $totalJouran->sum('debit');
                        $totalCredit   = $totalJouran->sum('credit');

                        $totalReceived = $totalDebit - $totalTransaction->sum('debit');
                        $totalPaid = $totalCredit - $totalTransaction->sum('credit');                        

                        $getToTalDebit = $getToTalDebit + $totalDebit + $totalPaid;
                        $getToTalCredit= $getToTalCredit + $totalCredit + $totalReceived;

                      ?>                       
                        <tr>
                          <td><a href="{{route('getTrialBalance', ['account_type'=> $acc->id])}}" target="_blank">{{$acc->account_name}}</a></td>
                          <td class="text-right">
                              {{Content::money($totalDebit)}}
                          </td>
                          <td class="text-right">                            
                              {{Content::money($totalCredit) }}
                          </td>
                        </tr> 
                      @if($acc->id == 10 || $acc->id == 8 || $acc->id == 9)
                        <?php
                         if ($acc->id == 10) {
                          $paytype = "Payable";
                         }elseif ($acc->id == 8 || $acc->id == 9) {
                          $paytype = "Receivable";
                         }
                         ?>
                        <tr>
                          <td>{{$acc->account_name}} - {{$paytype}}</td>                          
                          <td class="text-right">{{Content::money($totalPaid)}}</td>
                          <td class="text-right">{{Content::money($totalReceived)}}</td>
                        </tr>
                      @endif
                    @endforeach
                    <tr style="border-bottom: solid 1px; border-top: solid 2px; ">
                      <th></th>
                      <th class="text-right">{{Content::money($getToTalDebit)}}</th>
                      <th class="text-right">{{Content::money($getToTalCredit)}}</th>
                    </tr>
                  </table>
                </div>
              @else
                <div class="notify-message">
                  <div class="col-md-12">
                    <div class="alert alert-dismissible fade show warning" role="alert" style="position: relative; padding-left: 53px;">
                      <i class="fa fa-warning (alias)" style="font-size: 19px; position:absolute; top: 13px; left: 20px;"></i>
                      <div style="text-transform: capitalize;"><span>Result Not found...!</span></div>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close" style=" position: absolute;right: 7px;top: 13px;">
                        <span aria-hidden="true" style="font-size: 22px;padding: 1px 8px;">×</span>
                      </button>
                    </div>
                  </div>
                </div>
              @endif
          </div>
          <div class="clearfix"></div><br><br>
      </section>
    </div>
  </div>

@include('admin.include.datepicker')
<script type="text/javascript">
  $(document).ready(function(){
    $(".search_option").on("click", function(){
        $("#search_form").show();
    });
    $("#close").on("click", function(){
      $("#search_form").hide();
    });
    $(document).on("click", ".btnEditJournal", function(){
      dataId = $(this).data("id");
      $("#journal_id").val(dataId);
    });
  });
</script>


@include("admin.account.accountant")
@endsection

