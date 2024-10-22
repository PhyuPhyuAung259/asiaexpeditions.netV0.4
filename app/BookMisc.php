<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookMisc extends Model
{
    protected $table = 'misc_book';

    public function servicetype(){
		return $this->belongsTo(MISCService::class, 'service_id');
	}

	public function booking(){
  		return $this->belongsTo(Booking::class, 'book_id');
  	}

	
}
