<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookTransport extends Model
{
    protected $table = "transport_book";
 
    public function service(){
    	return $this->belongsTo(TransportService::class, 'service_id');
    }

    public function vehicle(){
    	return $this->belongsTo(TransportMenu::class, 'vehicle_id');
    }

    public function transport (){
    	return $this->belongsTo(Supplier::class, 'transport_id');
    }

    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function driver (){
        return $this->belongsTo(Driver::class);
    }

    public function book(){
        return $this->belongsTo(Booking::class);
    }

    public static function getTranportOrderBy(){
        return $projects = \DB::table('transport_book')
        ->join('booking', 'booking.id','=','transport_book.book_id')
        ->select("transport_book.*", "booking.*", "booking.id as book_id", "transport_book.id as tran_id")
        ->where(['transport_book.status'=> 1, 'booking.book_status'=> 1])
        ->orderBy('booking.book_checkin', 'ASC');
  }
    
}
