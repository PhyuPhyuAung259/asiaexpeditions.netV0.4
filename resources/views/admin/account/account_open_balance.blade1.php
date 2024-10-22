@extends('layout.backend')
@section('title', 'Open Balance Account')
<?php 
  $active = 'finance/accountPayable/project-booked'; 
  $subactive = 'finance/account-open-balance ';
  use App\component\Content;
    $countryId = isset($acc_tran->country_id) ? $acc_tran->country_id : Auth::user()->country_id;
?>
@section('content')
  <style type="text/css">

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
  </style>
<div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">
        <section class="content"> 
           @include('admin.include.message')
            <h3 class="border text-center" style="color:black;text-transform: uppercase;font-weight:bold;">
              <div class="col-md-10 col-md-offset-1" >
              <div class="pull-left" style="font-size: 12px;"><a class="btn btn-primary btn-xs" href="{{route('openBalance')}}"> create Openning Balance </a></div>
            </div><div class="clearfix"></div>
            Bring forward opening Balance </h3>          
            <form method="POST" action="{{route('addBankTransfer')}}" id="bank_transfer_form">
              <!-- bank_transfer_form -->
              {{csrf_field()}}
              <input type="hidden" name="eid" value="{{{$_GET['eid'] or ''}}}">
              <input type="hidden" name="action" value="{{{$_GET['action'] or ''}}}">
              <div class="row">
                <div class="col-md-10 col-md-offset-1" style="margin-bottom: 20px">
                  <div class="account-saction"><br>
                    <div class="col-md-2 col-xs-6">
                      <div class="form-group" >
                        <label>Date<span style="color:#b12f1f;">*</span></label> 
                        <input style="padding: 10px;" type="text" name="pay_date" class="form-control book_date" value="{{{$acc_tran->pay_date or date('Y-m-d')}}}" required readonly>
                      </div>
                    </div>
                    @if(\Auth::user()->role_id == 2)
                      <div class="col-md-3 col-xs-6">
                        <label>Location <span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control country_book_supplier location" name="country" data-type="supplier_book">
                          @foreach(App\Country::getCountry(5) as $con)
                            <option value="{{$con->id}}" {{isset($_GET['eid']) && $acc_tran->country_id == $con->id ? 'selected' : ''  }}>{{$con->country_name}}</option>
                          @endforeach
                        </select>
                      </div>
                    @endif
                     <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Account Type<span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control account_type " name="account_type" data-type="account_name" required="" data-method-type="one-account_name" >
                          <option value="0">Choose Account Types</option>
                          <?php $getAccountType = App\AccountType::where('status', 1)->orderBy('account_name', 'ASC')->get(); ?>
                          @foreach($getAccountType as $key => $acc)
                            <option value="{{$acc->id}}" {{isset($_GET['eid']) && $acc_tran->account_type_id == $acc->id ? 'selected':''}}>{{$acc->account_name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>   

                    <div class="col-md-4 col-xs-6">
                      <div class="btn-group form-group open" style='display: block;'>
                        <label>Account Name <span style="color:#b12f1f;">*</span></label> 
                          <button type="button" class="form-control arrow-down" data-toggle="dropdown" aria-haspopup="false" aria-expanded='false' data-backdrop="static" data-keyboard="false" role="dialog" data-backdrop="static" data-keyboard="false">
                            <span class="pull-left">
                              @if(isset($acc_tran->account_name))
                                {{{ $acc_tran->account_name->account_code or '' }}} - {{{ $acc_tran->account_name->account_name or '' }}}
                              @endif
                            </span>
                            <span class="pull-right"></span>
                          </button> 
                          <div class="obs-wrapper-search" style="max-height:250px; overflow: auto; ">
                            <div>
                              <input required="" type="text" data-url="{{route('getFilter')}}" id="search_Account" onkeyup="filterAccountName()" class="form-control input-sm">
                            </div>
                            <ul id="myAccountName" class="list-unstyled dropdown_account_name">
                              @if(isset($_GET['eid']) && !empty(isset($_GET['eid'])))
                                @foreach(\App\AccountName::where("account_type_id", $acc_tran->account_type_id)->orderBy('account_name', 'ASC')->get() as $acc_name)
                                  <li class='list' style=' padding: 4px 0px !important;'>
                                    <label style='position: relative;font-weight: 400; line-height:12px;'>
                                      <input type='radio' name='account_name' value="{{$acc_name->id}}" {{ $acc_name->id == $acc_tran->account_name_id ? 'checked' : ''}}> <span style='position:relative; top:-2px;'> {{ $acc_name->account_code}} - {{ $acc_name->account_name }}</span></label></li>
                                @endforeach
                              @endif
                            </ul>
                          </div>
                      </div>
                    </div>  
                    <div class="col-md-2 col-xs-6">
                      <div class="form-group">
                        <label>Enter Amount<span style="color:#b12f1f;">*</span></label> 
                        @if(isset($_GET['eid']))
                          <input type="text" name="amount" id="total_amount" class="form-control number_only text-center " placeholder="00.00" required="" value="{{ $acc_tran->total_amount > 0 ? $acc_tran->total_amount : $acc_tran->total_kamount}}">
                        @else
                          <input type="text" name="amount" id="total_amount" class="form-control number_only text-center " placeholder="00.00" required>
                        @endif
                      </div>
                    </div>           
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Currency <span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control" name="currency" id="currency">
                          @foreach(DB::table("tbl_currency")->where('status',1)->orderBy("id", "DESC")->get() as $crc)
                            <option value="{{$crc->id}}" {{isset($_GET['eid']) && $acc_tran->currency_id == $crc->id ? "selected" : ""}}>{{$crc->title}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>To Bank Account <span style="color:#b12f1f;">*</span></label> 
                        <div style="position: relative;">
                          @if(Auth::user()->role_id == 2)
                            <?php $getTBank = \App\Supplier::where(['supplier_status'=>1, 'business_id'=>5])->orderBy("supplier_name", "ASC")->get(); ?>
                          @else
                            <?php $getTBank = \App\Supplier::where(['supplier_status'=>1, 'business_id'=>5, 'country_id'=>Auth::user()->country_id])->orderBy("supplier_name", "ASC")->get(); ?>
                          @endif
                          <select class="form-control" name="supplier_id" id="supplier_id">
                            @foreach($getTBank as $sup_book)
                              <option value="{{$sup_book->id}}" {{isset($_GET['eid']) && $acc_tran->supplier_id == $sup_book->id ? "selected": ""}}>{{$sup_book->supplier_name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>                                             
                    <div class="col-md-12 col-xs-12">
                      <div class="form-group">
                        <label>Descriptions<span style="color:#b12f1f;">*</span></label> 
                        <textarea rows="5" type="text" name="memo" class="form-control">{{{$acc_tran->remark or ''}}}</textarea>
                      </div>
                    </div>
                    <div class="col-md-12 col-xs-12 text-center">
                      <div class="form-group">
                        <button type="submit" class=" btn btn-primary" id="btnComfirmTransfer">{{isset($_GET['edit']) ? "Update" : "Confirm"}}</button>
                      </div>
                    </div><div class="clearfix"></div>
                  </div>
                </div>
              </div>
            </form>
            <div class="col-md-10 col-md-offset-1" style="background: white;">
              <br>
              <table class="table table-hover table-borderd datatable">
                <thead>
                  <tr>
                    <th width="140px">Date</th>
                    <th>By User</th>
                    <th>Descriptions</th>
                    <th>To Account</th>
                    <th class="text-left">Total Balance</th>
                    <th width="12px" class="text-center">Status</th>
                  </tr>
                </thead>
                <tbody id="bank_transfer_datda datatable" >
                 @foreach($BalaceList as $key => $bl)
                  <?php 
                    $sup_bank = App\Supplier::find($bl->supplier_id);
                   ?>
                  <tr class="{{isset($_GET['eid']) && $_GET['eid'] == $bl->id ? 'active':''}}">
                    <td>{{Content::dateformat($bl->invoice_pay_date)}}</td>
                    <td>{{{ $bl->user->fullname or ''}}}</td>
                    <td>{!! $bl->remark !!}</td>
                    <td>{{{ $sup_bank->supplier_name or ''}}}</td>                  
                    <?php $currency = $bl->currency_id == 3 ? 0 : 1; ?>
                    <td>
                      @if($bl->total_amount > 0 || $bl->total_kamount > 0)
                        <a href="javascript:void(0)">
                          {{ $bl->total_amount > 0 ? Content::money($bl->total_amount) : Content::money($bl->total_kamount)}} {{Content::currency($currency)}}
                        </a>
                      @endif
                    </td>
                    <td><a href="{{route('openBalance', ['action'=> 'Edit', 'eid'=> $bl->id])}}"><i style="font-size: 18px" class="fa fa-edit"></i></a>
                      @if(Auth::user()->role_id == 2)  
                        <a href="javascript:void(0)" class="btnRemoveOption" data-type="acc_opening_balance" data-id="{{$bl->id}}" title="Remove this ?"> <i style="font-size: 18px; color: #b21c1c;"  class="fa fa-minus-circle"></i></a>
                      @endif
                    </td>

                  </tr>
                 @endforeach
                </tbody>
              </table>
              <br>
            </div>
            <div class="clearfix"></div>
        </section>
    </div>
</div>

<div class="modal" id="notification" role="dialog" data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-md">    
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Openning Balance</strong></h4>
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

<script type="text/javascript">
  $(document).ready(function(){
    $(".datatable").DataTable({
      language: {
        searchPlaceholder: "Type here..."
      }
    });
  });
</script>

@include('admin.include.datepicker')
@include("admin.account.accountant")
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

@endsection
