@extends('layout.backend')
@section('title', $view_type)
<?php 
  $active = 'finance/accountPayable/project-booked'; 
  $subactive = 'finance/accountPayable/project-booked';
  use App\component\Content;
  $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
 $user_role =  Auth::user()->role_id;
?>
@section('content')
  <div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">
      <section class="content"> 
        @include('admin.include.message')
          <h3 class="border text-center" style="color:black;text-transform:uppercase;font-weight:bold;">Select Option to Pay</h3>
          <div class="container-fluid ">
              <div class="text-center"> 
                <a href="{{route('accountPayable', ['type' => 'project-booked'])}}" class="btn btn-primary 
                  {{{ $view_type == 'project-booked' ? 'active' : 'btn-acc'}}}"> <b>Project Booked</b></a> &nbsp;
                <a href="{{route('accountPayable', ['type' => 'office-supply'])}}" class="btn btn-primary 
                  {{{ $view_type == 'office-supply' ? 'active' : 'btn-acc'}}}"> <b>Office Supplier</b></a> &nbsp;
              </div>
              <div class="tab-pane row">
                  <div class="col-md-6 col-md-offset-3">
                    <div id="search_form" style="padding: 6px;border: solid #d2d6de 1px; position: relative; background: #e8f1ff;margin: 12px 0px;border-radius: 3px;" class="alert alert-dismissible fade in" role="alert"> 
                      <br>
                      <form method="GET" action="">
                        <div class="col-md-3">
                          @if(\Auth::user()->role_id == 2)
                              <select class="form-control country_book_supplier input-sm" name="country" data-type="supplier_book">
                                <option value="0">Location</option>
                                @foreach(App\Country::LocalPayment() as $con)
                                  <option value="{{$con->id}}" {{$coun_id  == $con->id ? 'selected' : ''  }}>{{$con->country_name}}</option>
                                @endforeach
                              </select>
                          @endif
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <input type="text" name="start_date" class="form-control input-sm text-center" id="from_date" value="{{$currentDate}}" placeholder="Date From" readonly>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <input type="text" name="end_date" class="form-control input-sm text-center" id="to_date" value="{{$nextMonth}}" placeholder="Date To" readonly>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <div class="pull-left">
                              <button type="submit" class="btn btn-primary btn-sm btn-flat" id="btnSearchJournal">Search</button>
                            </div>
                          </div>
                        </div> 
                      </form>
                      <div class="clearfix"></div>
                    </div>  
                  </div><div class="clearfix"></div>
                  @if(isset($posteData) && $posteData->count() > 0)
                    <form target="_blank" action="{{route('PreviewPosted')}}"> 
                        <div>
                          <input type="submit" name="priviwe_print" value="Preview & Print" class="btn btn-default btn-acc btn-xs">
                        </div>
                        <table class="table datatable table-striped table-borderd table-hover ">
                          <thead>
                            <tr>
                              <th width="72px"> 
                                <label class="container-CheckBox" style="margin-bottom: 0px;">CheckAll
                                  <input type="checkbox" id="check_all" name="checkbox" >
                                  <span class="checkmark hidden-print" style="width: 14px; height: 14px;" ></span>
                                </label>
                              </th>
                              @if(isset($view_type) && $view_type == 'project-booked')
                                <th width="68"><a href="#">FileNo.</a></th>
                              @endif
                              <th><a href="javascript:void(1)">Date</a></th>
                              <th><a href="#">Supplier</a></th>
                              @if($view_type == "project-booked" )
                              <th title="Service Date">SVR-Date</th>
                              @endif
                              <th><a href="#">Account Type - Account Name</a></th>
                              <th class="text-right"><a href="javascript:void(0)" style="color: #f39c12; font-style: italic;">EST-Receive</a></th>
                              <th class="text-right"><a href="javascript:void(0)" style="color: #f39c12; font-style: italic;">EST-Cost</a></th>
                              @if($posteData->sum('book_amount') > 0)
                                <th class="text-right"><a href="javascript:void(0)">To Receive</a></th>
                                <th class="text-right"><a href="javascript:void(0)">To Pay</a></th>
                              @endif
                              @if($posteData->sum('book_kamount') > 0)
                                <th class="text-right"><a href="javascript:void(0)">To Receive {{Content::currency(1)}}</a></th>
                                <th class="text-right"><a href="javascript:void(0)">To Pay {{Content::currency(1)}}</a></th>
                              @endif
                              <th class="text-right"><a href="javascript:void(0)">Deposit</a></th>
                              <th class="text-right">Paid/Received</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php 
                              $toRBalance = 0;
                              $toPBalance = 0;
                              $toRBalancek = 0;
                              $toPBalancek = 0;
                          ?>
                          @foreach($posteData->get() as $key => $acc)
                              <?php     
                                $AccTrantotalAP = App\AccountTransaction::where(["journal_id"=>$acc->id, "status"=>1, 'type'=>$acc->type]);
                                // $AccTrantotalRP = App\AccountTransaction::where(["journal_id"=>$acc->id, "status"=>1, 'type'=>1]);
                                $depositAmount  = $AccTrantotalAP->sum('credit') > 0 ? $AccTrantotalAP->sum('credit') : $AccTrantotalAP->sum('debit');
                                $depositAmountk= $AccTrantotalAP->sum('kcredit') > 0 ? $AccTrantotalAP->sum('kcredit') : $AccTrantotalAP->sum('kdebit');

                                $hbook  = App\HotelBooked::find($acc->book_id);
                                $book   = App\Booking::find($acc->book_id);
                                $crBook = App\CruiseBooked::find($acc->book_id);
                                $rBook  = App\BookRestaurant::find($acc->book_id);
                                $enBook = App\BookEntrance::find($acc->book_id);
                                $miBook = App\BookMisc::find($acc->book_id);

                                $toRP   = ($acc->credit - $AccTrantotalAP->sum('debit'));
                                $toPA   = ($acc->debit - $AccTrantotalAP->sum('credit')); 
                                $toRPk  = ($acc->kcredit- $AccTrantotalAP->sum('kdebit'));
                                $toPAk  = ($acc->kdebit - $AccTrantotalAP->sum('kcredit'));
                                $toRBalance  = $toRBalance + ($toRP > 0 ? $toRP : 0);
                                $toPBalance  = $toPBalance + ($toPA > 0 ? $toPA : 0); 
                                $toRBalancek = $toRBalancek + ($toRPk > 0 ? $toRPk : 0);
                                $toPBalancek = $toPBalancek + ($toPAk > 0 ? $toPAk : 0);                            
                              ?>
                              <tr>
                                <td>
                                  <ul class="list-unstyled" style="display: flex;">
                                    <li><label class="container-CheckBox" style="margin-bottom: 0px;">
                                        <input type="checkbox" class="checkall" name="value_checked[]" value="{{$acc->id}}">
                                        <span class="checkmark hidden-print" style="width: 14px; height: 14px;"></span>
                                      </label>
                                    </li>
                                    <li style="padding-right: 6px">
                                      <a href="javascript:void(0)" class="btnRemoveOption" data-type="journal-entry" data-id="{{$acc->id}}" title="Remove this ?"><i class="fa fa-minus-circle text-danger" style="font-size: 16px;top: -2px;position: relative;"></i>
                                      </a> 
                                    </li>
                                    <li>
                                        <a href="#" data-acc_name_title="{{{ $acc->account_name->account_code or ''}}} - {{{ $acc->account_name->account_name or ''}}}" data-acc_type="{{{$acc->account_type->id or ''}}}" data-acc_name="{{{$acc->account_name->id or ''}}}" data-id="{{$acc->id}}"  data-type="account_name" data-credit="{{$acc->credit}}" data-debit="{{$acc->debit}}" data-kcredit="{{$acc->kcredit}}" data-kdebit="{{$acc->kdebit}}" class="btnEditJournal" data-pay_date="{{date('Y-m-d', strtotime($acc->entry_date))}}" data-toggle="modal" data-target="#myEditModal">
                                        <i style="font-size: 14px;top: -2px;position: relative;" class="fa fa-edit text-primary"></i>
                                      </a>    
                                    </li>
                                  </ul>             
                                </td>
                                @if(isset($view_type) && $view_type == 'project-booked')
                                  <td>{{{$acc->project->project_prefix or ''}}}-{{$acc->project_fileNo}}</td>
                                @endif
                                <td style="width: 92px;">{{Content::dateformat($acc->entry_date)}}</td>
                                <td>{{{ $acc->supplier->supplier_name or ''}}}</td>
                                @if($view_type  == "project-booked")
                                <td>
                                  @if(isset($acc->project) && !empty($acc->project))
                                    @if($acc->business_id == 1)
                                      {{Content::dateformat($hbook['checkin'])}} -> {{Content::dateformat($hbook['checkout'])}}
                                    @elseif ($acc->business_id == 2)

                                    {{isset($rBook) ? Content::dateformat($rBook->start_date) : ''}}
                                    @elseif ($acc->business_id == 4)                                    
                                      {{Content::dateformat($book['book_checkin'])}}  <!-- Flight  checkin date-->
                                    @elseif ($acc->business_id == 3)
                                      {{ $crBook['checkin'] ? Content::dateformat($crBook['checkin']) : ''}} 
                                      {{ $crBook['checkin'] &&  $crBook['checkout'] ? "->" : ""}}
                                      {{ $crBook['checkout'] ? Content::dateformat($crBook['checkout']) : ''}}
                                    @elseif ($acc->business_id == 6 || $acc->business_id == 7) 
                                    
                                      {{ isset($book) ? Content::dateformat($book->book_checkin) : '' }}
                                    @elseif ($acc->business_id == 54)
                                    
                                      {{ isset($miBook->booking) ? Content::dateformat($miBook->booking->book_checkin) : ''}}
                                    @elseif ($acc->business_id == 55)
                                    
                                      {{ isset($enBook) ? Content::dateformat($enBook->start_date) : '' }}
                                    @elseif ($acc->business_id == 29)
                                    
                                      {{ $book['book_checkin'] ? Content::dateformat($book['book_checkin']) : ''}}
                                    @elseif ($acc->business_id == 9)

                                      @if( isset($acc->project) ||  isset($acc->project) )
                                        {{ Content::dateformat($acc->project->project_start) }} -> 
                                        {{ Content::dateformat($acc->project->project_end) }}
                                      @endif
                                    @endif
                                  @endif
                                </td>
                                @endif
                                <td><a href="#">{{{ $acc->account_type->account_name or ''}}} - {{{ $acc->account_name->account_code or ''}}} - {{{ $acc->account_name->account_name or ''}}}</a></td>

                                <td class="text-right" style="color: #f39c12; font-style: italic;">
                                  {{ $acc->credit > 0 ? Content::money($acc->credit) : Content::money($acc->kcredit) }}
                                </td>
                                <td class="text-right" style="color: #f39c12; font-style: italic;">
                                  {{ $acc->debit > 0 ? Content::money($acc->debit) : Content::money($acc->kdebit) }}
                                </td> 
                                @if($posteData->sum('book_amount') > 0)                             
                                <td class="text-right">{{Content::money($toRP)}}</td>
                                <td class="text-right">{{Content::money($toPA)}}</td>
                                @endif
                                @if($posteData->sum('book_kamount') > 0)
                                <td class="text-right">{{Content::money($toRPk)}}</td>
                                <td class="text-right">{{Content::money($toPAk)}}</td>
                                @endif
                                <td class="text-right" style="color: #3c8dbc;"><b>{{$depositAmount ? Content::money($depositAmount) : Content::money($depositAmountk)}}</b></td>
                                <td class="text-right">
                                  @if($depositAmount < $acc->credit || $depositAmountk < $acc->kcredit)
                                    <a target="_blank" href="{{route('getPayable', ['journal_id'=> $acc->id])}}" class="btn btn-default btn-acc btn-xs pull-right hidden-print"> <b>Receive </b></a>
                                  @elseif($depositAmount < $acc->debit || $depositAmountk < $acc->kdebit)
                                    <a target="_blank" href="{{route('getPayable', ['journal_id'=> $acc->id])}}" class="btn btn-default btn-acc btn-xs pull-right hidden-print"><b>Pay </b></a>
                                  @else
                                    <span class="badge badge-light">{{$acc->type == 2 ? "Full Received" : "Full Paid" }}</span>
                                  @endif
                                </td>
                              </tr>
                          @endforeach
                            <tr style="background: #c3daf8;color: #795548;">
                              <th colspan="{{$view_type == 'project-booked' ? 6 : 4}}" class="text-right"> </th>
                              <th class="text-right">
                                @if($toRBalance > 0)
                                  {{Content::money($toRBalance)}} {{Content::currency()}}
                                @endif
                              </th>
                              <th class="text-right">
                                @if($toPBalance > 0)
                                  {{ Content::money($toPBalance)}} {{Content::currency()}}
                                @endif
                              </th>

                              <th class="text-right">
                                @if($toRBalancek > 0 )
                                  {{Content::money($toRBalancek)}} {{Content::currency(1)}}
                                @endif
                             </th>
                              <th class="text-right">
                                @if($toPBalancek > 0)
                                  {{Content::money($toPBalancek)}} {{Content::currency(1)}}
                                @endif
                              </th>
                              <th></th>
                              <th></th>
                            </tr>
                          </tbody>
                        </table>
                    </form>
                 
                  @else
                  <br>
                  <div class="notify-message">
                      <div class="alert alert-dismissible fade show warning " role="alert" style="position: relative; padding-left: 53px;">
                        <p style="text-transform: capitalize;"><i class="fa fa-warning (alias)"></i> <span>You need to choose one of tab above</span></p>
                      </div>
                  </div>
                  @endif
              </div>
          </div>
          <div class="clearfix"></div><br><br>
      </section>
    </div>
  </div>
<script type="text/javascript">
    $(document).ready(function(){
       $(".datatable").DataTable();
    });
</script>

<div class="modal" id="myEditModal" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-lg">    
    
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Edit Journal Entry</strong></h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{route('editJournal')}}">
            {{csrf_field()}}   
            <input type="hidden" name="journal_id" id="journal_id"> 
            <div class="row">
                @if(\Auth::user()->role_id == 2)
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="col-sm-3 text-right" style="padding-top: 7px;">Country</label>
                      <div class="col-sm-9">
                        <select class="form-control location" name="country" data-type="country">
                          @foreach(App\Country::getCountry(5) as $key => $con)
                          <option value="{{$con->id}}">{{$con->country_name}}</option>
                          @endforeach()
                        </select>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                @endif
                <div class="col-md-5">
                  <div class="form-group">
                      <label class="col-sm-4 text-right" style="padding-top: 7px;">Record Date</label>
                      <div class="col-sm-5">
                        <input type="text" name="pay_date"  id="pay_date"  class="form-control input-sm book_date" readonly="" value="{{date('Y-m-d')}}">
                      </div>
                      <div class="clearfix"></div>
                  </div>
                </div>
                <table class="table">
                  <tr class="table-head-row">
                    <th width="200px">Account Type</th>
                    <th width="350px">Account Name</th>
                    <th width="120px">Debit</th>
                    <th width="120px">Credit</th>
                    <th width="120px">{{Content::currency(1)}} Debit</th>
                    <th width="120px">{{Content::currency(1)}} Credit</th>
                  </tr>
                  <tbody id="data_payment_option">
                    <tr>
                    
                      <td>
                        <select class="form-control account_type input-sm" name="account_type" data-type="account_name" required="">
                          <option value="0">Account Type</option>
                          @foreach(App\AccountType::where('status', 1)->orderBy('account_name', 'ASC')->get() as $key=> $acc)
                            <option value="{{$acc->id}}">{{$acc->account_name}}</option>
                          @endforeach
                        </select>
                      </td>
                      <td style="position: relative;">
                         <div class="btn-group" style='display: block;'>
                          <button type="button" class="form-control input-sm arrow-down" data-toggle="dropdown" aria-haspopup="false" aria-expanded='false' data-backdrop="static" data-keyboard="false" role="dialog" data-backdrop="static" data-keyboard="false">
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
            <div class="modal-footer ">
              <div class="text-center">
                <button class="btn btn-info btn-sm" type="submit" id="btnUpdateAccount">Save</button>
                <a href="#" class="btn btn-default btn-sm btn-acc" data-dismiss="modal">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>      
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
  
    $("#check_all").click(function () {
        if($("#check_all").is(':checked')){
          $(".checkall").prop('checked', true);
            $(".checkall").closest("tr").css({'background': '#c3daf8'});
          // }
        } else {
          $(".checkall").prop('checked', false);
          $(".checkall").closest("tr").css({'background': 'none'});
        }
      });
      
      $(".checkall").on("click", function(){
        if ($(this).is(':checked')) {
          $(this).closest("tr").css({'background': '#c3daf8'});
        }else{
          $(this).closest("tr").css({'background': 'none'});
        }
      });
  });
</script>

<script type="text/javascript">
  function filterAccountName(){
    input = document.getElementById("search_Account");
    filter = input.value.toUpperCase();
    ul = document.getElementById("myAccountName");
    li = ul.getElementsByClassName ("list");
    for (i = 0; i < li.length; i++) {
        a = li[i];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
          li[i].style.display = "";
        } else {
          li[i].style.display = "none";
        }
    }
  }
</script>
@include("admin.account.accountant")
@endsection

