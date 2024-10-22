<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GolfMenu extends Model
{
    protected $table = 'golfmenu';

    public function book(){
        return $this->belongsTo(Booking::class);
    }

    public function supplier(){
    	return $this->belongsTo(Supplier::class);
    }
}
