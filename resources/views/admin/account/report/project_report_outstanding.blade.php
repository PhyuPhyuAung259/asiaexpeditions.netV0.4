@extends('layout.backend')
@section('title', 'Journal Entry')
<?php 
  $active = 'finance/journal'; 
  $subactive = 'finance/journal';
  use App\component\Content;
  $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
?>
@section('content')
  <style type="text/css">
    .table-head-row{
        background: -webkit-linear-gradient(top, #ffffff, #cccccc54);
    }
    #data_payment_option td{
      padding: 0px;
    }
    #data_payment_option tr td:last-child i:hover, #account_journal_data tr td:last-child i.btnRemoveOption:hover{
      color:#b81c1c;
      cursor: pointer;
    }
    #account_journal_data tr td:last-child i.fa-edit:hover{
      color: #3c8dbc;
    }
    #account_journal_data tr td:last-child i.fa-edit{
      color: #3c8dbc85;
      cursor: pointer;
    }
  
    #account_journal_data tr:hover {
      cursor: pointer;
    }
  </style>
  <div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">
      <section class="content"> 
        @include('admin.include.message')
        <span><a href="{{ URL::previous() }}"><i class="fa fa-arrow-circle-o-left"></i> Back to Aged Report</a></span>  
          <h3 class="border text-center" style="color:black;font-weight:bold;">Project Outstanding of Project No.: {{{$project->project_number or ''}}} & File No.: {{{$project->project_prefix or ''}}}-{{{$project->project_fileno or ''}}}</h3>
          <div class="col-md-12 ">
            <div class="col-md-12">
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="all">
                  <table class="table table-striped table-borderd table-hover">
                    <thead>
                      <tr>                       
                        <th>Entry Date</th>
                        <th><a href="#">Supplier</a></th>
                        <th><a href="#">Account Type - Account Name</a></th>
                        <th class="text-right"><a href="#">To Receive</a></th>
                        <th class="text-right"><a href="#">To Pay</a></th>
                        <th class="text-right"><a href="#">To Receive {{Content::currency(1)}}</a></th>
                        <th class="text-right"><a href="#">To Pay {{Content::currency(1)}}</a></th>
                        <th class="text-right">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($journalList->get() as $acc) 
                        <?php 
                          $supName = isset($acc->supplier->supplier_name)? $acc->supplier->supplier_name: '';
                          if ($acc->business_id == 55) {
                            $supName = isset($acc->ent_service->name)? $acc->ent_service->name :'';
                          }
                          if ($acc->business_id == 54) {
                            $supName = isset($acc->misc_service->name)? $acc->misc_service->name :'';
                          }                          
                          // $AccTrantotal  = \App\AccountTransaction::where("journal_id", $acc->id);
                          $proNO = isset($project->project_number) ? $project->project_number: '';
                          $AccTrantotal = \App\AccountTransaction::where(["journal_id"=>$acc->id, "project_number"=> $proNO, "status"=>1]);
                          $depositAmount = $AccTrantotal->sum("debit") > 0 ? $AccTrantotal->sum("debit") : $AccTrantotal->sum("credit");
                          $depositAmountk = $AccTrantotal->sum("kdebit") > 0 ? $AccTrantotal->sum("kdebit") : $AccTrantotal->sum("kcredit");
                        ?>            
                        <tr>
                          <td style="width: 92px;">{{Content::dateformat($acc->entry_date)}}</td>
                          <td>{{{ $supName or ''}}}</td>
                          <td><a href="#">{{{ $acc->account_type->account_name or ''}}} - {{{ $acc->account_name->account_code or ''}}} - {{{ $acc->account_name->account_name or ''}}}</a></td>                         
                          <td class="text-right">{{Content::money($acc->credit - $depositAmount)}}</td>
                          <td class="text-right">{{Content::money($acc->debit - $depositAmount)}}</td>
                          <td class="text-right">{{Content::money($acc->kcredit)}}</td>
                          <td class="text-right">{{Content::money($acc->kdebit)}}</td>
                          
                          <td class="text-right">
                            @if($depositAmount < $acc->debit || $depositAmountk < $acc->kdebit)
                              <a target="_blank" href="{{route('getPayable', ['journal_id'=> $acc->id])}}" class="btn btn-default btn-acc btn-xs pull-right"> <b>Pay Now</b></a>
                            @elseif($depositAmount < $acc->credit || $depositAmountk < $acc->kcredit)
                              <a target="_blank" href="{{route('getAccountReceivable', ['journal_id'=> $acc->id])}}" class="btn btn-default btn-acc btn-xs pull-right"> <b>Receive Now</b></a>
                            @else
                              Full Paid
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="clearfix"></div><br><br>
      </section>
    </div>
  </div>
@include('admin.include.datepicker')

<div class="modal" id="myModal" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-lg">    
    <form method="POST" action="{{route('editJournal')}}">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Edit Journal Entry</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}   
          <input type="hidden" name="journal_id" id="journal_id"> 
          <div class="row">
            <div class="col-md-12">
              <table class="table">
                <tr class="table-head-row">
                  <th width="250px">Account Type</th>
                  <th width="250px">Account Name</th>
                  <th width="120px">Debit</th>
                  <th width="120px">Credit</th>
                  <th width="120px">{{Content::currency()}} Debit</th>
                  <th width="120px">{{Content::currency()}} Credit</th>
                </tr>
                <tbody id="data_payment_option">
                  <tr>
                    <td>
                      <select class="form-control account_type input-sm" name="account_type" data-type="account_name" required="">
                        <option value="0">Choose Account Types</option>
                        @foreach(App\AccountType::where('status', 1)->orderBy('account_name', 'ASC')->get() as $key=> $acc)
                          <option value="{{$acc->id}}">{{$acc->account_name}}</option>
                        @endforeach
                      </select>
                    </td>
                    <td style="position: relative;">
                      <select class="form-control input-sm" name="account_name" id="dropdown-account_name">
                      </select>
                    </td>
                    <td>
                      <input type="text" class="debit form-control input-sm text-right balance number_only" data-type="debit" name="debit" id="debit" placeholder="00.0">
                    </td>
                    <td>
                      <input type="text" class="credit form-control input-sm text-right balance number_only" data-type="credit" name="credit" id="credit" placeholder="00.0">
                    </td>
                    <td>
                      <input type="text" class="kyat-debit form-control input-sm text-right balance number_only" data-type="kyat-debit" name="kyatdebit" id="kyat-debit" placeholder="00.0">
                    </td>
                    <td>
                      <input type="text" class="kyat-credit form-control input-sm text-right balance number_only" data-type="kyat-credit" name="kyatcredit" id="kyat-credit" placeholder="00.0">
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="col-md-6">
              </div>

              <div class="col-md-6 text-right">
                <div style="padding: 4px 0px;">
                  <div class="col-md-6"> <span>Subtotal {{Content::currency()}}</span></div>
                  <div class="col-md-3"> 
                    <span class="sub_total_debit">0.00</span>
                  </div>
                  <div class="col-md-3"> <span class="sub_total_credit">00.00</span>
                    <input type="hidden" name="debit_amount" id="debit_amount">
                  </div>
                  <div class="clearfix"></div>
                </div>
              
                <div style="padding: 7px 0px; border-top: solid 1px #999999b3;">
                  <div class="col-md-6"><strong>TOTAL</strong></div>
                  <div class="col-md-3"> 
                    <strong class="sub_total_debit">0.00</strong>
                  </div>
                  <div class="col-md-3"> 
                      <strong class="sub_total_credit">00.00</strong>
                      <input type="hidden" name="credit_amount" id="credit_amount">
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div style="padding: 4px 0px;">
                  <div class="col-md-6"> <span>Subtotal {{Content::currency(1)}}</span></div>
                  <div class="col-md-3"> 
                    <span class="kyat_sub_total_debit">0.00</span>
                  </div>
                  <div class="col-md-3"> <span class="kyat_sub_total_credit">00.00</span>
                    <input type="hidden" name="kyat_debit_amount" id="kyat_debit_amount">
                  </div>
                  <div class="clearfix"></div>
                </div>
              
                <div style="padding: 7px 0px; border-top: solid 1px #999999b3;">
                  <div class="col-md-6"> <strong>TOTAL</strong> </div>
                  <div class="col-md-3"> 
                    <strong class="kyat_sub_total_debit">0.00</strong>
                  </div>
                  <div class="col-md-3"> 
                      <strong class="kyat_sub_total_credit">00.00</strong>
                      <input type="hidden" name="kyat_credit_amount" id="kyat_credit_amount">
                  </div>                        
                </div>
                <div class="clearfix"></div>
                <hr style="padding: 1px;border: solid 1px #999999b3;margin-top: 0px;border-right: none;border-left: none;">
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer ">
          <div class="text-center">
            <button class="btn btn-info btn-sm" id="btnUpdateAccount">Save</button>
            <a href="#" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</a>
          </div>
        </div>
      </div>      
    </form>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on("click", ".btnEditJournal", function(){
      dataId = $(this).data("id");
      $("#journal_id").val(dataId);
    });
  });
</script>
@include("admin.account.accountant")
@endsection

