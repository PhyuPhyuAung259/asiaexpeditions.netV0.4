<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Business;
use App\Supplier;
use App\Company;
use PDF;
use App\Driver;
use App\Country;
use Excel;

use Illuminate\Support\Str;
class SupplierController extends Controller
{
    //
    public function supplierList(Request $req){
        $locationid = isset($req->location) ? $req->location: \Auth::user()->country_id;
    	$suppliers = Supplier::where('country_id', $locationid)->orderBy('id')->get();
        $business = Business::find(0);
        $getCountry = Country::where('country_status',1)->whereHas('supplier', 
            function($query){
                $query->where('supplier_status',1);
                $query->whereIn('business_id', [37,4,6,7,1,29]);
            })->orderBy('country_name')->get();
    	return view('admin.supplier.supplier', compact('suppliers', 'locationid', 'business', 'getCountry'));
    }

    public function supplierBusiness(Request $req, $supplierName){
        $locationid = isset($req->location) ? $req->location: \Auth::user()->country_id;
        $business = Business::where('slug', $supplierName)->first();
        $getCountry = Country::countryBySupplier($business['id']);
        $suppliers = Supplier::where(['country_id'=>$locationid, 'business_id'=>$business->id, 'supplier_status'=>1])
                // ->select('id', 'supplier_name', 'country_id', 'province_id', 'supplier_phone', 'supplier_email', 'supplier_photo', 'user_id')
                ->orderBy('supplier_name')->get();
    	return view("admin.supplier.supplier", compact('suppliers', 'supplierName', 'business', 'locationid', 'getCountry'));
    }
     // supplier report
    public function getSupplierReport(Request $req, $supId, $type){
        $currentAction = $req->path();
        $supplier = Supplier::find($supId);
        $essittype = isset($req->type) ? $req->type : '';
        if ($essittype == 'selling') {
            $priceType = $req->type;
            return view('admin.supplier.supplierReport', compact('supplier', 'type', 'priceType', 'currentAction'));
        }elseif ($essittype == 'contract') {
            $priceType = $req->type;
            return view('admin.supplier.supplierReport', compact('supplier', 'type', 'priceType', 'currentAction'));
        }else{
            $priceType = "";
            // return $supplier;
            return view('admin.supplier.supplierReport', compact('supplier', 'type' ,'currentAction', 'priceType'));
        }
    }

    public function sortHotelRateReport(Request $req, $supId, $type){
        // return date('Y', strtotime('+25 years'));
        $currentAction = $req->path();
        $supplier = Supplier::find($supId);
        $fmonth  = $req->fmonth;
        $tmonth  = $req->tmonth;
        $year   = $req->year;
        if (isset($req->type) == 'selling') {
            $priceType = "selling";
            return view('admin.supplier.supplierReport', compact('supplier', 'type', 'priceType', 'currentAction', 'fmonth','tmonth', 'year'));
        }else{
            $priceType = "";
            return view('admin.supplier.supplierReport', compact('supplier', 'type', 'priceType', 'currentAction', 'fmonth','tmonth', 'year'));
        }
    }

    public function getSupplierForm( Request $req){
        $locationid = isset($req->location)? $req->location: \Auth::user()->country_id;
        $type = isset($req->type) ? $req->type : '';
        $business = Business::where('slug', $type)->first();
        return view('admin.supplier.supplierForm', compact('locationid','type','business'));
    }    

    public function createSupplier(Request $req){
        $gallery = '';
        if (isset($req->gallery)) {
            foreach ($req->gallery as $key => $g) {
                $gallery .= $g."|";
            }
        } 
        $validate = \Validator::make($req->all(), [
            'title' => 'required',
            'contact_name' => 'required',
            'phone_one'    => 'required',
            'email_one' => 'required|min:6',
        ]);
        $exisSupplier = Supplier::where(['supplier_name'=>$req->title, 'business_id'=>$req->business_type])->first();
        if ($exisSupplier) {
            return back()->withErrors($validate)->withInput()->with(['message'=>"Hotel Name existing in system", 'status'=>'warning', 'status_icon'=>'fa-exclamation-circle']); 
        }                
        if (!$validate->fails()) {   
            $addsup = New Supplier;
            $addsup->supplier_name  = $req->title;
            $addsup->slug           = Str::slug($req->title).'.html';
            $addsup->supplier_contact_name = $req->contact_name;
            $addsup->country_id     = $req->country;
            $addsup->province_id    = $req->city;
            $addsup->user_id        = \Auth::user()->id;
            $addsup->author_id      = \Auth::user()->id;
            $addsup->business_id    = $req->business_type;
            $addsup->supplier_phone     = $req->phone_one;
            $addsup->supplier_phone2    = $req->phone_two;
            $addsup->supplier_fax       = $req->fax_number;
            $addsup->supplier_email     = $req->email_one;
            $addsup->supplier_email2    = $req->email_two;
            $addsup->supplier_website   = $req->website;
            $addsup->supplier_photo     = $req->image;
            $addsup->supplier_picture   = $gallery;
            $addsup->supplier_remark    = $req->remark;
            $addsup->supplier_intro     = $req->desc;
            $addsup->supplier_address   = $req->address;
            $addsup->supplier_status    = $req->status;
            $addsup->save();
            return back()->with(['message'=> "supplier has been created successfully",  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
        }else{
            return back()->withErrors($validate)->withInput()->with(['message'=> "Required Field ",  'status'=> 'warning', 'status_icon'=> 'fa-check-circle']); 
        }
        
    }

    public function getEditSupplier($supplierId){
        $supplier = Supplier::find($supplierId);
        if (empty($supplier)) {
            $message =  $supplierId;
            return view('errors.error', compact('message', 'type'));
        }
        return view('admin.supplier.supplierFormEdit', compact('supplier'));
    }

    public function udpateSupplier(Request $req){
        $gallery = '';
        $photo = '';
        
        if (isset($req->gallery) || isset($req->image)) {
            if (count($req->gallery) > 0) {
                foreach ($req->gallery as $key => $g) {
                    if ($g == null) {
                        $gallery .=$req->gallery_old[$key]."|";
                    }else{
                        $gallery .= $g."|";
                    }
                }
            }
            if (empty($req->image)) {
                $photo = $req->image_old;
            }else{
                $photo = $req->image;
            }
        }
        $addsup = Supplier::find($req->eid);
        $addsup->supplier_name  = $req->title;
        $addsup->country_id     = $req->country;
        $addsup->province_id    = $req->city;
        $addsup->slug           = Str::slug($req->title).'.html';
        $addsup->supplier_contact_name = $req->contact_name;
        $addsup->business_id        = $req->business_type;
        $addsup->supplier_phone     = $req->phone_one;
        $addsup->supplier_phone2    = $req->phone_two;
        $addsup->supplier_fax       = $req->fax_number;
        $addsup->supplier_email     = $req->email_one;
        $addsup->supplier_email2    = $req->email_two;
        $addsup->supplier_website   = $req->website;
        $addsup->supplier_photo     = $photo;
        $addsup->supplier_picture   = $gallery;
        $addsup->supplier_remark    = $req->remark;
        $addsup->supplier_intro     = $req->desc;
        $addsup->supplier_address   = $req->address;
        $addsup->supplier_status    = $req->status;
        $addsup->save();
        return back()->with(['message'=> "supplier has been updated successfully",  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }

    public function getDriver($id){
        $driver = Driver::where(["supplier_id"=>$id, "status"=> 1])->orderBy('driver_name', "ASC")->get();
        return view('admin.supplier.driver', compact('driver'));
    }

    public function getSupplierDownload(Request $req, $supId){
            $supplier = Supplier::find($supId);
            $pdf = PDF::loadView('admin.supplier.supplierReport', compact('supplier'));
            return $pdf->download($supplier->supplier_name.'.pdf');
    }
    
    public function getRestautantinfo(Request $req){
        if (isset($req->hotel_checked) && !empty($req->hotel_checked)) {
            $hotelChecked = isset($req->hotel_checked) ? $req->hotel_checked : [0];
            $suppliers = Supplier::where('supplier_status', 1)->whereIn('id', $hotelChecked)->orderBy('supplier_name')->get();
            return view('admin.report.restaurant_info', compact('suppliers'));
        }
    }
}
