<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $table = 'account_type';
   
  	public function journal(){
    	return $this->hasMany(AccountJournal::class);
    }

    public function acc_transaction(){
    	return $this->belongsTo(AccountTransaction::class);
    }
 
    public static function getAccountTypeByTransaction($period){
        return \DB::table('account_type as acc_type')
            ->leftJoin('account_journal as journal', 'journal.account_type_id','=','acc_type.id')
            ->select("journal.id as journal_id", "acc_type.*", "journal.*", "acc_type.id as acc_type_id")
            // ->whereDate("journal.entry_date", $period)
            ->groupBy("acc_type.id")
            // ->orderBy("journal.entry_date")
            ->get();
    }
}
