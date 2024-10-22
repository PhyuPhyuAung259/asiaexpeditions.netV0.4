<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';

    public function country(){
    	return $this->belongsTo(Country::class);
    }

    public function user(){
    	return $this->hasMany(User::class);
    }
}
