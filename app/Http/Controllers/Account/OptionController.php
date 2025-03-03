<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountName;
use App\AccountType;
use App\AccountJournal;
use App\AccountTransaction;
use App\Project;
use App\Booking; 
use App\Supplier; 
use App\HotelBooked;
use App\CruiseBooked;
use App\component\Content;
use App\BookTransport;
use App\BookGuide;
use App\BookRestaurant;
class OptionController extends Controller
{
    public function loadData(Request $req){
    	$type = $req->datatype;
    	$dataId = $req->id ? $req->id : 0;
        $message = '';
    	if ($type == "account_name") {
            $conId = isset($req->project) && is_numeric($req->project) ? $req->project : \Auth::user()->country_id;
            $acctype = AccountType::find($dataId);
            $accName = AccountName::where(['account_type_id'=>$dataId, 'country_id'=>$conId, 'status'=>1])->select('id','account_name', 'account_code')->orderBy('account_name')->get();
            $message ="<li data-toggle='modal' data-target='#createAccountName'><a><i class='fa fa-plus'></i> Add New Account</a> </li>";
    		if ($accName->count() > 0) {
                foreach ($accName as $key => $acc) {
                    if ($req->selectedid == "account_name_journal") {
                        $message .="<li class='list' style='padding: 4px 0px !important;'><label style='position: relative;top: 3px; font-weight: 400; line-height:12px;'> <input style='display:none' type='checkbox' value='".$acc->id."' ".($acc->id == $req->selectedid ? 'checked' : '')."> <span style='position:relative; top:-3px;'>".$acc->account_code."-".$acc->account_name." </span></label></li>";
                    }else{
                        $message .="<li class='list' style=' padding: 4px 0px !important;'><label style='position: relative;top: 3px; font-weight: 400; line-height:12px;'> <input type='radio' name='account_name' value='".$acc->id."'  ".($acc->id == $req->selectedid ? 'checked' : '')."> <span style='position:relative; top:-3px;'>".$acc->account_code."-".$acc->account_name." </span></label></li>";
                    }
                }
            }else{
                $message .= "<li>No Account</li>";    
            }
        }elseif ($type == "supplierBycountry") {
            // return $req->selectedid;
            $getSupplier = Supplier::where(['supplier_status'=>1, 'country_id'=>$dataId])->whereIn('business_id', [$req->selectedid])->orderBy("created_at", 'DESC')->get();

            if ($getSupplier->count() > 0) {
                $message = "<li data-toggle='modal' data-target='#LoadSupplier'><a><i class='fa fa-plus'></i> Add Supplier</a> </li>";
                foreach ($getSupplier as $key => $sup) {
                    $message .="<li class='list' style=' padding: 4px 0px !important;'><label style='position: relative;top: 3px; font-weight: 400;'><input type='radio' name='supplier_name' value='$sup->id'><span style='position:relative; top:-2px;'>$sup->supplier_name</span></label></li>";
                }
            }else{
                $message = "<li>No supplier</li>";    
            }
            
    	}elseif ($type == "country") {
            $getCity = \App\Province::getProvinceSub($req->selectedid ,$dataId);
            if ($getCity->count() > 0) {
                $message .="<option value='0'>--Choose--</option>";
                foreach ($getCity as $key => $pro) {
                    $message .="<option value=".$pro->id.">".$pro->province_name."</option>";
                }
            }else{
                $message="<option>No City</option>";
            }

        }elseif ($type == "supplier_bank_booked") {
            $getSupplierBook = AccountTransaction::getAccBookByCity($dataId)->groupBy('tran.supplier_id')->get(); 
            if ($getSupplierBook->count() > 0) {
                $message .="<option value='0'>--Choose--</option>";
                foreach ($getSupplierBook as $key => $sub) {
                    $message .="<option value=".$sub->id.">".$sub->supplier_name."</option>";
                }
            }else{
                $message="<option>No Supplier</option>";
            }

        }elseif ($type == "supplier_book") { 
            $getSupplierBook = Supplier::where(['business_id'=>5, 'country_id'=>$dataId])->whereNotIn('id', [$req->selectedid])->orderBy("supplier_name")->get();
            if ($getSupplierBook->count() > 0) {
                $message .="<option value='0'>--Choose--</option>";
                foreach ($getSupplierBook as $key => $sub) {
                    $message .="<option value=".$sub->id.">".$sub->supplier_name."</option>";
                }
            }else{
                $message="<option>No Supplier</option>";
            }
        }elseif ($type == "supplier_by_account_transaction") { 
            $acctransaction = AccountTransaction::supplierByAccountTransaction($dataId, $req->project);
            if ($acctransaction->count() > 0) {
                foreach ($acctransaction as $key => $sup) {
                    $message .="<li class='list' style='padding: 4px 0px !important;'><label style='position: relative;top: 3px; font-weight: 400; line-height:12px;'> <input type='radio' name='supplier_name' value='".$sup->id."'  ".($sup->id == $req->selectedid ? 'checked' : '')."> <span style='position:relative; top:-3px;'>".$sup->supplier_name." </span></label></li>";
                }
            }else{
                $message .= "<li>No​ Supplier</li>";    
            }            
        
        }elseif ($type == "province") {
            $getOfficeSupply = Supplier::where(["province_id"=> $dataId, 'business_id'=> 51])->orderBy('supplier_name',"ASC")->get();
            if ($getOfficeSupply->count() > 0) {
                $message = '<option value="0">--select--</option>';
                foreach ($getOfficeSupply as $key => $sup) {
                    $message .="<option value=".$sup->id.">".$sup->supplier_name."</option>";
                }
            }else{
                $message = '<option>No Supplier</option>';
            }
        }elseif ($type == "country_supply") {
            $getOfficeSupply = Supplier::where(["country_id"=> $req->selectedid, 'business_id'=> $dataId])->orderBy('supplier_name',"ASC")->get();
            if ($getOfficeSupply->count() > 0) {
                $message = '<option value="0">--select--</option>';
                foreach ($getOfficeSupply as $key => $sup) {
                    $message .="<option value=".$sup->id.">".$sup->supplier_name."</option>";
                }
            }else{
                $message = '<option>No Supplier</option>';
            }
        }elseif ($type == "sup_by_project") {
            if ($dataId == 1) {
                $getHotel = HotelBooked::where(['project_number'=>$req->project, 'status'=>1, 'hotel_option'=>0])
                            ->whereNotIn('hotel_id', [0, "Null"])->orderBy("project_number", "ASC");
                $getBooked = $getHotel->groupBy("hotel_id");
                // resturant booked
            }elseif ($dataId == 2) {
                $getRestBook = BookRestaurant::where(['project_number'=>$req->project, 'status'=>1])->orderBy("project_number", "ASC");
                $getBooked = $getRestBook->groupBy("supplier_id");
                // cruise booked
            }elseif ($dataId == 3) {
                $getCruiseBook = CruiseBooked::where(['project_number'=>$req->project, 'status'=>1, 'optioin'=>0])
                            ->whereNotIn('cruise_id', [0, "Null"])->orderBy("project_number", "ASC");
                $getBooked = $getCruiseBook->groupBy("cruise_id");
                // guide booked
            }elseif ($dataId == 6) {
                $getGuideBook = BookGuide::where(['project_number'=> $req->project, "status"=>1])
                            ->whereNotIn('supplier_id', [0, "Null"])->orderBy("project_number", "ASC");
                $getBooked = $getGuideBook->groupBy("supplier_id");
                // transport booked 
            }elseif ($dataId == 7) {
                $getTranBook = BookTransport::where(['project_number'=>$req->project, "status"=> 1])
                            ->whereNotIn('book_id', [0, "Null"])->orderBy("project_number", 'ASC');
                $getBooked = $getTranBook->groupBy("transport_id");
 
            }elseif ($dataId == 9) {
                $getAgentBook = Booking::where(['book_project'=>$req->project, "book_status"=>1, 'book_optoin'=>0])
                            ->whereNotIn('supplier_id', [0, "Null"])
                            ->orderBy("book_project", "ASC");
                $getBooked = $getAgentBook->groupBy("supplier_id");
                // booked flight 
            }elseif ($dataId == 37) {
                $getFlightBook = Booking::where(['book_project'=>$req->project, "book_status"=>1, 'book_option'=>0])
                            ->whereNotIn('flight_id', [0, "Null"])->orderBy("book_project", "ASC");
                $getBooked = $getFlightBook->groupBy("flight_id");
                // booked golf
            }elseif ($dataId == 29) {
                $getGolfBook = Booking::where(['book_project'=>$req->project, "book_status"=>1, 'book_option'=>0])
                            ->whereNotIn('golf_id', [0, "Null"])
                            ->orderBy("book_checkin", 'ASC');
                $getBooked = $getGolfBook->groupBy("golf_id");
            }
            // return $getBooked->get();
            if ($dataId > 0 && $getBooked->get()->count() > 0 ) {
                $message .= '<option value="">Choose Supplier</option>';
                $Project_Amount = 0;
                $Project_kAmount = 0;
                foreach($getBooked->get() as  $book) {
                    // hotel booked
                    if ($dataId == 1) {
                        $bhotel = HotelBooked::where(['project_number'=> $req->project, 'hotel_id'=> $book->hotel_id])->orderBy("project_number")->get();
                        // dd($book->hotel_id);
                        $supName = isset($book->hotel)?$book->hotel->supplier_name:'';
                        $supId = isset($book->hotel)?$book->hotel->id:'';
                        $Project_Amount = $bhotel->sum("net_amount");
                        $Project_kAmount = 0;
                        // resturant book
                    }elseif ($dataId == 2) {
                        $bRest = BookRestaurant::where(['project_number'=>$req->project, 'supplier_id'=> $book->supplier_id]);
                        $supName = isset($book->supplier)? $book->supplier->supplier_name:'';
                        $supId = isset($book->supplier)? $book->supplier->id:'';
                        $Project_Amount = $bRest->sum("amount");
                        $Project_kAmount = $bRest->sum("kamount");
                        // cruise booked
                    }elseif ($dataId == 3){
                        $bcruise = CruiseBooked::where(["project_number"=> $req->project, 'cruise_id'=> $book->cruise_id]);
                        // return $bcruise->get();
                        $supName = isset($book->cruise)?$book->cruise->supplier_name:'';
                        $supId = isset($book->cruise)?$book->cruise->id:'';
                        $Project_Amount = $bcruise->sum("net_amount");
                        $Project_kAmount = 0;
                        // Guide booked
                    }elseif ($dataId == 6) {
                        $bguide = BookGuide::where(["project_number"=> $req->project, 'supplier_id'=>  $book->supplier_id]);
                        $supName = isset($book->supplier)?$book->supplier->supplier_name:'';
                        $supId = isset($book->supplier)?$book->supplier->id:'';
                        $Project_Amount = $bguide->sum("price");
                        $Project_kAmount = $bguide->sum("kprice");
                        // transport booked
                    }elseif ($dataId == 7) {
                        $btransport = BookTransport::where(["project_number"=> $req->project, 'transport_id'=> $book->transport_id]);
                        $supName = isset($book->transport)?$book->transport->supplier_name:'';
                        $supId = isset($book->transport)?$book->transport->id:'';
                        $Project_Amount = $btransport->sum("price");
                        $Project_kAmount = $btransport->sum("kprice");
                        // agent booked;
                    }elseif ($dataId == 9) {
                        $supName = isset($book->supplier)?$book->supplier->supplier_name:'';
                        $supId = isset($book->supplier)?$book->supplier->id:'';
                        $bproject = Project::where(['project_number'=>$req->project, "active"=>1])->first();
                        $hotelBook  = \App\HotelBooked::where(['project_number'=>$bproject->project_number, 'hotel_option'=>0]);
                        $cruiseBook = \App\CruiseBooked::where(['project_number'=>$bproject->project_number, 'option'=>0]);
                        $tourBook   = \App\Booking::tourBook($bproject->project_number);
                        $flightBook = \App\Booking::flightBook($bproject->project_number);
                        $golfBook   = \App\Booking::golfBook($bproject->project_number);
                        $grandtotal = $cruiseBook->sum('sell_amount') + $golfBook->sum('book_amount') + $flightBook->sum('book_amount') + $hotelBook->sum('sell_amount') + $tourBook->sum('book_amount');
                        if (empty((int)$bproject->project_selling_rate)) {
                            $Project_total = $grandtotal;
                        }else{
                            $Project_total =  $bproject->project_selling_rate;
                        }
                        $Project_Amount = $Project_total;
                        // golf booked  
                    }elseif ($dataId == 29) {
                        $bgolf = Booking::where(['book_option'=>0, 'book_project'=> $req->project, 'golf_id' => $book->golf_id]);
                        $supName = isset($book->golf)?$book->golf->supplier_name:'';
                        $supId = isset($book->golf)?$book->golf->id:'';
                        $Project_Amount = $bgolf->sum("book_namount");
                        $Project_kAmount = $bgolf->sum("book_nkamount");
                        // flight booked
                    }elseif ($dataId == 37) {
                        // return $book->golf_id;
                        $bflight = Booking::where([ 'book_project'=> $req->project, 'flight_id'=>$book->flight_idm, 'book_option'=>0]);
                        $supName = isset($book->fagent)?$book->fagent->supplier_name:'';
                        $supId = isset($book->fagent)?$book->fagent->id:'';
                        $Project_Amount = $bflight->sum("book_namount");
                        $Project_kAmount = $bflight->sum("book_nkamount");
                    }
                   $message .="<option  data-kamount='".$Project_kAmount."'  data-amount='".$Project_Amount."' value=".$supId.">".$supName."</option>";
                }
            }else{
                $message =  '<option value"">No Supplier</option>';
            }

        }elseif ($type == "sup_by_bus") {
            $getSupplier = Supplier::where(['business_id'=>$dadaId])->get();
            if ($getSupplier->count() > 0 ) {
                $message .= "<option value=''>--Choose--</option>";
                foreach ($getSupplier as $key=> $sup) {
                    if ($sup->id != 37 ) {
                        $message .= "<option value='".$sup->id."' >".$sup->supplier_name."</option>";
                    }else{
                        $message = "<option>No Supplier</option>"; 
                    }   
                }
            }else{
                $message = "<option>No Supplier</option>";    
            }
        }elseif ($type == "pro_by_sup") {
            $type = $req->selectedid == "acc_receivable" ? 1 : 2;
            $getSupplier=AccountJournal::where(['supplier_id'=> $dataId, 'status'=>1,'type'=> $type])->orderBy("id","DESC")->get();
            if ($getSupplier->count() > 0 ) {
                $message .= "<option>Choose Project</option>";
                foreach ($getSupplier as $key => $pro) {
                    $prob = Project::where("project_number", $pro->project_number)->first();
                    $projectNo = isset($pro->project_number)? $pro->project_number :'';
                    $proClient = isset($prob->project_client) ? $prob->project_client:'';
                    $accTranbalance = AccountTransaction::where(["journal_id"=> $pro->id, 'type'=> $type]);
                    $accBalance = $type == 1 ? $pro->credit: $pro->debit;
                    $accBalancek = $type == 1 ? $pro->kcredit: $pro->kdebit;
                    if ($accTranbalance->count() > 0) {
                        if ( $req->selectedid == "acc_receivable") {
                            $accBalance = ($pro->credit - $accTranbalance->sum("debit"));
                            $accBalancek = ($pro->kcredit - $accTranbalance->sum("kdebit"));
                        }elseif ($req->selectedid == "acc_payable") {
                            $accBalance = ($pro->debit - $accTranbalance->sum("credit"));
                            $accBalancek = ($pro->kdebit - $accTranbalance->sum("kcredit"));
                        }
                    }
                    $message .= "<option data-kbalance='".$accBalancek."' data-balance='".$accBalance."' data-acc_type='".$pro->account_type_id."' data-acc_name='".$pro->account_name_id."' value=".$projectNo.">".$projectNo. "-".$proClient."</option>";
                }
            }else{
                $message = "<option>No Project </option>";    
            }
        }elseif ($type == "office-supply") {
            $getOfficeSupply = Supplier::where(["country_id"=> $req->selectedid, 'business_id'=> $dataId])->orderBy('supplier_name',"ASC")->get();
            if ($getOfficeSupply->count() > 0) {
                $message = '<option value="">Choose Supplier</option>';
                foreach ($getOfficeSupply as $key => $sup) {
                    $message .="<option value=".$sup->id.">".$sup->supplier_name."</option>";
                }
            }else{
                $message = '<option>No Supplier</option>';
            }
        }elseif ($type == "service-by-project") {
            if ($req->selectedid == 54) {
                $getMisc = \App\BookMisc::where(['project_number'=> $req->project])->orderBy("created_at", "DESC")->get();
                if ($getMisc->count() > 0) {
                    $message .= "<option value='0'>Choose Service</option>";
                    foreach ($getMisc as $key => $bm) {
                        $message .= "<option data-kamount='".($bm->kamount)."' data-amount='".($bm->amount)."' value=".$bm->service_id.">".$bm->servicetype->name."</option>";
                    }
                }else{
                    $message = "<option>No Service</option>";    
                }
            }else if ( $req->selectedid == 55) {
                $getEntran = \App\BookEntrance::where(['project_number'=> $req->project])->orderBy("created_at", "DESC")->get(); 
                if ($getEntran->count() > 0) {
                    $message .= "<option value=''>Choose Service</option>";
                    foreach ($getEntran as $key => $bm) {
                        $message .= "<option data-kamount='".($bm->kamount)."' data-amount='".($bm->amount)."' value=".$bm->service_id.">".$bm->entrance->name."</option>";
                    }
                }else{
                    $message = "<option>No Service</option>";    
                }
            }               
        }elseif ($type == "preview_journal") {
            $getTransaction = AccountTransaction::where(['journal_id'=>$dataId, 'status'=>1, 'supplier_id'=>$req->supplier])->orderBy('created_at', 'DESC')->get();
            $n = 0;
            foreach ($getTransaction as $key => $tran) {
                $sup = Supplier::find($tran->supplier_book);
                $n++;
                $message.= " <tr>
                            <td>$n</td>
                            <td>".Content::dateformat($tran->invoice_pay_date)."</td>
                            <td>$tran->remark</td>
                            <td>".$tran->project['project_prefix']."-".$tran->project['project_fileno']."</td>
                            <td>".$tran->project['project_client']."</td>
                            <td class='text-right'>".($tran->credit > 0 ? '<strong style="color:red;">-'.Content::money($tran->credit).'</strong>' : '<strong style="color:#8BC34A;">'.Content::money($tran->debit)."</strong>")."</td>
                            <td class='text-right'>".($tran->kcredit > 0 ? '<strong style="color:red;">-'.Content::money($tran->kcredit).'</strong>' : '<strong style="color:#8BC34A;">'.Content::money($tran->kdebit)."</strong>")."</td>
                            <td class='text-right'>".$sup['supplier_name']."</td>
                        </tr>";
            }
        }
    	echo $message;
    }

    public function filterData(Request $req){
        $title = $req->dataName;
        $message = "";
        if ($req->type == "account_name") {
            $acctype = AccountType::find($req->filter_type);
            $accName = AccountName::where('account_name', 'LIKE', '%'.$title.'%')
                                    ->orWhere('account_code', 'LIKE', '%'.$title.'%')
                                    ->where(['status'=>1, 'account_type_id'=>$req->filter_type])->get();
            if ($accName->count() > 0) {
                $message .= "<li><a href='#' data-toggle='modal' data-target='#myModal'> + Add New Account ... </a></li>";
                $message .= "<li><b>".$acctype->account_name."</b></li>";
                foreach ($accName as $key => $acc) {
                    $message .= "<li data-label='account' class='account_name' data-id='".$acc->id."' data-code='".$acc->account_code."'>".$acc->account_code.'-'.$acc->account_name."</li>";
                }
            }else{
                $message = "<li class='text-center'>Typing not match</li>";
            }
        }elseif ($req->type == "project") {
            $projects = project::where(['project_number'=>$title, 'project_status' => 1])
                          ->orWhere('project_fileno', $title)
                          ->orWhere('project_client', 'like', $title. '%')
                          ->orderBy('project_number', 'DESC')->get();
            if ($projects->count() > 0) {
                foreach ($projects as $key => $pro) {
                    $message .= "<li data-label='project' class='project_list' data-id=".$pro->project_number." data-code='".($pro->project_client)."'>".$pro->project_number. "-".$pro->project_client."</li>";
                }
            }else{
                $message = "<li class='text-center'>Typing not match</li>";
            }
        }elseif ($req->type == "supplier") {
            // return $time()tle;
            //  $supplier = Supplier::where(['supplier_status'=>1, 'business_id'=> $req->filter_type, "country_id"=> $req->countryId])
            //               ->orWhere('supplier_name', 'like', $title. '%')
            //               ->get();
            // if ($supplier->count() > 0) {
            //     foreach ($supplier as $key => $sup) {
            //         $message .="<li data-label='supplier' data-id=".$sup->id.">".$sup->supplier_name."</li>";
            //     }
            // }else{
            //     $message = "<li class='text-center'>Typing not match</li>";
            // }
        }
       
        echo $message;
    }

    public function RemoveOption(Request $req){
        $type = $req->datatype;
        $id   = $req->id;
        $message = "waring";
        $messagetype ="waring";
        if ($type == "journal-entry") {
            $jacc = AccountJournal::find($id);
            $jacc->status = 0; 
            $jacc->save();
            $message = "Journal Entry Successfully Move To Draft";
            $messagetype = "success";
        }elseif ($type == "acc_cash_book") {            
            $paymentlink = AccountTransaction::where("entry_code", $id)->update(['status'=>0]);
            $message = "Cash Book Successfully Deleted";
        }else if ($type == "payment_link") {
            $paymentlink = \App\Payment\PaymentLink::find($id);
            $paymentlink->delete();
            $message = "Payment Link Successfully Deleted";
        }elseif ($type == "acc_opening_balance") {
            
            $accTransaction = AccountTransaction::find($id);
            $accTransaction->status = 0; 
            $accTransaction->save();
            $message = " Successfully Deleted";
            $messagetype = "success";
        }
        return response()->json(["message"=> $message, "messagetype"=>$messagetype]);
    }

}
