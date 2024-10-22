<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransportMenu extends Model
{
    protected $table = "transport_type";

    public function transervice(){
    	return $this->belongsTo(TransportService::class);
    }

    public function booktranport(){ 
    	return $this->hasMany(BookTransport::class);
    }
}
