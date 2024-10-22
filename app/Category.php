<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = 'business';


    public function getAllUser(){
    	return $this->belongsTo('App\User');
    }

    public function tour(){
    	return $this->belongsToMany(Tour::class);
    }
}
