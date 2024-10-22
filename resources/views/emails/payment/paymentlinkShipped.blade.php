<!DOCTYPE html>
<html>
<head>
	<?php 
	$comadd = App\Company::find(1); ?>
	<title>Payment Getway - {{$comadd->title}}</title>
	<link rel="shortcut icon" type="image/x-icon" href="{{url('storage/avata')}}/{{$comadd->logo}}">
</head>
	<body>
		<div style="background-color:#e5e5e5;"><br>
			<div style="margin:2%"><div style="direction:ltr;text-align:left;font-family:'Open sans','Arial',sans-serif;color:#444;background-color:white;padding:1.5em;border-radius:1em;max-width:580px;margin:2% auto 0 auto">
				<table style="background:white;width:100%">
					<tbody>
						<tr> 
							<td>
								<div style="width:90px;height:54px;margin:10px auto">
									<img src="{{url('storage/avata/1532076657_logopng.png')}}" alt="Google" width="87" height="80" class="CToWUd">
								</div>			
								<br>		
								<div style="width:100%;padding-bottom:10px;padding-left:15px">
									<p>
										<p style="font-family:'Open sans','Arial',sans-serif;line-height:1.4em">Dear <b>{{{$name or 'Client Name'}}}</b>, <br><br>
										Thank you very much for using our service and here we are pleased to send you PAYMENT LINK that you can pay through your credit card.

									Please, review the informatin below before clicking Payment Process. When you click Pay Now you will be sent to our secure Payment Getway at Canadia Bank Plc, Phnom Penh, Cambodia</p>
										
									</p>
								</div>
							
								
								@if(isset($payment_type) && $payment_type == "unpaid")
									<div style="padding-left:15px">
										<p style="line-height:1.4em"><b>Payment for:</b> <br>
											{!! $desc !!}
										</p>
										<p style="line-height:1.4em font-size:12px;">
										 <span style="font-style: italic;">The final amount may vary with your main invoice due to service charges. if you doubt anything, please do not hesitate to contact us.</span> <br><br>
										This payment link will valid for 72 hours and your prompt payment is highly appreciated. All confirmed services will only be guaranteed upon receiving your payment, unless there is prior agreement in writing. <br><br>
										Thank you very much. <br><br>
									Asia Expeditions DMC.</p>
									</div><br>
									<div style="padding-left:15px; text-align:center;">
										<a href="{{route('getPaymentView', ['inv_number'=> $inv_number])}}"  class="btn btn-info" style="display: inline;padding: 1.2em 0.6em;font-weight: 700;line-height: 1;color: #fff;	    background-color: #00a65a !important;border-radius: .25em;text-decoration: none;">Payment Process</a>
									</div>
								@else

									<div style="padding-left:15px">
										<p style="line-height:1.4em">
											Your payment transaction successfully completed 
										</p>
									</div>
									<div style="padding-left:15px; text-align:center;">
										<a href="{{route('getPaymentView', ['inv_number'=> $inv_number])}}"  class="btn btn-info" style="display: inline;padding: 1.2em 0.6em;font-weight: 700;line-height: 1;color: #fff;	    background-color: #00a65a !important;border-radius: .25em;text-decoration: none;">View Invoice</a>
									</div>
									<br>
								@endif
								<br>
								<br>					
							</td>
						</tr>
					</tbody>
				</table>

			</div>
		<br>
		</div>
	</body>
</html>