<?php

namespace App\Http\Controllers\Admin;
 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use App\Booking;
use App\Project;
use App\Supplier;
use App\HotelBooked;
use App\CruiseBooked;
use App\RoomRate;
use App\FlightSchedule;
use App\CrProgram;
use App\CrPrice;
class BookingController extends Controller 
{
    public function geteditBookedType($bookType, $bookId){
    	$book = Booking::find($bookId);
        if (empty($book)) {
            $message = $bookId;
            return view('errors.error', compact('message', 'type'));
        }
    	return view('admin.booking.'.$bookType.'Booking', compact('book', 'bookType'));
    }

    public function updateBookedType($bookType, Request $req){
    	try {
            $checkin = Carbon::parse($req->book_start);
            $checkout = Carbon::parse($req->book_end);
            $bookday = $checkin->diffInDays($checkout);
	    	$abook = Booking::find($req->bookId);
	        $abook->book_checkin    = $req->book_start;
	        $abook->book_checkout   = $req->book_end;
	        $abook->country_id      = $req->country;
	        $abook->province_id     = $req->city;
	        $abook->tour_id         = $req->tour_name;
	        $abook->hotel_id        = $req->hotel_name;
	        $abook->flight_id       = $req->flight_name;
	        $abook->cruise_id       = $req->cruise_name;
	        $abook->golf_id         = $req->golf_name;
            $abook->supplier_id     = $req->supplier_flight;
	        $abook->book_agent      = $req->ticketing;
            $abook->book_pax        = $req->book_pax;
            $abook->book_day        = $bookday;
            $abook->program_id      = $req->cruise_program;
	        $abook->book_way        = $req->book_way;	        
	        $abook->book_price      = $req->book_price;
	        $abook->book_nprice     = $req->book_nprice;
            $abook->book_kprice     = $req->book_kprice;
            $abook->book_amount     = $req->book_amount;
            $abook->book_namount    = $req->book_nprice * $req->book_pax;
            $abook->book_kamount    = $req->book_kprice * $req->book_pax;
	        $abook->book_date       = date('Y-m-d');
	        $abook->book_time       = date('H:i:s');
	        $abook->book_status 	= $req->status;
	        $abook->save();
            
            return back()->with(['message'=> $bookType.' Booking successfully Update',  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
        }catch (Exception $e) {
            return back()->with(['message'=> 'Something went wrong please try again ',  'status'=> 'success', 'status_icon'=> 'fa-exclamation-circle']); 
        }
        // return $req->all();
    }

    public function hotelbookedRoomApplied(Request $req){
        try {
            if(isset($req->roomtype)){
                foreach ($req->roomtype as $catrom => $room) {
                    if ($_POST['roomCat'.$room]) {
                        foreach ($_POST['roomCat'.$room] as $key => $cat) {
                            $bhotel = New HotelBooked;
                            $bhotel->project_number = $req->projectNo;
                            $bhotel->book_id        = $req->bookId;
                            $bhotel->hotel_id       = $req->hotelId;
                            $bhotel->room_id        = $room;
                            $bhotel->category_id    = $cat;
                            $bhotel->option         = $req->option;
                            $bhotel->book_option    = $req->book_option;
                            $bhotel->checkin        = $req->book_checkin;
                            $bhotel->checkout       = $req->book_checkout;
                            $bhotel->book_day       = $req->book_day;
                            $bhotel->no_of_room     = $_POST['no_of_room'.$room.'_'.$cat];
                            $bhotel->ssingle        = $cat == 1 ? $_POST['price_'.$room.'_'.$cat]:'00.0';
                            $bhotel->stwin          = $cat == 2 ? $_POST['price_'.$room.'_'.$cat]:'00.0';
                            $bhotel->sdouble        = $cat == 3 ? $_POST['price_'.$room.'_'.$cat]:'00.0';
                            $bhotel->sextra         = $cat == 4 ? $_POST['price_'.$room.'_'.$cat]:'00.0';
                            $bhotel->schextra       = $cat == 5 ? $_POST['price_'.$room.'_'.$cat]:'00.0';
                            $bhotel->nsingle        = $cat == 1 ? $_POST['nprice_'.$room.'_'.$cat]:'00.0';
                            $bhotel->ntwin          = $cat == 2 ? $_POST['nprice_'.$room.'_'.$cat]:'00.0';
                            $bhotel->ndouble        = $cat == 3 ? $_POST['nprice_'.$room.'_'.$cat]:'00.0';
                            $bhotel->nextra         = $cat == 4 ? $_POST['nprice_'.$room.'_'.$cat]:'00.0';
                            $bhotel->nchextra       = $cat == 5 ? $_POST['nprice_'.$room.'_'.$cat]:'00.0';
                            $bhotel->sell_amount    = $_POST['sell_amount_'.$room.'_'.$cat];
                            $bhotel->net_amount     = $_POST['net_amount_'.$room.'_'.$cat];
                            $bhotel->remark         = $req->remark;
                            $bhotel->save();
                        }
                    }
                }
                return back()->with(['message'=> 'Hotel Rate Applied successfully', 'status'=> 'success', 'status_icon'=> 'fa-check-circle']);
            }
        } catch (Exception $e) {
            return back()->with(['message'=> 'Something went wrong please try again', 'status'=> 'warning', 'status_icon'=> 'fa-exclamation-circle']);
        }
    }

    public function bookedApplyroom($proNo, $hotelId,$bookid){
        
        $hotel = Supplier::find($hotelId);
        // $bookid= Booking::find($bookid); 
        $project= Booking::find($proNo);
        // dd('bookid=' . $bookid , 'proNo='.$proNo); 
        return view('admin.booking.hotelAppliedRoom', compact('hotel', 'project'));
    }

    public function bookedCruise ($proNo, $cruiseId,$bookid){
        $cruise = Supplier::find($cruiseId);
        $project = Booking::find($bookid);
        $rcProgram = CrProgram::find($project->program_id);
        return view('admin.booking.cruiseAppliedRoom', compact('cruise','project','rcProgram'));
    }

    public function addHotelRemark(Request $req){
        $hb = HotelBooked::find($req->bhotelId);
        $hb->remark = $req->remark;
        $hb->save();
        return back()->with(['message'=> 'Remark successfully Updated',  'status'=> 'success', 'status_icon'=> 'fa-check-circle']);
    }

    public function crusebookedRoomApplied( Request $req){
        try {
            if(isset($req->roomtype)){
                foreach ($req->roomtype as $catrom => $room) {
                    if ($_POST['roomCat'.$room]) {
                        foreach ($_POST['roomCat'.$room] as $key => $cat) {
                            $bhotel = New CruiseBooked;
                            $bhotel->project_number = $req->projectNo;
                            $bhotel->book_id        = $req->bookId;
                            $bhotel->cruise_id      = $req->cruiseId;
                            $bhotel->room_id        = $room;
                            $bhotel->category_id    = $cat;
                            $bhotel->program_id     = $req->program_id;
                            $bhotel->checkin        = $req->book_checkin;
                            $bhotel->checkout       = $req->book_checkout;
                            $bhotel->book_day       = $req->book_day;
                            $bhotel->option         = $req->option;
                            $bhotel->cabin_pax      = $_POST['cabin_pax'.$room.'_'.$cat];
                            $bhotel->ssingle        = $cat == 1 ? $_POST['price_'.$room.'_'.$cat]:'00.0';
                            $bhotel->stwin          = $cat == 2 ? $_POST['price_'.$room.'_'.$cat]:'00.0';
                            $bhotel->sdouble        = $cat == 3 ? $_POST['price_'.$room.'_'.$cat]:'00.0';
                            $bhotel->sextra         = $cat == 4 ? $_POST['price_'.$room.'_'.$cat]:'00.0';
                            $bhotel->schextra       = $cat == 5 ? $_POST['price_'.$room.'_'.$cat]:'00.0';
                            $bhotel->nsingle        = $cat == 1 ? $_POST['nprice_'.$room.'_'.$cat]:'00.0';
                            $bhotel->ntwin          = $cat == 2 ? $_POST['nprice_'.$room.'_'.$cat]:'00.0';
                            $bhotel->ndouble        = $cat == 3 ? $_POST['nprice_'.$room.'_'.$cat]:'00.0';
                            $bhotel->nextra         = $cat == 4 ? $_POST['nprice_'.$room.'_'.$cat]:'00.0';
                            $bhotel->nchextra       = $cat == 5 ? $_POST['nprice_'.$room.'_'.$cat]:'00.0';
                            $bhotel->sell_amount    = $_POST['sell_amount_'.$room.'_'.$cat];
                            $bhotel->net_amount     = $_POST['net_amount_'.$room.'_'.$cat];
                            $bhotel->remark         = $req->remark;
                            $bhotel->save();
                        }
                    }
                }
                return back()->with(['message'=> 'Cruise Rate Applied successfully',  'status'=> 'success', 'status_icon'=> 'fa-check-circle']);
            }
        } catch (Exception $e) {
            return back()->with(['message'=> 'Something went wrong please try again', 'status'=> 'warning', 'status_icon'=> 'fa-exclamation-circle']);
        }
    }
}
