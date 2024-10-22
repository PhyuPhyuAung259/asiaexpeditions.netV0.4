<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountJournal;
use App\Booking;
use App\AccountType;
use App\AccountName; 
use App\component\Content;
use App\Supplier;
use App\BalanceTransaction;
use Illuminate\Support\Facades\Crypt;
use App\Project;
use App\Bank;
use App\AccountTransaction;
use App\BankTransfer;
class AccountController extends Controller
{  
    
    public $start_date;
    public $end_date;

    public function __construct(Request $req)
    {
        $Enddate = date("Y-m-d", strtotime("+1 month"));
        $d =  Content::diffDate(date('Y-m-d'), $Enddate);         
        $this->start_date = isset($req->start_date)? $req->start_date : date('Y-m-y');
        $this->end_date = isset($req->end_date)? $req->end_date : date('Y-m-').$d;
    }

    public function getAccountReceivable(Request $req){
        $journal = AccountJournal::find($req->journal_id);
        if ($journal) {            
            return view('admin.account.recievable', compact('journal'));
        }
        return view('admin.account.recievable');
    } 

    public function PreviewPosted(Request $req){
        $view_type = $req->type;
        $preview_posted = AccountJournal::whereIn('id', $req->value_checked)->orderBy("project_id", "ASC")->get();
        return view('admin.account.report.preview_posted', compact('preview_posted', 'view_type') );
    }

    public function getLedger(){
        $ledgers = AccountJournal::where("status", 1)->orderBy('created_at', 'DESC')->get();
        return view("admin.account.index", compact('ledgers'));
    }

    public function createJournal(Request $req){
        try{
            foreach ($req->account_type as $key => $acc_type) {
                $type = $req->debit[$key] > 0 || $req->kyatdebit[$key] > 0 ? 1 : 2;
                $jngCode = AccountJournal::latest('entry_code')->first();
                $entry_code = sprintf("%04d", $jngCode['entry_code'] + 1);
                $crJournal = new AccountJournal;
                $crJournal->entry_date = $req->entry_date;
                $crJournal->user_id = \Auth::user()->id;
                $crJournal->business_id     = $req->business[$key];
                $crJournal->supplier_id     = $req->supplier[$key];
                $crJournal->country_id      = $req->country;
                $crJournal->account_name_id = $req->account_name[$key];
                $crJournal->debit           = $req->debit[$key]; 
                $crJournal->credit          = $req->credit[$key];
                $crJournal->kcredit         = $req->kyatcredit[$key];
                $crJournal->kdebit          = $req->kyatdebit[$key];  
                $crJournal->book_amount     = $req->debit[$key] ? $req->debit[$key] : $req->credit[$key];
                $crJournal->book_kamount    = $req->kyatcredit[$key] ? $req->kyatcredit[$key] : $req->kyatcredit[$key];
                $crJournal->account_type_id = $acc_type;
                $crJournal->entry_code      = $entry_code;
                $crJournal->type            = $type;
                $crJournal->save();      
            }
            $messagetype = "success";
            $message = "Journal Entry Successfully Created";
            $status_icon = "fa-check-circle";
        } catch (Exception $e) {
            $messagetype = "warning";
            $message = "Journal Entry can not create";
            $status_icon = "fa-exclamation-circle";
        }
        return response()->json(["message"=> $message, "messagetype"=>$messagetype, "status_icon"=>$status_icon, "collect"=>$req->all()]);
    }


    public function addNewAccount(Request $req){
        try{
            if (!AccountName::checkAccountName($req->code)) {
                $addAcc = new AccountName;
                $addAcc->account_type_id = $req->account_type;
                $addAcc->account_name    = $req->name;
                $addAcc->account_code    = $req->code;
                $addAcc->account_desc    = $req->account_desc;
                $addAcc->save();
                $message = "Your Account Name Successfully Added";
                $messagetype = "success";
            }else{
                $message = "Your Account Code Already Exit";
                $messagetype = "warning";
            }
        } catch (Exception $e) {
            $messagetype = "warning";
            $message = "Account Receivable Not Confirm";
            $status_icon = "fa-check-circle";
        }
        return response()->json(["message"=> $message, "messagetype"=>$messagetype, "status_icon"=> $status_icon, "collect"=> $req->all()]);
    }   
    
    public function getJournalJson(){      
        $suppliers = Booking::whereNotIn("hotel_id", ["","Null"])->groupBy('hotel_id')->orderBy('book_checkin', 'ASC')->get();
        return view("admin.account.journal", compact('suppliers'));
    }

    public function udpateExchangeRate(Request $req){
        if ($req->ex_rate) {
            $total_ex_converted = $req->kamount / $req->ex_rate;
        }else{
            $total_ex_converted = '';
        }
        $acctran = AccountTransaction::where('entry_code', $req->tran_entry_code)
                ->update(['ex_rate_converted'=>$total_ex_converted, 
                        'ex_rate'=>$req->ex_rate, 
                        'account_type_id'   =>$req->account_type, 
                        'invoice_pay_date'  =>$req->invoice_pay_date,
                        'invoice_rc_date_from_sup' =>$req->invoice_from_sup,
                        'remark'            =>$req->payment_detail,
                        'invoice_number'    =>$req->invoice_number,
                        'payment_voucher'   =>$req->payment_voucher,
                    ]);
        $message = "Payment Transaction have been changed";
        $messagetype = "success";
        $status_icon = "fa-check-circle";
        return back()->with(['message'=> $message, 'status'=> $messagetype, 'status_icon'=> $status_icon, "bus_type" => $req->bus_type]);
    }


    public function getPayable(Request $req){
        $journal = AccountJournal::find($req->journal_id);
        if ($journal) {    
            $getAccTran = AccountTransaction::where(["journal_id"=> $req->journal_id, 'type'=> $journal->type]);
            return view('admin.account.payable', compact('journal'));
        }
        return view('admin.account.payable');        
    }

  
    public function getJournalEdit(Request $req){
        $getAccJournal = AccountJournal::find($req->eid);
        return response()->json(["collect"=> $getAccJournal]);
    }
    

    public function createPayment(Request $req){
        try {
            $actCode = AccountTransaction::latest('entry_code')->first();
            $entry_code = sprintf("%08d", (int)$actCode['entry_code'] + 1);
            if ($req->exchange_rate) {
                $total_ex_converted = $req->pay_kamount / $req->exchange_rate;
            }else{
                $total_ex_converted =0;
            }
            $acctran = New AccountTransaction;
            $acctran->user_id  = \Auth::user()->id;
            $acctran->journal_id      = $req->journal_id;
            $acctran->business_id     = $req->business;
            $acctran->country_id      = $req->country;
            $acctran->province_id     = \Auth::user()->province_id;
            $acctran->supplier_id     = $req->type == 2 ? $req->supplier_to : $req->supplier_from;
            $acctran->supplier_book   = $req->type == 1 ? $req->supplier_to : $req->supplier_from;
            $acctran->project_id      = $req->projectNo;
            $acctran->account_type_id = $req->account_type_id;
            $acctran->account_name_id = $req->account_name_id;
            $acctran->invoice_number  = $req->invoice_number;
            $acctran->pay_date        = $req->pay_date;
            $acctran->entry_code      = $entry_code;
            $acctran->invoice_pay_date= $req->invoice_pay_date;
            $acctran->invoice_rc_date_from_sup = $req->invoice_from_sup;
            $acctran->credit          = $req->type == 1 ? $req->deposit_amount : '';
            $acctran->debit           = $req->type == 2 ? $req->deposit_amount : '';
            $acctran->kcredit         = $req->type == 1 ? $req->deposit_kamount : '';
            $acctran->kdebit          = $req->type == 2 ? $req->deposit_kamount : '';
            $acctran->total_amount    = $req->pay_amount;
            $acctran->bankfees        = $req->bank_fees;
            $acctran->total_kamount   = $req->pay_kamount;      
            $acctran->ex_rate         = $req->exchange_rate;
            $acctran->ex_rate_converted  = $total_ex_converted;
            $acctran->payment_voucher = $req->payment_voucher;
            $acctran->type            = $req->type;
            $acctran->remark  = $req->remark;
            if ($acctran->save()) {
                $secondTran = New AccountTransaction;
                $secondTran->user_id  = \Auth::user()->id;
                $secondTran->journal_id      = $req->journal_id; 
                $secondTran->business_id     = $req->business;
                $secondTran->country_id      = \Auth::user()->country_id;
                $secondTran->province_id     = \Auth::user()->province_id;
                $secondTran->supplier_id     = $acctran->supplier_book;
                $secondTran->supplier_book   = $acctran->supplier_id;
                $secondTran->project_id      = $req->projectNo;
                $secondTran->account_type_id = $req->account_type_id;
                $secondTran->account_name_id = $req->account_name_id;
                $secondTran->invoice_number  = $req->invoice_number;
                $secondTran->pay_date        = $req->pay_date;
                $acctran->bankfees           = $req->bank_fees;
                $secondTran->entry_code      = $entry_code;
                $secondTran->invoice_pay_date= $req->invoice_pay_date;
                $secondTran->invoice_rc_date_from_sup = $req->invoice_from_sup;
                $secondTran->credit          = $req->type == 2 ? $req->deposit_amount : '';
                $secondTran->debit           = $req->type == 1 ? $req->deposit_amount : '';
                $secondTran->kcredit         = $req->type == 2 ? $req->deposit_kamount : '';
                $secondTran->kdebit          = $req->type == 1 ? $req->deposit_kamount : '';                
                $secondTran->total_amount    = $req->pay_amount;
                $secondTran->total_kamount   = $req->pay_kamount;      
                $secondTran->ex_rate         = $req->exchange_rate;
                $secondTran->ex_rate_converted  = $total_ex_converted;
                $secondTran->payment_voucher = $req->payment_voucher;
                $secondTran->type            = $req->type == 1 ? 2 : 1;
                $secondTran->remark  = $req->remark;
                $secondTran->save();
            }
            $messagetype = "success";
            $message = "Payment Transaction Completed";
            $status_icon = "fa-check-circle";
        } catch (Exception $e) {
            $messagetype = "warning";
            $message = "Payment can not create";
            $status_icon = "fa-exclamation-circle";
        }
        return response()->json(["message"=>$message, "messagetype"=>$messagetype, 'status_icon'=>$status_icon]);
    } 

    public function editJournal(Request $req){
        $acctran = AccountJournal::find($req->journal_id);
        if ($acctran) {
            $acctran->user_id  = \Auth::user()->id;
            $acctran->entry_date      = $req->pay_date;
            $acctran->account_type_id = $req->account_type;
            $acctran->account_name_id = $req->account_name;
            $acctran->credit          = $req->credit;
            $acctran->debit           = $req->debit;
            $acctran->kdebit          = $req->kyatdebit;
            $acctran->kcredit         = $req->kyatcredit;
            $acctran->book_amount     = $req->credit ? $req->credit : $req->debit;
            $acctran->book_kamount    = $req->kyatdebit ? $req->kyatdebit : $req->kyatcredit;    
            $acctran->remark          = $req->payment_desc;
            $acctran->save();
            $messagetype = "success";
            $status_icon = "fa-check-circle";
            $message = "Journal Successfully Updated";
        }else{
            $messagetype = "warning";
            $message = "Journal can not Update";
            $status_icon = "fa-exclamation-circle";
        }
        return back()->with(["message"=>$message, "status"=>$messagetype, 'status_icon'=> $status_icon]);
    }

    public function getTransferForm(Request $req){
        $getbTransfer = AccountTransaction::where(['type'=>4, 'status'=>1])->paginate(12);
        return view("admin.account.bank_transfer", compact('getbTransfer'));
    }

    public function getBankPreview(Request $req){
        $bank = Bank::find($req->preview_bank);
        $bankTransactionPreview = AccountTransaction::where(['bank_id'=> $req->preview_bank, 'status'=>1])
                        ->orWhere("bank_to", $req->preview_bank)
                        ->whereBetween('pay_date', [$this->start_date, $this->end_date])
                        ->get();
        if (isset($req->start_date) && isset($req->end_date)) {
            $bankTransactionPreview = AccountTransaction::where(['bank_id'=> $req->preview_bank, 'status'=>1])
                        ->whereBetween('pay_date', [$this->start_date, $this->end_date])
                        ->orWhere("bank_to", $req->preview_bank)                        
                        ->get();
        }
        return view("admin.account.report.bank_preview", compact('bankTransactionPreview', 'bank'));
    }

    public function getPostingAccount(Request $req){
        $projects = Project::AccountProjectTags($this->start_date, $this->end_date)->get();
        return view("admin.account.report.posting_account", compact('projects'));
    }

    public function findPostingAccount(Request $req){
        $startDate  = $this->start_date;
        $endDate    = $this->end_date; 
        $projectNum = $req->textSearch;
        if ( !empty($projectNum) ) { 
            $projects = Project::AccountProjectSearch($projectNum, 1)->get();       
        }else if( !empty($startDate) && !empty($endDate)){
            $projects = Project::AccountProjectTags($startDate, $endDate, 1)->get();
        }else {
            $projects = project::where(['project_number'=>$projectNum, 'project_status'=>1, 'active'=>1])
                        ->orWhere('project_fileno', $projectNum)
                        ->Where('project_client', 'like', $projectNum. '%')
                        ->whereNotNull("project_fileno")
                        ->whereBetween('project_start', [$startDate, $endDate])
                        ->orderBy('project_number', 'DESC')->get();
        }
        return view("admin.account.report.posting_account", compact('projects', 'projectNum', 'startDate', 'endDate'));
    }

    public function previewPosting($projectNo, $type){
        $project = Project::Where('project_number', $projectNo)->first();
        $booking = Booking::where(['book_project'=>$projectNo,'book_status'=>1])->groupBy('book_checkin')->orderBy('book_checkin', 'ASC')->get();
        if ($project) {
            return view('admin.account.report.posting_account_preview', compact('project', 'booking', 'type'));
        }else{
            $message =  $projectNo;
            return view('errors.error', compact('message', 'type'));
        }
    }

    public function makeToJournal(Request $req){
        $sup_id = !empty($req->supplier_id) ? $req->supplier_id: $req->supplier_name;
        $getPro = AccountJournal::latest('id')->first();
        $entry_code = sprintf("%07d", $getPro['id'] + 1);
        $project_fileNo = isset($pro->project_fileno) ? $pro->project_fileno:'';
        try{
            $crJournal = new AccountJournal;
            $crJournal->entry_date =    $req->pay_date;
            $crJournal->user_id = \Auth::user()->id;
            $crJournal->business_id     = $req->business_id;
            $crJournal->supplier_id     = $sup_id;
            $crJournal->country_id      = $req->country;
            $crJournal->province_id     = $req->country == 122 ? 126 : 15;
            $crJournal->account_name_id = $req->account_name;
            $crJournal->account_type_id = $req->account_type;
            $crJournal->project_id      = $req->project_id;
            $crJournal->book_id         = $req->book_id;
            $crJournal->project_number  = $req->project_number;
            $crJournal->project_fileno  = $req->project_fileno;
            $crJournal->book_amount     = $req->debit > 0 ? $req->debit : $req->credit;
            $crJournal->book_kamount    = $req->kyatcredit > 0 ? $req->kyatcredit : $req->kyatdebit;
            $crJournal->entry_code      = $entry_code;
            $crJournal->type            = $req->process_type == "pay" ? 1 : 2;
            $crJournal->remark          = $req->payment_desc;
            $crJournal->debit           = $req->debit;
            $crJournal->credit          = $req->credit;
            $crJournal->kcredit         = $req->kyatcredit;
            $crJournal->kdebit          = $req->kyatdebit;                
            $crJournal->save();   
            $messagetype = "success"; 
            $message = "Successfully Posted";
            $status_icon = "fa-check-circle";
            
        } catch (Exception $e) {
            $messagetype = "warning";
            $message = "Payment can not create";
            $status_icon = "fa-exclamation-circle";
        }
        return back()->with(['message'=> $message, 'status'=> $messagetype, 'status_icon'=> $status_icon, "bus_type"=>$req->bus_type]);
    }

    public function accountPayable(Request $req, $view_type){
        $nextMonth = $this->end_date;
        $currentDate = $this->start_date;
        $coun_id = isset($req->country) ? $req->country : \Auth::user()->country_id;
        if ($view_type == "office-supply"){
            $posteData = AccountJournal::where(['status'=>1, 'country_id'=>$coun_id])
            ->whereBetween('entry_date', [$currentDate, $nextMonth])
            ->whereNull("project_number")
            ->orderBy("entry_date", "DESC");
        }elseif ($view_type == "project-booked"){
            $posteData = AccountJournal::where(['status'=>1, 'country_id'=>$coun_id])  
                ->whereBetween('entry_date', [$currentDate, $nextMonth])                 
                ->whereNotNull("project_number")
                ->orderBy("entry_date", "DESC");
        }
        return view("admin.account.account_payable", compact('view_type', 'coun_id', 'posteData',  'currentDate', 'nextMonth'));
    }

    public function openBalance(Request $req){
        if (\Auth::user()->role_id == 2) {
            $BalaceList = AccountTransaction::where(['status'=>1, 'type'=>3])->orderBy('invoice_pay_date', 'ASC')->get();
        }else{
            $BalaceList = AccountTransaction::where(['status'=>1, 'type'=>3, 'user_id'=> \Auth::user()->id])->orderBy('invoice_pay_date', 'ASC')->get();
        }
        
        $acc_tran = AccountTransaction::find($req->eid);
        if ($acc_tran) {
            return view('admin.account.account_open_balance', compact('BalaceList', 'acc_tran'));
        }
        return view('admin.account.account_open_balance', compact('BalaceList'));
        
    }

    public function addBankTransfer(Request $req){
        try {
            if (isset($req->eid) && !empty($req->eid)) {
                $acctran = AccountTransaction::find($req->eid); 
                $userId = $acctran->user_id;
            }else{
                $userId = \Auth::user()->id;
                $acctran = New AccountTransaction;    
            }
            $getPro = AccountTransaction::latest('entry_code')->first();
            
            $entry_code = sprintf("%04d", $getPro['entry_code'] + 1);
            $acctran->user_id           = $userId;    
            $acctran->country_id        = $req->country;
            $acctran->supplier_id       = $req->supplier_id;
            $acctran->account_type_id   = $req->account_type;
            $acctran->account_name_id   = $req->account_name;
            $acctran->invoice_pay_date  = $req->pay_date;
            $acctran->pay_date          = $req->pay_date;
            $acctran->debit             = $req->currency == 3 ? $req->amount : '';
            $acctran->total_amount      = $req->currency == 3 ? $req->amount : '';
            $acctran->kdebit            = $req->currency == 2 ? $req->amount : '';
            $acctran->total_kamount     = $req->currency == 2 ? $req->amount : '';
            $acctran->entry_code        = $entry_code;
            $acctran->currency_id       = $req->currency;
            $acctran->type              = 3;
            $acctran->remark            = $req->memo;
            $acctran->save();
            $sub_bank = Supplier::find($req->supplier_id);
            $messagetype = "success";
            $status_icon = "fa-check-circle";
            $message = $req->amount." Successfully Transferred to ".$sub_bank['supplier_name'];
        } catch (Exception $e) {
            $messagetype = "warning";
            $message = "Bank Transfer can not Update";
            $status_icon = "fa-exclamation-circle";
        }
        return back()->with(["message"=>$message, "status"=>$messagetype, 'status_icon'=>$status_icon]);

    }

    public function createAccountName( Request $req){
        try{
            if ( !AccountName::ExitAccountCode($req->account_code)) {       
                $accName = New AccountName;
                $accName->account_name    = $req->account_name;
                $accName->account_type_id = $req->account_type;
                $accName->account_code    = $req->account_code;
                $accName->account_desc    = $req->desc;
                $accName->country_id  = \Auth::user()->country_id;
                $accName->province_id = \Auth::user()->province_id;
                $accName->user_id     = \Auth::user()->id;
                $accName->save();
                $messagetype = "success";
                $status_icon = "fa-check-circle";
                $message = "<strong>$req->account_name</strong>  have been added";
            }else{
                $messagetype = "warning";
                $message = "Account code exiting in system";
                $status_icon = "fa-exclamation-circle";
            }
        } catch (Exception $e) {
            $messagetype = "warning";
            $message = "Account Name could not create";
            $status_icon = "fa-exclamation-circle";
        }
        return response()->json(["message"=>$message, "messagetype"=>$messagetype, 'status_icon'=> $status_icon]);
    }


    public function AddNewSupplier( Request $req){
        try{
            $addsup = New Supplier;
            $addsup->supplier_name  = $req->title;
            $addsup->country_id     = $req->country;
            $addsup->province_id    = $req->city;
            $addsup->supplier_contact_name = $req->contact_name;
            $addsup->business_id    = $req->business_type;
            $addsup->supplier_phone = $req->supplier_phone;
            $addsup->supplier_phone2= $req->supplier_email;
            $addsup->supplier_intro = $req->desc;
            $addsup->save();
            $messagetype = "success";
            $status_icon = "fa-check-circle";
            $message = "Supplier <strong> $req->title</strong> have been added";
        } catch (Exception $e) {
            $messagetype = "warning";
            $message = "Supplier Name could not create";
            $status_icon = "fa-exclamation-circle";
        }
        return response()->json(["message"=>$message, "messagetype"=>$messagetype, 'status_icon'=> $status_icon]);
    }

}
