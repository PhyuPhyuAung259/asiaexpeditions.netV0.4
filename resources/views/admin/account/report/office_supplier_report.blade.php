@extends('layout.backend')
@section('title', 'Profit & Loss For ')
<?php 
	use App\component\Content;
	$comadd = \App\Company::find(1);
	$amount = "Amount";
	$invoice = "Invoice";
?>
@section('content')
<div class="container">
	@include('admin.report.headerReport')
	<h3 class="text-left"><strong style="font-size: 18px; text-transform:capitalize;">Profit & Loss For <b>{{{$supplier->supplier_name or ''}}}</b></strong></h3>
	<div class="pull-left">
		<div><p>Contact Name: <b>{{ $supplier->supplier_contact_name }}</b></p></div>
		<div><p>Phone: <b>{{ $supplier->supplier_phone }}</b>, &nbsp; Email: <b>{{$supplier->supplier_email}}</b></p></div>
	</div>
    <form method="GET" action="" class="hidden-print">      
        <div class="form-group">
        	<div class="col-md-5">
        		<input type="hidden" name="office_supplier_report" class="form-control input-sm text-center" value="{{{$_GET['office_supplier_report'] or ''}}}" placeholder="Ex-Rate 0.0">
	            <input type="text" name="exrate" class="form-control input-sm text-center number_only" value="{{{$_GET['exrate'] or ''}}}" placeholder="Ex-Rate 0.0">
            </div>		           
            <div class="col-md-2" style="padding-left: 0px;">
              	<button type="submit" class="btn btn-primary btn-sm btn-flat">Make Change</button>
            </div>
        </div>
    </form>
	<table class="table">
		<tr>
			<th width="120px" style="border-top: none;border-bottom: 1px solid #ddd;">Date</th>
			<th width="230px" style="border-top: none;border-bottom: 1px solid #ddd;"><b>Suppliers Entry</b></th>
			<th width="130px" class="text-left" style="border-top: none;border-bottom: 1px solid #ddd;">{{$invoice}} Date</th>
			<th class="text-left" width="400px" style="border-top: none;border-bottom: 1px solid #ddd;">Descriptions</th>
			<th class="text-right" width="190px" style="border-top: none;border-bottom: 1px solid #ddd;">Paid {{$amount}} </th>
			<th class="text-right" width="210px" style="border-top: none;border-bottom: 1px solid #ddd;">Paid {{$amount}} {{Content::currency(1)}}</th>
		</tr>
		<tbody>
			<?php 
				$usdBalance = 0;
				$kyatBalance = 0;
			?>
			@foreach($OfficeSupList as $key => $acc)
				<?php 
					$usdBalance = $usdBalance + $acc->credit;
					$kyatBalance = $kyatBalance + $acc->kcredit;
				?>
				<tr>
					<td>{{Content::dateformat($acc->pay_date)}}</td>
					<td>{{{$acc->supplier->supplier_name or ''}}}</td>
					<td>{{Content::dateformat($acc->invoice_pay_date)}}</td>
					<td>{{$acc->remark}}</td>						
					<td class="text-right">{{Content::money($acc->credit)}}</td>
					<td class="text-right">{{Content::money($acc->kcredit)}}</td>
				</tr> 
			@endforeach
			@if($OfficeSupList->count() > 0)
				<tr>
					<th colspan="4"></th>
					<th class="text-right">Sub Total: {{Content::currency(0)}} {{Content::money($usdBalance)}}</th>
					<th class="text-right">Sub Total: {{Content::currency(1)}} {{Content::money($kyatBalance)}}</th>
					<?php 
						if (isset($_GET['exrate']) && !empty($_GET['exrate'])) {
							$exrate = $_GET['exrate'];
						}else{
							$exrate = 1;
						}
						$exBalance = $kyatBalance / $exrate;
					?>
				</tr>
				<tr>
					<th colspan="6" class="text-right">Grand Total: {{Content::money($exBalance + $usdBalance)}}</th>				
				</tr>
			@endif
		</tbody>
	</table>
</div>
<br><br><br> 
@endsection
