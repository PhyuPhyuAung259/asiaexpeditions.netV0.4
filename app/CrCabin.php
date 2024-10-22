<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrCabin extends Model
{
    protected $table = 'rc_cabin';

    public function crProgram(){
    	return $this->belongsToMany(CrProgram::class);
    }

    public function cruisebooked (){
    	return $this->hasMany(CruiseBooked::class);
    }
}
