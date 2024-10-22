<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    //
    protected $table = 'tours';
    public function categories(){
    	return $this->belongsToMany(Business::class, 'category_tours', 'tour_id', 'category_id');
    }

    public function tour_feasility(){
        return $this->belongsToMany(Service::class);
    }

    public function supplier(){
        return $this->belongsToMany(Supplier::class, 'tour_suppliers', 'tour_id', 'supplier_id');
    }

    public function country(){
    	return $this->belongsTo(Country::class);
    } 

    public function province(){
    	return $this->belongsTo(Province::class);
    }

    public function pricetour(){
        return $this->hasMany(TourPrice::class);
    }

    public function book(){
        return $this->hasMany(Booking::class);
    }

    public function viewtour (){
      return $this->hasMany(CountView::class);
    }



}
