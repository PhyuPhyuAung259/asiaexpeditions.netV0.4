<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountName extends Model
{
    protected $table = "account_name";

    public static function checkAccountName($accountName){
    	return self::where('account_code', $accountName)->first();
    }

    public function journal(){
    	return $this->belongsTo(AccountJournal::class);
    }

    public function acc_transaction(){
    	return $this->belongsTo(AccountTransaction::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function country () {
        return $this->belongsTo(Country::class);
    }
    
    public function project(){
        return $this->belongsTo(Project::class);
    }

    public static function ExitAccountCode($acc_code){
        return self::where('account_code', $acc_code)->first();
    }
    
}
