<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 
class RestaurantMenu extends Model
{
    protected $table = "restaurant_menu";


    public function supplier (){
    	return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function book_restaurant() {
   		return $this->hasMany(BookRestaurant::class);
   	}
}
