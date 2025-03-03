@extends('layout.backend')
@section('title', 'Journal Entry')
<?php 
  $active = 'finance/accountPayable/project-booked'; 
  $subactive = 'finance/journal/create';
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
          <h3 class="border text-center" style="color:black;text-transform: uppercase;font-weight:bold;">Journal Entry</h3>
          <form method="POST" action="{{route('createJournal')}}" id="account_journal_entry_form">
            {{csrf_field()}}
            <div class="row">
              <div class="col-md-12" style="margin-bottom: 20px">
                <div class="account-saction"><br>
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                          <label>Date<span style="color:#b12f1f;">*</span></label> 
                          <input type="text" name="entry_date" class="form-control book_date" value="{{date('Y-m-d')}}" required="">
                        </div>
                      </div>
                      <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                          <label>Country</label>
                          <select class="form-control location" name="country" data-type="country" id="country">
                            @foreach(App\Country::countryByProject() as $key => $con)
                            <option value="{{$con->id}}" {{$con->id == Auth::user()->country_id ? 'selected' : ' '}}>{{$con->country_name}}</option>
                            @endforeach()
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
                  <div class="col-md-12">
                    <table>
                      <tr class="table-head-row">
                        <th width="152">Business Type</th>
                        <th width="200">Supplier</th>
                        <th width="190">Account Type</th>
                        <th width="350">Account Name</th>
                        <th width="120" class="text-center">Debit</th>
                        <th width="120" class="text-center">Credit</th>
                        <th width="120" class="text-center">{{Content::currency(1)}} Debit</th>
                        <th width="120" class="text-center">{{Content::currency(1)}} Credit</th>
                        <th></th>
                      </tr>
                      <tbody id="data_payment_option">
                        <tr class="clone-data">
                          <td>
                            <select class="form-control input-sm business" name="business[]" id="business" required="">
                              <option value="0">--choose--</option>
                              @foreach(App\Business::where(['category_id'=>0, 'status'=>1])->get() as $key => $bn)
                                <option value="{{$bn->id}}">{{$bn->name}}</option>
                              @endforeach()
                            </select>
                          </td>
                          <td style="position: relative;">
                          <!-- <select class="form-control suppliers input-sm" name="supplier[]" id="dropdown_supplier" required=""></select> -->
                            <select class="form-control supplier input-sm" name="supplier[]" id="supplier" required>
                              <option value="">Select a supplier</option>
                            </select>  
                          </td>
                          <td>
                            <select class="form-control account_type input-sm" name="account_type[]" data-multiType="account_name_journal" data-type="account_name" required="">
                              <option value="">--select--</option>
                              @foreach(App\AccountType::where('status', 1)->orderBy('account_name', 'ASC')->get() as $key=> $acc)
                                <option value="{{$acc->id}}">{{$acc->account_name}}</option>
                              @endforeach
                            </select>
                          </td>
                          <td style="position: relative;">
                            <div class="btn-group open" style='display: block;'>
                                <button type="button" class="form-control input-sm arrow-down" data-toggle="dropdown" aria-haspopup="false" aria-expanded='false' data-backdrop="static" data-keyboard="false" role="dialog" data-backdrop="static" data-keyboard="false">
                                  <span class="pull-left">
                                    @if(isset($acc_tran->account_name))
                                      {{{ $acc_tran->account_name->account_code or '' }}} - {{{ $acc_tran->account_name->account_name or '' }}}
                                    @endif
                                  </span>
                                  <span class="pull-right"></span>
                                </button> 
                                <div class="obs-wrapper-search" style="max-height:250px; overflow: auto; ">
                                  <div>
                                    <input type="text" name="acc" data-url="{{route('getFilter')}}" id="search_Account" onkeyup="filterAccountName()" class="form-control input-sm" required="">
                                    <input type="hidden" name="account_name[]" >
                                  </div>
                                  <ul id="myAccountName" class="list-unstyled dropdown_account_name">
                                    @if(isset($_GET['eid']) && !empty(isset($_GET['eid'])))
                                      @foreach(App\AccountName::where("account_type_id", $acc_tran->account_type_id)->orderBy('account_name', 'ASC')->get() as $acc_name)
                                        <li class='list' style=' padding: 4px 0px !important;'>
                                          <label style='position: relative;font-weight: 400; line-height:12px;'>
                                            <input type='radio' name='account_name' value="{{$acc_name->id}}" {{ $acc_name->id == $acc_tran->account_name_id ? 'checked' : ''}}> <span style='position:relative; top:-2px;'> {{ $acc_name->account_code}} - {{ $acc_name->account_name }}</span></label></li>
                                      @endforeach
                                    @endif
                                  </ul>
                                </div>
                            </div>
                          </td>
                          <td>
                            <input type="text" class="debit form-control input-sm text-right balance number_only" data-type="debit" name="debit[]" id="debit" placeholder="00.0">
                          </td>
                          <td>
                            <input type="text" class="credit form-control input-sm text-right balance number_only" data-type="credit" name="credit[]" id="credit" placeholder="00.0">
                          </td>
                          <td>
                            <input type="text" class="kyat-debit form-control input-sm text-right balance number_only" data-type="kyat-debit" name="kyatdebit[]" id="kyat-debit" placeholder="00.0">
                          </td>
                          <td>
                            <input type="text" class="kyat-credit form-control input-sm text-right balance number_only" data-type="kyat-credit" name="kyatcredit[]" id="kyat-credit" placeholder="00.0">
                          </td>
                          <td class="text-center">
                            <span class="btnRemoveEntry">
                              <i class="fa fa-times-circle btn-block" style="font-size: 19px; background: #ddd;padding: 6px;"></i></span>
                          </td>
                        </tr>
                      </tbody>
                    </table><br>
                    <div class="col-md-6">
                      <div class="row">
                        <span class="btn btn-flat btn-primary btn-xs btnAddLine" ><i class="fa fa-plus-square"></i> Add New Line</span>
                      </div>
                    </div>
                    <div class="col-md-6 text-right">
                      <div class="row">
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
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="col-md-12 text-center">
                      <div class="form-group">
                        <button class="btn btn-info btn-sm btn-flat" id="btnSaveReceivable" style="width: 120px;">Save</button>
                      </div>
                    </div>
                  </div>
                <div class="clearfix"></div>
                </div>
              </div>
            </div>
          </form> 
        <br><br>
      </section>
    </div>
  </div>
@include('admin.include.datepicker')
<div class="modal" id="myAlert" role="dialog" data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-sm">    
    <form method="POST" action="{{route('addNewAccount')}}" id="add_new_account_form">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Delete line item</strong></h4>
        </div>
        <div class="modal-body">
          <strong id="message">You must have at least 2 line items.</strong>        
        </div>
        <div class="modal-footer">
          <div class="text-center">
            <a href="#" class="btn btn-danger btn-xs" data-dismiss="modal">OK</a>
          </div>
        </div>    
      </div>  
    </form>
  </div>
</div> 

@include("admin.account.accountant")  

<script >
  $(document).ready(function() {
            $('#business').change(function() {
                var bus_id = $(this).val();
                var country_id = $('#country').val(); 
                console.log(bus_id);
                $.ajax({
                    url: '/supplierbybus/' + bus_id + '?country_id=' + country_id, // Adding country_id as query parameter
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {                   
                        var supplierSelect = $('#supplier');
                        supplierSelect.empty();
                        if (response.length === 0) {
                          supplierSelect.append('<option value="">No supplier available</option>');
                        } else {
                            $.each(response, function(key, value) {
                              supplierSelect.append('<option value="' + value.id + '">' +
                                    value.supplier_name + '</option>');
                            });
                        }
                    }
                });
            });
        });
</script>
<script type="text/javascript">
  // $(document).ready(function(){

     function filterAccountName(){
          input = document.getElementById("search_Account");
          // input = $(this).find("#search_Account");
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
  // });
</script>

@endsection