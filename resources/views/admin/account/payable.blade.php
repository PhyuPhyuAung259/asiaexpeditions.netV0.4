@extends('layout.backend')
@section('title', $journal['type'] == 1 ? "Payment " : "Receive Payment")
<?php 
  use App\component\Content;
  $active = 'finance/journal'; 
  $subactive = 'finance/payable/create';
  $bus_id = isset($journal->business_id)? $journal->business_id:'';
  $sup_id = isset($journal->supplier_id)? $journal->supplier_id:'';
  $projectNo = isset($journal->project_number)? $journal->project_number:0;
  $conId = isset($journal->country_id) ? $journal->country_id: \Auth::user()->country_id;
  $proId = \Auth::user()->province_id;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper"> 
    <section class="content"> 
      @include('admin.include.message')
      <h3 class="border text-center" style="color:black;text-transform: capitalize;font-weight:bold;">Cash / Bank Transactions </h3>
        <form method="POST" action="{{route('createPayment')}}" id="account_CreatePayment_form">
          {{csrf_field()}}
          <div class="row">
            <div class="col-md-10 col-md-offset-1" style="margin-bottom: 20px">
              <div class="account-saction"><br>
                <div class="col-md-2 col-xs-6" >
                  <div class="form-group">
                    <label>Date<span style="color:#b12f1f;">*</span></label> 
                    <input type="text" name="pay_date" class="form-control book_date" value="{{date('Y-m-d')}}" required="" readonly="">
                  </div>
                </div> 
                <div class="col-md-2 col-xs-6 {{\Auth::user()->role_id != 2 ? 'hidden': ''}}">
                  <div class="form-group">
                    <label>Location<span style="color:#b12f1f;"></span></label> 
                    @if(Auth::user()->role_id == 2)
                        <select class="form-control country_book_supplier" data-selected="{{$journal['supplier_id']}}" name="country" data-type="supplier_book">
                          @foreach(App\Country::getCountry(5) as $con)
                            <option value="{{$con->id}}" {{ $journal['country_id'] == $con->id ? 'selected' : ''  }}>{{$con->country_name}}</option>
                          @endforeach
                        </select>
                    @else
                      <span class="form-group">
                        <span class="form-control ">{{{ $journal->country->country_name or ''}}}</span>
                        <input type="hidden" name="country" value="{{$journal->country_id}}">
                      </span>
                    @endif
                  </div>
                </div>
                <div class="col-md-2 col-xs-6">
                  <div class="form-group">
                    <label>Business Type</label>
                    @if(isset($journal->business_id) && !empty($journal->business_id))
                      <span class="form-group">
                        <span class="form-control ">{{{ $journal->business->name or ''}}}</span>
                        <input type="hidden" name="business" value="{{$journal->business_id}}">
                      </span>
                    @else
                      <select class="form-control business_type_receive" name="business" data-type="sup_by_bus" required="">
                        <option value="">Choose Business</option>
                          @foreach(App\Business::PaymentBusiness() as $key => $bn)
                            <option value="{{$bn->id}}" {{$bus_id == $bn->id ? "selected":''}}>{{$bn->name}}</option>
                          @endforeach()
                      </select>
                    @endif
                  </div>
                </div>
                <div class="col-md-3 col-xs-6" id="supplier_Name">
                  <div class="form-group">
                    <?php $supplierType = $journal->type == 1 ? 'to' : 'from'; ?>
                    <label style="text-transform: capitalize;">{{ $supplierType }} Supplier <span style="color:#b12f1f;">*</span></label> 
                      <div class="form-group" >
                        <select class="form-control" name="supplier_{{$supplierType}}" required="">
                          <option value="{{$journal->supplier_id}}">{{{ $journal->supplier->supplier_name or ''}}}</option>
                        </select>
                      </div>
                  </div>               
                </div>
                <?php 
                  $getFBank = App\Supplier::where(['business_id'=>5, 'country_id'=>$conId])->whereNotIn('id',[$journal->supplier_id] )->orderBy("supplier_name", "ASC")->get();
                  ?>                
                @if($getFBank->count() > 0)
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <?php $getSupBook = $journal->type == 1 ? "from" : "to"; ?>
                        <label style="text-transform: capitalize;">{{ $getSupBook }} Supplier <span style="color:#b12f1f;">*</span></label> 
                        <div style="position: relative;">
                          <select class="form-control" name="supplier_{{ $getSupBook }}" id="supplier_book" required="">
                            @foreach($getFBank as $sup_book)
                              <option value="{{$sup_book->id}}">{{$sup_book->supplier_name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                @endif        
                <div class="col-md-6 col-xs-6">
                  <div class="row">
                    <div class="col-md-5 col-xs-12">
                      <div class="total_balance_to_pay" >
                        <?php
                          $accBalance = 0;
                          $fieldName = "deposit_amount";
                          $fieldhiddenName = "pay_amount";
                          if ( isset($journal->id)) {
                            $getAccTran = App\AccountTransaction::where(["journal_id"=>$journal->id, 'type'=>$journal->type, 'status'=>1]);
                            
                            if ($journal->debit > 0 || $journal->credit > 0 ) {
                              $amountDebit = ($journal->debit - $getAccTran->sum("credit"));
                              $amountCredit = ($journal->credit - $getAccTran->sum("debit"));
                              $accBalance = $amountDebit > 0 ? $amountDebit : $amountCredit;
                              $fieldName = "deposit_amount";
                              $fieldhiddenName = "pay_amount";
                              $currency = Content::currency();
                              $reqired = ""; 
                            }else{
                              $currency = Content::currency(1);
                              $AcmountKDebit = ($journal->kdebit - $getAccTran->sum("kcredit"));
                              $AcmountKcredit = ($journal->kcredit - $getAccTran->sum("kdebit"));
                              $accBalance = $AcmountKDebit > 0 ? $AcmountKDebit : $AcmountKcredit;
                              $fieldName = "deposit_kamount";
                              $reqired = "required"; 
                              $fieldhiddenName = "pay_kamount";
                            }
                          }                 
                        ?>
                        <input type="hidden" id="currency" value="{{$currency}}">
                        <input type="hidden" name="type" value="{{{ $journal->type or ''}}}">
                        <label><input style="margin:0px;" type="radio" name="payoption" class="payoption" value="1" checked> Pay Full</label>
                        <label><input style="margin:0px;" type="radio" name="payoption" class="payoption" value="0"> Deposit {{$currency}}</label>
                        <input type="text" name="{{ $fieldName }}" class="form-control text-center balance number_only" id="deposit_amount" placeholder="0." value="{{$accBalance}}" {{$accBalance == 0 ? 'readonly': ''}} required>
                        <input type="hidden" name="{{$fieldhiddenName}}" class="form-control text-right balance number_only" id="pay_amount" placeholder="00.0 " value="{{ $accBalance }}">
                      </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="amount_to_pay ">
                        <div class="form-group amount_to_pay">
                          <label>Balance</label>
                          <span class="form-control" id="amount_to_pay"></span>
                          <input type="hidden" id="input_amount_to_pay">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2 col-xs-6">                      
                        <div class="amount_to_pay">
                            <div class="form-group amount_to_pay">
                              <label>BankFees</label>
                              <input type="text" class="form-control text-center" name="bank_fees" placeholder="00.00">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6">
                      <div class="form-group">                      
                          <label>Exc-Rate</label>
                          <input class="form-control text-center" name="exchange_rate" type="text" placeholder="00.00" {{$reqired}}>
                      </div>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="journal_id" id="journal_id" value="{{{$journal->id or ''}}}">
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Account Type</label>
                    <input type="hidden" name="account_type_id"id="account_typeID" value="{{{$journal->account_type_id or ''}}}" >
                    <span class="form-control">{{{$journal->account_type->account_name or ''}}}</span>
                  </div>
                </div>
                <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Account Name</label>
                      <input type="hidden" name="account_name_id" id="account_nameID" value="{{{$journal->account_name_id or ''}}}">
                      <span class="form-control">{{{$journal->account_name->account_code or ''}}} - {{{$journal->account_name->account_name or ''}}}</span>
                    </div>
                </div>
                @if(asset($journal->project) && $journal->project_id > 0)
                <div class="col-md-6 col-xs-6">
                  <div class="form-group">                 
                    <label for="for_project">For Project<span style="color:#b12f1f;">*</span></label>
                      <input type="hidden" name="projectNo" value="{{{$journal->project_id or ''}}}">
                      <span class="form-control">
                        {{{$journal->project->project_prefix or ''}}}-{{{$journal->project->project_fileno or ''}}} - {{{$journal->project->project_client or ''}}},  Date:{{ Content::dateformat($journal->project->project_start)}}->{{Content::dateformat($journal->project->project_end)}}</span>
                  </div>
                </div>
                @endif
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label title="Please enter actual pay date">INV Paid Date<span style="color:#b12f1f;"> * <small style="font-size: 79%;">Actual pay date</small></span></label>
                    <input type="text" name="invoice_pay_date" id="invoice_pay_date" class="form-control book_date" value="{{date('Y-m-d')}}" required="" readonly="">
                  </div>
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Payment Voucher<span style="color:#b12f1f;">*</span></label>
                    <input type="text" name="payment_voucher" id="payment_voucher" class="form-control text-right" required placeholder="xxx xxx xxx xxx">
                  </div>
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Invoice Number<span style="color:#b12f1f;">*</span></label>
                    <input type="text" name="invoice_number" id="invoice_number" class="form-control text-right" placeholder="xxx xxx xxx xxx" required>
                  </div>
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>INV-RCVD Date From Supplier<span style="color:#b12f1f;">*</span></label>
                    <input type="text" name="invoice_from_sup" id="invoice_from_sup" class="form-control book_date" value="{{date('Y-m-d')}}" required="" readonly="">
                  </div>
                </div>
                <div class="col-md-12 col-xs-12">
                  <div class="form-group">
                    <label>Remarks / Descriptions</label>
                    <textarea class="form-control" rows="4" id="remark" name="remark" placeholder="Message here ...!">{{{$journal->remark or ''}}}</textarea>
                  </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12 text-center">
                  <div class="form-group">
                    <button {!! $accBalance == 0 ? "disabled" : "" !!} class="btn btn-info btn-sm" id="btnSaveCreatedPayment" style="width: 120px;">
                      {{ $journal->type == 1 ? "Pay Now" : "Confirm Receive" }}
                    </button>
                  </div>
                </div><div class="clearfix"></div>
              </div>
            </div>
          </div>
        </form>
    </section>
  </div>
</div>

<!-- menun -->
<div class="modal" id="myModal" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-md">    
    <form method="POST" action="{{route('addNewAccount')}}" id="add_new_account_form">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add New Account</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
          <div class="row">
            <div class="col-md-6 col-xs-4">
              <div class="form-group">
                <label>Account Type<span style="color:#b12f1f;">*</span></label> 
                <select class="form-control  account_type" name="account_type" data-type="account_name">
                  @foreach(App\AccountType::where('status', 1)->orderBy('account_name', 'ASC')->get() as $key=> $acc)
                    <option value="{{$acc->id}}">{{$acc->account_name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6 col-xs-4">
              <div class="form-group">
                <label>Account Code<span style="color:#b12f1f;">*</span> <small>(A unique code/number)</small></label> 
                <input type="text" name="code" class="form-control" required="">
              </div>
            </div>
            <div class="col-md-12 col-xs-4">
              <div class="form-group">
                <label>Account Name<span style="color:#b12f1f;">*</span></label> 
                <input type="text" name="name" class="form-control " required="">
              </div>
            </div>
            <div class="col-md-12 col-xs-12">
              <div class="form-group">
                <label>Description (Option)<span style="color:#b12f1f;">*</span></label> 
                <textarea class="form-control" name="account_desc" rows="4"></textarea>
              </div>
            </div>
          </div>
           <div class="col-md-12 col-xs-12">
            <div class="form-group">
              <div class="checkMebox">
                <label>
                  <span style="position: relative;top: 4px;"> 
                    <i class="fa fa-square-o"></i>
                    <input type="checkbox" name="check_paid" >&nbsp;
                  </span>
                  <span>Already Paid</span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer ">
          <div class="text-center">
            <button class="btn btn-info btn-sm" style="width: 120px;" id="btnAddNewAccount">Save</button>
            <a href="#" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</a>
          </div>
        </div>
      </div>      
    </form>
  </div>
</div>

<div class="modal" id="myAlert" role="dialog" data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-md">    
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Payment Confirmation</strong></h4>
          <div class="clearfix"></div>
        </div>
        <div class="modal-body">
          <div id="modal-body">
            <strong id="message">Result not found...! </strong>        
          </div>
          <p><label>Are you sure to make this payment transaction ?</label></p>
        </div>
        <div class="modal-footer" style="text-align: center;">
          <button type="submit" class="btn btn-info btn-sm btnOkay" value="1"  >OK</button>
          <button type="button" class="btn btn-default btn-flat btn-sm btn-acc" data-dismiss="modal">Cancel</button>
        </div>  
      </div>  
  </div>
</div>
@include("admin.account.accountant")
@include("admin.include.datepicker")
@endsection
