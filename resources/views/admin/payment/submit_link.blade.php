<body onload="document.order.submit()">
<!--body-->
	<form name="order" action="<?php echo($redirectURL); ?>" method="post">
    <!-- input type="submit" name="submit" value="Continue"/ -->
    <p>Please wait while your payment is being processed...</p>

<?php
	$hashinput = "";
   foreach($_POST as $key => $value) {
    // create the hash input and URL leaving out any fields that have no value
    if (strlen($value) > 0) {

?>
        	<input type="hidden" name="<?php echo($key); ?>" value="<?php echo($value); ?>"/><br>
<?php 			
        if ((strlen($value) > 0) && ((substr($key, 0,4)=="vpc_") || (substr($key,0,5) =="user_"))) {
		$hashinput .= $key . "=" . $value . "&";
		}
    }
}
$hashinput = rtrim($hashinput, "&");
?>		
	<!-- attach SecureHash -->
    <input type="hidden" name="vpc_SecureHash" value="<?php echo(strtoupper(hash_hmac('SHA256', $hashinput, pack('H*',$securesecret)))); ?>"/>
		<input type="hidden" name="vpc_SecureHashType" value="SHA256">
</td></tr>
</table>
</form>
</html>
