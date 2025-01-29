@extends('layout.backend')
@section('title', 'OutStanding')
<?php 

use App\Supplier;
  use App\component\Content;
  $comadd = \App\Company::find(1);
?>
@section('content')
<div class="container-fluid">
    <div class="container">
      @include('admin.report.headerReport') 
      <div style="background-color: #e8f1ff;border: 1px solid #c1c1c1; padding: 18px;​​ border-radius: 5px;">
        <form method="GET" action="">      
          <div class="col-md-2 col-xs-6">
            @if(\Auth::user()->role_id == 2)
              <div class="form-group">
                <select class="form-control country_account" name="country" data-type="supplier_by_account_transaction"  id="country">
                  <option>--Choose--</option>
                  @foreach(\App\Country::LocalPayment() as $con)
                    <option value="{{$con->id}}" {{$conId ==$con->id ? 'selected' : ''}}>{{$con->country_name}}</option>
                  @endforeach
                </select>
              </div>
            @else
              <?php $country = App\Country::find($conId); ?>
              <input type="hidden" class="country_account" value="{{ $conId }}">
              <input type="text" readonly class="form-control" value="{{$country['country_name']}}">
            @endif
          </div>
          <div class="col-md-2 col-xs-6">
            <div class="form-group">
              <select class="form-control country_book_supplier" data-type="supplier_by_account_transaction" id="business"  name="business" >
                <option value="0">--choose--</option>
                <?php $getBusiness= App\Business::where('status',1)->whereHas('accountTransaction', function($query) {
                  $query->where(['status'=>1]);
                })->orderBy('name')->get(); ?>
                @foreach($getBusiness as $key => $bn)
                    <option value="{{$bn->id}}">{{ $bn->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3 col-xs-12">
            <div class="form-group">
              <!-- <div class="btn-group" style='display: block;'>
                <button type="button" class="form-control arrow-down" data-toggle="dropdown" aria-haspopup="false" aria-expanded='false' data-backdrop="static" data-keyboard="false" role="dialog" data-backdrop="static" data-keyboard="false">
                  <span class="pull-left">{{{ $supplier->supplier_name or ''}}}</span>
                  <span class="pull-right"></span>
                </button> 
                <div class="obs-wrapper-search" style="max-height:250px; overflow: auto; ">
                  <div><input type="text" data-url="{{route('getFilter')}}"  id="search_Account" onkeyup="filterAccountName()" class="form-control input-sm"></div>
                  <ul id="myAccountName" class="list-unstyled dropdown_account_name">
                  <?php $getSupplier = App\AccountTransaction::supplierByAccountTransaction($supplier['business_id'], $conId); ?>
                      @foreach($getSupplier as $sup)
                        <li class='list'>
                          <label style='position: relative;top: 3px; font-weight: 400; line-height:12px;'> 
                          <input type='radio' name='supplier_name' value="{{$sup->id}}" {{ $supplier['id'] == $sup->id ? 'checked' : ''}}><span style='position:relative; top:-3px;'>{{$sup->supplier_name}}</span></label></li>
                      @endforeach              
                  </ul>
                </div>
              </div> -->
              <select class="form-control suppliers input-sm" name="supplier[]" id="supplier" >
                              <option value="">Select a supplier</option>
                            </select>  
            </div>
          </div>
          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <div class="input-group">
                <input type="text" name="start_date" class="form-control text-center" id="from_date" value="{{{$from_date or '' }}}" readonly>
                <span class="input-group-addon" id="basic-addon3">From To </span>
                <input type="text" name="end_date" class="form-control text-center" id="to_date" value="{{{$to_date or ''}}}" readonly>
              </div>
            </div>
          </div>
          <div class="col-md-1 text-center"><button type="submit" class="btn btn-primary btn-flat row">Search</button></div>
        </form>
        <div class="clearfix"></div>
      </div>
      <div class="clearfix"></div>
      <div class="pull-right">
          <!-- <button type="submit" class="btn btn-default btn-acc btn-xs hidden-print" onclick="window.print();"><i class="fa fa-print Print"></i> Print</button> -->
          <span class="btn btn-default btn-acc btn-xs hidden-print myConvert"><i class="fa fa-download"></i> Download</span>
        </div>
    </div>
    <br>
      
    <form target="_blank" action="{{route('getOutstanding')}}">
      @if($journals->count() > 0)
       <!--  <div class="pull-left">
          <button type="submit" class="btn btn-default btn-acc btn-xs hidden-print"><i class="fa  fa-eye"></i> Preview</button>
        </div> -->
        <div class="clearfix"></div><br>
        <h3 style="color: #3c8dbc;">Oustanding for {{$supplier->supplier_name or ''}}</h3>
        <table class="table tableExcel">
          
          <tr style="background-color: #e8f1ff; color: #3c8dbc;">
            
            <th width="50">
               <label class="container-CheckBox" style="margin-bottom: 0px;"> No.
                  <input type="checkbox" id="check_all" name="checkbox" >
                  <span class="checkmark hidden-print"></span>
                </label>
            </th>
            <th title="Posting Date" width="100">Posting Date</th>
            <th title="Travelling Date" width="200">Travelling Date</th>
            <th >Descriptions</th>
            <th width="75">FileNo.</th>
            <th>Client Name</th>   
            
            <th>Supplier</th>   
            @if($conId === 122)      
            <th class="text-right" width="85" title="Amount To Pay {{Content::currency(1)}}">ToPay {{Content::currency(1)}}</th> 
            <th class="text-right" width="85" title="Amount To Receive {{Content::currency(1)}}">ToReceive {{Content::currency(1)}}</th> 
            <th class="text-right">Deposit/Paid AP {{Content::currency(1)}} </th>
            <th class="text-right">Deposit/Paid RP {{Content::currency(1)}}</th>
            <th class="text-right" width="117" title="Balance to Pay/Receive">BL To AP/RP{{Content::currency(1)}}</th>
            @else
            <th class="text-right" title="Amount To Pay/Receive">To Pay</th> 
            <th class="text-right" title="Amount To Pay/Receive">To Receive</th> 
            <th class="text-right">Deposit/Paid AP </th>
            <th class="text-right">Deposit/Paid RP </th>
            <th class="text-right" width="112" title="Balance to Pay/Receive">BL To AP/RP</th>            
           @endif
            <!-- <th class="text-right" width="128">Paid From/To</th> -->
          </tr>
            <?php
              // $totalBalance = 0;
              // $totalPayBalance=0;
              // $totalKBalanceKyat = 0;
              // $totalKPayBalance=0;
              $toPayBalance=0;
              $toReceiveBalance=0;
              $paidBalance=0;
              $receivedBalance=0;
              $ktoPayBalance=0;
              $ktoReceiveBalance=0;
              $kpaidBalance=0;
              $kreceivedBalance=0;
              $n=0;
              $remark="";
              $tranAPBalance = 0;
              $tranRPBalance =0;
            ?>
          <tbody>
              @foreach($journals as $jn)  
                <?php
                    $transaction = App\AccountTransaction::where(['journal_id'=>$jn->id, 'status'=>1])->whereNotIn('supplier_id',[$jn->supplier_id])->get();
                   
                    $n++;
                    $transRemark= App\AccountTransaction::where(['journal_id'=>$jn->id, 'status'=>1])->whereNotIn('supplier_id',[$jn->supplier_id])->first();
                    $remark = $transRemark ? $transRemark->remark : null;  
                    $tranAPBalance = $jn->book_amount - $transaction->sum('credit');
                    $tranRPBalance = $jn->book_amount - $transaction->sum('debit');   
                    
                    //$project=App\Project::where('id',$jn->project_id)->first();
                //    dd($project->project_start);
                   // dd($tranAPBalance,$tranRPBalance);            
                ?> 
                @if($tranAPBalance !== 0.00 && $tranRPBalance!==0.00)
                <?php
                 $paidBalance = $paidBalance + $transaction->sum('credit');
                 $receivedBalance=$receivedBalance + $transaction->sum('debit');
                 $kpaidBalance = $paidBalance + $transaction->sum('kcredit');
                 $kreceivedBalance=$receivedBalance + $transaction->sum('kdebit'); 
                ?>
                <tr>
                  <td>
                    <label class="container-CheckBox" style="margin-bottom: 0px;"> {{$n}}
                      <input type="checkbox" class="checkall" name="journCheck[]" value="{{$jn->id}}">
                      <span class="checkmark hidden-print" ></span>
                    </label>
                  </td>
                   <td class="text-left">{{Content::dateformat($jn->entry_date)}}</td>
                   <td>
                     @if(isset($jn->project))
                      {{Content::dateformat($jn->project->project_start)}} - {{Content::dateformat($jn->project->project_end)}}
                    @endif
                  </td>
                  <td>{{ $remark or ''}}</td>
                  <td class="text-left">
                    <?php $project = ''; ?>
                      @if(isset($jn->project))
                        <?php $project = $jn->project['project_prefix']."-".$jn->project['project_fileno']; ?>
                        {{$project}}
                      @endif
                    </a>
                  </td>
                  <td class="text-left">
                    @if(isset($jn->project))
                      {{$jn->project['project_client']}} <i>x</i> <b>{{$jn->project['project_pax']}}</b>
                    @endif
                  </td>
                  <td>
                   <?php
                   $supplier=Supplier::where('id',$jn->supplier_id)->first();
                   $suppliername = $supplier ? $supplier->supplier_name : null;  
                   $toPayBalance=$toPayBalance + $jn->debit;
                   $toReceiveBalance=$toReceiveBalance +  $jn->credit;
                   $ktoPayBalance=$toPayBalance + $jn->kdebit;
                   $ktoReceiveBalance=$toReceiveBalance +  $jn->kcredit;   
                   ?>
                   {{$suppliername }}
                  </td>
                  @if($conId=== 122)
                    <td class="text-right" style="color:#3c8dbc;">{{Content::money($jn->kdebit)}}</td>  
                    <td class="text-right" style="color:#3c8dbc;">{{Content::money($jn->kcredit)}}</td>         
                    <td class="text-right" style="color:#3c8dbc;">
                      
                          <a href="#" data-toggle="modal" data-url="{{route('loadData')}}"​ data-supplier="{{$jn->supplier_id}}" data-id="{{$jn->id}}" class="PrevViewOutstanding" data-title="{{$project}}" data-target="#myModal">
                            <strong style="color:#8BC34A;">{{Content::money($transaction->sum('kcredit'))}}</strong>
                            <?php $tranBalace = $jn->book_amount - $transaction->sum('kcredit'); ?>
                          </a>
                    </td>
                    <td class="text-right" style="color:#3c8dbc;">
                          <a href="#" data-toggle="modal" class="PrevViewOutstanding" data-target="#myModal" data-url="{{route('loadData')}}" data-supplier="{{$jn->supplier_id}}" data-id="{{$jn->id}}" data-title="{{$project}}">
                          <?php $tranBalace = $jn->book_amount - $transaction->sum('kdebit'); ?>
                            <strong style="color:red;">
                              {{$transaction->sum('kdebit') > 0 ? '-'.number_format($transaction->sum('kdebit'), 2) : ''}}
                            </strong>
                          </a>
                    </td>
                    <td class="text-right" style="color:#3c8dbc;">{{Content::money($jn->book_kamount)}}</td>
                  @else
                    <td class="text-right" style="color:#3c8dbc;">{{Content::money($jn->debit)}}</td>  
                    <td class="text-right" style="color:#3c8dbc;">{{Content::money($jn->credit)}}</td>         
                    <td class="text-right" style="color:#3c8dbc;">
                      
                          <a href="#" data-toggle="modal" data-url="{{route('loadData')}}"​ data-supplier="{{$jn->supplier_id}}" data-id="{{$jn->id}}" class="PrevViewOutstanding" data-title="{{$project}}" data-target="#myModal">
                            <strong style="color:#8BC34A;">{{Content::money($transaction->sum('credit'))}}</strong>
                            <?php $tranBalace = $jn->book_amount - $transaction->sum('credit'); ?>
                          </a>
                    </td>
                    <td class="text-right" style="color:#3c8dbc;">
                          <a href="#" data-toggle="modal" class="PrevViewOutstanding" data-target="#myModal" data-url="{{route('loadData')}}" data-supplier="{{$jn->supplier_id}}" data-id="{{$jn->id}}" data-title="{{$project}}">
                          <?php $tranBalace = $jn->book_amount - $transaction->sum('debit'); ?>
                            <strong style="color:red;">
                              {{$transaction->sum('debit') > 0 ? '-'.number_format($transaction->sum('debit'), 2) : ''}}
                            </strong>
                          </a>
                        
                    </td>
                    <td class="text-right" style="color:#3c8dbc;font-weight:700;">{{number_format($tranBalace,2)}}</td>
                  @endif
                </tr>
                @endif
              @endforeach
          </tbody>
          <tfoot>
            <tr style="background-color: #e8f1ff; font-weight: 700; color: #3c8dbc;">
                 @if($conId===122)
                  <td colspan="8" class="text-right" >Total : {{$ktoPayBalance}}</td>
                  <td class="text-right" >{{$ktoReceiveBalance}}</td>
                  <td class="text-right" >{{$kpaidBalance}}</td>
                  <td class="text-right" >{{$kreceivedBalance}}</td>
                  <td></td>
                @else
                  <td colspan="8" class="text-right" >Total : {{$toPayBalance}}</td>
                  <td class="text-right" >{{$toReceiveBalance}}</td>
                  <td class="text-right" >{{$paidBalance}}</td>
                  <td class="text-right" >{{$receivedBalance}}</td>
                  <td></td>
                @endif
            </tr>
            <tr style="background-color: #e8f1ff; font-weight: 700; color: #3c8dbc;">
            @if($conId===122)
                  <td colspan="12" class="text-right" >Balance to AP : {{$ktoPayBalance - $kpaidBalance}}</td>
                  
                 
                @else
                <td colspan="12" class="text-right" >Balance to AP : {{$toPayBalance - $paidBalance}}</td>
               
                 
                @endif
            </tr>
            <tr style="background-color: #e8f1ff; font-weight: 700; color: #3c8dbc;">
            @if($conId===122)
                 
                  <td colspan="12" class="text-right" >Balance to RP :{{$ktoReceiveBalance - $kreceivedBalance}}</td>
                  
               
                @else
                
                <td colspan="12" class="text-right" >Balance to RP :{{$toReceiveBalance - $receivedBalance}}</td>
                 
                @endif
            </tr>
          </tfoot>
        </table>  
      @endif
    </form>
    <br>
  <!-- </div> -->
</div>


<div class="modal" id="myModal" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-lg" style="width: 1489px">    
    <form method="POST" action="{{route('addGolfService')}}">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <strong class="modal-title preview_title_journal">Preview Outstanding</strong>
        </div>
        <div class="modal-body"  style="padding: 0px;">
            <table class="table" id="preview_outstanding_jounal" border="1" cellpadding="1" cellspacing="0">
           
              <tr>
                  <th>#N.</th>
                  <th title="Invoice Paid Date">INV Paid Date</th>
                  <th>Descriptions</th>
                  <th>FileNo.</th>
                  <th>Client Name</th>            
                  <th class="text-right">Deposit/Paid {{Content::currency()}}</th>
                  <th class="text-right">Deposit/Paid {{Content::currency(1)}}</th>
                  <th class="text-right">Paid From/To</th>
              </tr>
              <tbody id="preview_outstanding_by_jounal">
                <!--   <tr>
                    <td>1</td>
                    <td>2019-01-21</td>
                    <td>Descriptions</td>
                    <td>AE-4444</td>
                    <td>Client Name</td>
                    <td>Asia Expeditions.com</td>
                    <td>500USD</td>
                    <td>300</td>
                    <td>155000 Kyat</td>
                    <td>400 kyat</td>
                    <td>20000</td>
                    <td>Cashbook Phnom Penh</td>
                  </tr> -->
              </tbody>
          </table>
         
        </div>
        <div class="modal-footer" style="text-align: center;">
          <a href="#" class="btn btn-acc btn-default btn-flat btn-sm" data-dismiss="modal">Close</a>
          <button type="button" class="btn btn-default btn-acc btn-xs hidden-print pull-right btnPrint" onclick='printDiv();'><i class="fa fa-print Print"></i> Print</button>
        </div>
      </div>      
    </form>
  </div>
</div>


<script type="text/javascript">

  function printDiv() 
      {
        var divToPrint=document.getElementById('preview_outstanding_jounal');

        var newWin=window.open('','Print-Window');

        newWin.document.open();

        // newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
        newWin.document.write('<html><head><title>Program Preview </title></head><body onload="window.print()">'+divToPrint.outerHTML+'</body></html>')
        newWin.document.close();

        setTimeout(function(){newWin.close();},10);

      }



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
<script type="text/javascript" src="{{asset('js/jquery.table2excel.min.js')}}"></script>
<script type="text/javascript">
  
  $(document).ready(function() {
            $('#business').change(function() {
                var bus_id = $(this).val();
                var country_id = $('#country').val(); 
                console.log(country_id);
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

  $(document).ready(function(){

    $(document).on("click", ".PrevViewOutstanding", function(){
      
        var journal_id = $(this).data('id'),
          supplier = $(this).data('supplier'),
          title = $(this).data('title'),
          dataUrl = $(this).data("url");
          $(".preview_title_journal").text("Preview By "+ title);
        $.ajax({
            method: "GET",
            url: dataUrl, 
            data: "id="+journal_id +"&supplier="+supplier +"&datatype=preview_journal",
            dataType: 'html',
            beforeSend: function() {
              $("#preview_outstanding_by_jounal").html('<tr><td colspan="8" class="text-center"><i style="padding: 27px;font-size: 43px;" class="fa fa-spinner fa-spin loading"></i></td></tr>');
            },
            success: function(html){
              // alert(html)
                // $(".data_filter", this).next().remove(); 
                $("#preview_outstanding_by_jounal").html(html);
            },
            error: function(xhr, status, error){
                alert("Error!" + xhr.status);
                return false;
            },
            complete: function() {
               // $("#preview_outstanding_by_jounal").html('<tr><td><i class="fa fa-spinner fa-spin loading"></i></td></tr>');
            }
        });
        return false;

    });


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
@include('admin.include.datepicker')
@include("admin.account.accountant")
@endsection