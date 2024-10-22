
<?php 
use App\component\Content;
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="https://asia-expeditions.com/img/icon-ae.png">
	<title>Tour Request - Asia Expeditions </title>
</head>
<body style="background-color: #f2f2f2; #473a8f;  font-size: 14px !important;font-family: Arial,Tahoma,Bitstream Vera">
	<div class="wrapper" style="width: 698px;margin: auto; background: white; padding: 12px; border: solid 1px #e5e5ee; border-radius: 5px">
		<table class="table" style="width: 100%">
			<tbody>
				<tr>
					<td colspan="2" width="330" style="text-align: center;"><img src="https://asia-expeditions.com/img/ae-logo.png" style="width: 289px;">
						<hr style="border-color: #22d029;border: solid 2px #22d029;">
					</td>
				</tr>
				<tr>
					<td width="350">
						<img src="{{Content::urlImage($tour->tour_photo)}}" style="width: 100%">
					</td>
				</tr>
				<tr>
					<td valign="TOP">
						<ul style="list-style: none; padding-left: 0px">
							<li style="padding: 4px"><strong>Full Name</strong>: {{$data->name}}</li>
							<li style="padding: 4px"><strong>Nation</strong>: {{$data->country['country_name']}}</li>
							<li style="padding: 4px"><strong>Email</strong>: {{$data->email}}</li>
							<li style="padding: 4px"><strong>Phone</strong>: {{$data->phone}}</li>
							<li style="padding: 4px"><strong>Prefer Date:</strong> Start Date: {{Content::dateformat($data->start_date)}} -> End Date : {{Content::dateformat($data->end_date)}} </li>
							<li style="padding: 4px"><strong>Tour Name: </strong><a href="https://asia-expeditions.com/tour/{{$tour->slug}}">{{$tour->tour_name}}</a></li>
							<li style="padding: 4px"><div><strong>Additional Requests</strong></div>
								<p>{!! $data->message !!}</p>
							</li>

							<li style="padding: 4px"><div><strong>Program Details</strong></div>
								<p>{!! $tour->tour_desc !!}</p>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr style="border-color: #CDDC39;border: solid 1px #CDDC39;">
						<table class="table" style="width: 120px;margin: 0 auto;">
							<tr>
								<td><a target="_blank" href="https://www.facebook.com/AsiaExpeditionsDM"><img width="23" src="https://asia-expeditions.com/img/facebook.ico" /></a></td>
								<td><a target="_blank" href="https://www.instagram.com/asiaexpeditions/"><img width="23" src="https://asia-expeditions.com/img/68d99ba29cc8.png" /></a></td>
								<td><a target="_blank" href="https://twitter.com/AsiaExpeditions"><img width="30" src="https://asia-expeditions.com/img/twitter.png" /></a></td>
							</tr>
						</table>						
					</td>
				</tr>
			</tbody>
		</table>
	</div>

</body>
</html>