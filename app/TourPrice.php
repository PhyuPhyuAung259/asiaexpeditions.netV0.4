<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TourPrice extends Model
{
    protected $table = 'tour_price';

    public function tour(){
    	return $this->belongsTo(Tour::class);
    }
}
