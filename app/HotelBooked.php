<?php

namespace App;
 
use Illuminate\Database\Eloquent\Model;

class HotelBooked extends Model
{
    protected $table = 'hotelb';

    public function hotel(){
    	return $this->belongsTo(Supplier::class, 'hotel_id');
    }

    public function room (){
    	return $this->belongsTo(Room::class, 'room_id');
    }

    public function category (){
    	return $this->belongsTo(RoomCategory::class, 'category_id');
    }

    public function book(){
        return $this->belongsTo(Booking::class, 'book_id');
    }

    public function project () {
        return $this->belongsTo(Project::class, 'project_number');
    }
}
