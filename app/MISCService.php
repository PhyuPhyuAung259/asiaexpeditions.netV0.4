<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MISCService extends Model
{
    protected $table = "misc_service";

    public function country(){
    	return $this->belongsTo(Country::class);
    }

    public function province(){
    	return $this->belongsTo(Province::class);
    }

    public function bookmisc(){
    	return $this->hasMany(BookMisc::class);
    }

    public function acc_transaction(){
        return $this->hasMany(AccountTransaction::class);
    }

    public function acc_journal(){
        return $this->hasMany(AccountJournal::class);
    }
}
