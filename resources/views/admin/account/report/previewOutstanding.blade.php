@extends('layout.backend')
@section('title', 'OutStanding')
<?php 
  use App\component\Content;
  $comadd = \App\Company::find(1);
?>
@section('content')
  <div class="container-fluid">
      @include('admin.report.headerReport') 
      @if($journals->count() > 0)
      <div class="pull-right">
        <button type="submit" class="btn btn-default btn-acc btn-xs hidden-print" onclick="window.print();"><i class="fa fa-print Print"></i> Print</button>
        <span class="btn btn-default btn-acc btn-xs hidden-print myConvert"><i class="fa fa-print Print"></i> Download</span>
      </div>

      <table class="table tableExcel" border="1">
        <tr style="background-color: #e8f1ff; color: #3c8dbc;">
          <th style="border-top: none;border-bottom: 1px solid #ddd;" width="8px">No.</th>
          <th style="border-top: none;border-bottom: 1px solid #ddd;" width="106px" title="Invoice Paid Date">INV Paid Date</th>
          <th style="border-top: none;border-bottom: 1px solid #ddd;" width="200px">Descriptions</th>
          <th style="border-top: none;border-bottom: 1px solid #ddd;">File/Project</th>
          <th style="border-top: none;border-bottom: 1px solid #ddd;">Client Name</th>            
          <th style="border-top: none;border-bottom: 1px solid #ddd;" class="text-right" title="Amount To Pay/Receive"> Amount To Pay</th> 
          <th style="border-top: none;border-bottom: 1px solid #ddd;" class="text-right">Deposit/Paid </th>
          <th style="border-top: none;border-bottom: 1px solid #ddd;" class="text-right" title="Balance to Pay/Receive">Balance To AP/RP</th>
          
          <th style="border-top: none;border-bottom: 1px solid #ddd;" class="text-right" title="Amount To Pay {{Content::currency(1)}}">To Pay {{Content::currency(1)}}</th> 
          <th style="border-top: none;border-bottom: 1px solid #ddd;" class="text-right">Deposit/Paid {{Content::currency(1)}}</th>
          <th style="border-top: none;border-bottom: 1px solid #ddd;" class="text-right" title="Balance to Pay/Receive">Balance To AP/RP {{Content::currency(1)}}</th>

          <th style="border-top: none;border-bottom: 1px solid #ddd;" class="text-right">Paid From / To</th>
        </tr>
          <?php
            $totalBalance = 0;
            $totalKBalanceKyat = 0;
            $n=0;
          ?>
        <tbody>
          
            @foreach($journals as $jn)  
              <?php
                // $jnlTransaction = App\AccountTransaction::where(['journal_id'=>$sup->id, 'status'=>1])->whereNotIn('id',$tranCheck)->get();
                $jnlTransaction = App\AccountTransaction::where(['journal_id'=>$jn->id, 'status'=>1, 'supplier_id'=> $jn->supplier_id])->whereIn('id',[$tranCheck])->get();
              ?>

              @if($jnlTransaction->count() > 0)
                @foreach($jnlTransaction as $key => $tran)
                  <?php $sub = App\Supplier::find($tran->supplier_book); 
                   $n++;
                   $DepositAmt = ($tran->credit > 0 ? $tran->credit : $tran->debit);
                   $DepositAmtKyat = ($tran->kcredit > 0 ? $tran->kcredit : $tran->kdebit);
                   $totalBalance = $totalBalance + ($tran->total_amount - $DepositAmt);
                   $totalKBalanceKyat = $totalKBalanceKyat + ($tran->total_kamount - $DepositAmtKyat);
                  ?>
                  <tr>
                    <td class="text-center">{{$n}}</td>
                    <td class="text-left">{{Content::dateformat($tran->invoice_pay_date)}}</td>
                    <td>{{ $tran->remark }}</td>
                    <td class="text-left">
                      @if($tran->project)
                        <a target="_blank" href="{{route('getJournalReport', ['journal_entry'=>$tran->project['project_number']])}}">{{ $tran->project['project_prefix'] }}-{{ $tran->project['project_fileno'] }}</a>
                      @endif
                    </td>
                    <td class="text-left">{{$tran->project['project_client']}}</td>
                    <td class="text-right">{{ Content::money($tran->total_amount) }}</td>                
                    <td class="text-right">
                      @if($tran->type == 1)
                        <span style="color:red">{{$tran->credit < 0 ? number_format($tran->credit,2) :''}}</span>
                      @else 
                        <span style="color: #10e719;font-weight: 700;">{{number_format($tran->debit,2)}}</span>
                      @endif
                    </td>
                    <td class="text-right">{{ number_format(($tran->total_amount - $DepositAmt),2) }}</td>
                    <td class="text-right">{{ Content::money($tran->total_kamount) }}</td>                
                    <td class="text-right" style="color:#3c8dbc; font-weight: 700">{{Content::money($DepositAmtKyat)}}</td>
                    <td class="text-right">{{ number_format(($tran->total_kamount - $DepositAmtKyat), 2) }}</td>
                    <td class="text-right">{{$sub['supplier_name']}}</td> 
                  </tr>
                @endforeach
              @else
                <?php $totalBalance = $totalBalance + $jn->book_amount;
                  $n++;
                   ?>
                  <tr>
                    <td>{{$n}} </td>
                    <td class="text-left">{{Content::dateformat($jn->entry_date)}}</td>
                    <td>{{ $jn->remark }}</td>
                    <td class="text-left">
                      @if($jn->project)
                        <a target="_blank" href="{{route('getJournalReport', ['journal_entry'=>$jn->project['project_number']])}}">{{ $jn->project['project_prefix'] }}-{{ $jn->project['project_fileno'] }}</a>
                      @endif
                    </td>
                    <td class="text-left">{{$jn->project['project_client']}}</td>
                    <td class="text-right">{{Content::money($jn->book_amount)}}</td>                
                    <td class="text-right" ></td>
                    <td class="text-right" style="color:#3c8dbc; font-weight: 700">{{Content::money($jn->book_amount)}}</td>

                    <td class="text-right">{{Content::money($jn->book_kamount)}}</td>                
                    <td class="text-right" style="color:#3c8dbc;"></td>
                    <td class="text-right">{{Content::money($jn->book_kamount)}}</td>
                    <td class="text-right"></td> 
                  </tr>
              @endif
            @endforeach
        </tbody>
        <tfoot>
          <tr style="background-color: #e8f1ff; font-weight: 700;">
            <th colspan="7" class="text-right">Sub Total: </th> 
            <th class="text-right">{{number_format($totalBalance,2)}}</th>
            <th class="text-right" colspan="3">{{number_format($totalKBalanceKyat,2)}}</th>
            <th class="text-right"></th>
          </tr>
        </tfoot>
      </table>  
      @endif
  </div>

  <script type="text/javascript" src="{{asset('js/jquery.table2excel.min.js')}}"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      $(".myConvert").click(function(){
          if(confirm('Do you to export in excel?')){
            $(".tableExcel").table2excel({
              exclude: ".noExl",
              name: "Daily Cash",
              filename: "OutStanding of ",
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
