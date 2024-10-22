@extends('layout.backend')
@section('title', 'Trial Balance Sheets')
<?php 
  $active = 'finance/accountPayable/project-booked'; 
  $subactive = 'finance/accountPayable/project-booked';
  use App\component\Content;
  $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
?>
@section('content')
<style type="text/css">
  .table>tbody>tr>th, .table>tbody>tr>td{
    /*border:solid 1px #ddd !important;*/
  }
</style>
  <div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft') 
    <div class="content-wrapper">
      <section class="content"> 
        @include('admin.include.message')
          <h3 class="border text-center" style="color:black;text-transform: capitalize;font-weight:bold;">Trial Balance As On {{Content::dateformat($period)}}</h3>
          <div class="container">       
              <div style="background-color: white; border-radius: 3px; border: solid 1px #ddd; padding: 25px; ">
                <table class="table">
                  <tr style="color: #21919f;">
                    <th style="border-bottom: solid 1px #000000; border-top: none;">{{$acc_name->account_code}} - {{$acc_name->account_name}}</th>
                    <th width="180px" class="text-right" style="border-bottom: solid 1px #000000; border-top: none;">Debit {{Content::currency()}}</th>
                    <th width="180px" class="text-right" style="border-bottom: solid 1px #000000; border-top: none;">Credit {{Content::currency()}}</th>
                    <th width="180px" class="text-right" style="border-bottom: solid 1px #000000; border-top: none;">Debit {{Content::currency(1)}}</th>
                    <th width="180px" class="text-right" style="border-bottom: solid 1px #000000; border-top: none;">Credit {{Content::currency(1)}}</th>
                  </tr>
                    <?php 
                      $getToTalDebit = 0;
                      $getToTalCredit = 0;
                      $getToTalDebitKyat = 0;
                      $getToTalCreditKyat = 0;
                    ?>
                  @foreach($acc_journal as $key => $acc)                    
                     <?php 
                        $accJournalAmount = App\AccountJournal::where(["account_name_id"=>$acc->account_name_id, 'status'=>1]);
                        $getToTalDebit = $getToTalDebit + $acc->debit;
                        $getToTalCredit = $getToTalCredit + $acc->credit;
                        $getToTalDebitKyat = $getToTalDebitKyat + $acc->kdebit;
                        $getToTalCreditKyat = $getToTalCreditKyat + $acc->kcredit;
                      ?>
                    <tr>
                      <td style="display: block;margin-left: 20px;"><a href="{{route('getTrialBalance', ['account_name'=> $acc->id])}}" target="_blank">{{{ $acc->supplier->supplier_name or ''}}}</a></td>

                      <td class="text-right" >{{Content::money($acc->credit)}}</td>
                      <td class="text-right" >{{Content::money($acc->debit)}}</td>
                      <td class="text-right" >{{Content::money($acc->kcredit)}}</td>
                      <td class="text-right" >{{Content::money($acc->kdebit)}}</td>
                    </tr>
                  @endforeach
                  <tr >
                    <th style="border-top: 1px solid #2e2b2b;"></th>
                    <th class="text-right" style="border-top: 1px solid #2e2b2b;">
                      @if($getToTalCredit)
                        Total: {{Content::money($getToTalCredit)}} 
                      @endif
                    </th>
                    <th class="text-right" style="border-top: 1px solid #2e2b2b;">
                    @if($getToTalDebit) 
                        Total: {{Content::money($getToTalDebit)}} 
                      @endif
                    </th>
                    <th class="text-right" style="border-top: 1px solid #2e2b2b;">
                      @if($getToTalCreditKyat)
                        Total: {{Content::money($getToTalCreditKyat)}} {{Content::currency(1)}}
                      @endif
                    </th>
                    <th class="text-right" style="border-top: 1px solid #2e2b2b;">
                      @if($getToTalDebitKyat)
                        Total: {{Content::money($getToTalDebitKyat)}} {{Content::currency(1)}}
                      @endif
                    </th>
                  </tr>
                 
                </table>
              </div>
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

