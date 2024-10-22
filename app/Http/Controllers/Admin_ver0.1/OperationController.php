<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Project;
use App\Booking; 
use App\BookTransport; 
use App\BookRestaurant;
use App\BookEntrance;
use App\Supplier;
use App\BookMisc;
use App\BookGuide; 
use App\HotelBooked; 
use App\CruiseBooked;
class OperationController extends Controller
{
    public function applyOperation($opstype, $projectNo){
    	$project = Project::where('project_number', $projectNo)->first();
        if (empty($project)) {
            $message = $projectNo;
            return view('errors.error', compact('message', 'opstype'));
        }
    	if ($opstype == "transport" || $opstype == "guide" || $opstype == "misc"){
    		$booking = Booking::tourBook($projectNo)->get();
    	}elseif ($opstype == 'restaurant') {            
    		$booking = \App\BookRestaurant::where('project_number', $projectNo)->orderBy('start_date', 'ASC')->get();
    	}elseif ($opstype == 'entrance') {
    		$booking = \App\BookEntrance::where('project_number', $projectNo)->orderBy('start_date', 'ASC')->get();
    	}elseif ($opstype == "golf") {
            $booking = Booking::golfBook($projectNo)->get();
        }
    	return view('admin.operation.booking_'.$opstype, compact("booking","project", "opstype"));
    }

    public function opsVoucher($type, $projectNo, $ospBid) {
        try {
            $project = Project::where('project_number', $projectNo)->first();
            if ($type == 'entrance') {
                $bops = BookEntrance::find($ospBid);
                $title = 'Entrance Booking Form';
            }
            $supplier = Supplier::find($bops['supplier_id']);
            return view('admin.report.operation_voucher', compact('type', 'project', 'bops', 'title', 'supplier'));
        } catch (Exception $e) {
            // return $e;
        }
    }
    public function opsReservation($type, $projectNo, $ospBid) {
        try {
            $project = Project::where('project_number', $projectNo)->first();
            if ($type == 'entrance') {
                $bops = BookEntrance::find($ospBid);
                $title = 'Entrance Voucher';
            }
            $supplier = Supplier::find($bops['supplier_id']);
            return view('admin.report.operation_reservation', compact('type', 'project', 'bops', 'title', 'supplier'));
        } catch (Exception $e) {
            // return $e;
        }
    }

    public function bookingTransport($projectNo, $supplier_id = 0){
        $project = Project::where("project_number",$projectNo)->first();
        $book = Booking::find($supplier_id);
        $btransport = BookTransport::where(['project_number'=>$projectNo,'book_id'=>$supplier_id])->first();
        return view("admin.report.transport_booking_form", compact("book","project", "btransport"));
    }

    public function assignTransport(Request $req){
    	$getTran = BookTransport::where(['project_number'=> $req->project_number, 'book_id'=> $req->bookid])->first();
    	if ( $getTran) {
    		$btran = BookTransport::find($getTran->id);
    		$btran->book_id        = $req->bookid;
	    	$btran->service_id     = $req->tran_name;
	 		$btran->project_number = $req->project_number;
	    	$btran->country_id     = $req->country;
	    	$btran->province_id    = $req->city;
	    	$btran->transport_id   = $req->transport;
            $btran->driver_id      = $req->driver_name;
	    	$btran->transport_phone= $req->phone1;
            $btran->driver_phone   = $req->phone2;
	    	$btran->vehicle_id     = $req->vehicle;
            $btran->pickup_time    = $req->pickup_time;
            $btran->flightno       = $req->flightno;
            $btran->remark         = $req->remark;
	    	$btran->price 	       = $req->price;
	    	$btran->kprice         = $req->kprice;
	    	$btran->save();
	    	$message = "Transport Successfully Updated";
    	}else{
	    	$btran = New BookTransport;
	    	$btran->book_id        = $req->bookid;
	    	$btran->service_id     = $req->x;
	 		$btran->project_number = $req->project_number;
	    	$btran->country_id     = $req->country;
	    	$btran->province_id    = $req->city;
            $btran->transport_id   = $req->transport;
            $btran->driver_id   = $req->driver_name;
            $btran->transport_phone = $req->phone1;
            $btran->driver_phone   = $req->phone2;
	    	$btran->vehicle_id     = $req->vehicle;
	    	$btran->price 	       = $req->price;
	    	$btran->kprice         = $req->kprice;
            $btran->pickup_time    = $req->pickup_time;
            $btran->flightno       = $req->flightno;
            $btran->remark         = $req->remark;
	    	$btran->save();
	    	$message = "Transport Successfully Added";
	    }
        return back()->with(['message'=> $message,  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }

    public function assignResturant(Request $req){
    	$restName = BookRestaurant::find($req->restId);
    	if($restName){
    		$restName->project_number = $req->project_number;
    		$restName->start_date  = $req->start_date;
    		$restName->book_date   = $req->book_date;
            $restName->book_id     = $req->bookid;
    		$restName->country_id  = $req->country;
    		$restName->province_id = $req->city;
    		$restName->supplier_id = $req->rest_name;
    		$restName->menu_id	   = $req->rest_menu;
    		$restName->book_pax	   = $req->pax;
    		$restName->price       = $req->price;
    		$restName->kprice      = $req->kprice;
    		$restName->amount      = $req->price * $req->pax;
    		$restName->kamount     = $req->kprice * $req->pax;
    		$restName->remark      = $req->remark;
    		$restName->save();
    		$message = "Restaurant Successfully Applied";
    	}else{
    		$restName = New BookRestaurant;
    		$restName->project_number = $req->project_number;
    		$restName->start_date  = $req->start_date;
    		$restName->book_date   = $req->book_date;
            $restName->book_id     = $req->bookid;
    		$restName->country_id  = $req->country;
    		$restName->province_id = $req->city;
    		$restName->supplier_id = $req->rest_name;
    		$restName->menu_id	   = $req->rest_menu;
    		$restName->book_pax	   = $req->pax;
    		$restName->price       = $req->price;
    		$restName->kprice      = $req->kprice;
    		$restName->amount      = $req->price * $req->pax;
    		$restName->kamount     = $req->kprice * $req->pax;
    		$restName->remark      = $req->remark;
    		$restName->save();
    		$message = "Restaurant Successfully applied";
    	}
    	return back()->with(['message'=> $message,  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }

    public function assignEntrance(Request $req){
    	$entrance = BookEntrance::find($req->restId);
    	if ($entrance) {
    		$entrance->start_date 	  = $req->start_date;
    		$entrance->project_number = $req->project_number;
    		$entrance->service_id     = $req->rest_menu;
    		$entrance->country_id 	  = $req->country;
    		$entrance->province_id    = $req->city;
            $entrance->supplier_id    = $req->transportation;
    		$entrance->book_pax       = $req->pax;
    		$entrance->price 		  = $req->price;
    		$entrance->kprice 		  = $req->kprice;
    		$entrance->amount         = $req->price * $req->pax;
    		$entrance->kamount        = $req->kprice * $req->pax;
    		$entrance->remark		  = $req->remark;
    		$entrance->save();
    		$message = "Entrance Successfully Updated";
    	}else{
    		$entrance = New BookEntrance;
    		$entrance->start_date 	  = $req->start_date;
    		$entrance->project_number = $req->project_number;
    		$entrance->service_id     = $req->rest_menu;
    		$entrance->country_id 	  = $req->country;
    		$entrance->province_id    = $req->city;
            $entrance->supplier_id    = $req->transportation;
    		$entrance->book_pax       = $req->pax;
    		$entrance->price 		  = $req->price;
    		$entrance->kprice 		  = $req->kprice;
    		$entrance->amount         = $req->price * $req->pax;
    		$entrance->kamount        = $req->kprice * $req->pax;
    		$entrance->remark		  = $req->remark;
    		$entrance->save();
    		$message = "Entrance Applied Successfully";
    	}
    	return back()->with(['message'=> $message,  'status'=> 'success', 'status_icon'=> 'fa-check-circle']);
    }

    public function assignGuide(Request $req){
    	$guidb=BookGuide::where(['project_number'=>$req->project_number,'book_id'=>$req->bookid])->first();
    	if ($guidb) {
    		$aguide = BookGuide::find($guidb->id);
  			$aguide->project_number = $req->project_number;
  			$aguide->start_date     = $req->start_date;
  			$aguide->country_id     = $req->country;
  			$aguide->province_id    = $req->city;
  			$aguide->service_id     = $req->tran_name;
  			$aguide->book_id		= $req->bookid;
  			$aguide->language_id    = $req->language;
            $aguide->phone          = $req->phone;
  			$aguide->supplier_id    = $req->guide_name;
  			$aguide->book_pax       = $req->pax;
  			$aguide->price  		= $req->price;
  			$aguide->kprice  		= $req->kprice;
  			$aguide->amount 		= $req->price * $req->pax;
  			$aguide->kamount 		= $req->kprice * $req->pax;
  			$aguide->save();
  			$message = "Guide Applied Successfully";
    	}else{
    		$aguide = New BookGuide;
  			$aguide->project_number = $req->project_number;
  			$aguide->start_date     = $req->start_date;
  			$aguide->country_id     = $req->country;
  			$aguide->province_id    = $req->city;
  			$aguide->service_id     = $req->tran_name;
  			$aguide->book_id		= $req->bookid;
  			$aguide->language_id    = $req->language;
            $aguide->phone          = $req->phone;
  			$aguide->supplier_id    = $req->guide_name;
  			$aguide->book_pax       = $req->pax;
  			$aguide->price  		= $req->price;
  			$aguide->kprice  		= $req->kprice;
  			$aguide->amount 		= $req->price * $req->pax;
  			$aguide->kamount 		= $req->kprice * $req->pax;
  			$aguide->save();
  			$message = "Guide Applied Successfully";
    	}
    	return back()->with(['message'=> $message,  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }

    public function assignMisc(Request $req){
        $aMisc = BookMisc::find($req->bookid);
        if ($aMisc) {
            $aMisc->project_number = $req->project_number;
            $aMisc->country_id     = $req->country;
            $aMisc->province_id    = $req->city;
            $aMisc->service_id     = $req->service_type;
            $aMisc->book_pax       = $req->book_pax;
            $aMisc->price          = $req->price;
            $aMisc->kprice         = $req->kprice;
            $aMisc->amount         = $req->price * $req->book_pax;
            $aMisc->kamount        = $req->kprice * $req->book_pax;
            $aMisc->remark         = $req->remark;
            $aMisc->save();
            $message = "Miscellaneouse update Successfully";
        }else{
            $eMisc = New BookMisc;
            $eMisc->project_number = $req->project_number;
            $eMisc->country_id     = $req->country;
            $eMisc->province_id    = $req->city;
            $eMisc->book_id        = $req->bookid;
            $eMisc->service_id     = $req->service_type;
            $eMisc->book_pax       = $req->book_pax;
            $eMisc->price          = $req->price;
            $eMisc->kprice         = $req->kprice;
            $eMisc->amount         = $req->price * $req->book_pax;
            $eMisc->kamount        = $req->kprice * $req->book_pax;
            $eMisc->remark         = $req->remark;
            $eMisc->save();
            $message = "Miscellaneouse Applied Successfully";
        }
        return back()->with(['message'=> $message,  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }

    public function restVoucher($projectNo, $restId){
    	$project = Project::where('project_number', $projectNo)->first();
    	$restb = BookRestaurant::find($restId);
        if (empty($project)) {
            $message = $projectNo;
            return view('errors.error', compact('message'));
        }
    	return view('admin.report.restaurant_voucher', compact('project', 'restb'));
    }

    public function restBooking($projectNo, $restId){
    	$project = Project::where('project_number', $projectNo)->first();
    	$restb = BookRestaurant::find($restId);
        if (empty($project)) {
            $message = $projectNo;
            return view('errors.error', compact('message'));
        }
    	return view('admin.report.restaurant_booking', compact('project', 'restb'));
    }

    public function updateTeetime(Request $req){
        $ateetime = Booking::find($req->bookid);
        $ateetime->book_golf_time = $req->hour.":".$req->minute."&nbsp;".$req->start;
        $ateetime->save();
        return back()->with(['message'=> "Tee time successfully update",  'status'=> 'success', 'status_icon'=> 'fa-check-circle']);   
    } 

    public function getReportRequest(Request $req, $projectNo){
        $project = Project::where(['project_number'=>$projectNo])->first();
        if (empty($project)) {
            $message = $projectNo;
            return view('errors.error', compact('message'));
        }
        $checkedhotel  = isset($req->checkedhotel) ? $req->checkedhotel : []; 
        $checkedcruise = isset($req->checkedcruise) ? $req->checkedcruise : []; 
        $checkedgolf   = isset($req->checkedgolf) ? $req->checkedgolf : []; 
        $checkedflight = isset($req->checkedflight) ? $req->checkedflight : [0];
        $checkedRest   = isset($req->checkedRest) ? $req->checkedRest : [];
        $checkedentran = isset($req->checkedentran) ? $req->checkedentran : [];
        $checkedtran = isset($req->checkedtransport) ? $req->checkedtransport : [];
        $checkedguide= isset($req->checkedguide) ? $req->checkedguide : [];
        $checkedMisc = isset($req->checkedmisc) ? $req->checkedmisc : [];
        $title   = $req->btnstatus;
        $remark  = $req->remark;
        $hotelb  = HotelBooked::where(['status'=>1])->whereIn("id", $checkedhotel)->orderBy("checkin", 'ASC');
        $cruiseb = CruiseBooked::where(['status'=>1])->whereIn("id", $checkedcruise)->orderBy("checkin", 'ASC');
        $golfb   = Booking::where(['book_status'=>1])->whereIn("id", $checkedgolf)->orderBy("book_checkin", 'ASC');
        $flightb = Booking::where(['book_status'=>1])->whereIn("id",$checkedflight)->orderBy("book_checkin", 'ASC');
        $transportb =BookTransport::getTranportOrderBy()->whereIn("transport_book.id", $checkedtran);
        $guideb = BookGuide::getGuideByProject()->whereIn("guide_book.id", $checkedguide);
        $restaurantb = BookRestaurant::whereIn("id", $checkedRest)->orderBy("start_date", "ASC");
        $miscb = BookMisc::whereIn("book_id", $checkedMisc)->orderBy("created_at", "ASC");
        $entranb = BookEntrance::whereIn('id', $checkedentran)->orderBy("start_date", 'ASC');
        if ($title == "Booking Status" || $title == "Booking Records" ) {
            return view('admin.report.booking_status',compact('project', 'guideb', 'transportb', 'hotelb', 'cruiseb','golfb', 'flightb', 'entranb','title', 'remark'));

        }elseif ($title == "Payment Voucher") {
            return view('admin.report.payment_voucher',compact('project', 'guideb', 'transportb', 'hotelb', 'cruiseb','golfb', 'flightb', 'restaurantb','entranb', 'miscb','title', 'remark'));

        }elseif ($title == "Guide Fees") {
            return view('admin.report.guide_fees',compact('project', 'guideb', 'transportb', 'hotelb', 'cruiseb','golfb', 'flightb', 'restaurantb','entranb', 'miscb','title', 'remark'));
        }else{
            return view('admin.report.operation_booking_form',compact('project', 'hotelb', 'cruiseb','golfb', 'flightb', 'title', 'remark', 'transportb', 'guideb', 'restaurantb','entranb', 'miscb'));
        }
    }

    public function changebookingStatus(Request $req){
        $type = $req->datatype;
        $id   = $req->dataid;
        if ($type == "hotel") {
            $hbook = HotelBooked::find($id);
            if ($hbook->confirm == 0) {
                $hbook->confirm = 1;
                $status = 1;
            }else{
                $status = 0;
                $hbook->confirm = 0;
            }            
            $hbook->save();
        }elseif ($type == "flight" || $type == "golf") {
            $bbook = Booking::find($id);
            if ($bbook->book_confirm == 0) {
                $bbook->book_confirm = 1;
                $status = 1;
            }else{
                $bbook->book_confirm = 0;
                $status = 0;
            }
            $bbook->save();
        }elseif ($type == "cruise") {
            $bcruise = \App\CruiseBooked::find($id);
            if ($bcruise->confirm == 0) {
                $bcruise->confirm = 1;
                $status = 1;
            }else{
                $bcruise->confirm = 0;
                $status = 0;
            }
            $bcruise->save();
        }
        return response()->json(['status'=> $status]);
    }
}
