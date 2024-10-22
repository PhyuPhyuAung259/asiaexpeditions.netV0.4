<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'tbl_bank';

    Public function banktransfer(){
    	return $this->belongsTo(BankTransfer::class);
    }

    public function user(){
        return $this->hasMany(User::class);
    }

    public static function getBankReceived( $bankId = 0){
    	return \DB::table('tbl_bank as bank')
            ->join('account_transaction as acc_tran', 'acc_tran.bank_id', '=', 'bank.id')
            ->where(['bank.id'=> $bankId]);
            // ->orderBy('room_supplier.id', 'ASC');
    }

    public static function getBankTransferred( $bankId = 0){
    	return \DB::table('tbl_bank as bank')
            ->join('bank_transfer as bank_tran', 'bank_tran.bank_to', '=', 'bank.id')
            ->where(['bank.id'=> $bankId]);
            // ->orderBy('room_supplier.id', 'ASC');
    }


    public static function getBankReportByTransaction($bankId){
    	return \DB::table('tbl_bank as bank')
            ->select('bank.name'
                ,'tran.debit','tran.credit', 'tran.kdebit','tran.kcredit', 'tran.user_id','tran.invoice_pay_date','tran.pay_date','tran.pay_date','tran.total_amount','tran.total_kamount', 'tran.remark', 'tran.ex_rate'
            
            )
            ->Leftjoin('account_transaction as tran','bank.id','=','tran.bank_id')
            ->LeftJoin('account_transaction as tran_to','tran_to.bank_to','=','bank.id')
            ->where(['bank.id'=> $bankId])
            // ->groupBy("bank.id")
            ->orderBy("tran.pay_date", "ASC")
            ->get();
    }


    public static function getBankByCountry($bankId, $counID){
        return \DB::table('tbl_bank as bank')
            ->select('bank.name'
                ,'tran.debit','tran.credit', 'tran.kdebit','tran.kcredit', 'tran.user_id','tran.invoice_pay_date','tran.pay_date','tran.pay_date','tran.total_amount','tran.total_kamount', 'tran.remark', 'tran.ex_rate')
            ->Leftjoin('account_transaction as tran','bank.id','=','tran.bank_id')
            ->where(['tran.bank_id'=> $bankId, "bank.country_id"=> $counID])
            ->orWhere(['tran.bank_to'=> $bankId]);
            
    }

    public function acc_transaction(){
        return $this->hasMany(AccountTransaction::class);
    }
}
