<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuideLanguage extends Model
{
    protected $table = "guide_language";

    public function service(){
    	return $this->belongsTo(GuideService::class);
    }

    public function bookguide(){
    	return $this->hasMany(BookGuide::class);
    }

    public function supplier(){
        return $this->belongsToMany(Supplier::class);
    }


}
