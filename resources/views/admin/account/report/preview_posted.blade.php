
<?php 
  use App\component\Content;
  $comadd = \App\Company::find(1);
  $page_title = "SUPPLIER BY SELECTED ON ".date('Y-m-d H:m A');
  $user_role =  Auth::user()->role_id;
?>

@extends('layout.backend')
@section('title', $page_title)

@section('content')
<style type="text/css"> 
  .no_of_row:hover span{
      display: none;
  }
  .no_of_row:hover a{
    display: block !important;
  }
</style>
<div class="container-fluid">
  <div class="col-md-12">
    @include('admin.report.headerReport') 
  </div>
  <div class="row">
    @include("admin.include.message")
    <div class="col-md-12 text-center">
      <div class="row"><h4><strong style="border-bottom: solid 2px #ddd;">{{$page_title}}</strong></h4></div>
    </div>
  </div> 

  <div class="text-right" style="margin-bottom: 8px;">
    @if($preview_posted->count() > 0 )
      <a href="javascript:void(0)" class="myConvert hidden-print"><span class=" btn btn-primary btn-acc btn-xs"><i class="fa fa-download"></i> Download In Excel</span></a>&nbsp;
      <span onclick="window.print()" class="hidden-print btn btn-xs btn-primary btn-acc"><i class="fa fa-print"></i> Print</span>
    @endif
  </div>
  <table class="tableExcel table" border="1">
      <?php $n=0;?>
      @if($preview_posted->count() > 0 )
        <tr>
          @if(isset($view_type) && $view_type == 'project-booked')
            <th><a href="#">File No.</a></th>
          @endif
          <th><a href="javascript:void(1)">Date</a></th>
          <th><a href="#">Supplier</a></th>
          <th>Commenced</th>
          <th><a href="#">Account Type - Account Name</a></th>
          <th class="text-right"><a href="#" style="color: #f39c12; font-style: italic;">EST-Receive</a></th>
          <th class="text-right"><a href="#" style="color: #f39c12; font-style: italic;">EST-Cost</a></th>
          <th class="text-right" ><a href="#">To Receive</a></th>
          <th class="text-right"><a href="#">To Pay</a></th>
          <th class="text-right"><a href="#">To Receive {{Content::currency(1)}}</a></th>
          <th class="text-right"><a href="#">To Pay {{Content::currency(1)}}</a></th>
          <th class="text-right"><a href="#">Deposit</a></th>
          <th class="text-right">Paid/Received</th>
        </tr>
        <tbody>
             <?php 
                $getReceiveBalance = 0;
                $getPaymentBalance = 0;
                $getReceiveBalanceKyat = 0;
                $getPaymentBalanceKyat = 0;
              ?>
              @foreach($preview_posted as $key => $acc)
                 <?php
                    $AccTrantotal = App\AccountTransaction::where(["journal_id"=>$acc->id, "status"=>1]); 
                    $depositAmount=$AccTrantotal->sum("credit") > 0 ? $AccTrantotal->sum("credit"): $AccTrantotal->sum("debit");
                    $depositAmountk = $AccTrantotal->sum("kcredit") > 0 ? $AccTrantotal->sum("kcredit"): $AccTrantotal->sum("kdebit");

                    if ($depositAmount > 0) {
                      $depositTotal = $depositAmount > 0 ? Content::money($depositAmount) : '';
                    }else{
                      $depositTotal = $depositAmountk > 0 ? Content::money($depositAmountk) : '';
                    }

                    $ReceiveAmount = ($acc->credit - $depositAmount);
                    $amountToPay = ($acc->debit - $depositAmount); 
                    if ($depositAmountk > 0 && $depositAmountk > 0 && !empty($acc->kcredit)) {
                        $ReceiveAmountKyat = ($acc->kcredit - $depositAmountk);
                    }else{
                      $ReceiveAmountKyat = $acc->kcredit;
                    }

                    if (isset($depositAmountk) && $depositAmountk > 0 && !empty($acc->kdebit)) {
                      $amountToPayKyat = ($acc->kdebit - $depositAmountk);
                    }else{
                      $amountToPayKyat = $acc->kdebit;
                    }
                    $getReceiveBalance = $getReceiveBalance + $ReceiveAmount;
                    $getPaymentBalance = $getPaymentBalance + $amountToPay;
                    $getReceiveBalanceKyat = $getReceiveBalanceKyat + $ReceiveAmountKyat;

                    $hBook = App\HotelBooked::find($acc->book_id);
                    $fBook = App\Booking::find($acc->book_id); 
                    // $Book = App\Booking::find($acc->book_id);
                    $crBook= App\CruiseBooked::find($acc->book_id);
                    $tBook = App\Booking::find($acc->book_id); 
                    $gBook = App\Booking::find($acc->book_id); 
                    $rBook = App\BookRestaurant::find($acc->book_id);
                    $enBook= App\BookEntrance::find($acc->book_id);
                    $miBook= App\BookMisc::find($acc->book_id);
                    
                  ?>
                 <tr>                              
                      @if(isset($view_type) && $view_type == 'project-booked')
                        <td>{{{$acc->project->project_prefix or ''}}} - {{$acc->project_fileNo}}</td>
                      @endif
                      <td style="width: 92px;">{{Content::dateformat($acc->entry_date)}}</td>
                      <td>{{{ $acc->supplier->supplier_name or ''}}}</td>
                      
                      <td>
                        @if($acc->business_id == 1)
                          {{Content::dateformat($hBook['checkin'])}} -> {{Content::dateformat($hBook['checkout'])}}<!-- hotel checkin ->checkout -->
                        @elseif ($acc->business_id == 4)
                          {{Content::dateformat($fBook['book_checkin'])}}  <!-- Flight  checkin date-->
                        @elseif ($acc->business_id == 3)
                          {{ $crBook['checkin'] ? Content::dateformat($crBook['checkin']) : ''}} -> 
                          {{ $crBook['checkout'] ? Content::dateformat($crBook['checkout']) : ''}}
                        @elseif ($acc->business_id == 6)
                          {{Content::dateformat($tBook['book_checkin'])}}
                        @elseif ($acc->business_id == 29)
                          {{ $gBook['book_checkin'] ? Content::dateformat($gBook['book_checkin']) : ''}}
                        @elseif ($acc->business_id == 9)
                          {{ isset($acc->project->project_start) ? Content::dateformat($acc->project->project_start) : ''}} ->
                          {{ isset($acc->project->project_end) ? Content::dateformat($acc->project->project_end) : ''}}
                        @endif

                      </td>
                      <td><a href="#">{{{ $acc->account_type->account_name or ''}}} - {{{ $acc->account_name->account_code or ''}}} - {{{ $acc->account_name->account_name or ''}}}</a></td>
                      <td class="text-right" style="color: #f39c12; font-style: italic;">
                         {{ $acc->credit ? Content::money($acc->credit) :  Content::money($acc->kdebit) }}  
                      </td>
                      <td class="text-right" style="color: #f39c12; font-style: italic;">
                        {{ $acc->debit ? Content::money($acc->debit) : Content::money($acc->kdebit) }}
                      </td>                        
                      <td class="text-right">
                          @if( $ReceiveAmount > 0)  
                            {{Content::money($ReceiveAmount)}}  
                          @else  
                            {!! "<a href='javascript:void(0)' style='font-weight: 700;'>".Content::money($acc->credit)."</a>" !!} 
                          @endif
                      </td>
                      <td class="text-right">
                          @if( $amountToPay > 0)  
                            {{Content::money($amountToPay)}}  
                          @else  
                            {!! "<a href='javascript:void(0)' style='font-weight: 700;'>".Content::money($acc->debit)."</a>" !!} 
                          @endif
                      </td>
                      <td class="text-right">
                          @if( $ReceiveAmountKyat > 0)  
                            {{Content::money($ReceiveAmountKyat)}}  
                          @else  
                            {!! "<a href='javascript:void(0)' style='font-weight: 700;'>".Content::money($acc->kcredit)."</a>" !!} 
                          @endif
                      </td>
                      <td class="text-right">
                          @if( $amountToPayKyat > 0)  
                            {{Content::money($amountToPayKyat)}}  
                          @else  
                            {!! "<a href='javascript:void(0)' style='font-weight: 700;'>".Content::money($acc->kdebit)."</a>" !!} 
                          @endif
                      </td>
                      <td class="text-right">
                        <a href="javascript:void(0)">{{$depositTotal}}</a></td>
                      <td class="text-right">
                        @if($depositAmount < $acc->credit || $depositAmountk < $acc->kcredit)
                          <a target="_blank" href="{{route('getPayable', ['journal_id'=> $acc->id])}}" class="btn btn-default btn-acc btn-xs pull-right hidden-print"> <b>Receive Now</b></a>
                        @elseif($depositAmount < $acc->debit || $depositAmountk < $acc->kdebit)
                          <a target="_blank" href="{{route('getPayable', ['journal_id'=> $acc->id])}}" class="btn btn-default btn-acc btn-xs pull-right hidden-print"><b>Pay Now</b></a>
                        @else
                          <span class="badge badge-light">{{$acc->type == 2 ? "Full Received" : "Full Paid" }}</span>
                        @endif
                      </td>
                    </tr>
            @endforeach
        </tbody>
      @else
        <tr>
          <td colspan="12" class="text-center">Result not found</td>
        </tr>
      @endif
  </table>  
</div>
<div class="clearfix"></div>
<br><br><br>
<script type="text/javascript" src="{{asset('js/jquery.table2excel.min.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function(){
      $(".myConvert").click(function(){
          if(confirm('Do you to export in excel?')){
            $(".tableExcel").table2excel({
              exclude: ".noExl",
              name: "{{$page_title}}",
              filename: "{{$page_title}}",
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

@include("admin.account.accountant")
@endsection
