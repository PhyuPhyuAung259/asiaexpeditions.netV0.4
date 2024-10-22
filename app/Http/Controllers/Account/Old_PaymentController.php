<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Payment\PaymentLink;
use App\Project;
use App\component\Content;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentLinkShipped;
use App\payment\VPCPaymentConnection;
class PaymentController extends Controller
{
    public function getPaymentLink(Request $req){
        if (isset($req->sort)) {
            $paymentlink = PaymentLink::where(["status"=> 1, 'payment_confirm' => $req->sort])->orderBy("created_at", "ASC")->get();
        }else{
            $paymentlink = PaymentLink::where(["status"=> 1])->orderBy("created_at", "ASC")->get();
        }  	
    	return view('admin.payment.payment_link', compact('paymentlink'));
    }
  
    public function createPaymentLink(Request $req){
    	$payment = PaymentLink::find($req->eid);
        $pro = PaymentLink::max('invoice_number');
        if ($req->action == "edit") {
            $invNumber = $payment['invoice_number'];
        }else{
            $invNumber = isset($pro) ? sprintf("%04d",(int) $pro + 1) : sprintf("%04d",000+1);    
        }
        $nextMonth = isset($req->end_date) ? $req->end_date : date('Y-m-d', strtotime('+2 years'));
        $currentDate = isset($req->start_date) ? $req->start_date : date('Y-m-d');
        $getClient = \App\Project::where(['project_status'=>1])->whereNotIn('project_client', ["","Null"])->whereNotIn('project_fileno', ["Null", ""])
          ->whereBetween('project_start', [$currentDate, $nextMonth])
            ->orderBy("project_fileno", "ASC")->get();

    	return view('admin.payment.payment_link_form', compact('payment', "invNumber", "getClient"));
    }

    public function addPaymentLink(Request $req){        
        $payment = PaymentLink::find($req->inv_number);
        if (isset($payment->invoice_number)) {
            $invNumber + 1;
        }else{
            $invNumber = $req->inv_number;
        }
        $tax = intval(strval($req->vpc_Amount / 100) * 3.5);
        $pay_amount = $req->vpc_Amount + $tax;
    	$aPay = New PaymentLink;
    	$aPay->project_id   = $req->project_id;
        $aPay->fullname     = $req->fullname;
        $aPay->card_type    = $req->vpc_card;
        $aPay->payment_confirm = "unpaid";
        $aPay->user_id      = \Auth::user()->id;
        $aPay->email        = $req->email;
        $aPay->invoice_number = $req->invoice_number;
        $aPay->amount       = $pay_amount;
        $aPay->original_amount = $req->vpc_Amount;
        $aPay->desc         = $req->desc;
        if($aPay->save()){
            $payment = PaymentLink::findOrFail($aPay->id);
            Mail::to(\Auth::user()->email)
                // ->cc(\Auth::user()->email)
                ->bcc(config('app.acc_email'), 'Account Department')
                ->send( new PaymentLinkShipped($payment));
        }
        return redirect()->route('getPaymentLink')->with(['message'=> 'Payment link successfully created',  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }

    public function editPaymentLink(Request $req){
        $tax = intval(strval($req->vpc_Amount / 100) * 3.5);
        $pay_amount = $req->vpc_Amount + $tax;
        $aPay = PaymentLink::find($req->eid_payment);
        $aPay->project_id       = $req->project_id;
        $aPay->fullname         = $req->fullname;
        $aPay->card_type        = $req->vpc_card;
        $aPay->payment_confirm  = "unpaid";
        $aPay->email            = $req->email;
        $aPay->invoice_number   = $req->invoice_number;
        $aPay->amount           = $pay_amount;
        $aPay->original_amount = $req->vpc_Amount;
        $aPay->desc             = $req->desc;
        $aPay->save();
        return back()->with(['message'=> 'Payment link successfully updated',  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }

    public function getPaymentView($inv_id){
        $pay_link = PaymentLink::where(['invoice_number'=>$inv_id])->first();
        return view("admin.payment.preview_invoice", compact('pay_link'));
    } 

    public function paymentReturnData(Request $req){
        $pay_link = PaymentLink::where('invoice_number',  $req->vpc_MerchTxnRef)->first();
        $rpay = PaymentLink::find($pay_link->id);
        if (isset($req->vpc_TxnResponseCode) && $req->vpc_TxnResponseCode == "0") {            
            if ($pay_link) {                
                $rpay->payment_confirm      = "paid";
                $rpay->bank_authorization_id = $req->vpc_AuthorizeId;
                $rpay->card_type            = $req->vpc_Card;
                $rpay->vpc_TransactionNo    = $req->vpc_TransactionNo;
                $rpay->vpc_Locale           = $req->vpc_Locale;
                $rpay->batch_number         = $req->vpc_BatchNo;
                $rpay->vpc_ReceiptNo        = $req->vpc_ReceiptNo;
                if ($rpay->save()) {
                    Mail::to($rpay->email)
                        ->bcc(config('app.acc_email'), 'Account Department')
                        ->send(new PaymentLinkShipped($rpay));
                }
            }
            $message = Content::getResultDescription($req->vpc_TxnResponseCode);
            $status = "success";
            $icon = "fa-check-circle";
        }else{
            $message = Content::getResultDescription($req->vpc_TxnResponseCode);
            $status = "warning";
            $icon = "fa-exclamation-circle";
        }
        return redirect()->route('getPaymentView', ['inv_number' => $req->vpc_MerchTxnRef, 'pay_link' => $pay_link, "message" => $message, 'status'=> $status, 'icon'=> $icon, 'action'=> $rpay->payment_confirm]);
    }

    public function paymentSubmit(Request $req){
        return view("admin.payment.PHP_VPC_3DS_2_5_Party_DO");
    }
}
