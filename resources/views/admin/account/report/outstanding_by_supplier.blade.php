
@extends('layout.backend')
@section('title', 'Preview Of ')
<?php 
  use App\component\Content;
  $comadd = \App\Company::find(1);
?>
@section('content')
<div class="container">
  @include('admin.report.headerReport') 
    <div class="row">
      <form method="GET" action="" class="hidden-print">
          <div class="col-md-3 col-xs-6">
              <div class="form-group">
                <select class="form-control location" name="country" data-type="sup_by_bus">
                  <option value="">Choose Location</option>
                  @foreach(App\Country::LocalPayment() as $con)
                    <option value="{{$con->id}}" {{isset($_GET['country']) && $_GET['country'] == $con->id ? 'selected' : ''  }}>{{$con->country_name}}</option>
                  @endforeach
                </select>
              </div>
          </div>          
          <div class="col-md-3 col-xs-6">
            <div class="form-group">
              <select class="form-control business_type_receive" name="business" data-type="sup_by_bus">
                <option value="">Choose Business</option>
                 
              </select>
            </div>
          </div>
          <div class="col-md-2 col-xs-6" id="supplier_Name">
            <div class="form-group">
              <select class="form-control  supplier-receivable" name="supplier" id="dropdown_receive_supplier" >
                <option value="">Choose Supplier</option>
                  <?php 
                  $bus_id = isset($_GET['business']) ? $_GET["business"]:''; ?>
                  @foreach(App\Supplier::SupByAccount($bus_id) as $sup)
                    <option value="{{$sup->id}}" {{isset($_GET['supplier']) && $_GET['supplier'] == $sup->id ? 'selected' : ''  }}>{{$sup->supplier_name}}</option>
                  @endforeach
              </select>
            </div>               
          </div>
          <div class="col-md-2 col-xs-6">
            <div class="form-group">
              <input type="submit"  value="Search" class="btn btn-default btn-acc">
            </div>               
          </div>
          
      </form>
    </cener>
    
    <form target="_blank" action="{{route('getOutstanding', ['outstanding' => 'print'])}}">
  
      <input type="submit" name="outstanding_print" value="Query" class="btn btn-primary btn-acc btn-sm">
      <input type="hidden" name="supplier" value="{{{ $_GET['supplier'] or ''}}}">
      <table class="table">
          <tr>
            <th width="10px" style="border-top: none;border-bottom: 1px solid #ddd;">#</th>
            <th width="70" style="border-top: none;border-bottom: 1px solid #ddd;"><b>File No.</b></th>  
            <th  width="200"  style="border-top: none;border-bottom: 1px solid #ddd;">Client Name</th>
            <th  width="120"  style="border-top: none;border-bottom: 1px solid #ddd;">Arrival Date</th>
            
            <th class="text-right" width="130" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Invoice  {{Content::currency()}}</b></th>   
            <th class="text-right" width="130" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Paid {{Content::currency()}}</b></th> 

            <th class="text-right" width="130" style="border-top: none;border-bottom: 1px solid #ddd;"><b>{{Content::currency()}} ToPay </b></th> 
            <th width="100px" style="border-top: none;border-bottom: 1px solid #ddd;">Paid Date</th>
            
            <th class="text-right" width="130" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Invoice  
            {{Content::currency(1)}}</b></th> 
            <th class="text-right" width="130" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Paid  {{Content::currency(1)}}

            <th class="text-right" width="130" style="border-top: none;border-bottom: 1px solid #ddd;"><b>{{Content::currency(1)}} ToPay </b></th> 
            <th class="text-right" width="130" style="border-top: none;border-bottom: 1px solid #ddd;">SUP-INV Date</th> 
          </tr>
          @if($outstanding->count() > 0 )
            @foreach($outstanding as $sup)
                <?php 
                  $project = App\Project::where(["project_number"=> $sup->project_number, "project_status"=>1])->first();
                  $accTranc = App\AccountTransaction::where(["journal_id"=> $sup->id]);
                ?>
              <tr>
                <td><input type="checkbox" name="checkOurstanding[]" value="{{$sup->id}}"></td>
                <td>{{{ $project->project_prefix or ''}}}-{{{ $project->project_fileno or ''}}}</td>
                <td>{{{ $project->project_client or ''}}}</td>
                <td>{{ Content::dateformat($project->project_start) }}</td>
                <td class="text-right">{{ Content::money($sup->debit) }}</td>
                <td class="text-right">{{ Content::money($accTranc->sum('credit')) }}</td>
                <td class="text-right">{{ Content::money( $sup->debit - $accTranc->sum('credit')) }}</td>
                <td>{{ isset($accTranc->first()->invoice_pay_date) ? Content::dateformat($accTranc->first()->invoice_pay_date):'' }}</td>
                <td class="text-right">{{ Content::money($sup->kdebit) }}</td>
                <td class="text-right">{{ Content::money($accTranc->sum('kcredit')) }}</td>
                <td class="text-right">{{ Content::money( $sup->kdebit - $accTranc->sum('kcredit')) }}</td>
                <td class="text-right">{{ isset($accTranc->first()->invoice_rc_date_from_sup) ? Content::dateformat($accTranc->first()->invoice_rc_date_from_sup):'' }}</td>
              </tr>
            @endforeach
          @endif
      </table>  
    </form>
    <br>
</div>



@include('admin.include.datepicker')
@include("admin.account.accountant")
@endsection
