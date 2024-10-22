<?php


// Initialisation
// ==============

// 
// include('VPCPaymentConnection.php');
use App\Payment\VPCPaymentConnection;
$conn = new VPCPaymentConnection();


// This is secret for encoding the MD5 hash
// This secret will vary from merchant to merchant

// $secureSecret = "52BCFBCDAD7EB246371B5295851B7A83";
$secureSecret = "52BCFBCDAD7EB246371B5295851B7A83";

// Set the Secure Hash Secret used by the VPC connection object
$conn->setSecureSecret($secureSecret);


// Set the error flag to false
$errorsExist = false;



// *******************************************
// START OF MAIN PROGRAM
// *******************************************





// Add VPC post data to the Digital Order
foreach($_GET as $key => $value) {
    if (($key!="vpc_SecureHash") && ($key != "vpc_SecureHashType") && ((substr($key, 0,4)=="vpc_") || (substr($key,0,5) =="user_"))) {
        $conn->addDigitalOrderField($key, $value);
    }
}


// Obtain a one-way hash of the Digital Order data and
// check this against what was received.
$secureHash = $conn->hashAllFields();

if(array_key_exists("vpc_SecureHash", $_GET)) 
{
    if ($secureHash==$_GET["vpc_SecureHash"]) {
        $hashValidated = "<font color='#00AA00'><strong>CORRECT</strong></font>";
    } else {
        $hashValidated = "<font color='#FF0066'><strong>INVALID HASH</strong></font>";
        $errorsExist = true;
    }
} else {
    $hashValidated = "<font color='#FF0066'><strong>NO HASH RETURNED</strong></font>";
}

    
    // Extract the available receipt fields from the VPC Response
    // If not present then let the value be equal to 'Unknown'
    // Standard Receipt Data
if(array_key_exists("Title", $_GET)) $Title = $_GET["Title"];
if(array_key_exists("AgainLink", $_GET))$againLink       = $_GET["AgainLink"];
if(array_key_exists("vpc_Amount", $_GET))$amount          = $_GET["vpc_Amount"];
if(array_key_exists("vpc_Locale", $_GET))$locale          = $_GET["vpc_Locale"];
if(array_key_exists("vpc_BatchNo", $_GET))$batchNo         = $_GET["vpc_BatchNo"];
if(array_key_exists("vpc_Command", $_GET))$command         = $_GET["vpc_Command"];
if(array_key_exists("vpc_Message", $_GET))$message         = $_GET["vpc_Message"];
if(array_key_exists("vpc_Version", $_GET))$version         = $_GET["vpc_Version"];
if(array_key_exists("vpc_Card", $_GET))$cardType        = $_GET["vpc_Card"];
if(array_key_exists("vpc_OrderInfo", $_GET))$orderInfo       = $_GET["vpc_OrderInfo"];
if(array_key_exists("vpc_ReceiptNo", $_GET))$receiptNo       = $_GET["vpc_ReceiptNo"];
if(array_key_exists("vpc_Merchant", $_GET))$merchantID      = $_GET["vpc_Merchant"];
if(array_key_exists("vpc_MerchTxnRef", $_GET))$merchTxnRef     = $_GET["vpc_MerchTxnRef"];
if(array_key_exists("vpc_AuthorizeId", $_GET))$authorizeID     = $_GET["vpc_AuthorizeId"];
if(array_key_exists("vpc_TransactionNo", $_GET))$transactionNo   = $_GET["vpc_TransactionNo"];
if(array_key_exists("vpc_AcqResponseCode", $_GET))$acqResponseCode = $_GET["vpc_AcqResponseCode"];
if(array_key_exists("vpc_TxnResponseCode", $_GET))$txnResponseCode = $_GET["vpc_TxnResponseCode"];

        // Obtain the 3DS response
if(array_key_exists("vpc_3DSECI", $_GET))$vpc_3DSECI                     = $_GET["vpc_3DSECI"];
if(array_key_exists("vpc_3DSXID", $_GET))$vpc_3DSXID                     = $_GET["vpc_3DSXID"];
if(array_key_exists("vpc_3DSenrolled", $_GET))$vpc_3DSenrolled           = $_GET["vpc_3DSenrolled"];
if(array_key_exists("vpc_3DSstatus", $_GET))$vpc_3DSstatus               = $_GET["vpc_3DSstatus"];
if(array_key_exists("vpc_VerToken", $_GET))$vpc_VerToken                 = $_GET["vpc_VerToken"];
if(array_key_exists("vpc_VerType", $_GET))$vpc_VerType               = $_GET["vpc_VerType"];
if(array_key_exists("vpc_VerStatus", $_GET))$vpc_VerStatus               = $_GET["vpc_VerStatus"];
if(array_key_exists("vpc_VerSecurityLevel", $_GET))$vpc_VerSecurityLevel         = $_GET["vpc_VerSecurityLevel"];


    // CSC Receipt Data
if(array_key_exists("vpc_CSCResultCode", $_GET))$cscResultCode  = $_GET["vpc_CSCResultCode"];
if(array_key_exists("vpc_AcqCSCRespCode", $_GET))$ACQCSCRespCode = $_GET["vpc_AcqCSCRespCode"];
    
    // AVS Receipt Data
if(array_key_exists("vpc_AVSResultCode", $_GET))$avsResultCode  = $_GET["vpc_AVSResultCode"];
if(array_key_exists("vpc_AcqAVSRespCode", $_GET))$ACQAVSRespCode = $_GET["vpc_AcqAVSRespCode"];
// Get the descriptions behind the QSI, CSC and AVS Response Codes
    // Only get the descriptions if the string returned is not equal to "No Value Returned".
    
$txnResponseCodeDesc = "";
$cscResultCodeDesc = "";
$avsResultCodeDesc = "";
    
    if ($txnResponseCode != "No Value Returned") {
        $txnResponseCodeDesc = getResultDescription($txnResponseCode);
    }
    
    if ($cscResultCode != "No Value Returned") {
        $cscResultCodeDesc = getCSCResultDescription($cscResultCode);
    }
    
    if ($avsResultCode != "No Value Returned") {
        $avsResultCodeDesc = getAVSResultDescription($avsResultCode);
    }
    
        $error = "";
    // Show this page as an error page if error condition
    if ($txnResponseCode=="7" || $txnResponseCode=="No Value Returned" ) {
        $error = "Error ";
    }
        
    // FINISH TRANSACTION - Process the VPC Response Data
    // =====================================================
    // For the purposes of demonstration, we simply display the Result fields on a
    // web page.
?> <!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
    <html>
    <head><title><?php echo($Title); ?>- VPC Response <?php echo($error); ?>Page</title>
        <meta http-equiv='Content-Type' content='text/html, charset=iso-8859-1'>
        <style type='text/css'>
    <!--
        h1       { font-family:Arial,sans-serif; font-size:24pt; color:#08185A; font-weight:100}
        h2.co    { font-family:Arial,sans-serif; font-size:24pt; color:#08185A; margin-top:0.1em; margin-bottom:0.1em; font-weight:100}
        h3.co    { font-family:Arial,sans-serif; font-size:16pt; color:#000000; margin-top:0.1em; margin-bottom:0.1em; font-weight:100}
        body     { font-family:Verdana,Arial,sans-serif; font-size:10pt; color:#08185A; background-color:#FFFFFF }
        TR       { height:25px; }
        TR.shade { height:25px; background-color:#E1E1E1 }
        TR.title { height:25px; background-color:#C1C1C1 }
        td       { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A }
        th       { font-family:Verdana,Arial,sans-serif; font-size:10pt; color:#08185A; font-weight:bold; background-color:#E1E1E1; padding-top:0.5em; padding-bottom:0.5em}
        td.red   { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#FF0066 }
        td.green { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#008800 }
        P.blue   { font-family:Verdana,Arial,sans-serif; font-size:7pt;  color:#08185A }
        p        { font-family:Verdana,Arial,sans-serif; font-size:10pt; color:#FFFFFF }
        p.bl     { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A }
        a:link   { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A }
        a:visited{ font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A }
        a:hover  { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#FF0000 }
        a:active { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#FF0000 }
        input    { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A; background-color:#E1E1E1; font-weight:bold }
        select   { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A; background-color:#E1E1E1; font-weight:bold }
        textarea { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A; background-color:#E1E1E1; font-weight:normal }
    -->
        </style>
    </head>
    <body>
    
    <!-- Start Branding Table -->
<table width='100%' border='2' cellpadding='2' bgcolor='#C1C1C1'><tr><td bgcolor='#E1E1E1' width='90%'><h2 class='co'>&nbsp;Virtual Payment Client Example</h2></td><td bgcolor='#C1C1C1' align='center'><h3 class='co'>MIGS</h3></td></tr></table>
    <center><h1>PHP 2.5 Party (MerchantHosted Authenticate and Pay) Example</H1></center>
</table>
    <!-- End Branding Table -->
    
    <center><h1><?php echo($Title); ?> <?php echo($error); ?>Response Page</H1></center>
    
    <table width="85%" align='center' cellpadding='5' border='0'>
      
        <tr class='title'>
            <td colspan="2" height="25"><p><strong>&nbsp;Standard Transaction Fields</strong></p></td>
        </tr>
        <tr>
            <td align='right' width='50%'><strong><i>VPC API Version: </i></strong></td>
            <td width='50%'><?php echo($version); ?></td>
        </tr>
        <tr class='shade'>                  
            <td align='right'><strong><i>Command: </i></strong></td>
            <td><?php echo($command); ?></td>
        </tr>
        <tr>
            <td align='right'><strong><i>Merchant Transaction Reference: </i></strong></td>
            <td><?php echo($merchTxnRef); ?></td>
        </tr>
        <tr class='shade'>
            <td align='right'><strong><i>Merchant ID: </i></strong></td>
            <td><?php echo($merchantID); ?></td>
        </tr>
        <tr>                  
            <td align='right'><strong><i>Order Information: </i></strong></td>
            <td><?php echo($orderInfo); ?></td>
        </tr>
        <tr class='shade'>
            <td align='right'><strong><i>Transaction Amount: </i></strong></td>
            <td><?php echo($amount); ?></td>
        </tr>
        <tr>                  
            <td align='right'><strong><i>Locale: </i></strong></td>
            <td><?php echo($locale); ?></td>
        </tr>
      
        <tr>
            <td colspan='2' align='center'><font color='#0074C4'>Fields above are the primary request values.<br/></font><hr/>
            </td>
        </tr>

        <tr class='shade'>                  
            <td align='right'><strong><i>VPC Transaction Response Code: </i></strong></td>
            <td><?php echo($txnResponseCode); ?></td>
        </tr>
        <tr>
            <td align='right'><strong><i>Transaction Response Code Description: </i></strong></td>
            <td><?php echo($txnResponseCodeDesc); ?></td>
        </tr>
        <tr class='shade'>                  
            <td align='right'><strong><i>Message: </i></strong></td>
            <td><?php echo($message); ?></td>
        </tr>
<?
// only display the following fields if not an error condition
if ($txnResponseCode!="7" && $txnResponseCode!="No Value Returned") { 
?>.
        <tr>
            <td align='right'><strong><i>Receipt Number: </i></strong></td>
            <td><?php echo($receiptNo); ?></td>
        </tr>
        <tr class='shade'>                  
            <td align='right'><strong><i>Transaction Number: </i></strong></td>
            <td><?php echo($transactionNo); ?></td>
        </tr>
        <tr>
            <td align='right'><strong><i>Acquirer Response Code: </i></strong></td>
            <td><?php echo($acqResponseCode); ?></td>
        </tr>
        <tr class='shade'>                  
            <td align='right'><strong><i>Bank Authorization ID: </i></strong></td>
            <td><?php echo($authorizeID); ?></td>
        </tr>
        <tr>
            <td align='right'><strong><i>Batch Number: </i></strong></td>
            <td><?php echo($batchNo); ?></td>
        </tr>
        <tr class='shade'>                  
            <td align='right'><strong><i>Card Type: </i></strong></td>
            <td><?php echo($cardType); ?></td>
        </tr>
      
        <tr>
            <td colspan='2' align='center'><font color='#0074C4'>Fields above are for a standard transaction.<br/><hr/>
                Fields below are additional fields for extra functionality.</font><br/></td>
        </tr>

        <tr class='title'>
            <td colspan="2" height="25"><p><strong>&nbsp;Card Security Code Fields</strong></p></td>
        </tr>
        <tr class='shade'>
            <td align='right'><strong><i>CSC Acquirer Response Code: </i></strong></td>
            <td><?php echo($ACQCSCRespCode); ?></td>
        </tr>
        <tr>                    
            <td align='right'><strong><i>CSC QSI Result Code: </i></strong></td>
            <td><?php echo($cscResultCode); ?></td>
        </tr>
        <tr class='shade'>
            <td align='right'><strong><i>CSC Result Description: </i></strong></td>
            <td><?php echo($cscResultCodeDesc); ?></td>
        </tr>
      
        <tr><td colspan = '2'><hr/></td></tr>
      
        <tr class='title'>
            <td colspan="2" height="25"><p><strong>&nbsp;Address Verification Service Fields</strong></p></td>
        </tr>
        <tr>
            <td align='right'><strong><i>AVS Acquirer Response Code: </i></strong></td>
            <td><?php echo($ACQAVSRespCode); ?></td>
        </tr>
        <tr class='shade'>                    
            <td align='right'><strong><i>AVS QSI Result Code: </i></strong></td>
            <td><?php echo($avsResultCode); ?></td>
        </tr>
        <tr>
            <td align='right'><strong><i>AVS Result Description: </i></strong></td>
            <td><?php echo($avsResultCodeDesc); ?></td>
        </tr>

                <tr class="title">
            <td colspan="2" height="25"><P><strong>&nbsp;3-D Secure Fields</strong></P></td>
        </tr>
        <tr>
            <td align="right"><strong><i>Unique 3DS transaction identifier (xid): </i></strong></td>
            <td class="red"><?php echo($vpc_3DSXID); ?></td>
        </tr>
        <tr class="shade">
            <td align="right"><strong><i>3DS Authentication Verification Value: </i></strong></td>
            <td class="red"><?php echo($vpc_VerToken); ?></td>
        </tr>
        <tr>
            <td align="right"><strong><i>3DS Electronic Commerce Indicator (ECI): </i></strong></td>
            <td class="red"><?php echo($vpc_3DSECI); ?></td>
        </tr>
        <tr class="shade">
            <td align="right"><strong><i>3DS Authentication Scheme: </i></strong></td>
            <td class="red"><?php echo($vpc_VerType); ?></td>
        </tr>
        <tr>
            <td align="right"><strong><i>3DS Security level used in the AUTH message: </i></strong></td>
            <td class="red"><?php echo($vpc_VerSecurityLevel); ?></td>
        </tr>
        <tr class="shade">
            <td align="right">
                <strong><i>3DS CardHolder Enrolled: </strong>
                <br>
                <font size="1">Takes values: <strong>Y</strong> - Yes <strong>N</strong> - No</i></font>
            </td>
            <td class="red"><?php echo($vpc_3DSenrolled); ?></td>
        </tr>
        <tr>
            <td align="right">
                <i><strong>Authenticated Successfully: </strong><br>
                <font size="1">Only returned if CardHolder Enrolled = <strong>Y</strong>. Takes values:<br>
                <strong>Y</strong> - Yes <strong>N</strong> - No <strong>A</strong> - Attempted to Check <strong>U</strong> - Unavailable for Checking</font></i>
            </td>
            <td class="red"><?php echo($vpc_3DSstatus); ?></td>
        </tr>
        <tr class="shade">
            <td align="right"><strong><i>Payment Server 3DS Authentication Status Code: </i></strong></td>
            <td class="green"><?php echo($vpc_VerStatus); ?></td>
        </tr>
        <tr>
            <td colspan="2" class="red" align="center">
                <br>The 3-D Secure values shown in red are those values that are important values to store in case of future transaction repudiation.
            </td>
        </tr>
        <tr>
            <td colspan="2" class="green" align="center">
                The 3-D Secure values shown in green are for information only and are not required to be stored.
            </td>
        </tr>

        <tr>
            <td colspan = '2'><hr/></td>
        </tr>
        <? } ?>
        <tr class='title'>
            <td colspan="2" height="25"><p><strong>&nbsp;Hash Validation</strong></p></td>
        </tr>
        <tr>
            <td align="right"><strong><i>Hash Validated Correctly: </i></strong></td>
            <td><?php echo($hashValidated); ?></td>
        </tr>

</table><br/>
    
    <center><P><A HREF='PHP_VPC_3Party_Super_Order.html'>New Transaction</A></P></center>
    
    </body>
    </html>