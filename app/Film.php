<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $table = "tbl_film";

    
     public function country(){ 
        return $this->belongsTo(Country::class);
    }

    public function province(){
        return $this->belongsTo(Province::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
