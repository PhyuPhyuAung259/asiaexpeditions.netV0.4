<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Company;
use App\Bank;
use App\Setting;
class ThemeController extends Controller
{
    //
    public function getTheme(){
    	return view('admin.setting.theme_option');
    }

    public function getCompany(){
    	$companies = Company::where('status',1)->orderBy('name', "ASC")->get();
    	return view('admin.setting.company_list', compact('companies'));
    }
 
    public function setting(){
        $settings = Setting::all();
        return view('admin.setting.index', compact('settings'));
    }

    public function companyForm(Request $req){
    	$cp = Company::find($req->cp_id);
    	return view("admin.setting.company_form", compact('cp'));
    }

    public function addCompany(Request $req){
        if (!empty($req->company_id)) {
            $ecp = Company::find($req->company_id);
            $ecp->title = $req->title;
            $ecp->name  = $req->name;
            $ecp->address = $req->address;
            $ecp->desc    = $req->desc;
            $ecp->country_id = $req->country;
            $ecp->province_id = $req->city;
            $ecp->status  = $req->status;
            $ecp->save();
            $message = "Company successfully updated";
            $messagetype = "success";
            $status_icon = "fa-check-circle";
        }else{
            $acp = New Company;
            $acp->title = $req->title;
            $acp->name  = $req->name;
            $acp->address = $req->address;
            $acp->desc    = $req->desc; 
            $acp->country_id = $req->country;
            $acp->province_id = $req->city;
            $acp->status  = $req->status;
            $acp->save();
            $messagetype = "success";
            $status_icon = "fa-check-circle";
            $message = "Company successfully added";
        }
        return back()->with(['message'=> $message, 'status'=> 'success', 'status_icon'=> 'fa-check-circle']);
    }

    public function getBank(){
        $banks = Bank::orderBy("type", 'ASC')->get();
        return view("admin.setting.bank", compact('banks'));
    }

    public function settingForm( $setID){
        $setting = Setting::find($setID);
        return view('admin.setting.settingForm', compact('setting'));
    }

    public function updateSetting(Request $req, $setID){
        $add = Setting::find($setID);
        $add->title = $req->title;
        $add->details = $req->details;
        $add->status  = $req->status;
        $add->save();
        return back()->with(['message'=>"Successfully Updated", 'status'=>'success', 'status_icon'=>'fa-check-circle']);
    }

    public function getBankForm(Request $req){ 
        $bankId = isset($req->bank_id) ? $req->bank_id: 0;
        $bank = Bank::find($bankId);
        return view('admin.setting.bank_form', compact('bank'));
    }

    public function addBankInfo( Request $req){
        if (!empty($req->ebank_id)) {
            $ebank = Bank::find($req->ebank_id);
            $ebank->name = $req->name;
            $ebank->details = $req->details;
            $ebank->status  = $req->status;
            $ebank->save();
            $message = "Bank info successfully updated";
            $messagetype = "success";
            $status_icon = "fa-check-circle";
            // $message = "Company successfully added";
        }else{
            $abank = New Bank;
            $abank->name = $req->name;
            $abank->details = $req->details;
            $abank->status  = $req->status;
            $abank->save();
            $message = "Bank info successfully added";
            $messagetype = "success";
            $status_icon = "fa-check-circle";
            // $message = "Company successfully added";
        }
        return back()->with(['message'=> $message,  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
        // return response()->json(["message"=> $message, "messagetype"=>$messagetype, "status_icon"=> $status_icon]);
    }
}
