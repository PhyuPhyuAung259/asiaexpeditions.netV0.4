<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankTransfer extends Model
{
    protected $table = "bank_transfer";

    public function bank_from (){
    	return $this->hasMany(Bank::class, "bank_from");
    }

    public function bank_to (){
    	return $this->hasMany(Bank::class, "bank_to");
    }

    // public static function getBankAmount(){
    // 	return \DB::table('tbl_bank as bank')
    //         ->join('account_transaction as acc_tran', 'acc_tran.bank_id', '=', 'bank.supplier_id')
    //         ->join('room', 'room.id', '=', 'room_supplier.room_id')
    //         ->select('room_supplier.*','suppliers.supplier_name', 'room.name')
    //         ->where(['suppliers.country_id'=> $locationId]) 
    //         ->orderBy('room_supplier.id', 'ASC')
    //         ->get();
    // }
}
