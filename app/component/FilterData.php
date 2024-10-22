<?php 
namespace App\component;
/**

*/
class FilterData
{
	
  	private static $baseUrl = '/home3/asiagolf/public_html/tourwriter';
    private static $dir 	= '/upload/photos/';
    private static $dirthumb= '/upload/photos/thumbs/';
   
	public static $NoImg = "/img/no_image.png";
	public static $user;
	// $this->dir_path = public_path('storage/'.\Auth::user()->name);
 //    $this->dir_thumb = public_path('storage/'.\Auth::user()->name. '/thumbnail/');
    public function urI(){
    	return static::$dir;
    }

    public static function ($){
    	// $content = ""
    }
	
}