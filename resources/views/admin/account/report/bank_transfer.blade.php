@extends('layout.backend')
@section('title', 'Bank Transfer')
<?php 
  $active = 'finance/journal'; 
  $subactive = 'finance/journal';
  use App\component\Content;
  $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
?>
@section('content')
  <style type="text/css">
  .table-head-row{
      /*background: linear-gradient(to bottom, #f4f4f4 0%,#eeeeee1a 50%);*/
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
  </style>
<div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">
      <section class="content"> 
         @include('admin.include.message')
          <h3 class="border text-center" style="color:black;text-transform: uppercase;font-weight:bold;"> Bank Transfer</h3>          
          <form method="POST" action="{{route('addBankTransfer')}}" id="bank_transfer_form">
            {{csrf_field()}}
            <div class="row">
              <div class="col-md-10 col-md-offset-1" style="margin-bottom: 20px">
                <div class="account-saction"><br>
                  <div class="col-md-4 col-xs-6">
                    <div class="form-group">
                      <label>Transfer Date<span style="color:#b12f1f;">*</span></label> 
                      <input type="text" name="pay_date" class="form-control" id="from_date" value="{{date('Y-m-d')}}" required="">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>From Bank<span style="color:#b12f1f;">*</span></label> 
                      <select class="form-control" name="bank_from" id="bank_from">
                        <option value="0">Choose Bank</option>
                        @foreach(App\Bank::orderBy("name", "ASC")->get() as $bk)
                            <?php
                              $accBalanceFrom = \App\AccountTransaction::where(['bank_id'=> $bk->id, 'status'=> 1]);
                              $accBalanceTo = \App\AccountTransaction::where(['bank_to'=> $bk->id, 'status'=> 1]);
                              $tdebit = $bk->id ? $accBalanceTo->sum('debit') :'';
                              $tcredit = $bk->id ? $accBalanceFrom->sum('credit') :'';
                              $tdebitk = $bk->id ? $accBalanceFrom->sum('kdebit') :'';
                              $tcreditk = $bk->id ? $accBalanceTo->sum('kcredit') :'';
                              $Balance = $accBalanceTo->sum('debit') - $accBalanceFrom->sum('credit');
                              $Balancek = $accBalanceTo->sum('kdebit') - $accBalanceFrom->sum('kcredit');
                            ?>                         
                            <option value="{{$bk->id}}" data-balance="{{ Content::money($Balance) }}" data-balancek="{{ Content::money($Balancek) }}">{{$bk->name}}: USD {{ Content::money($Balance) }} / MMk {{ Content::money($Balancek) }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>To Bank<span style="color:#b12f1f;">*</span></label> 
                      <select class="form-control" name="bank_to" id="bank_to">
                        <option value="0">Choose Bank</option>
                        @foreach(App\Bank::orderBy("name", "ASC")->get() as $bk)
                        <option value="{{$bk->id}}">{{$bk->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2 col-xs-6">
                    <div class="form-group">
                      <label>Bank charges<span style="color:#b12f1f;">*</span></label> 
                      <input type="text" name="bank_charges" class="form-control number_only text-right transfer_amount" placeholder="00.00">
                    </div>
                  </div>
                  <div class="col-md-2 col-xs-6">
                    <div class="form-group">
                      <label>Enter Amount<span style="color:#b12f1f;">*</span></label> 
                      <input type="text" name="amount" class="form-control number_only text-left transfer_amount" placeholder="00.00" required="">
                    </div>
                  </div>   
                  <div class="col-md-2 col-xs-6">
                    <div class="form-group">
                      <label>Exchange Rate<span style="color:#b12f1f;">*</span></label> 
                      <input type="text" name="ex_rate" class="form-control number_only text-left" placeholder="00.00" required="">
                    </div>
                  </div>                               
                  <div class="col-md-2 col-xs-6">
                    <div class="form-group">
                      <label>Currency <span style="color:#b12f1f;">*</span></label> 
                      <select class="form-control" name="currency_from">
                        @foreach(DB::table("tbl_currency")->where('status',1)->get() as $crc)
                          <option value="{{$crc->id}}">{{$crc->title}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2 col-xs-6">
                    <div class="form-group">
                      <label>Currency <span style="color:#b12f1f;">*</span></label> 
                      <select class="form-control" name="currency_to">
                        @foreach(DB::table("tbl_currency")->where('status',1)->get() as $crc)
                          <option value="{{$crc->id}}">{{$crc->title}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                      <label>Descriptions<span style="color:#b12f1f;">*</span></label> 
                      <textarea rows="5" type="text" name="memo" class="form-control"></textarea>
                    </div>
                  </div>
                  <div class="col-md-12 col-xs-12 text-center">
                    <div class="form-group">
                      <button type="submit" class=" btn btn-primary" id="btnComfirmTransfer">Comfirm Transfer</button>
                    </div>
                  </div><div class="clearfix"></div>
                </div>
              </div>
            </div>
          </form>

          <div class="col-md-10 col-md-offset-1">
            <div class="row">
              <table class="table table-striped table-borderd datatable">
                <thead>
                  <tr>
                    <th width="140px">Transferred Date</th>
                    <th>From Bank</th>
                    <th>To Bank</th>
                    <th class="text-right">Transfer Amount</th>
                    <th width="12px" class="text-center">Status</th>
                  </tr>
                </thead>
                <tbody id="bank_transfer_datda">
                   @foreach($getbTransfer as $key => $bn)
                    <tr>
                      <td>{{date("M d, Y", strtotime($bn->pay_date))}} | <span style="color:#1a6bb3; font-weight:700;">{{date("H:i:s", strtotime($bk->pay_date))}}</span></td> 
                      <td>
                        <a target="_blank" href="{{route('getBankPreview', ['preview_bank' => $bn->bank_id])}}">{{{ $bn->bank->name or ''}}}</a></td>
                      <td><a target="_blank" href="{{route('getBankPreview', ['preview_bank' => $bn->bank_to])}}">{{{ $bn->bank2->name or ''}}}</a></td> 
                      <td class="text-right">
                        <a href="#" data-toggle="modal" class="entry_code_view" data-target="#myModal" data-entry_code="{{$bn->entry_cod}}">{{ Content::money($bn->credit) ? Content::money($bn->credit) : Content::money($bn->Kcredit) }}</a>
                      </td> 
                      <td class="text-center">
                        <i style="color:#4ad44a; font-size:18px;" class="fa fa-check-circle"></i>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              {{$getbTransfer->links()}}
            </div>
          </div>
          <div class="clearfix"></div>
      </section>
    </div>
</div>
<div class="modal" id="myModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md">    
    <form method="POST" action="{{route('addNewAccount')}}" id="add_new_account_form">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>View Bank Transferred Details</strong></h4>
        </div>
        <div class="modal-body" id="body_message_bank_transfer">
          <strong>Loading ...</strong>        
        </div>
        <div class="modal-footer">
          <div class="text-center">
            <!-- <a href="#" class="btn btn-danger btn-xs" data-dismiss="modal">OK</a> -->
          </div>
        </div>    
      </div>  
    </form>
  </div>
</div>
@include('admin.include.datepicker')
@include("admin.account.accountant")

@endsection
