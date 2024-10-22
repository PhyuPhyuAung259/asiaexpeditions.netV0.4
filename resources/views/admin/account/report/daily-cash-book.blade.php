
@extends('layout.backend')
@section('title', 'Daily Cash Book for '. $country['country_name'])
<?php 
  use App\component\Content;
  $comadd = \App\Company::find(1);
?>

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
      <div class="row">
        <h4><b>{{{ $supplier->supplier_name or ''}}}</b> As Of <strong>{{Content::dateformat($start_date) }}</strong> to <strong>{{ Content::dateformat($end_date) }}</strong></h4>  
      </div>
    </div>

    <div class="col-md-8 col-md-offset-2 hidden-print text-center" style="background-color: #e8f1ff;border: 1px solid #c1c1c1;padding: 18px;margin-bottom: 12px;border-radius: 5px;">
      <form method="GET" action="">      
        @if(\Auth::user()->role_id == 2)
          <div class="col-md-3">
            <select class="form-control country_book_supplier " name="country" data-type="supplier_bank_booked">
              <option value="">Location</option>
              <?php $getCountry = App\Country::LocalPayment(); ?>
              @if($getCountry->count() > 0 )
                @foreach($getCountry as $con)
                  <option value="{{$con->id}}" {{$country['id']  == $con->id ? 'selected' : ''  }}>{{$con->country_name}}</option>
                @endforeach
              @endif
            </select>
          </div> 
        @endif
        <div class="col-md-3">
            <select class="form-control text-center" name="sub_book" id="supplier_book">
              @if (Auth::user()->role_id == 8 ) 
                <?php $getCountry = App\AccountTransaction::getAccBookByCitySupplierBook(\Auth::user()->province_id)->groupBy('tran.supplier_id')->get();  ?>
              @else
                <?php $getCountry = App\AccountTransaction::getAccBookByCity($country['id'])->groupBy('tran.supplier_id')->get(); ?>
              @endif
              <?php
            //   $supId = $supplier['id'] > 0 ? $supplier->id : 1469; 
            $supId = $supplier && $supplier->id > 0 ? $supplier->id : 1469;

              ?>
              @if($getCountry->count() > 0 )
                <option value="">--select--</option>
                @foreach($getCountry as $con)
                  <option value="{{$con->supplier_id}}" {{ $supId == $con->id ? 'selected' : ''}}>{{$con->supplier_name}}</option>
                @endforeach
              @else
              <option value="0">--No Supplier--</option>
              @endif
            </select>
        </div>
        <div class="col-md-6" style="padding: 0px;">
          <div class="input-group">
              <input type="text" name="start_date" class="form-control text-center" id="from_date" value="{{{$start_date or ''}}}" readonly>
              <span class="input-group-addon" id="basic-addon3">From To </span>
              <input type="text" name="end_date" class="form-control text-center" id="to_date" value="{{{$end_date or ''}}}" readonly>
          </div>
        </div>
        
        <div class="col-md-12 text-center"><br>
          <button type="submit" class="btn btn-primary btn-sm btn-flat">Search</button>
        </div>            
      </form>
    </div>
  </div> 
  @if($accTransaction->count() > 0 )
    <div class="col-md-12">
      <div class="text-right hidden-print" style="margin-bottom: 8px;">
          <a href="javascript:void(0)" class="myConvert hidden-print"><span class=" btn btn-primary btn-acc btn-xs"><i class="fa fa-download"></i> Download In Excel</span></a>&nbsp;
          <span onclick="window.print()" class="hidden-print btn btn-xs btn-primary btn-acc"><i class="fa fa-print"></i> Print</span>
      </div>
    </div>
  @endif
  
  <table class="tableExcel table" border="1">
      <?php $n = 0; ?>
      @if($accTransaction->count() > 0 )
        <tr style="background-color: #3c8dbc0a; font-size: 11px;" >
        <th class="text-center" style="width: 4%;">No</th>
          <th style="border-bottom: 1px solid #ddd; width: 6.5%;"><b>Date </b></th>  
          <th class="text-left" style="border-bottom: 1px solid #ddd;">Descriptions</th>    
          <th style="border-bottom: 1px solid #ddd; width: 8%;"><b>INV-Number</b></th>          
          <th style="border-bottom: 1px solid #ddd; width: 9%;"><b>Cashbook/Bank</b></th>          
          <th style="border-bottom: 1px solid #ddd; width: 7%;"><b>P/R Voucher</b></th>  
          <th class="text-right" style="border-bottom: 1px solid #ddd; width: 7%;">RCVD Amount</th>
          <th class="text-right" style="border-bottom:1px solid #ddd; width: 7%;">Paid Amount</th>
          <th class="text-right" style="border-bottom:1px solid #ddd; width: 7%;">Balance {{Content::currency()}}</th>
          <th class="text-right" style="border-bottom:1px solid #ddd; width: 9%;">RCVD Amount {{Content::currency(1)}}</th>
          <th class="text-right" style="border-bottom:1px solid #ddd; width: 8%;">Paid Amount {{Content::currency(1)}}</th>
          <th class="text-right" style="border-bottom:1px solid #ddd; width: 7%;">Balance {{Content::currency(1)}}</th>
          
          <th style="border-bottom: 1px solid #ddd; width: 9%;"><b>From/To Supplier</b></th>
          @if($cashBookedproject->count() > 0)
          <th style="border-bottom: 1px solid #ddd; width: 4%;" class="text-right"><b>File No. </b></th>  
          @endif
          <th style="border-bottom: 1px solid #ddd; width: 7%;" class="text-right"><b>Client Name </b></th>  
        </tr>

        <?php
          $getBalance = ((int)$AmountOpenBalance->sum('debit') - (int)$AmountOpenBalance->sum('credit'));
          $getBalanceKyat = ((int)$AmountOpenBalance->sum('kdebit') - (int)$AmountOpenBalance->sum('kcredit'));
        ?>
        <tbody>
        @foreach($accTransaction as $key=> $acc)
          <?php
            $getBalance = ($getBalance + ((int)$acc['debit'] - (int)$acc['credit']));
            $getBalanceKyat = ($getBalanceKyat + ((int)$acc['kdebit'] - (int)$acc['kcredit']));
            $n++;
            $fsup   = App\Supplier::find($acc->supplier_id);
            $tsup   = App\Supplier::find($acc->supplier_book); 
            $project = App\Project::find($acc->project_id);
            $highlight = 'style=background:#ff98004f';
          ?>

          <tr>
              <td class="text-center no_of_row" style="padding: 2px">
                <span style="font-size: 14px;">{{$n}}</span>
                @if(Auth::user()->role_id == 2 || Auth::user()->id == $acc->user_id)
                  <a style="display:none;" class="btnRemoveOption pull-left" data-type="acc_cash_book" data-id="{{$acc->entry_code}}" title="Remove this ?">
                    <label class="icon-list ic_remove" style="width: 15px;height: 15px;"></label> 
                  </a>
                  <a class="pull-left" style="display: none;">&nbsp;|&nbsp;</a> 
                @endif

                @if(Auth::user()->id == $acc->user_id || Auth::user()->role_id == 2)
                  <a title="Preview & Edit" href="#" data-pv_voucher="{{$acc->payment_voucher}}" data-inv_number="{{$acc->invoice_number}}"  data-detail="{{$acc->remark}}" data-inv_rcv_date="{{date('Y-m-d', strtotime($acc->invoice_rc_date_from_sup))}}" data-inv_paid_date="{{date('Y-m-d', strtotime($acc->invoice_pay_date))}}" data-acc_name_title="{{{$acc->account_name->account_name or ''}}}" data-acc_type="{{{$acc->account_type->id or ''}}}" data-acc_name="{{{$acc->account_name->id or ''}}}" data-type="account_name" class="{{Auth::user()->id == $acc->user_id ? '' : 'pull-left'}} btnEditJournal" style="display: none;" data-id="{{$acc->entry_code}}" data-exchange="{{$acc->ex_rate}}" data-kamount="{{ $acc->kdebit > 0 ? $acc->kdebit : $acc->kcredit }}"  data-toggle="modal" data-target="#myModal">
                    Edit</a>
                @endif
              </td>
              <td>{{Content::dateformat($acc->invoice_pay_date)}} </td>
              <td {{ strlen($acc->remark) > 12 ? "style=font-size:10px" : '' }}> {{ $acc->remark }}</td>
              <td>{{{ $acc->invoice_number or '' }}}</td>                            
              <td><span {{$supplier['id'] == $fsup['id'] ? $highlight : ''}}> {{$fsup['supplier_name'] }}</span></td>

              <td>{{{ $acc->payment_voucher or ''}}}</td>          
              <td class="text-right">{{ Content::money($acc->debit) }} </td>
              <td class="text-right">{{ Content::money($acc->credit) }}</td>
              <td class="text-right" style="color:{{$getBalance <= 0 ? 'red' : '#72afd2'}}">{{ number_format($getBalance, 2)}}</td>
              <td class="text-right">{{ Content::money($acc->kdebit) }}</td>
              <td class="text-right">{{ Content::money($acc->kcredit) }}</td>
              <td class="text-right" style="color:{{$getBalanceKyat <= 0 ? 'red' : '#72afd2'}}">{{ number_format($getBalanceKyat, 2) }}</td>
            
            <td>
                <span {{ isset($supplier['id'], $tsup['id']) && $supplier['id'] == $tsup['id'] ? $highlight : '' }}>
                    {{ $tsup['supplier_name'] ?? '' }}
                </span>
            </td>
            @if($cashBookedproject->count() > 0)
            <td class="text-right"> 
                {{ ($project['project_prefix'] ?? '') . "-" . ($project['project_fileno'] ?? '') }} 
            </td>
            @endif
              <td class="text-left">
                @if(isset($project->project_client))
                  {{$project['project_client']}}
                @elseif(empty($acc->supplier_book))
                    Openning Balance
                @else
                    Office Supply
                @endif
              </td>
          </tr>
        @endforeach
      </tbody>

        <tr style="background: #1b648e1f;">
          <th colspan="{{$cashBookedproject->count() > 0 ? 9 : 8 }}" class="text-right">
            Sub Total: 
            <a href="javascript:void(0)" {{$getBalance <= 0 ? 'style=color:red' : ''}}> {{number_format($getBalance, 2)}} {{Content::currency()}}</a>
          </th>
          <th colspan="3" class="text-right">
            <a href="javascript:void(0)" {{$getBalanceKyat <= 0 ? 'style=color:red' : ''}}> {{number_format($getBalanceKyat, 2)}} {{Content::currency()}}</a>
          </th>
          <th style="border-left: none;"></th>
          <th style="border-left: none;"></th>
          <th style="border-left: none;"></th>
        </tr>
      @else
        <tr>
          <td colspan="12" class="text-center">Result not found</td>
        </tr>
      @endif
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
              name: "Daily Cash",
              filename: "Daily Cash Between {{Content::dateformat($start_date)}} And {{Content::dateformat($end_date)}} ",
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
<div class="modal" id="myModal" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-md">    
    <form method="POST" action="{{route('udpateExchangeRate')}}">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Update Payment Form</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}   
          <input type="hidden" name="tran_entry_code" id="transaction_id">
          <input type="hidden" name="kamount" id="kamount">
            <div class="row">
             <!--  @if(\Auth::user()->role_id == 2)
                <div class="col-md-4 col-xs-12">
                  <div class="form-group">
                      <label>Country</label>
                      <select class="form-control location" name="country" data-type="country">
                        <option value="">--choose--</option>                          
                        @foreach(App\Country::LocalPayment() as $con)
                        <option value="{{$con->id}}">{{$con->country_name}}</option>
                        @endforeach()
                      </select>
                  </div>
                </div>
              @endif -->
              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                    <label>Exchange Rate</label>
                    <input type="text" placeholder="00.00 " name="ex_rate" id="ex_rate" class="form-control number_only text-center">
                </div>
              </div>
              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                    <label>Payment Voucher</label>
                    <input type="text" placeholder="#A00333" name="payment_voucher" id="payment_voucher"  class="form-control text-center">
                </div>
              </div>
              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                    <label>Invoice Number</label>
                    <input type="text" placeholder="#A00333" name="invoice_number" id="invoice_number"  class="form-control text-center">
                </div>
              </div>
              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                  <label title="Please enter actual pay date">INV Paid Date </label>
                  <input type="text" placeholder="2019-06-27" name="invoice_pay_date" id="invoice_pay_date" class="form-control book_date"  required="" readonly="">
                </div>
              </div>
            
              <div class="col-md-4 col-xs-6">
                  <div class="form-group">
                    <label>INV-RCVD Date</label>
                    <input type="text" name="invoice_from_sup" id="invoice_from_sup" class="form-control book_date" value="{{date('Y-m-d')}}" placeholder="2019-06-27" required="" readonly="">
                  </div>
              </div>
            </div>
<!-- 
            <table class="table">
                <tr>
                  <td style="width: 31%; padding-left: 0px">
                    <select class="form-control account_type " name="account_type" data-type="account_name" required="">
                      <option value="0">Choose Account Types</option>
                      @foreach(App\AccountType::where('status', 1)->get() as $key=> $acc)
                        <option value="{{$acc->id}}">{{$acc->account_name}}</option>
                      @endforeach
                    </select>
                  </td>
                  <td style="position: relative; width: 70%; padding-right: 0px;">
                    <div class="col-md-12" style="padding-right: 0px;">
                      <div class="btn-group" style='display: block;'>
                        <button type="button" class="form-control arrow-down" data-toggle="dropdown" aria-haspopup="false" aria-expanded='false' data-backdrop="static" data-keyboard="false" role="dialog" data-backdrop="static" data-keyboard="false">
                          <span class="pull-left"></span>
                          <span class="pull-right"></span>
                        </button> 
                        <div class="obs-wrapper-search" style="max-height:250px; overflow: auto; ">
                          <div>
                            <input type="text" data-url="{{route('getFilter')}}" id="search_Account" onkeyup="filterAccountName()" class="form-control input-sm">
                          </div>
                          <ul id="myAccountName" class="list-unstyled dropdown_account_name">
                          </ul>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
            </table> -->  
            <div class="row">
             <div class="col-md-12 col-xs-12">
                  <div class="form-group">
                    <label>Descriptions</label>
                    <textarea class="form-control" rows="5" id="payment_detail" name="payment_detail" placeholder="Type Descriptions here...!"> </textarea>
                  </div>
              </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="modal-footer ">
          <div class="text-center">
            <button class="btn btn-info btn-sm" id="btnUpdateAccount">Save</button>
            <a href="#" class="btn btn-default btn-sm btn-acc" data-dismiss="modal">Cancel</a>
          </div>
        </div>
      </div>      
    </form>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $(".btnEditJournal").on("click", function(){
      $("#transaction_id").val($(this).data('id'));
      $("#ex_rate").val( $(this).data('exchange'));
      $("#payment_voucher").val($(this).data('pv_voucher'));
      $("#invoice_number").val($(this).data('inv_number'));
      $("#invoice_pay_date").val($(this).data('inv_paid_date'));
      $("#invoice_from_sup").val($(this).data('inv_rcv_date'));
      $("textarea#payment_detail").val($(this).data('detail'));
    });
  });
</script>
@include('admin.include.datepicker')
@include("admin.account.accountant")
@endsection
