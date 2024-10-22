@extends('layout.backend')
<?php $invoice_number = isset($pay_link->invoice_number) ? $pay_link->invoice_number: ''; ?>
@section('title', 'INVOICE#'. $invoice_number)
<?php 
	use App\component\Content;
	$comadd = \App\Company::find(1);
	$logo =  Storage::url("avata/".$comadd->logo);
?>
@section('content')
<style type="text/css" >
  @media print {
    .title-company{
      color: #0d6b1e !important;  
    }
    .title{
      color: #4b98dc !important;
    }
    .text-title{
    	color: #E91E63 !important;
    }
    .paid{
    	font-size: 3em !important;
    	padding: 5px !important;
    	color: #f57e00 !important;
    	font-weight: 700 !important;
    }    
  }

  img.selected{
  	border: 2px solid #1061a3;
  }
  	.pay-card{
  		border: 2px solid #aaaaaa80;
    	display: inline-block;
	    margin: 7px auto 7px auto;
	    border-radius: 6px;
	    cursor: pointer;
	    padding: 11px;
	    box-shadow: 0px 1px 3px #DDD;
	    background: #fff;
	    color: #000;
    }
</style>
<div class="container">
	<div class="col-md-10 col-md-offset-1" style="border: 2px solid #e3e3e3; border-radius:5px; ">
		@if(isset($pay_link->payment_confirm) && $pay_link->payment_confirm == "paid")
			<div class="text-right row">
				<strong class="paid" style="background-color: #ade810a6;font-size: 3em;padding: 5px;color: #f57e00;font-weight: 700;">
					#{{$pay_link->invoice_number}} Paid
				</strong> 
				<div style="padding: 0px 12px;">
					<br>{{date('d M Y', strtotime($pay_link->updated_at))}}<br>{{date('H:s A', strtotime($pay_link->updated_at))}}
				</div>
			</div>
		@else
			<div class="text-right row"><strong class="paid" style="background-color: #ade810a6;font-size: 3em;padding: 5px;color: #f57e00;font-weight: 700;">UnPaid</strong> 
			</div>
		@endif
		<center>
			<img src="{{$logo}}" style="width: 20%;">
			<font color="#0d6b1e"><h1 class="title-company">{{$comadd->title }} </h1><h3 class="title-company">Secure Payment Gateway</h3></font>
		</center><br>
		
		@if(isset($_GET['action']) && $_GET['action'] == "unpaid")
			<div class="alert alert-dismissible fade show" role="alert" style=" background-color: #fcf8e3;border-color: #faf2cc;color: #8a6d3b;padding: .75rem 1.25rem;margin-bottom: 1rem;border: 1px solid transparent;border-radius: .25rem;">
			  	<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: relative;top: -.75rem;right: -1.25rem;padding: .75rem 1.25rem;color: inherit;">
			    <span aria-hidden="true">×</span>
			  	</button>
			  	<strong style="text-transform: capitalize;">{{{$_GET['status'] or ''}}}:  </strong> {{{ $_GET['message'] or '' }}}
			</div>
		@endif
		
		<h4><b  class="title" style="font-size: 21px; color: #4b98dc; text-transform: capitalize;">Dear/For, {{{$pay_link->fullname or ''}}}</b></h4>
		<div>
			<p>Please, review the informatin below befor clicking <strong>Pay Now</strong>. When you click <strong>Pay Now</strong> you will be sent to our secure Payment Getway at Canadia Bank Plc, Phnom Penh, Cambodia. <span class="text-title" style="font-style: italic; color: red;">Currently, we can process Visa Card and Master Card only.</span></p>
		</div>
		<div><strong>Payment Details</strong></div>
		<form action="{{route('paymentSubmit')}}" method="post">
	    	{{csrf_field()}}
	    	<table class="table">
	    		<tr width="120px">
					<td>Invoice Number:</td>
					<th><strong>#{{{$pay_link->invoice_number or ''}}}</strong></th>
				</tr>
				<tr width="120px">
					<td>Details:</td>
					<td><p>{!! $pay_link->desc or '' !!}</p></td>
				</tr>
				<tr width="120px">
					<td>Amount:</td>
					<th><strong style="font-size: 28px;">{{Content::currency()}} {{isset($pay_link->amount) ?  Content::money($pay_link->amount) : ''}} </strong></th>
				</tr>
	            <tr class="shade hide">
	                <td align="right"><strong><em>Virtual Payment Client URL:&nbsp;</em></strong></td>
	                <td><input name="virtualPaymentClientURL" size="65" value="https://migs.mastercard.com.au/vpcpay" maxlength="250"/></td>
	            </tr>
	            <tr class="hide"><td colspan="2">&nbsp;<hr width="75%">&nbsp;</td></tr>
	            <tr class="title hide">
	                <td colspan="2" style="text-align: left"><p>&nbsp;<strong>Transaction Fields</strong></p></td>
	            </tr>
	    		<tr class="shade hide">
	    			<td align="right"><strong><em> VPC Version: </em></strong></td>
	    			<td><input name="vpc_Version" value="1" size="20" maxlength="8"/></td>
	    		</tr>
	            <tr class="hide">
	                <td style="text-align: right"><strong><em>Command:</em></strong></td>
	                <td style="text-align: left"><input name="vpc_Command" type="text" value="pay" id="vpc_Command" /></td>
	            </tr>
	    		 <tr class="shade hide">
	    			<td align="right"><strong><em>MerchantID: </em></strong></td>
	    			<td><input name="vpc_Merchant" value="ASIAEXP" size="20" maxlength="16"/></td>
	    		</tr>
	    		<tr class="hide">
	    			<td align="right"><strong><em>Merchant AccessCode: </em></strong></td>
	    			<td><input name="vpc_AccessCode" value="D103A32C" size="20" maxlength="8"/></td>
	    		</tr>
	            <tr class="shade hide">
	                <td style="text-align: right"><strong><em>Merchant Transaction Reference:</em></strong></td>
	                <td style="text-align: left"><input name="vpc_MerchTxnRef" value="{{$pay_link->invoice_number}}"type="text" id="vpc_MerchTxnRef" /></td>
	            </tr>
	            <tr class="hide hide">
	                <td style="text-align: right"><strong><em>OrderInfo:</em></strong></td>
	                <td style="text-align: left"><input name="vpc_OrderInfo" value="{{$pay_link->fullname}}" type="text" id="vpc_OrderInfo" /></td>
	            </tr>
	            <tr class="shade hide">
	                <td style="text-align: right"><strong><em>Purchase Amount:</em></strong></td>
	                <td style="text-align: left"><input name="vpc_Amount" value="{{intval(strval($pay_link->amount * 100) )}}" type="text" id="vpc_Amount" /></td>
	            </tr>
	            <tr class="hide">
	                <td style="text-align: right"><strong><em>Currency (optional field):</em></strong></td>
	                <td style="text-align: left"><input name="vpc_Currency" type="text" id="vpc_Currency" /></td>
	            </tr>
	            <tr class="shade hide">
	                <td style="text-align: right"><strong><em>TicketNo (optional field):</em></strong></td>
	                <td style="text-align: left"><input name="vpc_TicketNo" type="text" id="vpc_TicketNo" /></td>
	            </tr>
	    		<tr class="hide"> 
	                <td align="right"><strong><em>Receipt ReturnURL: </em></strong></td>
	                <td><input name="vpc_ReturnURL" size="65" value="{{route('paymentReturnData')}}" maxlength="250"/></td>
	            </tr>
	            <tr class="shade hide">
	                <td style="text-align: right"><strong><em>Gateway:</em></strong></td>
	                <td style="text-align: left">
	                    <select name="vpc_Gateway" id="vpc_Gateway">
	                		<option value="ssl">Auth-Purchase with 3DS Authentication</option>
	                		<option value="threeDSecure">3DS Authentication Only</option>
	                	</select>
	            	</td>                       
	            </tr>

	            <tr class="hide hide"><td colspan="2">&nbsp;<hr width="75%"/>&nbsp;</td></tr>
	            <tr class="title hide">
	                <td colspan="2" style="text-align: left"><p><strong>&nbsp;Optional Card Details (Requires External Payment Selection)</strong></p></td>
	            </tr>
	            <tr>
	                <td style="text-align: right"><strong><em></em></strong></td>
	                <td style="text-align: left">
	                	<div class="form-group ">
		                    <label>Payment Methods</label>
		                    <label class="radio-inline {{$pay_link->payment_confirm == 'paid' && $pay_link->card_type != 'MC' ? 'hide' : '' }}">
		                        <input class="vpc_card" style="margin-top: 13px; opacity: 0;" type="radio" name="vpc_card" value="Mastercard" {{$pay_link->card_type == 'MC' ? 'checked':''}}>
		                        <img src="{{url('img/master-card.png')}}" class="pay-card {{$pay_link->card_type == 'MC' ? 'selected':''}}">
		                    </label>
		                    <label class="radio-inline {{$pay_link->payment_confirm == 'paid' && $pay_link->card_type != 'VC' ? 'hide' : '' }}">
		                        <input class="vpc_card" style="margin-top: 13px; opacity: 0;"  type="radio" name="vpc_card" value="Visa" {{$pay_link->card_type == 'VC' ? 'checked':''}}> 
		                       	<img src="{{url('img/visa-card.png')}}" class="pay-card {{$pay_link->card_type == 'VC' ? 'selected':''}}">
		                    </label>
		                </div>
	                </td>  
	            </tr>
	            <tr class="shade hide">
	                <td style="text-align: right"><strong><em>Card Number:</em></strong></td>
	                <td style="text-align: left"><input name="vpc_CardNum" type="text" id="vpc_CardNum" /></td>
	            </tr>
	            <tr class="hide">
	                <td style="text-align: right"><strong><em>Card Expiry Date (YYMM):</em></strong></td>
	                <td style="text-align: left"><input name="vpc_CardExp" type="text" id="vpc_CardExp" /></td>
	            </tr>
	    		<tr>
                    <td colspan="2" class="text-center"> 
                    @if($pay_link->payment_confirm == "unpaid")
                    	<input type="submit" name="btnPay" value="Pay Now!" id="btnPay" class="btn btn-primary btn-flat hidden-print" />
                    @endif
                    </td>
                </tr>
	        </table>
	        @include("admin.payment.team_and_conditions")
		    @if($pay_link->payment_confirm == "unpaid")
		    	<label style="color: red;"><input type="checkbox" name="team_and_condition" required="" > Agree with team and conditions</label>
		    @endif

	    </form>
		<div class="text-right">
			<a class="hidden-print" href="javascript:void(0)" onclick="window.print();"><span class="btn btn-primary btn-xs"><i class="fa fa-print"></i></span></a>
		</div>
	</div>
</div>
<br><br>

<script type="text/javascript">
	$(document).ready(function(){
		$(".pay-card").on("click", function(){
			$(".pay-card").removeClass("selected");
			if ( !$(this).hasClass("selected") ) {
			   	$(this).addClass("selected");
			}
			else {
			   	$(".pay-card").removeClass("selected");
			}
		});
	});
</script>
@endsection
