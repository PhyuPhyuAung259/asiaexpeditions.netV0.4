<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    //
    protected $table = 'tbl_subscribe';

	public function country(){
        return $this->belongsTo(Country::class);
    }

    public static function Email($email){
      return self::where(['email'=>$email])->first();
    }
}
