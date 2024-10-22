
<div style="width: 689px; margin:0 auto">
<table style="width:100%;text-align:center">
	<tbody>
		<tr>
			<td style="padding:0 0 20px 0;border-bottom:1px solid #e9edee">
				<a href="#" style="display:block;margin:0 auto" target="_blank" alt="jngtravelpro logo" style="border:0px" class="CToWUd">
					<img src="{{url(config('app.logo'))}}">
				</a>
			</td>
		</tr> 
		<tr>
			<td colspan="2" style="padding:30px 0">
				<p style="color:#1d2227;line-height:28px;font-size:22px;margin:12px 10px 20px 10px;font-weight:400">Hi {{$last_name}} it's great to meet you.</p>
				<p style="margin:0 10px 10px 10px;padding:0">We'd like to make sure we got your email address right.</p>
				<p>
					<a style="display:inline-block;text-decoration:none;padding:15px 20px;background-color:#2baaed;border:1px solid #2baaed;border-radius:3px;color:#fff;font-weight:bold" href="{{route('getSignin', ['token'=> $md5])}}">Yes, it's me – let's get started</a>
				</p>
				<p><strong>Your Account Email & Password</strong></p>
				<p>Email: {{$email}}</p>
				<p>Password: {{$password}}</p>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding:30px 0 0 0;border-top:1px solid #e9edee;color:#9b9fa5">
				<!-- If you have any questions you can get in touch at <a style="color:#666d74;text-decoration:none" target="_blank" >support.xero.com</a> -->
			</td>
		</tr>
	</tbody>
</table>