<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model 
{
    //
    protected $table = "account_transaction";
 
    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function supplier_book(){
        return $this->belongsTo(Supplier::class, 'supplier_book');
    }

    public function account_type(){
        return $this->belongsTo(AccountType::class, "account_type_id")->orderBy("order", "DESC");
    } 

    public function business(){
        return $this->belongsTo(Business::class);
    } 

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function account_name(){
        return $this->belongsTo(AccountName::class, "account_name_id");
    }

    public function acc_journal(){
        return $this->belongsTo(AccountJournal::class, "journal_id");
    }

    public function project (){
        return $this->belongsTo(Project::class);
    }

    public function misc_service(){
        return $this->belongsTo(MISCService::class, "supplier_id");
    }

    public function ent_service(){
        return $this->belongsTo(Entrance::class, "supplier_id");
    }

    public function bank(){
        return $this->belongsTo(Bank::class);
    }

    public function bank2(){
        return $this->belongsTo(Bank::class, 'bank_to');
    }

    public static function getAccountByTransaction($acc_type_id =4){
        return \DB::table('account_transaction as tran')
            ->select('acc_type.account_name as type_name',
                'acc_name.account_name as acc_name', 
                'acc_name.id as nameID',
                'acc_type.id as typeID',
                'acc_name.account_code', 
                'tran.debit','tran.credit', 
                'tran.kdebit','tran.kcredit', 
                'tran.invoice_pay_date',
                'tran.pay_date','tran.pay_date', 
                'tran.total_amount',
                'tran.total_kamount', 
                'tran.remark',
                'tran.supplier_id','tran.user_id','tran.journal_id', 'tran.bank_id', 'tran.bank_to','tran.currency_to', 'tran.currency_id',
                'tran.project_number', 
                'tran.ex_rate'    
            )
            ->join('account_type as acc_type', 'acc_type.id','=','tran.account_type_id')
            ->join('account_name as acc_name', 'acc_name.id','=','tran.account_name_id')
            ->where(['acc_type.id'=> $acc_type_id])
            ->groupBy('tran.account_name_id')
            ->orderBy('acc_type.order', 'ASC')->get();
    } 


    public static function getAccBookSupplier($con_id = 0){
        return \DB::table('account_transaction as tran')
            ->join('suppliers', 'suppliers.id','=','tran.supplier_book')
            ->select("suppliers.id as supplier_id", "suppliers.*")
            ->where(["tran.country_id"=>$con_id, 'suppliers.business_id'=> 5])
            ->groupBy("tran.supplier_book")
            ->orderBy("suppliers.supplier_name")->get();
    }

    public static function profitAndLoss($conId, $start_date, $end_date){
        return \DB::table('account_transaction as tran')
            ->join('account_type as acc_type', 'acc_type.id','=','tran.account_type_id')
            ->join('account_name as acc_name', 'acc_name.id','=','tran.account_name_id')
            ->select("acc_type.*", "tran.*")
            ->where(['tran.status'=>1, 'tran.country_id'=>$conId])
            ->whereBetween('tran.invoice_pay_date', [$start_date, $end_date])
            ->whereNotIn('tran.account_type_id', ['', 0, "Null"])
            ->whereNotIn('tran.account_name_id', ['', 0, "Null"])
            // ->whereIn('acc_type.id', [8,9,10,11,12])
            ->groupBy("tran.account_type_id");
    }

    public static function AccountBySegment($projectId){
        return \DB::table("account_type as acc_type")
            ->leftJoin('account_transaction as tran', 'acc_type.id', '=','tran.account_type_id')
            ->select('tran.*', 'acc_type.*')
            ->where(['acc_type.status'=>1, 'tran.status'=>1])
            ->whereIn('acc_type.id', [8,9,10,11,12])
            ->groupBy('tran.account_type_id')
            ->orderBy('acc_type.order', 'ASC')->get();
            
    }

    public static function accNameByAccType($conId, $start_date, $end_date, $acc_type){
        return \DB::table('account_transaction as tran')
            ->join('account_name as acc_name', 'acc_name.id','=','tran.account_name_id')
            ->select("acc_name.*", "tran.*")
            ->where(['tran.status'=>1, 'tran.country_id'=>$conId, 'tran.account_type_id'=>$acc_type])
            ->whereBetween('tran.invoice_pay_date', [$start_date, $end_date])
            ->whereNotIn('tran.account_name_id', ['', 0, "Null"])
            ->groupBy("tran.account_name_id")
            ->orderBy("acc_name.account_code", 'ASC')->get();
    }

    

    public static function getAccBookByCity($con_id = 0){
        return \DB::table('suppliers as sup')
            ->join('account_transaction as tran', 'tran.supplier_id','=','sup.id')
            ->select("sup.id as supplier_id", "tran.country_id as con_id", "tran.*", "sup.*")
            ->where(["tran.status"=>1, 'tran.country_id'=>$con_id, 'sup.business_id'=>5])
            ->orderBy('sup.supplier_name');
    }

    public static function getCashBookBySupplier(){
        return \DB::table('suppliers as sup')
            ->join('account_transaction as tran', 'tran.supplier_id','=','sup.id')
            ->select("sup.id as supplier_id", "tran.country_id as con_id", "tran.*", "sup.*")
            ->where(["tran.status"=>1, 'sup.business_id'=>5]);
    }

    public static function getAccBookByCitySupplierBook( $pro_id = 0){
        return \DB::table('suppliers')
            ->LeftJoin('account_transaction as tran', 'tran.supplier_id','=','suppliers.id')
            ->select("suppliers.id as supplier_id", "tran.country_id as con_id", "tran.*", "suppliers.*")
            ->where(["tran.status"=>1, 'suppliers.province_id'=> $pro_id]);
    }

    public static function getJournalTransactionBySupplierBook($con_id = 0, $supplier_book = 0, $from_date, $to_date){
        return \DB::table('account_transaction as tran')
            ->join('suppliers as supplier', 'supplier.id','=','tran.supplier_book')
            ->join('account_journal as journal', 'journal.id','=','tran.journal_id')
            ->select("tran.id as transaction_id", "journal.id as journal_id", "tran.*", "journal.*")
            ->where(["tran.country_id"=>$con_id, "tran.country_id as con_id", 'tran.supplier_book'=> $supplier_book, 'journal.status'=>1, 'tran.status'=>1, 'supplier.business_id' => 5])
            ->whereBetween('tran.invoice_pay_date', [$from_date, $to_date])
            // ->groupBy("tran.invoice_pay_date")
            ->groupBy("tran.supplier_book")
            ->orderBy("tran.invoice_pay_date", "ASC")->get();

    }

    public static function getJournalTransactionByBetweenDate($con_id = 0, $from_date, $to_date){
        return \DB::table('account_transaction as tran')
            ->join('suppliers as supplier', 'supplier.id','=','tran.supplier_book')
            ->join('account_journal as journal', 'journal.id','=','tran.journal_id')
            ->select("tran.id as transaction_id", "journal.id as journal_id", "tran.*", "journal.*")
            ->where(["tran.country_id"=>$con_id, 'journal.status'=>1, 'tran.status'=>1, 'supplier.business_id' => 5])
            ->whereBetween('tran.invoice_pay_date', [$from_date, $to_date])
            ->groupBy("tran.supplier_book")
            ->orderBy("tran.invoice_pay_date", "ASC")->get();
    }

    public static function getJournalTransactionCurrentDate($from_date, $con_id = 0){
        return \DB::table('account_transaction as tran')
            ->join('account_journal as journal', 'journal.id','=','tran.journal_id')
            ->select("tran.id as transaction_id", "journal.id as journal_id", "tran.*", "journal.*")
            ->where(['journal.status'=>1, 'tran.status'=>1, 'tran.country_id'=>$con_id])
            ->whereDate("invoice_pay_date", $from_date)
            ->groupBy("invoice_pay_date")
            ->orderBy("invoice_pay_date", "ASC")->get();
    }

    public static function supplierByAccountTransaction($bus_id=0, $con_id = 0){
        return Supplier::where(['supplier_status'=>1, 'business_id'=>$bus_id, 'country_id'=> $con_id])->select('supplier_name', 'id')
                        ->whereHas('account_journal', function($query) {
                            $query->where(['status'=>1]);
                        })->orderBy('supplier_name')->get();
    }

    
}
