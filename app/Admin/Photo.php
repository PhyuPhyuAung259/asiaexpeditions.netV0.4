<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    //
    public function user (){
    	return $this->belongsTo(\App\User::class);
    }

    public function country () {
    	return $this->belongsTo(\App\Country::class);
    }

    public function province () {
    	return $this->belongsTo(\App\Province::class);
    }
}
