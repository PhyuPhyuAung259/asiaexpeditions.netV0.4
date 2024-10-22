<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\component\Content;
use App\AccountJournal;  
use App\Supplier;
use App\AccountTransaction; 
use App\Project;
use App\AccountName;
use App\AccountType;
use App\Country;
use Illuminate\Support\Carbon;
class JournalController extends Controller
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

    public function getGrossProfitePL(Request $req){ 
        $projects = Project::where(["project_status"=>1, "active"=>1])
                ->whereBetween('project_end', [$this->start_date, $this->end_date])
                ->whereNotIn("project_fileno", ["", "Null", 0])->orderBy("project_start")->get();
        return view("admin.account.report.gross_profit_p_and_l", compact("projects"));
    }

    public function getGrossProfitePLPreview(Request $req){
        $projectId = isset($req->projectId) ? $req->projectId : [0];
        $projects = Project::where(['active'=>1])->whereIn('id', $projectId)->orderBy("project_start")->get();
        return view("admin.account.report.gross_profit_p_and_l_preview", compact("projects", "projectNo", "currentDate", "endDate"));
    }

    public function searchGrossProfitPL(Request $req){
        if (isset($req->printBtn) && $req->printBtn == "print") {
            $projectId = isset($req->projectId) ? $req->checkSegment : [0];
            $projects=Project::where(['active'=>1])->whereNotNull('project_fileno')->whereIn('id', $projectId)->orderBy("project_start")->get();
            return redirect()->route('getGrossProfitePLPreview', ['projects'=>'preview_project_pandl_printing', 'projectId' => $req->checkSegment]);
        }
        $currentDate = $this->start_date;
        $endDate = $this->end_date;
        $projectNo = $req->textSearch;
        if (isset($projectNo) && !empty($projectNo)) {
            $projects = Project::where(["project_status"=>1, "active"=>1, "project_number"=>$projectNo])
                ->orWhere('project_fileno', $projectNo)
                ->orWhere('project_client', 'like', $projectNo. '%')
                ->whereNotNull("project_fileno")->orderBy("project_start")->get();
        }elseif (!empty($req->start_date) && !empty($req->end_date)) {
            $projects = Project::where(["project_status"=>1, "active"=>1])->whereBetween('project_start', [$currentDate, $endDate])->whereNotNull("project_fileno")->orderBy("project_start")->get();
        }else{
            $projects = Project::where(["project_status"=>1, "active"=>1])->whereBetween('project_start', [$currentDate, $endDate])->whereNotNull("project_fileno")->orderBy("project_start")->get();
        }
        return view("admin.account.report.gross_profit_p_and_l", compact("projects", "projectNo", "currentDate", "endDate"));
    }

    public function getOfficeSupplier (Request $req){
        $proNo = $req->search;
        $journalType = isset($req->journal_type) ? $req->journal_type : 0; 
        if (isset($req->search) && !empty($req->search)) {
            $OfficeSupplier = AccountJournal::where([["project_number", 'LIKE', "%".$proNo."%"]])
                ->whereNull('project_number')
                ->orwhere("project_fileNo", 'LIKE', "%".$proNo."%")
                ->groupBy('project_number')

                ->orderBy("project_fileNo", "ASC")->get();
        }elseif (isset($req->from_date) && isset($req->to_date)) {
            $OfficeSupplier = AccountJournal::where(['status'=>1])->whereNull('project_number')              
                ->whereBetween('entry_date', [$this->start_date, $this->to_date])
                ->groupBy('project_number')
                ->orderBy("project_fileNo", "DESC")->get();
        }elseif (isset($req->search) && isset($req->from_date) && isset($req->to_date)) {
            $OfficeSupplier = AccountJournal::where(['status'=>1, 'country_id' => \Auth::user()->country_id])
                ->whereNull('project_number')
                ->whereBetween('entry_date', [$this->start_date, $this->to_date])
                ->groupBy('project_number')
                ->orderBy("project_fileNo", "DESC")->get();
        }else{
            $OfficeSupplier = AccountJournal::where(['status'=>1])
                    ->whereNull("project_number")
                    ->groupBy('supplier_id')
                    ->orderBy("project_number", "DESC")->get();
        }
        return view('admin.account.office_supplier_list', compact('OfficeSupplier'));
    }

    public function getJournalReport(Request $req){
    	$journalID = isset($req->journal_entry)? $req->journal_entry:0;
    	$journal = AccountJournal::where(["project_number"=>$journalID, 'status'=>1])->first();

        $start_date = $this->start_date;
        $end_date = $this->end_date;

        if ( isset($req->journal_entry) && !empty($req->journal_entry) ) {
            $journalList = AccountJournal::where(["project_number"=>$req->journal_entry,'status'=>1])->orderBy("entry_date", "ASC");
            $project = Project::where(['project_number'=>$req->journal_entry])->first();
            return view('admin.account.journal-entry-preview', compact("journalList", "project"));
        }elseif (isset($req->outstanding)) {
            $journalList = AccountJournal::where(["project_number"=>$req->outstanding, 'status'=>1])->orderBy("entry_date", "ASC");
            $project = Project::where(['project_number'=> $req->outstanding])->first();
            return view('admin.account.report.project_report_outstanding', compact("journalList", "project"));
        }elseif (isset($req->project_by_date)) {
            $project = Project::where(['project_number'=> $req->outstanding])->first();
        }elseif (isset($req->office_supplier)) {
            $supplier = Supplier::find($req->office_supplier);
            $OfficeSupList = AccountJournal::where(['status'=>1, 'supplier_id' => $req->office_supplier])->whereNull('project_number')->orderBy("project_number", "ASC")->get();
            return view('admin.account.office_supplier_preview', compact("OfficeSupList", 'supplier'));
        }elseif (isset($req->office_supplier_report)) {
            $supplier = Supplier::find($req->office_supplier_report);
            $OfficeSupList = AccountTransaction::where(['status'=>1, 'supplier_id' => $req->office_supplier_report])->whereNull('project_number')->orderBy("entry_date","ASC")->get();
            return view('admin.account.report.office_supplier_report', compact("OfficeSupList", "supplier"));
        }elseif ( isset($req->journal) ) {
            if (isset($req->country) && $req->country != 0) {
                $countryId = $req->country ;
            }else{
                return $countryId = isset($req->country) ? $req->country : \Auth::user()->country_id;
            }
            return view('admin.account.report.journal_report', compact("getJournalReport", 'start_date', 'end_date'));
        }else{
            return redirect()->route('journalList');
        }    
    }
 
    public function getCashbook(Request $req){
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $countryId = isset($req->country) ? $req->country : \Auth::user()->country_id;
        $accTransaction = AccountTransaction::whereIn('type', array(1,2))->where(['status'=>1, 'country_id' => $countryId])
            ->whereBetween('invoice_pay_date', [$start_date, $end_date])
            ->orderBy("invoice_pay_date", "ASC")->get();
        return view("admin.account.report.cash-book", compact('accTransaction', 'start_date'));
    } 

    public function getDailytCashbook(Request $req){
        $start_date = $this->start_date;
        $end_date   = $this->end_date;
        $supplier  = Supplier::find($req->sub_book);
        $supplier_id = isset($req->sub_book) ? $req->sub_book : '';
        $countryId = isset($req->country) ? $req->country : \Auth::user()->country_id;
        $country   = Country::find($countryId);   
        $AmountOpenBalance = AccountTransaction::where(['supplier_id'=>$req->sub_book, 'status'=>1])->whereDate('invoice_pay_date', '<', $start_date)->get();
        $accTransaction=AccountTransaction::where(['supplier_id'=>$supplier_id,'status'=>1])
                    ->whereBetween('invoice_pay_date', [$start_date, $end_date])
                    ->orderBy('invoice_pay_date', 'ASC')->get();
        $cashBookedproject = AccountTransaction::where(["country_id"=>$countryId, 'supplier_id'=>$supplier_id, 'status'=>1])
                    ->whereBetween('invoice_pay_date', [$start_date, $end_date])->whereNotNull("project_id")->get();
        return view("admin.account.report.daily-cash-book", compact('AmountOpenBalance', 'country', 'accTransaction', 'start_date', 'end_date', 'supplier', 'cashBookedproject'));
    }           

    public function getTrialBalance(Request $req){ 
        $period = $this->start_date;
        $conId = isset($req->country) ? $req->country : \Auth::User()->country_id;
        $account_type = AccountType::where('status', 1)->get();
        if (isset($req->account_type) && !empty($req->account_type)) {
            $acc_name = AccountType::find($req->account_type);
            $acc_journal = AccountJournal::where(["account_type_id"=>$req->account_type, 'status'=>1])->groupBy("account_name_id")->get();
            return view('admin.account.report.account_type_preview', compact('acc_journal', "period", 'acc_name', 'conId'));
        }elseif (isset($req->account_name) && !empty($req->account_name)) {
            $acc_name    = AccountName::find($req->account_name);
            $acc_journal = AccountTransaction::where(['account_name_id'=> $req->account_name, 'status'=>1])->get();
            return view('admin.account.report.account_name_preview', compact('acc_journal', "period", 'acc_name', 'conId'));        
        }else{            
            return view('admin.account.report.trial_balance', compact('account_type', 'period', 'conId'));
        }
    }

    public function getOutstanding(Request $req){
        $from_date = $this->start_date;
        $to_date = $this->end_date;
        $supplier = Supplier::find($req->supplier_name);
        $conId = isset($req->country) ? $req->country : \Auth::user()->country_id;
        if($supplier===Null){
            $journals= collect();
        }else{
            $journals=AccountJournal::where(['status'=>1, 'supplier_id'=>$supplier['id']])->whereBetween('entry_date', [$this->start_date, $this->end_date])
                    ->whereNotNull('supplier_id')->orderBy('created_at', 'DESC')->get();
            if (isset($req->journCheck) && !empty($req->journCheck)) {
                $journals=AccountJournal::whereIn('id', $data)->orderBy('entry_date', 'DESC')->get();
                return view("admin.account.report.previewOutstanding", compact('journals','from_date', 'to_date'));
            }
        }
         
        return view("admin.account.outstanding_by_supplier", compact('journals','supplier', 'conId', 'from_date', 'to_date'));
    }
 
    public function getAccountStatement(Request $req){
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $conId = isset($req->country) ? $req->country : \Auth::user()->country_id;
        $journals = AccountTransaction::profitAndLoss($conId, $start_date, $end_date)->whereIn('acc_type.id', [8,9,10,11,12])->orderBy("acc_type.order", 'ASC')->get();
        return view("admin.account.account_statement", compact('journals', 'start_date','end_date', 'conId'));
    }


    public function getBalanceSheet(Request $req){
        $start_date = $this->start_date;
        $end_date   = $this->end_date;
        $conId = isset($req->country) ? $req->country : \Auth::user()->country_id;
        $journals = AccountTransaction::profitAndLoss($conId, $start_date, $end_date)->orderBy("acc_type.order", 'DESC')->get();
        return view("admin.account.balance_sheet", compact('journals', 'start_date','end_date', 'conId'));
    }


    public function getAccountReport(Request $req){  
        $start_date = $this->start_date;
        $getAccByTran = AccountTransaction::getAccountByTransaction(4);
        if (isset($req->reportid) && !empty($req->reportid)) {
            $accountName = AccountName::find($req->reportid);
            $viewReportID = AccountTransaction::where(['account_name_id' => $req->reportid])->get();
            return view("admin.account.report.report_detail", compact('viewReportID', 'accountName'));
        }
        return view("admin.account.report.account_report", compact('getAccByTran', 'start_date '));
    }

    public function pnlbysegment(Request $req){
        $start_date = isset($req->start_date) ? $req->start_date : $this->start_date;
        $end_date = isset( $req->end_date) ? $req->end_date : $this->end_date;
        $projects = Project::AccountProjectTags($start_date, $end_date)->get();
        if (isset($req->preview_segment) && !empty($req->preview_segment)) {
            $projectNum = $req->preview_segment;
            $project = Project::where('project_number', $projectNum)->first();
            $journals = (new AccountTransaction)->AccountBySegment($project['id']);
            return view("admin.account.report.previewProfitAndLossSegment", compact('journals', 'project', 'start_date', 'end_date'));
        }
        return view("admin.account.report.profil_and_loss_by_segment", compact('projects', 'start_date', 'end_date'));
    }

    public function searchPnlbysegment(Request $req){
        $projectId = isset($req->projectId) ? $req->projectId : [0];
        if (isset($req->printBtn) && $req->printBtn == "print") {
            $previewSegments = Project::where(['active'=>1])->whereIn('id', $projectId)->orderBy('project_start', 'ASC')->get();
            return redirect()->route('getProjectPreview', ['previewSegments'=>'preview_project_printing', 'projectId'=>$req->checkSegment]);
        }
        $start_date  = $this->start_date;
        $end_date    = $this->end_date; 
        $projectNum = $req->textSearch;
        if ( !empty($projectNum) ) { 
            $projects = Project::AccountProjectSearch($projectNum)->get();       
        }else if( !empty($start_date) && !empty($end_date)){
            $projects = Project::AccountProjectTags($start_date, $end_date)->get();
        }else {
            $projects = project::where(['project_number'=>$projectNum, 'project_status'=>1, "active"=>1])
                ->orWhere('project_fileno', $projectNum)
                ->orWhere('project_client', 'like', $projectNum. '%')
                ->whereNotNull("project_fileno")
                ->whereBetween('project_start', [$end_date, $end_date])
                ->orderBy('project_number', 'DESC')->get();
        }
        return view("admin.account.report.profil_and_loss_by_segment", compact('projects', 'projectNum', 'start_date', 'end_date'));
    }

    public function getProjectPreview(Request $req){
        $projectId = isset($req->projectId) ? $req->projectId : [0];
        $project_preview = Project::where(['active'=>1])->whereIn('id',$projectId)->orderBy("project_start")->get();
        return view('admin.account.report.project_preview', compact('project_preview'));
    }

    public function getProfitAndLoss(Request $req){
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $conId = isset($req->country) ? $req->country : \Auth::User()->country_id;
        $projectNum =  $req->projectNum;
        if (isset($req->projectNum) && $req->projectNum > 0 ) {
            $getGrossProfite = AccountJournal::where(['country_id'=>$conId, 'status'=>1])
                ->whereHas('project', function ($query) use ($start_date, $end_date, $projectNum) {
                    $query->where('project_fileno', 'like', $projectNum. '%');
                    $query->whereBetween('project_start', [$start_date, $end_date]);
                })->groupBy('project_id')->get();
        }else{
             $getGrossProfite = AccountJournal::where(['country_id'=>$conId, 'status'=>1])
                ->whereHas('project', function ($query) use ($start_date, $end_date) {
                    $query->whereBetween('project_start', [$start_date, $end_date]);
                })->groupBy('project_id')->get();
        }
        
        if (isset($req->checkbox)) {
           $journals = AccountJournal::where('status', 1)->whereIn("id", $req->journCheck)->whereHas('project', function ($query) {
                $query->orderBy('project_start');
            })->groupBy('project_id')->get();
            return view("admin.account.report.previewProfitAndLoss", compact('journals', 'start_date', 'end_date', 'projectNum'));
        }
        return view ("admin.account.report.profit_and_loss",compact('getGrossProfite', 'start_date', 'end_date', 'conId', 'projectNum'));
    }

}
