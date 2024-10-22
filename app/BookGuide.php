<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookGuide extends Model
{
  protected $table = 'guide_book';

  public function service(){
    	return $this->belongsTo(GuideService::class, 'service_id');
    }
	public function supplier(){
  	return $this->belongsTo(Supplier::class);
  }

  public function book(){
    return $this->belongsTo(Booking::class);
  }

	public function language(){
		return $this->belongsTo(GuideLanguage::class);
	} 

  public function province(){
    return $this->belongsTo(Province::class);
  }

  public static function GuideByCountry(){
    return \DB::table('guide_book as guid')
        ->join('country', 'country.id','=','guid.country_id')
        ->where(['country.country_status'=>1])
        // ->select('book.*')
        ->groupBy("guid.country_id")
        ->orderBy('country.country_name', 'ASC');
  }


  public static function getGuideByProject(){
    return $projects = \DB::table('guide_book')
        ->join('booking', 'booking.id','=','guide_book.book_id')
        ->select("guide_book.*", "booking.*", "booking.id as book_id", "guide_book.supplier_id as sup_id", "guide_book.id as guide_id")
        ->where(['guide_book.status'=> 1, 'booking.book_status'=> 1])
        ->orderBy('booking.book_checkin', 'ASC');
  }
}
