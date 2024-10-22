<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
  protected $table = "tbl_driver";

  public function supplier(){
   	 return $this->belongsTo(Supplier::class);
  }

  public function country(){
    	return $this->belongsTo(Country::class);
   }

  public function province(){
    	return $this->belongsTo(Province::class);  
  }


  public function driver (){
      return $this->hasMany(BookTrasport::class);
  }


}
