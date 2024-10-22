<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuideService extends Model
{
    protected $table = "guide_service";


    public function country(){
    	return $this->belongsTo(Country::class);
    }

    public function supplier ( ){
        return $this->belongsTo(Supplier::class);
    }
    // public function service_guide(){
    //     return $this->hasMany(BookGuide::class);
    // }

    public function province (){
    	return $this->belongsTo(Province::class);
    }

    public function language(){
    	return $this->hasMany(GuideLanguage::class);
    }

    public function bookguide(){
        return $this->hasMany(BookGuide::class);
    }
}
