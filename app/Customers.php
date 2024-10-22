<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    //
    public static function Exitemail($email){
        // return self->select('email')->where('email', $email)->first();
        return self::where('email', $email)->first();
    }

    public function province(){
    	return $this->belongsTo(Province::class);
    }

    public function country(){
    	return $this->belongsTo(Country::class);
    }
}
