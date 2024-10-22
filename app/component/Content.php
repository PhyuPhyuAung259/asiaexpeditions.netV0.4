<?php 
namespace App\component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use App\User;
/**

*/
class Content
{
	
  	private static $baseUrl = '/home3/asiagolf/public_html/tourwriter';
    private static $dir = '/upload/photos/';
    private static $dirthumb = '/upload/photos/thumbs/';
   
	public static $NoImg = "/img/no_image.png";
	public static $user;
	// $this->dir_path = public_path('storage/'.\Auth::user()->name);
 //    $this->dir_thumb = public_path('storage/'.\Auth::user()->name. '/thumbnail/');
    public function urI(){
    	return static::$dir;
    }

	public static function diffDate($start, $end){
		$start_ts = strtotime($start);
	  	$end_ts = strtotime($end);
	  	$diff = $end_ts - $start_ts;
	  return round($diff / 86400); 
	}

	public static function urlImage( $filename, $fileId = 0){
		$user = User::find($fileId);		
		$userDir = isset($user) ? $user->name: \Auth::check() ? \Auth::user()->name : '';
		if($filename){
			if ( file_exists(public_path("storage/").$filename) ) {	
				$file = url('').\Storage::url("public/".$filename);	
			}elseif ( file_exists(public_path("storage/$userDir/").$filename) ) {
				$file = url('').\Storage::url("public/".$userDir."/".$filename);
			}else{
				$file = self::$NoImg;
			}
		}else{
			$file = self::$NoImg;
		}
		return $file;
	}
	public static function urlthumbnail( $filename, $fileId = 0, $authID = 0){
		$user = User::find($fileId);		
		$userDir = isset($user) ? $user->name: \Auth::check() ? \Auth::user()->name : '';
		if($filename){
			if ( file_exists(public_path("storage/thumbnail/").$filename) ) {	
				$file = url('').\Storage::url("public/thumbnail/".$filename);	
			}elseif ( file_exists(public_path("storage/$userDir/thumbnail/").$filename) ) {
				$file = url('').\Storage::url("public/".$userDir."/thumbnail/".$filename);
			}else{
				$file = self::$NoImg;
			}
		}else{
			$file = self::$NoImg;
		}
		return $file;
	}

	public static function currency($curren =0 ){
		$currency = ['USD', 'Kyat', 'Rupee'];
		switch ($curren) {
			case 1:
				$money = $currency[1];
				break;
			case 2:
				$money = $currency[2];
				break;
			default:
				$money = $currency[0];
				break;
		}
		return $money;
	}

	public static function money($money){
		return $money > 0 ? number_format($money,2):'';
	}

	public static function dateformat($date ){
		return date('d M Y', strtotime($date));
	}

	public static function DelUserRole($title, $type, $Id, $authID = 0){
		if(\Auth::user()->role_id == 2 || \Auth::user()->role_id == 4){
		    $message = "<span style='cursor:pointer;' class='RemoveHotelRate' data-type='".$type."' data-id='".$Id."' title='".$title."'>
                <label class='icon-list ic_remove'></label></span>";                   
        }else{
        	if (\Auth::user()->id === $authID ) {
        		$message = "<span style='cursor:pointer;' class='RemoveHotelRate' data-type='".$type."' data-id='".$Id."' title='".$title."'>
                <label class='icon-list ic_remove'></label></span>"; 
        	}else{
	        	$message = "<span style='cursor:pointer;' title='Permission Denied'><label  class='icon-list ic_remove'></label></span>";
	        }
        }
        return $message;
	}

	public static function marginRate($amount = 1, $margin_rat ){
		$getRate = empty($margin_rat) ? 1 : $margin_rat;
		$rate = ($amount * $getRate ) / 100;
		return self::money($amount + $rate);
	}

	// payment transaction
	public static function getResultDescription($responseCode) {

	    switch ($responseCode) {
	        case "0" : $result = "Transaction Successful"; break;
	        case "?" : $result = "Transaction status is unknown"; break;
	        case "E" : $result = "Referred"; break;
	        case "1" : $result = "Transaction Declined"; break;
	        case "2" : $result = "Bank Declined Transaction"; break;
	        case "3" : $result = "No Reply from Bank"; break;
	        case "4" : $result = "Expired Card"; break;
	        case "5" : $result = "Insufficient funds"; break;
	        case "6" : $result = "Error Communicating with Bank"; break;
	        case "7" : $result = "Payment Server detected an error"; break;
	        case "8" : $result = "Transaction Type Not Supported"; break;
	        case "9" : $result = "Bank declined transaction (Do not contact Bank)"; break;
	        case "A" : $result = "Transaction Aborted"; break;
	        case "C" : $result = "Transaction Cancelled"; break;
	        case "D" : $result = "Deferred transaction has been received and is awaiting processing"; break;
	        case "F" : $result = "3D Secure Authentication failed"; break;
	        case "I" : $result = "Card Security Code verification failed"; break;
	        case "L" : $result = "Shopping Transaction Locked (Please try the transaction again later)"; break;
	        case "N" : $result = "Cardholder is not enrolled in Authentication scheme"; break;
	        case "P" : $result = "Transaction has been received by the Payment Adaptor and is being processed"; break;
	        case "R" : $result = "Transaction was not processed - Reached limit of retry attempts allowed"; break;
	        case "S" : $result = "Duplicate SessionID (Amex Only)"; break;
	        case "T" : $result = "Address Verification Failed"; break;
	        case "U" : $result = "Card Security Code Failed"; break;
	        case "V" : $result = "Address Verification and Card Security Code Failed"; break;
	        default  : $result = "Unable to be determined"; 
	    }
	    return $result;
	}
}