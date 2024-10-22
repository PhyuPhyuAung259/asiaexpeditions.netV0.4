
@extends('layout.backend')
@section('title', 'Cash Book ')
<?php 
  use App\component\Content;
  $comadd = \App\Company::find(1);
?>

@section('content')
<div class="col-md-12">
  <div class="col-md-12">
  @include('admin.report.headerReport') 
  <p class="pull-left"><a href="{{Route('getCashBook')}}"><i class="fa fa-arrow-circle-o-left"></i> Back to Aged Report</a></p> <div class="clearfix"></div>
  </div>
  <div class="col-md-3">
    <div class="col-md-12">
      <h4 ><strong  style=" text-transform:capitalize;">Daily Cash On <b>{{Content::dateformat($from_date)}}</b></strong></h4>
    </div>
  </div>

  <div class="col-md-7">
      <form method="GET" action="" class="hidden-print">      
        @if(\Auth::user()->role_id == 2)
          <div class="col-md-3">
            <div class="form-group">
              <select class="form-control input-sm" name="country" data-type="sup_by_bus">
                <option value="">Choose Location</option>
                @foreach(App\Country::LocalPayment() as $con)
                  <option value="{{$con->id}}" {{isset($_GET['country']) && $_GET['country']  == $con->id ? 'selected' : ''  }}>{{$con->country_name}}</option>
                @endforeach
              </select>
            </div>
          </div>
        @endif
        <div class="col-md-4">
          <div class="form-group">
            <input type="text" name="date_from" class="form-control input-sm" id="from_date" value="{{{$_GET['date_from'] or ''}}}" placeholder="From: 2019-09-27">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <input type="text" name="date_to" class="form-control input-sm" id="to_date" value="{{{$_GET['date_to'] or ''}}}" placeholder="To: 2019-10-27">
          </div>
        </div>
        <div class="col-md-1">
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-sm btn-flat">Search</button>
          </div>
        </div>           
      </form>
  </div>
  <div class="pull-right">
    <a href="javascript:void(0)" class="myConvert"><span class=" btn btn-default btn-sm"><i class="fa fa-download"></i> Download In Excel</span></a>&nbsp;
  </div>
  <br>
  <table class="tableExcel table">
    <tr style="background-color: #3c8dbc0a;">
        <th width="80px" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Date </b></th>  
        <th class="text-left" style="width:160px; border-top: none;border-bottom: 1px solid #ddd;">Descriptions</th>    
        <th width="80px" style="border-top:none; border-bottom: 1px solid #ddd;"><b>INV Number</b></th>  
        <th style="max-width:320px; border-top: none;border-bottom: 1px solid #ddd;"><b>Suppliers </b></th>
        <th style="border-top: none; border-bottom: 1px solid #ddd;"><b>P/R Voucher</b></th>  
        <th  class="text-right" style="border-top: none;border-bottom:1px solid #ddd;">RCVD Amount</th>
        <th  class="text-right" style="border-top: none;border-bottom:1px solid #ddd;">Paid Amount</th>
        <th  class="text-right" style="border-top: none;border-bottom:1px solid #ddd;">Balance {{Content::currency()}}</th>
        <th  class="text-right" style="border-top: none;border-bottom:1px solid #ddd;">RCVD Amount {{Content::currency(1)}}</th>
        <th  class="text-right" style="border-top: none;border-bottom: 1px solid #ddd;">Paid Amount {{Content::currency(1)}}</th>
        <th  class="text-right" style="border-top: none;border-bottom: 1px solid #ddd;">Balance {{Content::currency(1)}}</th>
        <th width="70px" style="border-top: none;border-bottom: 1px solid #ddd;" class="text-right"><b>File No. </b></th>  
        <th style="border-top: none;border-bottom: 1px solid #ddd;">Action</th>
    </tr>
    @if($accTransaction->count() > 0)
      <?php
        $UsdBalanace = 0;
        $KyatBalanace = 0;
        $getBalanceUsd = 0;
        $getBalanceKyat = 0;
      ?>
      @foreach($accTransaction as $acc_rp)
        <?php 
          $project=App\Project::where("project_number", $acc_rp->project_number)->first();
          $UsdBalanace =  ($acc_rp->debit - $acc_rp->credit);
          $KyatBalanace = ($acc_rp->kdebit - $acc_rp->kcredit);
          $getBalanceUsd = $getBalanceUsd + $UsdBalanace;
          $getBalanceKyat = ($getBalanceKyat + $KyatBalanace);
          $supplier = App\Supplier::find($acc_rp->supplier_id);
          $supName=isset($supplier->supplier_name)? $supplier->supplier_name: '';
          if ($acc_rp->business_id == 55) {
            $supName=isset($acc_rp->ent_service->name)? $acc_rp->ent_service->name :'';
          }
          if ($acc_rp->business_id == 54) {
            $supName=isset($acc_rp->misc_service->name)?$acc_rp->misc_service->name :'';
          }
        ?>
        <tr>
          <td>{{Content::dateformat($acc_rp->invoice_pay_date)}}</td>
          <td>{!!$acc_rp->remark!!}</td>
          <td>{{ $acc_rp->invoice_number }}</td>
          <td>{{{ $supName or '' }}}</td>
          <td>{{$acc_rp->pay_voucher}}</td>          
          <td class="text-right">{{ Content::money($acc_rp->debit)}}</td>
          <td class="text-right">{{ Content::money($acc_rp->credit)}}</td>
          <td class="text-right">
            {!! $acc_rp->credit > 0 || $acc_rp->debit > 0 ? $UsdBalanace >= 0 ? "<b style='color:#3c8dbc'>".Content::money($UsdBalanace)."</b>" : "<b style='color:#ee3721;'>". number_format($UsdBalanace,2)."</b>" :'' !!}</td>
          <td class="text-right">{{ Content::money($acc_rp->kdebit)}}</td>
          <td class="text-right">{{ Content::money($acc_rp->kcredit)}}</td>
          <td class="text-right">{!! $acc_rp->kdebit > 0 || $acc_rp->kcredit > 0 ? $KyatBalanace >= 0 ? "<b style='color:#3c8dbc'>".Content::money($KyatBalanace)."</b>" : "<b style='color:#ee3721;'>". number_format($KyatBalanace,2)."</b>" : '' !!}</td>
          <td class="text-right">{{{ $project->project_prefix or ''}}} - {{{ $project->project_fileno or ''}}}</td>
          <td class="text-right">
            <a class="btnRemoveOption" data-type="acc_cash_book" data-id="{{$acc_rp->id}}" title="Remove this ?">
              <label class="icon-list ic_remove"></label>
            </a>
          </td>
        </tr>
      @endforeach   
        <tr style="background: #3c8dbc0a;">
          <th colspan="13" class="text-right">
            Total Balance: {{Content::currency()}} {!! $getBalanceUsd >= 0 ? "<b style='color:#3c8dbc'>".Content::money($getBalanceUsd)."</b>" : "<b style='color:#ee3721;'>". number_format($getBalanceUsd,2)."</b>" !!}, 
            Total Balance: {{Content::currency(1)}} {!! $getBalanceKyat >= 0 ? "<b style='color:#3c8dbc'>".Content::money($getBalanceKyat)."</b>" : "<b style='color:#ee3721;'>". number_format($getBalanceKyat,2)."</b>" !!}
          </th>
        </tr> 
     @else 

     <tr><td colspan="10" class="text-center" style="color: #999;"> Result Not Found !</td></tr>
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
            name: "Daily Cash On {{Content::dateformat($from_date)}}",
            filename: "Daily Cash On {{Content::dateformat($from_date)}}",
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

    $(function() {
        $('.lazy').lazy();
    });
</script>
<script type="text/javascript" src="{{asset('js/jquery.table2excel.min.js')}}"></script>

@include('admin.include.datepicker')
@include("admin.account.accountant")
@endsection
