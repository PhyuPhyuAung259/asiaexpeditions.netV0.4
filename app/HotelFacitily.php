<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelFacitily extends Model
{
    protected $table = 'hotel_facility';

    public function supplier (){
        return $this->belongsToMany(Supplier::class);//->withPivot($hotelRate);
    }
}
