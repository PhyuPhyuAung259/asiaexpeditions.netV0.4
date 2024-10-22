<?php

namespace App\Payment;

use Illuminate\Database\Eloquent\Model;

class PaymentLink extends Model
{
    //
    public function project(){
    	return $this->belongsTo(\App\Project::class);
    }

    public function user(){
    	return $this->belongsTo(\App\User::class);
    }
}
