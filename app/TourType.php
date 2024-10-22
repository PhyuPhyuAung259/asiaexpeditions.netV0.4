<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TourType extends Model
{
    protected $table = "tour_types";


    // public function tour(){ 
    // 	return $this->belongsToMany(Tour::class,  'category_tour', 'tour_id', 'category_id');
    // }
    public function tour(){
    	return $this->belongsToMany(TourType::class, 'category_tours',  'tour_id', 'category_id');
    }
}
