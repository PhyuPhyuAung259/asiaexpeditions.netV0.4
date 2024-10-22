<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CruiseBooked extends Model
{
    protected $table = 'rc_book';

    public function cruise(){
    	return $this->belongsTo(Supplier::class, 'cruise_id');
    }

    public function program (){
    	return $this->belongsTo(CrProgram::class, 'program_id');
    }

    public function category (){
    	return $this->belongsTo(RoomCategory::class, 'category_id');
    }

    public function room (){
    	return $this->belongsTo(CrCabin::class, 'room_id');
    }

    public function book(){
        return $this->belongsTo(Booking::class, 'book_id');
    }
}
