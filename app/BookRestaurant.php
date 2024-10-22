<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookRestaurant extends Model
{
    //	
    protected $table = 'restaurant_book';

    public function supplier(){
  		return $this->belongsTo(Supplier::class);
  	}

  	public function rest_menu (){
  		return $this->belongsTo(RestaurantMenu::class, 'menu_id');
  	}

  
}
