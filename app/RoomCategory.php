<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomCategory extends Model
{
    //
    protected $table = 'category';

    public function hotelbooked (){
    	return $this->hasMany(Hotelbooked::class);
    }

    public function cruisebooked (){
    	return $this->hasMany(Cruisebooked::class);
    }
}
