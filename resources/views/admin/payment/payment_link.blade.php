@extends('layout.backend')
@section('title', 'Payment Link')
<?php
  $active = 'finance'; 
  $subactive = 'finance/payment_getway';
  use App\component\Content;
?>

@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <div class="row">
        @include('admin.include.message')
        <section class="col-lg-12 connectedSortable">
            <h3 class="border">Payment Link <span class="fa fa-angle-double-right"></span> <a href="{{route('createPaymentLink')}}" class="btn btn-default btn-sm">Add New Payment</a></h3>
            <form action="" method="">
              <div class="col-sm-2 col-xs-6 pull-right" style="text-align: right; position: relative; z-index: 2;">
                <label class="sort">
                  <select class="form-control input-sm locationchange" name="sort">
                    <option value="">Sort</option>
                    <option value="paid" {{isset($_GET['sort']) && $_GET['sort'] == 'paid' ? 'selected':''}}>Paid</option>
                    <option value="unpaid" {{isset($_GET['sort']) && $_GET['sort'] == 'unpaid' ? 'selected':''}}>UnPaid</option>
                  </select>
                </label>
              </div>
            </form>
            <table class="datatable table table-hover table-striped">
              <thead>
                <tr>     
                  <th width="90px">Invoice #</th>                
                  <th>Client Name</th>
                  <th>Customer Name</th>
                  <th>User Created</th>
                  <th>Email</th>
                  <th>Invoice Date</th>
                  <th>Pay Date</th>
                  <th class="text-right">Amount {{Content::currency()}}</th>
                  <th width="100" class="text-center">Status</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($paymentlink as $key => $plk)
                  <tr>
                    <td><a target="_blank" href="{{route('getPaymentView', ['preview_inv'=> $plk->invoice_number])}}">#{{ $plk->invoice_number }}</a></td>
                    <td>{{{ $plk->project->project_prefix or ''}}}-{{{ $plk->project->project_fileno or ''}}} - {{{ $plk->project->project_client or ''}}}</td>
                    <td>{{ $plk->fullname }}</td>
                    <td>{{{ $plk->user->fullname or ''}}}</td>
                    <td>{{ $plk->email }}</td>
                    <td class="text-right">{{date("d M Y, H:s A", strtotime($plk->created_at))}}</td>
                    <td class="text-right">{{date("d M Y, H:s A", strtotime($plk->updated_at))}}</td>
                    <td class="text-right">{{Content::money($plk->amount)}}</td>                    
                    <td class="text-center">
                      @if($plk->payment_confirm == "paid")
                        <span class="label label-success block">Paid</span>
                      @else
                        <span class="label label-warning block">UnPaid</span>
                      @endif                     
                    </td>
                    <td class="text-right">
                      @if($plk->payment_confirm == "unpaid")
                        <a href="{{route('createPaymentLink', ['action'=> 'edit', 'eid'=>$plk->id ]) }}">
                          <label class="icon-list ic_edit"></label>
                        </a>
                        <span class="btnRemoveOption" data-type="payment_link" data-id="{{$plk->id}}" title="Remove this ?">
                          <label class="icon-list ic_remove"></label>
                        </span>
                      @else
                        <i class="fa fa-check-circle " style="color: #b8cac2;font-size: 25px;"></i>
                      @endif                     
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>                
        </section>
      </div>
    </section>
  </div>
</div> 
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@include("admin.account.accountant")
@endsection
