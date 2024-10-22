<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountJournal extends Model
{ 
    protected $table = 'account_journal';

    public function account_name(){
    	return $this->belongsTo(AccountName::class)->orderBy("account_code", 'ASC');
    }

    public function account_type(){
    	return $this->belongsTo(AccountType::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function supplier(){
    	return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function misc_service (){
        return $this->belongsTo(MISCService::class, 'supplier_id');
    }

    public function ent_service (){
        return $this->belongsTo(Entrance::class, 'supplier_id');
    }

    public function acc_transaction(){
        return $this->hasMany(AccountTransaction::class, 'journal_id');
    }

    public function EntranceBooked () {
        return $this->belongsTo(BookEntrance::class);
    }

    public static function getJournalEntry($entry_type = 1){
        if ($entry_type == 1) {
        $journalList = \DB::table('account_journal as journal')
            ->join('account_transaction as transaction', 'transaction.journal_id','=','journal.id')
            ->select('journal.*')
            ->where(["journal.status"=>1])
            ->groupBy('journal.project_number')
            ->orderBy('journal.project_fileNo', 'DESC');
        }else{
        $journalList = \DB::table('account_journal as journal')
            ->join('account_transaction as transaction', 'transaction.journal_id','=','journal.id')
            ->select('journal.*')
            ->where(["journal.status"=>1]) 
            ->where(['journal.type'=>$entry_type, "journal.status"=>1]) 
            ->groupBy('journal.project_number')
            ->orderBy('journal.project_fileNo', 'DESC');
        }
        return $journalList;
    }

    // public static function getJournalEntry($entry_type = 1){
    public static function getJournalByTransaction($conId = 0, $from_date, $to_date){
        return \DB::table('account_journal as journal')
            ->Join('account_transaction as transaction', 'transaction.journal_id','=','journal.id')
            ->select('journal.*', 'journal.id as journal_id',
                    'journal.supplier_id as journal_supplier', 'journal.type as journal_type',
                    '.transaction.supplier_id as tran_supplier', 'transaction.*', '.transaction.account_name_id as accountname_id',  
                    'transaction.type as tran_type', 'transaction.id as tran_id', 'transaction.ex_rate as exchange_Rate')
            ->where(["journal.status"=>1, "journal.country_id"=> $conId]) 
            ->whereNotIn("journal.project_id", ["", "null", 0])
            ->whereBetween('journal.entry_date', [$from_date, $to_date])
            ->orderBy('transaction.pay_date', "ASC");
    }

    public static function getTransactionByAccountName($conId = null, $from_date, $to_date){
        return \DB::table('account_name')
            ->LeftJoin('account_transaction as transaction', 'transaction.account_name_id','=','account_name.id')
            ->where(["account_name.status"=>1, "transaction.status"=>1, "account_name.country_id"=> $conId]) 
            ->whereBetween('transaction.pay_date', [$from_date, $to_date])
            ->whereIn("account_name.account_type_id", [11, 12])
            ->groupBy('transaction.account_name_id')
            ->orderBy('transaction.pay_date', "ASC")
            ->get();
    }

    public static function getAllJournalByAccountType($conId = null, $from_date, $to_date){
        if (!empty($conId) ){
            $journal = \DB::table('account_journal as journal')
                ->select('journal.account_name_id',
                    'acc_type.account_name', 'journal.account_type_id',
                    'acc_type.id', 'journal.country_id', 'journal.*'
                )->join('account_type as acc_type', 'acc_type.id','=','journal.account_type_id')
                ->where(["journal.status"=>1, "journal.country_id"=> $conId])
                ->whereBetween('journal.entry_date', [$from_date, $to_date])
                ->groupBy('journal.account_type_id')
                ->orderBy("journal.entry_date", "DESC")->get();
        }else{
            $journal = \DB::table('account_journal as journal')
                ->select('journal.account_name_id',
                    'acc_type.account_name', 'journal.account_type_id',
                    'acc_type.id', 'journal.country_id'
                )->join('account_type as acc_type', 'acc_type.id','=','journal.account_type_id')
                ->where(["journal.status"=>1])
                ->whereBetween('journal.entry_date', [$from_date, $to_date])
                ->groupBy('journal.account_type_id')
                ->orderBy("journal.entry_date", "DESC")->get();
        }
        return $journal;
    }


    public static function getOutstanding($supID){
        return \DB::table('account_journal as journal')
            ->join('booking as book', 'journal.project_number','=','book.project_number')
            ->join("project", "project.project_number", "=", "journal.project_number")
            ->where(["journal.status"=>1, 
                "project.active"    => 1,
                "book.book_status"  => 1,
                "journal.country_id"=> \Auth::user()->country_id])
            ->groupBy('journal.account_type_id')
            ->orderBy("journal.entry_date", "DESC");
    }
}
