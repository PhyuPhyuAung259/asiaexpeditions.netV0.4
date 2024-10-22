@extends('layout.backend')
@section('title', 'Create Receivable')
<?php 
  $active = 'finance/accountPayable/project-booked'; 
  $subactive = 'finance/receivable/create';
  use App\component\Content;
  $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
  $bus_id = isset($journal->business_id)? $journal->business_id : ''; 
  $sup_id = isset($journal->supplier_id)? $journal->supplier_id : ''; 
  $conId = isset($journal->country_id) ? $journal->country_id : \Auth::user()->country_id;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      @include('admin.include.message')
      <h3 class="border text-center" style="color:black;text-transform: uppercase;font-weight:bold;">Make Receivable</h3>
        <form method="POST" action="{{route('createReceivable')}}" id="account_ReceivePayment_form">
          {{csrf_field()}}
          <div class="row">
            <div class="col-md-10 col-md-offset-1" style="margin-bottom: 20px">
              <div class="account-saction"><br>
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="pull-right">
                      <div class="form-group">
                        <label>Receivable Type</label>
                        <select class="form-control input-sm receivable-type" style="background: linear-gradient(#fff, #dddddd6b);border-radius: 12px;">
                            <option value="Null">Project Booked</option>
                            <option value="1">Other Payments</option>
                        </select>
                      </div>
                    </div> 
                    <div class="pull-left">
                      <div class="form-group">
                        <label>Date<span style="color:#b12f1f;">*</span></label> 
                        <input type="text" name="pay_date" class="form-control book_date" value="{{date('Y-m-d')}}" required="">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-xs-6  {{\Auth::user()->role_id != 2 ? 'hidden': ''}}">
                  <div class="form-group">
                    <label>Location<span style="color:#b12f1f;"></span></label> 
                    <select class="form-control location" name="country" data-type="sup_by_bus" required="">
                      <option value="">Choose Location</option>
                      @foreach(App\Country::LocalPayment() as $con)
                        <option value="{{$con->id}}" {{ $conId == $con->id ? 'selected':''}}> {{ $con->country_name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>                              
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Business Type</label>
                    <select class="form-control business_type_receive" name="business" data-type="sup_by_bus" required="">
                      <option value="">Choose Business</option>
                      @foreach(App\Business::PaymentBusiness() as $key => $bn)
                        <option value="{{$bn->id}}" {{$bus_id == $bn->id ? 'selected':''}}>{{$bn->name}}</option>
                      @endforeach()
                    </select>
                  </div>
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>From Supplier <span style="color:#b12f1f;">*</span></label> 
                    <select class="form-control supplier-receivable" name="supplier" id="dropdown_receive_supplier" data-type="pro_by_sup" data-acc_type="acc_receivable" required="">
                      <option value="">Choose Supplier</option>                        
                        @foreach(App\Supplier::SupByAccount($bus_id) as $sup)
                          <option value="{{$sup->id}}" {{$sup_id==$sup->id?"selected":''}}>{{$sup->supplier_name}}</option>
                        @endforeach
                    </select>                    
                  </div>               
                </div>
                <input type="hidden" name="journal_id" id="journal_id" value="{{{$journal->id or ''}}}">
                <input type="hidden" name="account_name_id" id="account_nameID" value="{{{$journal->account_name_id or ''}}}">
                <input type="hidden" name="account_type_id" id="account_typeID" value="{{{$journal->account_type_id or ''}}}">
                <div id="proJectNoOPtion">
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label for="for_project">For Project<span style="color:#b12f1f;">*</span></label>
                      <select class="form-control projectPayable" name="projectNo" id="dropdown-project" data-type="pro-receivable">
                        <option value="0">Project</option>
                        <?php 
                          $getSupplier = \App\AccountJournal::where(['supplier_id'=> $sup_id, 'status'=>1, "type"=>1])->orderBy("id", "DESC")->get();
                        ?>
                        @foreach($getSupplier as $key => $pro)
                          <?php 
                            $getAccTran = \App\AccountTransaction::where(["journal_id"=>$pro->id, 'type'=>1]);
                            $balanceTotal = (int)$pro->credit - (int)$getAccTran->sum("debit");
                            $balanceTotalk = ((int)$pro->kcredit - (int)$getAccTran->sum("kdebit"));
                            $acc_pro = \App\Project::where('project_number', $pro->project_number)->first();
                          ?>
                          <option data-kbalance="{{ $balanceTotalk }}" data-balance="{{ $balanceTotal }}" data-acc_type="{{$pro->account_type_id}}" data-acc_name="{{$pro->account_name_id}}" value="{{$pro->project_number}}" {{$journal->project_number == $pro->project_number ? "selected":''}} > {{$pro->project_number}}-{{{ $pro->project->project_client or ''}}}</option>
                        @endforeach
                      </select>                    
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Choose Bank <span style="color:#b12f1f;">*</span></label> 
                    <select class="form-control" name="bank" required="">
                      @foreach(App\Bank::where('status', 1)->orderBy('type','DESC')->get() as $key=> $acc)
                        <option value="{{$acc->id}}">{{$acc->name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>     
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12">
                        <label class="label" style="font-weight: 400; color: #374850; font-size: 11px;"><input type="radio" name="payoption" class="payoption" value="1" checked=""> Pay Full</label>
                        <label class="label" style="font-weight: 400; color: #374850; font-size: 11px;"><input type="radio" name="payoption" class="payoption" value="0"> Deposit</label>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group">
                          <?php 
                            $accBalance = "";
                            $fieldName = "deposit_amount";
                            $fieldhiddenName = "pay_amount";
                            if ( isset($journal->id)) {
                              $getAccTran = \App\AccountTransaction::where(["journal_id"=> $journal->id, 'type'=>1]);
                              if (!empty($journal->credit)) {
                                $accBalance = ($journal->credit - $getAccTran->sum("debit"));
                                $fieldName = "deposit_amount";
                                $fieldhiddenName = "pay_amount";
                              }else{
                                $accBalance = ($journal->kcredit - $getAccTran->sum("kdebit"));
                                $fieldName = "deposit_kamount";
                                $fieldhiddenName = "pay_kamount";
                              }
                            }                                         
                          ?>
                          <input type="text" name="{{$fieldName}}" class="form-control  text-center balance number_only" id="deposit_amount" placeholder="0.00" value="{{{$accBalance or ''}}}">
                          <input type="hidden" name="{{$fieldhiddenName}}" class="form-control  text-right balance number_only" id="pay_amount" placeholder="00.0" value="{{{$accBalance or ''}}}">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="row amount_to_pay" >
                            <label><span>Amount To Pay</span></label>
                              <div><span id="amount_to_pay"></span>
                                <input type="hidden" id="input_amount_to_pay" name="">
                              </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>                           
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Receipt Voucher<span style="color:#b12f1f;">*</span></label>
                    <input type="text" name="payment_voucher" class="form-control  text-right" placeholder="xxx xxx xxx xxx">
                  </div>
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Invoice Number<span style="color:#b12f1f;">*</span></label>
                    <input type="text" name="invoice_number" class="form-control  text-right" placeholder="xxx xxx xxx xxx">
                  </div>
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Received Date<span style="color:#b12f1f;">*</span></label>
                    <input type="text" name="invoice_pay_date" class="form-control book_date" value="{{date('Y-m-d')}}">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Remarks</label>
                    <textarea class="form-control" rows="4" name="remark" placeholder="Remark here ...!"></textarea>
                  </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12 text-center">
                  <div class="form-group">
                    <button class="btn btn-info btn-sm" id="btnSaveReceivable" style="width: 120px;">Confirm Receive </button>
                  </div>
                </div><div class="clearfix"></div>
              </div>
            </div>
          </div>
        </form>
    </section>
  </div>
</div>
@include('admin.include.datepicker')
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
                <input type="text" name="code" class="form-control " required="">
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
@include("admin.account.accountant")
@endsection
