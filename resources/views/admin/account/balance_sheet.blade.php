@extends('layout.backend')
@section('title', 'Balance Sheet')
<?php 
	use App\component\Content;
	$comadd = \App\Company::find(1);
	$amount = "Amount";
	$invoice = "Invoice";
?>
@section('content')
<div class="container">
	@include('admin.report.headerReport')		
	<div class="col-md-12 text-center">
		<div class="row text-center">
			<h2>Balance Sheet</h2>
			<p>{{Content::dateformat($start_date) }} to {{ Content::dateformat($end_date) }}</p>
		</div>
	</div>
	<div class="col-md-8 col-md-offset-2 hidden-print" style="background-color: #e8f1ff;border: 1px solid #c1c1c1;padding-top: 15px;border-radius: 5px;">
	    <form method="GET" action="" class="form-group">      
	    	@if(\Auth::user()->role_id == 2)
		        <div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
		          	<div class="form-group">
			            <select class="form-control" name="country" >
			              <option value="">Location</option>
			              @foreach(App\Country::LocalPayment() as $con)
			                <option value="{{$con->id}}" {{$conId == $con->id ? 'selected' : '' }}>{{$con->country_name}}</option>
			              @endforeach
			            </select>
		            </div>
		        </div>
	        @endif
	        <div class="col-md-7 col-sm-7 col-lg-7 col-xs-7">
	        	<div class="form-group ">
		          	<div class="input-group">
			  			<input type="text" name="start_date" class="form-control text-center" id="from_date" value="{{{$start_date or '' }}}" readonly>
					  	<span class="input-group-addon" id="basic-addon3">From To</span>
					  	<input type="text" name="end_date" class="form-control text-center" id="to_date" value="{{{$end_date or ''}}}" readonly>
					</div>
				</div>
	        </div>
	        <div class="col-md-1 col-sm-1 col-lg-1 col-xs-1">
	        	<div class="form-group">
		          	<button type="submit" class="btn btn-primary">Search</button>
		        </div>
	        </div>           
	    </form>
	    <div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
	<div class="pull-right hidden-print">
	   	<a href="javascript:void(0)" class="myConvert hidden-print"><span class=" btn btn-primary btn-acc btn-xs"><i class="fa fa-download"></i> Download In Excel</span></a>&nbsp;
	    <span onclick="window.print()" class="hidden-print btn btn-xs btn-primary btn-acc"><i class="fa fa-print"></i> Print</span>
	</div>
	<div class="clearfix"></div><br>
	<table class="table tableExcel table-hover table_account_statement"  >
  		@foreach($journals as $key => $jour)
  			<?php 
			  	$transactions = App\AccountTransaction::accNameByAccType($conId, $start_date, $end_date, $jour->account_type_id);
			  	$totalRevenue = 0;
			  	$totalCostOfSale = 0;
	  		?>
	  		@if($transactions->count() > 0)
	  			<tr class="{{$jour->slug}}_head">
		  			<th colspan="2" style="text-transform: uppercase; border-top: none; border-bottom: solid 1px #ddd; text-align: left;"><font data-acc_type="{{$jour->slug}}" style="cursor: pointer;" class="open" color="#07C">  {{$jour->account_name}}</font></th>
	  			</tr>		  		
		  		@foreach($transactions as $kay => $tran)
			  		<?php 
						$reVenue = App\AccountTransaction::where(['status'=>1, 'account_type_id'=>$jour->account_type_id, 'account_name_id'=>$tran->account_name_id, 'country_id'=>$conId])
								->whereBetween('invoice_pay_date', [$start_date, $end_date])
								->whereIn('account_type_id', [8,9]);

						$CostOfSale = App\AccountTransaction::where(['status'=>1, 'account_type_id'=>$jour->account_type_id, 'account_name_id'=>$tran->account_name_id, 'country_id'=>$conId])
								->whereBetween('invoice_pay_date', [$start_date, $end_date])
								->whereNotIn('account_type_id', [8,9]);
			  		 ?>
		  			<tr class="{{$jour->slug}}">
			  			<td>&nbsp;&nbsp;&nbsp;{{$tran->account_code}}-{{$tran->account_name}}</td>
			  			<td class="text-right">
			  				@if(in_array($jour->account_type_id, [8,9]))
			  					<?php $reVenureAmt = ($reVenue->sum('total_amount') + $reVenue->sum('ex_rate_converted'))?>
			  					{{number_format($reVenureAmt, 2)}}
			  					<?php $totalRevenue = $totalRevenue + $reVenureAmt; ?>
			  				@else
			  					<?php $cosOfSaleAmt = ($CostOfSale->sum('total_amount') + $CostOfSale->sum('ex_rate_converted'))?>
			  					{{number_format($cosOfSaleAmt, 2)}}
								<?php $totalCostOfSale = $totalCostOfSale + $cosOfSaleAmt; ?>			  					
			  				@endif
			  			</td>
		  			</tr>
		  		@endforeach
		  		
		  		<tr><th colspan="2" class="text-right" style="color: #07C;">
			  			@if(in_array($jour->account_type_id, [8,9]))
			  				Total {{$jour->account_name}}: {{number_format($totalRevenue,2 )}}
			  			@else
			  				Total {{$jour->account_name}}: {{number_format($totalCostOfSale,2 )}}
			  			@endif
			  		</th>
			  	</tr>
	  		@endif
  		@endforeach
  		
	</table>
</div>
<br><br>
<script type="text/javascript">
  	$(document).ready(function(){
      	$(".myConvert").click(function(){
          if(confirm('Do you to export in excel?')){
            $(".tableExcel").table2excel({
              exclude: ".noExl",
              name: "Profit & Loss",
              filename: "Daily Cash Between {{Content::dateformat($start_date)}} And {{Content::dateformat($end_date)}} ",
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

      	$(".open").on("click", function(){
      		var className = $(this).data('acc_type');
	      	var no_of_room = $("tr."+ className);
      		if ($(this).hasClass("active")) {
	      		$(this).removeClass('active');
	      		$(no_of_room).removeClass('active');
	      	}else{
				$(this).addClass('active');
				$(no_of_room).addClass('active');
	      	}
      	});
  	});
</script>
<script type="text/javascript" src="{{asset('js/jquery.table2excel.min.js')}}"></script>

@include('admin.include.datepicker')
@endsection
