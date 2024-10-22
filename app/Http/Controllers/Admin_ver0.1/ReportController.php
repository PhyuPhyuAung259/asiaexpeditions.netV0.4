<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\component\Content;
use App\Project;
use App\HotelBooked;
use App\CruiseBooked;
use App\BookRestaurant;
use App\BookGuide;
use App\Booking;
use App\BookTransport;
use App\Supplier;
use DB;
use App\Admin\ProjectClientName;
class ReportController extends Controller
{

    public function getHotelVoucher(Request $req, $project, $hotelId, $bookid, $action){
    	$project = Project::where('project_number', $project)->first();
        if (empty($project)) {
            $message =  $projectNo;
            return view('errors.error', compact('message', 'action'));
        }
        $bhotel = HotelBooked::find($hotelId);
        if ($bhotel) {
            $hotel = HotelBooked::where(['book_id'=> $bookid, 'hotel_id'=> $bhotel->hotel->id])->whereBetween('checkin', [$req->checkin, $req->checkout])->get();
        }
        $bcruise = CruiseBooked::find($hotelId);
          $booking = Booking::find($bookid);
    	if ($action == "hotel-voucher") {
    		return view('admin.report.hotel_voucher', compact('project', 'bhotel', 'booking', "hotel"));
    	}elseif ($action == "hotel-booking-form") {
    		return view('admin.report.hotel_booking_form', compact('project', 'bhotel', 'booking', "hotel"));
    	}elseif ($action == "cruise-booking-form") {
            return view('admin.report.cruise_booking_form', compact('project', 'bcruise', 'booking'));
        }elseif ($action == "cruise-voucher") {
            return view('admin.report.cruise_voucher', compact('project', 'bcruise', 'booking'));
        }
    }

    public function getProjectBooked(Request $req, $projectNo, $view_type){
        $project = Project::where('project_number', $projectNo)->first();
        $clientByProject = ProjectClientName::where('project_number', $projectNo)->orderBy("client_name")->get();
        if ($view_type == "passenger-manifast") {
            return view("admin.report.passenger_manifast", compact("clientByProject", "project"));    
        }elseif ($view_type == "project_quotation_printing") {           
        	$type = "Proposal";
            $tourCheck  = $req->group_tour_pax ? $req->group_tour_pax : [];
            $hotelCheck = $req->hotel_option ? $req->hotel_option : [];
            $preview_quot = $req->preview_quot ? $req->preview_quot : '';
            $booking = Booking::where(['book_project'=>$projectNo,'book_status'=>1, 'book_option'=>1])
            ->groupBy('book_checkin')
            ->orderBy('book_checkin', 'ASC')
            ->get();
            if ($preview_quot == "package_cost") {
                return view("admin.report.project_preview_package_cost", compact("tourCheck", "type", "hotelCheck", "project", 'preview_quot', 'booking')); 
            }else{
                return view("admin.report.project_quotation_printing", compact("tourCheck", "type", "hotelCheck", "project", 'preview_quot', 'booking')); 
            }
        } 
        
    }

    // Preview report project_number
    public function getPreviewProject($projectNo, $type){
        $option = $type == "group-price" ? 1 : 0;
        $project = Project::Where('project_number', $projectNo)->first();
        if (empty($project)) {
            $message = $projectNo;
            return view('errors.error', compact('message', 'type'));
        }
        $booking = Booking::where(['book_project' => $projectNo,'book_status'=>1, 'book_option'=> $option])
            ->groupBy('book_checkin')
            ->orderBy('book_checkin', 'ASC')
            ->get();
        $servicedata = [];
        if ($project->service) {
            foreach ($project->service as $key => $sv) {
                $servicedata[] = "".$sv->pivot->service_id."";
            }
        }        
        // for assign all operatin & arragement Travel and Tour
        if ($type == "operation") {
            return view('admin.report.operation_report', compact('project', 'booking', 'type'));
        }elseif ($type == "sales-net") {
            return view('admin.report.project_sales_net', compact('project', 'booking', 'servicedata', 'type'));
        }elseif ($type == "group-price") {
            return view('admin.report.project_group_price', compact('project', 'booking'));
        }else{
            return view('admin.report.project_report', compact('project', 'booking', 'servicedata', 'type'));
        }
    }

    public function getInvoice($projectNo, $type){
        $option = $type == "group-price" ? 1 : 0;
        $project = Project::Where('project_number', $projectNo)->first();
        if (empty($project)) {
            $message =  $projectNo;
            return view('errors.error', compact('message', 'type'));
        }
        $booking = Booking::where(['book_project'=> $projectNo, 'book_status'=>1, 'book_option'=>$option])
                ->orderBy('book_checkin', 'ASC')->get();
        return view('admin.report.'.$type, compact('project', 'booking'));
    }

    public function getClientarrival(){       
        $Enddate = date("Y-m-d", strtotime("+1 month"));
        $d =  Content::diffDate(date('Y-m-d'), $Enddate); 
        $nextMonth = date('Y-m-').$d;
        $currentDate = date('Y-m-d');
        $projects = Project::Where(['project_status'=> 1])->whereBetween('project_start', [$currentDate, $nextMonth])->whereNotIn('project_fileno', [""," Null"])->orderBy('project_start', 'DESC')->get();
        return view('admin.report.arrival_report', compact('projects'));
    }
 
    public function getQuotation(){
        $Enddate = date("Y-m-d", strtotime("+1 month"));
        $d =  Content::diffDate(date('Y-m-d'), $Enddate); 
        $nextMonth = date('Y-m-').$d;
        $currentDate = date('Y-m-d');
        $projects = Project::where(['project_status'=>1, 'project.active'=> 1])
                    ->whereBetween('project_start', [$currentDate, $nextMonth])
                    ->whereNotIn('project_fileno', ["", "Null"])
                    ->orWhere('project_status', 1)
                    ->orderBy('project_start', 'DESC')->get();
        return view('admin.report.arrival_report', compact('projects'));
    }

    public function getOperationDailyChart(Request $req){
        $Enddate    = date("Y-m-d", strtotime("-1 month"));
        $d          =  Content::diffDate(date('Y-m-d'), $Enddate); 
        $nextMonth  = date('Y-m-').$d;
        $currentDate= date('Y-m-d');        
        $bookingTour = Booking::bookingTour($nextMonth, $currentDate)->get();
        return view("admin.report.operation_daily_chart", compact('bookingTour'));
    } 

    public function searchOperationDailyChart(Request $req){
        $startDate = $req->start_date;
        $endDate   = $req->end_date;
        $projectNo = $req->textSearch;
        $country = $req->sort_location;
        if (!empty($req->textSearch)) {
            $bookingTour = \DB::table('booking as book')
                ->join('tours', 'tours.id','=','book.tour_id')
                ->join('project', 'project.project_number', '=', 'book.book_project')
                ->where(['book.book_status'=>1, 'project.project_status'=>1,'project.project_number'=> $projectNo, 'project.active'=>1])
                ->orWhere('project.project_fileno', $projectNo)
                ->orwhere('project.project_client', 'like', $projectNo.'%')
                ->whereNotIn('project.project_fileno', ["", "Null",0])
                ->select('book.id as book_id', 'book.*', 'tours.tour_name', "project.*")
                ->orderBy('book.book_checkin', 'ASC')->get();
       
        }elseif (!empty($req->start_date) && !empty($req->end_date) && !empty($req->sort_location)) {
            $bookingTour = \DB::table('booking as book')
                ->join('tours', 'tours.id','=','book.tour_id')
                ->join('project', 'project.project_number', '=', 'book.book_project')
                ->where(['book.book_status'=>1, 'project.project_status'=>1, 'book.country_id'=> $country, 'project.active'=>1])
                ->whereBetween('book.book_checkin', [$startDate, $endDate])
                ->whereNotIn('project.project_fileno', ["", "Null",0])
                ->select('book.id as book_id', 'book.*', 'tours.tour_name', "project.*")
                ->orderBy('book.book_checkin', 'ASC')->get();
        }elseif (!empty($req->start_date) && !empty($req->end_date)) {
            $bookingTour = \DB::table('booking as book')
                ->join('tours', 'tours.id','=','book.tour_id')
                ->join('project', 'project.project_number', '=', 'book.book_project')
                ->where(['book.book_status'=>1, 'project.project_status'=>1, 'project.active'=>1])
                ->whereBetween('book.book_checkin', [$startDate, $endDate])
                ->whereNotIn('project.project_fileno', ["", "Null",0])
                ->select('book.id as book_id', 'book.*', 'tours.tour_name', "project.*")
                ->orderBy('book.book_checkin', 'ASC')->get(0);
        }else{
            $bookingTour = Booking::bookingTour($startDate, $endDate)->get();
        }
        return view("admin.report.operation_daily_chart", compact("bookingTour", "startDate", "endDate", "projectNo", "country"));

    }

    public function searchArrival(Request $req){
        $Enddate    = date("Y-m-d", strtotime("+1 month"));
        $d          =  Content::diffDate(date('Y-m-d'), $Enddate); 
        $nextMonth  = date('Y-m-').$d;
        $currentDate= date('Y-m-d');
        $startDate  = $req->start_date;
        $endDate    = $req->end_date;
        $projectNo  = $req->textSearch;
        $agentid    = $req->agent;
        $location   = isset($req->sort_location) ? $req->sort_location : 'AE';
        $sort_main  = $req->sort_main;
        if (!empty($projectNo)) {
            $projects = Project::Where(['active'=>1,'project_status'=>1,'project_fileno'=> $projectNo])
                ->orWhere('project_number',$projectNo)
                ->orWhere('project_client', 'like', $projectNo. '%')
                ->whereNotIn('project_fileno', ['','Null', 0])
                ->orderBy('project_start', 'DESC')->get();
        
        }elseif (!empty($startDate) && !empty($endDate)) {
            $projects = Project::Where(['active'=>1,'project_status'=>1, 'project_prefix'=> $location, 'supplier_id'=> $agentid])
                ->whereNotIn('project_fileno', ['Null','',0])
                ->whereBetween('project_start', [$startDate, $endDate])
                ->orderBy('project_start', 'DESC')->get();
        }else{           
            $projects = Project::Where(['active'=>1,'project_status'=>1,'project_fileno'=> $projectNo, 'project_prefix'=> $location])
                ->whereBetween('project_start', [$currentDate, $nextMonth])
                ->whereNotIn('project_fileno', ['','Null', 0])
                ->orderBy('project_start', 'DESC')->get();
        }
        return view('admin.report.arrival_report', compact('projects','projectNo', 'agentid', 'startDate', 'endDate', 'sort_main', 'location'));
    }

    public function reportSupplierBooked(Request $req) {
        $Enddate = date("Y-m-d", strtotime("+1 month"));
        $d =  Content::diffDate(date('Y-m-d'), $Enddate); 
        $start_date = isset($req->start_date) ? $req->start_date : date('Y-m-d');
        $end_date = isset($req->end_date) ? $req->end_date : date('Y-m-').$d;
        $supplier_name = $req->supplier;
        $bus_id = $req->business;
        $bookeds =[];
        $supp = Supplier::where([['business_id','=',$bus_id],['supplier_status', '=',1], ["supplier_name", "LIKE", "%".$supplier_name."%"]])->first();
        if ($bus_id == 1 && $supp != null) {
            $bookeds = HotelBooked::where(['status'=>1, 'hotel_id'=>$supp->id])->whereHas('book', 
                function($query) {
                    $query->where(['book_status'=>1]);
                    $query->whereNotIn('book_fileno', [0, '', 'null']);
                })->whereBetween('checkin', [$start_date, $end_date])->orderBy('checkin')->get();
        }elseif ($bus_id == 2 && $supp != null) {
            $bookeds = BookRestaurant::where(['status'=>1, 'supplier_id'=>$supp->id])->whereBetween('start_date', [$start_date, $end_date])->orderBy('start_date')->get();
        }elseif ($bus_id == 6 && $supp != null) {
            $bookeds = BookGuide::where(['status'=>1, 'supplier_id'=>$supp->id])->whereHas('book', 
                function($query) use ($start_date, $end_date) {
                    $query->where(['book_status'=>1]);
                    $query->whereBetween('book_checkin', [$start_date, $end_date]);
                })
                ->orderBy('created_at')->get(); 
        }elseif ($bus_id == 7 && $supp != null) {
            $bookeds = BookTransport::where(['status'=>1, 'transport_id'=>$supp->id])->whereHas('book',
                function($query) use ($start_date, $end_date) {
                    $query->where(['book_status'=>1]);
                    $query->whereBetween('book_checkin', [$start_date, $end_date]);
                })
                ->orderBy('created_at', 'ASC')->get(); 
        
        }elseif ($bus_id == 29 && $supp != null) {
            $bookeds = Booking::where(['book_status'=>1, 'golf_id'=>$supp->id])
                ->whereHas('project', function($query) {
                    $query->whereNotIn('project_fileno', ['', 0, 'Null']);
                })                
                ->whereBetween('book_checkin', [$start_date, $end_date])->orderBy('book_checkin')->get();
        
        }elseif ($bus_id == 37 && $supp != null) {
            $bookeds = Booking::where(['book_status'=>1, 'book_agent'=>$supp->id])
                ->whereHas('project', function($query) {
                    $query->whereNotIn('project_fileno', ['', 0, 'Null']);
                })
                ->whereNotIn('flight_id', ['', 0, 'Null'])
                ->whereBetween('book_checkin', [$start_date, $end_date])->orderBy('book_checkin')->get();
            
        }
        return view('admin.report.report_supplier_booked', compact('start_date', 'end_date', 'bus_id', 'supplier_name', 'bookeds', 'supp'));
    }
}
