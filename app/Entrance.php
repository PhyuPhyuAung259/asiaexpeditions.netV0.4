<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrance extends Model
{
    protected $table = 'entrance_service';

    public function country(){
    	return $this->belongsTo(Country::class);
    }

    public function province(){
    	return $this->belongsTo(Province::class);
    }

    public function bentrance(){
    	return $this->hasMany(BookEntrance::class);
    }

    public function acc_transaction(){
        return $this->hasMany(AccountTransaction::class);
    }

    public function acc_journal(){
        return $this->hasMany(AccountJournal::class);
    }
}

