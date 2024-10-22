
@extends('layout.backend')
@section('title', 'Preview Of ')
<?php 
  use App\component\Content;
  $comadd = \App\Company::find(1);
?>
@section('content')
<div class="container-fluid">
  @include('admin.report.headerReport')

    <h4 class="text-left pull-left" style="text-transform: capitalize;">Outstanding for <b>{{{ $supplier->supplier_name or '' }}} </b> As of {{date('M-d-Y')}}</h4>
    <div class="pull-right">
      <span onclick="window.print();" class="hidden-print btn btn-primary btn-xs"><i class="fa fa-print"></i></span>
    </div>
    <table class="table">
        <tr>
          <th width="10" style="border-top: none;border-bottom: 1px solid #ddd;">N.</th>
          <th width="80" style="border-top: none;border-bottom: 1px solid #ddd;"><b>File No.</b></th>  
          <th width="200" style="border-top: none;border-bottom: 1px solid #ddd;">Client Name</th>
          <th width="200" style="border-top: none;border-bottom: 1px solid #ddd;">Supplier Name</th>
          <th width="120" style="border-top: none;border-bottom: 1px solid #ddd;">Arrival Date</th>
          <th class="text-right" width="130" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Invoice {{Content::currency()}}</b></th>
          <th class="text-right" width="130" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Paid {{Content::currency()}}</b></th> 
          <th class="text-right" width="130" style="border-top: none;border-bottom: 1px solid #ddd;"><b>{{Content::currency()}} ToPay </b></th> 
          <th width="120" style="border-top: none;border-bottom: 1px solid #ddd;">Paid Date</th>
          <th class="text-right" width="130" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Invoice
          {{Content::currency(1)}}</b></th> 
          <th class="text-right" width="130" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Paid  {{Content::currency(1)}}
          <th class="text-right" width="130" style="border-top: none;border-bottom: 1px solid #ddd;"><b>{{Content::currency(1)}} ToPay </b></th>
          <th class="text-right" width="140" style="border-top: none;border-bottom: 1px solid #ddd;">SUP-INV Date</th> 
        </tr>
        @if($printoutstanding->count() > 0 )
          <?php $n= 1; 
            $totalAmount = 0;
            $totalAmountk = 0;
          ?>
          <tbody >
            @foreach($printoutstanding as $sup)
              <?php 
                $project = App\Project::where(["project_number"=> $sup->project_number, "project_status"=>1])->first();
                $accTranc = App\AccountTransaction::where(["journal_id"=> $sup->id]);
                $totalAmount = $totalAmount + $sup->debit - $accTranc->sum('credit');
                $totalAmountk = $totalAmountk + $sup->kdebit - $accTranc->sum('kcredit');

                $supplier = App\Supplier::find($sup->supplier_id);
                $supName=isset($supplier->supplier_name)? $supplier->supplier_name: '';
                if ($sup->business_id == 55) {
                  $supName=isset($sup->ent_service->name)? $sup->ent_service->name :'';
                }
                if ($sup->business_id == 54) {
                  $supName=isset($sup->misc_service->name)?$sup->misc_service->name :'';
                }
              ?>
              <tr>
                <td>{{$n++}}</td>
                <td>{{{ $project->project_prefix or ''}}}-{{{ $project->project_fileno or ''}}}</td>
                <td>{{{ $project->project_client or ''}}}</td>
                <td>{{$supName}}</td>
                <td>{{ isset($project->project_start) ? Content::dateformat($project->project_start) : '' }}</td>
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
          </tbody>
          <tr>
            <th colspan="13" class="text-right">
              Total ToPay: <font style="color:#3c8dbc;">{{Content::currency()}} {{Content::money($totalAmount)}}</font>, &nbsp;&nbsp;&nbsp;
              Total ToPay: <label style="color:#3c8dbc;">{{Content::currency(1)}} {{Content::money($totalAmountk)}}</label>
            </th>
          </tr>
        @endif         
    </table>  
</div>
<br>
@endsection
