<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\component\Content;
use App\Project;
use App\Booking;
use App\HotelBooked;
use App\CruiseBooked;
use App\Supplier;
use Validator;
use App\Admin\ProjectClientName;
use App\Admin\Photo;
class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response 
     */
    public function projectList(Request $req, $project) 
    { 
        $dt = Carbon::now();        
        $Enddate = date("Y-m-d", strtotime("+1 month"));
        $d =  Content::diffDate(date('Y-m-d'), $Enddate); 
        $endDate = isset($req->checkin) ? $req->checkin : date('Y-m-').$d;
        $startDate = isset($req->checkout) ? $req->checkout : date('Y-m-d');
        $projectNum = '';
        if ($project == "project")
        {
            if ($req->status == "Inactive") { 
                $projects = Project::getProjectTags($startDate, $endDate, 0)->get();
            }elseif ($req->type == "quotation") {
                $projects = Project::getProjectQuotation($startDate, $endDate)->get();  
                return view('admin.project.projectQuotation', compact('projects', 'startDate', 'endDate'));
            }else{
                $projects = Project::getProjectTags($startDate, $endDate)->get(); 
            }            
            return view('admin.project.bookedProject', compact('projects', 'startDate', 'endDate'));
        } elseif ($project == "tour") {
            $projects = Booking::getBookedProjectByDate($startDate, $endDate)->whereNotIn('tour_id', ['NULL','0'])->get();
            return view('admin.project.bookedTour', compact('projects', 'startDate', 'endDate'));
        } elseif ($project == "hotel"){
            $projects = Booking::getBookedProjectByDate($startDate, $endDate)->whereNotIn('hotel_id', ['NULL','0'])->get();
            return view('admin.project.bookedHotel', compact('projects', 'startDate', 'endDate'));
        } elseif ($project == "flight") 
        {
            $projects = Booking::getBookedProjectByDate($startDate, $endDate)->whereNotIn('flight_id', ['NULL','0'])->get();
            return view('admin.project.bookedFlight', compact('projects', 'startDate', 'endDate'));
        } elseif ($project == "cruise") 
        {
            $projects = Booking::getBookedProjectByDate($startDate, $endDate)->whereNotIn('cruise_id', ['NULL','0'])->get();
            return view('admin.project.bookedCruise', compact('projects', 'startDate', 'endDate'));
        } elseif ($project == "golf"){
            $projects = Booking::getBookedProjectByDate($startDate, $endDate)->whereNotIn('golf_id', ['NULL','0'])->get();
            return view('admin.project.bookedGolf', compact('projects', 'startDate', 'endDate'));
        } elseif ($project == "hotelrate") {
            if (isset($req->textSearch)) {
                $projectNum = $req->textSearch;
                $projects = HotelBooked::where(['project_number'=>$req->textSearch, 'status'=>1])
                    ->orderBy('id', 'DESC')->get();
            }else{
                $projects = HotelBooked::where('status', 1)
                    ->whereBetween('checkin', [$startDate, $endDate])
                    ->orderBy('id', 'DESC')->get();
            }
            return view('admin.project.bookedHotelrate', compact('projects', 'projectNum', 'startDate', 'endDate'));
        } elseif ($project == "cruiserate") {
            if (isset($req->textSearch)) {
                $projectNum = $req->textSearch;
                $projects = CruiseBooked::where(['project_number'=>$req->textSearch, 'status'=> 1])
                    ->orderBy('id', 'DESC')->get();
            }else{
                $projects = CruiseBooked::where('status', 1)
                    ->whereBetween('checkin', [$startDate, $endDate])
                    ->orderBy('id', 'DESC')->get();
            }
            return view('admin.project.bookedCruiserate', compact('projects', 'projectNum', 'startDate', 'endDate'));
        }
    }

    // public 



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function projectForm(Request $req)
    {       
        if (isset($req->ref) ) {
            $project = Project::where('project_number', $req->ref)->first();
        }else{
            $project = Project::where('project_number', 0)->first();
        }
        $pro = Project::latest('project_number')->first();
        if ($pro) {
            $projectNo = sprintf("%06d",$pro->project_number + 1);
        }else{
            $projectNo = sprintf("%06d",00000 + 1);
        }        
        return view('admin.project.projectForm', compact('projectNo', 'project'));
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createProject(Request $req)
    {
        try{
            if (isset($req->project_edit) && !empty($req->project_edit)) {
                $project = Project::where('project_number', $req->project_edit)->first();
                $aPro = Project::find($project->id);
            }else{
                if (Project::projectExit($req->project_number)) {
                    return back()->with('message','Duplicate project number. Please check and try again');
                }
                $aPro = New Project;
                $aPro->project_number   = $req->project_number;
                $aPro->project_pax      = $req->pax_num;
                $aPro->project_date     = date('Y-m-d');
                $aPro->project_time     = date('H:i:s');
                $aPro->project_revise   = date('Y-m-d');
                $aPro->project_client   = $req->client_name;
                $aPro->project_fileno   = $req->fileno; 
                $aPro->project_start    = $req->start_date;
                $aPro->project_end      = $req->end_date;
                $aPro->project_dep_time = $req->dep_time;
                $aPro->project_arr_time = $req->arr_time;
                $aPro->project_option   = $req->option; 
                $aPro->project_book_ref = $req->reference;
                $aPro->project_book_consultant = $req->consultant;
                $aPro->project_location = $req->location;
                $aPro->country_id       = $req->location;
                $aPro->project_prefix   = $req->location == 30 ? "AE":"AM";
                $aPro->project_hight    = $req->pro_hightlight;
                $aPro->project_desc     = $req->pro_desc;
                $aPro->project_add_desc = $req->pro_add_desc;
                $aPro->project_note_desc= $req->pro_note_desc;
                $aPro->project_bank     = $req->bank;
                $aPro->user_id          = \Auth::user()->id;        
                $aPro->supplier_id      = $req->agent;
                $aPro->active           = 1;
                $aPro->project_amount   = $req->project_amount;
                $aPro->save();
            }
                $usertags = $req->usertag;
                if ($req->usertag) {
                    array_push($usertags, \Auth::user()->id);
                }else{
                    $usertags = \Auth::user()->id;
                }
                $aPro->usertag()->sync($usertags, true);
                $aPro->service()->sync($req->service, true);
                // add booking tour
                if (isset($req->group_tour)) {
                    foreach ($req->group_tour as $key => $tour) {
                        $abook = New Booking;
                        $abook->project_id    = $aPro->id;
                        $abook->user_id       = \Auth::user()->id;
                        $abook->book_fileno   = $req->fileno;
                        $abook->book_client   = $req->client_name;
                        $abook->book_project  = $req->project_number;
                        $abook->book_checkin  = $req->tour_start[$key];
                        $abook->country_id    = $req->country_tour[$key];
                        $abook->province_id   = $req->city_tour[$key];
                        $abook->tour_id       = $req->tour_name[$key];
                        $abook->book_pax      = $req->tour_pax[$key];
                        $abook->book_price    = $req->tour_price[$key];
                        $abook->book_option   = $req->option;
                        $abook->book_nprice   = $req->tour_nprice[$key];
                        $abook->book_amount   = $req->tour_amount[$key];
                        $abook->book_namount  = $req->tour_nprice[$key] * $req->tour_pax[$key];
                        $abook->book_date     = date('Y-m-d');
                        $abook->book_time     = date('H:i:s');
                        $abook->save();
                    }
                }

                // add booking hotel
                if (isset($req->group_hotel)) {
                    foreach ($req->group_hotel as $key => $hotel) {
                        $checkin = Carbon::parse($req->fromdate[$key]);
                        $checkout = Carbon::parse($req->todate[$key]);
                        $bookday = $checkin->diffInDays($checkout);
                        $abook = New Booking;
                        $abook->project_id    = $aPro->id;
                        $abook->user_id       = \Auth::user()->id;
                        $abook->book_fileno   = $req->fileno;
                        $abook->book_client   = $req->client_name;
                        $abook->book_quot_hotel_option = isset($req->hotel_option) ? $req->hotel_option[$key] : '';
                        $abook->book_project  = $req->project_number;
                        $abook->book_checkin  = $req->fromdate[$key];
                        $abook->book_checkout = $req->todate[$key];
                        $abook->country_id    = $req->country_hotel[$key];
                        $abook->province_id   = $req->city_hotel[$key];
                        $abook->hotel_id      = $req->hotel_name[$key];
                        $abook->book_day      = $bookday;
                        $abook->book_option   = $req->option;
                        $abook->book_date     = date('Y-m-d');
                        $abook->book_time     = date('H:i:s');
                        $abook->save();
                    }
                }

                // add booking Flight 
                if (isset($req->group_flight)) {
                    foreach ($req->group_flight as $key => $fligt) {
                        $flightId = \App\FlightSchedule::find($req->flightno[$key]);
                        $abook = New Booking;
                        $abook->project_id    = $aPro->id;
                        $abook->user_id       = \Auth::user()->id;
                        $abook->book_fileno   = $req->fileno;
                        $abook->book_client   = $req->client_name;
                        $abook->book_project  = $req->project_number;
                        $abook->book_checkin  = $req->ftodate[$key];
                        $abook->book_way      = $req->fway[$key];
                        $abook->country_id    = $req->country_flight[$key];
                        $abook->province_id   = $req->city_flight[$key];
                        $abook->supplier_id   = isset($flightId)? $flightId->supplier_id:'';
                        $abook->flight_id     = $req->flightno[$key];
                        $abook->book_pax      = $req->flightPax[$key];
                        $abook->book_agent    = $req->ticketing[$key];
                        $abook->city_destination  = $req->city_destination[$key];
                        $abook->book_price    = $req->flight_price[$key];
                        $abook->book_nprice   = $req->flight_nprice[$key];
                        $abook->book_kprice   = $req->flight_kprice[$key];
                        $abook->book_amount   = $req->flight_amount[$key];
                        $abook->book_kamount  = $req->flight_kprice[$key] * $req->flightPax[$key]; 
                        $abook->book_namount  = $req->flight_nprice[$key] * $req->flightPax[$key];
                        $abook->book_date     = date('Y-m-d');
                        $abook->book_option   = $req->option;
                        $abook->book_time     = date('H:i:s');
                        $abook->save();
                    }
                }

                // add cruise booking/s
                if (isset($req->group_cruise)) {
                    foreach ($req->group_cruise as $key => $cruise) {
                        $checkin = Carbon::parse($req->cfromdate[$key]);
                        $checkout = Carbon::parse($req->ctodate[$key]);
                        $bookday = $checkin->diffInDays($checkout);
                        $cruiseId = \App\CrProgram::find($req->cruise_program[$key]);
                        $abook = New Booking;
                        $abook->project_id    = $aPro->id;
                        $abook->user_id       = \Auth::user()->id;
                        $abook->book_fileno   = $req->fileno;
                        $abook->book_client   = $req->client_name;
                        $abook->book_project  = $req->project_number;
                        $abook->book_checkin  = $req->cfromdate[$key];
                        $abook->book_checkout = $req->ctodate[$key];
                        $abook->country_id    = $req->country_cruise[$key];
                        $abook->province_id   = $req->city_cruise[$key];
                        $abook->cruise_id     = isset($cruiseId)? $cruiseId->supplier_id :'';
                        $abook->book_day      = $bookday;
                        $abook->program_id    = $req->cruise_program[$key];
                        $abook->book_option   = $req->option;
                        $abook->book_date     = date('Y-m-d');
                        $abook->book_time     = date('H:i:s');
                        $abook->save();
                    }
                }

                // add golf booking
                if (isset($req->group_golf)) {
                    foreach ($req->group_golf as $key => $tour) {
                        $abook = New Booking;
                        $abook->project_id     = $aPro->id;
                        $abook->user_id        = \Auth::user()->id;
                        $abook->book_fileno    = $req->fileno;
                        $abook->book_client    = $req->client_name;
                        $abook->book_project   = $req->project_number;
                        $abook->book_checkin   = $req->gdate[$key];
                        $abook->country_id     = $req->country_golf[$key];
                        $abook->province_id    = $req->city_golf[$key];
                        $abook->golf_id        = $req->golf_name[$key];
                        $abook->program_id     = $req->golf_service[$key];
                        $abook->book_pax       = $req->pax[$key];
                        $abook->book_price     = $req->golfprice[$key];
                        $abook->book_nprice    = $req->golfnprice[$key];
                        $abook->book_kprice    = $req->golfkprice[$key];
                        $abook->book_nkprice   = $req->golfnkprice[$key];
                        $abook->book_amount    = $req->golfamount[$key];
                        $abook->book_namount   = $req->golfnamount[$key];
                        $abook->book_kamount   = $req->golfkamount[$key];
                        $abook->book_nkamount  = $req->golfnkamount[$key];
                        $abook->book_option    = $req->option;
                        $abook->book_date      = date('Y-m-d');
                        $abook->book_time      = date('H:i:s');
                        $abook->save();
                    }
                }
            return back()->with(['message'=> 'Project successfully Created',  'status'=> 'success', 'status_icon'=> 'fa-check-circle']);
        }catch (Exception $e) {
            return back()->with(['message'=> 'Something went wrong please try again', 'status'=> 'warning', 'status_icon'=> 'fa-exclamation-circle']);
        }
    }

    public function addClientForProject(Request $req){
        try{
            foreach ($req->first_name as $key => $firstname) {
                if (!empty($firstname)) {
                    if (!empty($req->eid[$key])) {
                        $add = ProjectClientName::find($req->eid[$key]);
                        $message = "Client successfully Updated";
                    }else{
                        $add = new ProjectClientName;    
                        $message = "Client successfully Created";
                    }                    
                    $add->project_id    = $req->project_id;
                    $add->project_number= $req->client_project_number;
                    $add->first_name    = $firstname;
                    $add->last_name     = $req->last_name[$key];
                    $add->client_name   = $firstname." ".$req->last_name[$key];
                    $add->country_id    = $req->country_id[$key];
                    $add->passport    = $req->passport[$key];  
                    $add->date_of_birth = $req->date_of_birth[$key];
                    $add->expired_date  = $req->expire_date[$key];
                    $add->share_with    = $req->share_with[$key];
                    $add->phone         = $req->phone[$key];
                    $add->dietary       = $req->dietary[$key];
                    $add->allergies     = $req->allergies[$key];
                    $add->flight_arr    = $req->flight_arr[$key];
                    $add->flight_dep    = $req->flight_dep[$key];
                    $add->save();
                }
                $messagetype = "success";            
                $status_icon = "fa-check-circle";
            }            
        } catch (Exception $e) {
            $messagetype = "warning";
            $message = "Client Could not add";
            $status_icon = "fa-exclamation-circle";
        }
        return back()->with(['message'=> $message, 'status'=> $messagetype, 'status_icon'=> $status_icon]);
    }

    public function addProjectPdF(Request $req){
        if ($req->pdf_type == "project_pdf") {
            $dir_path = public_path("storage/contract/projects");
        }elseif ($req->pdf_type == "hotel_contract_pdf") {
            $dir_path = public_path("storage/contract/hotels");
        } 
        $validator = Validator::make($req->all(), [
            // https://www.greennet.org.uk/support/understanding-file-sizes
            'project_pdf' => 'max:500000',
        ]);
       
        if (!$validator->fails()) {
            if ($req->hasFile("project_pdf")) {            
                foreach ($req->file("project_pdf") as $key=>$image) {
                    $filename = str_slug(time()."_".pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME), "_").'.'.$image->getClientOriginalExtension();
                    if( $image->move($dir_path, $filename) ){
                        $addPhoto = new Photo;
                        $addPhoto->name = $filename;
                        $addPhoto->original_name = $image->getClientOriginalName();
                        $addPhoto->photo_path   = $dir_path;
                        $addPhoto->country_id   = \Auth::user()->country_id;
                        $addPhoto->province_id  = \Auth::user()->province_id;
                        $addPhoto->user_id      = \Auth::user()->id;
                        $addPhoto->company_id   = isset(\Auth::user()->company->id) ? \Auth::user()->company->id: '';
                        $addPhoto->role_id      = \Auth::user()->role_id;
                        $addPhoto->groupby_month = date('m');
                        $addPhoto->type         = $req->pdf_type;
                        $addPhoto->project_number = $req->project_number_pdf;
                        $addPhoto->project_id   = $req->project_id_pdf;
                        $addPhoto->supplier_id  = $req->supplier_id_pdf;
                        $addPhoto->save();
                        $message = "Uploaded Successfully";
                    }
                }
                return back()->with(['message'=> 'successfully Uploaded', 'status'=> 'success', 'status_icon'=> 'fa-check-circle']);
            }
        }else{
            return back()->with(['message'=> 'Image size too big, Max size: 10 Mb', 'status'=> 'warning', 'status_icon'=> 'fa-exclamation-circle']);
        }
    }

    public function projectFormEdit($projectNum){
        $project = Project::where('project_number', $projectNum)->first();
        if (isset($project) && !empty($project)) {
            return view('admin.project.projectFormEdit', compact('project'));
        }else{
            return redirect()->intended('/');
        }        
    }

    public function projectAddNetPrice(Request $req) {
        $addpro = Project::find($req->project_id);
        $addpro->project_net_price = $req->project_net_price;
        $addpro->supplier_agent = $req->supplier_agent;
        $addpro->save();
        return back()->with(['message'=>'Net Price Have been successfully added', 'status'=>'success', 'status_icon'=>'fa-check-circle']);
    }

    public function updateProject(Request $req)
    {           
        try {
            $project = Project::where('project_number', $req->project_number)->first();
            $aPro = Project::find($project->id);
            $aPro->project_number   = $req->project_number;
            $aPro->project_revise   = $req->revise_date;           
            $aPro->project_ex_rate  = $req->ex_rate;
            $aPro->project_selling_rate  = $req->sell_rate;
            $aPro->project_add_invoice = $req->add_invoice;
            $aPro->project_cnote_invoice  = $req->cnote_invoice;
            $aPro->project_invoice_number  = $req->invoice_num;
            $aPro->project_prefix   = $req->project_prefix;
            $aPro->project_main_status   = $req->prefix;
            $aPro->project_option   = $req->option;
            $aPro->active           = isset($req->active) ? $req->active : $project->active;
            $aPro->project_check    = $req->project_check;
            $aPro->project_check_date = isset($req->project_check) == 1? date('Y-m-d'): '';
            $aPro->project_pax      = $req->pax_num;
            $aPro->project_client   = $req->client_name;
            $aPro->project_email    = $req->client_email;
            $aPro->project_fileno   = $req->fileno; 
            $aPro->project_start    = $req->start_date;
            $aPro->project_end      = $req->end_date;
            $aPro->project_dep_time = $req->dep_time;
            $aPro->project_arr_time = $req->arr_time;
            $aPro->project_book_ref = $req->reference;
            $aPro->project_book_consultant = $req->consultant;
            $aPro->project_location = $req->location;
            $aPro->country_id = $req->location;
            $aPro->project_hight    = $req->pro_hightlight;
            $aPro->project_desc     = $req->pro_desc;
            $aPro->project_add_desc = $req->pro_add_desc;
            $aPro->project_note_desc= $req->pro_note_desc;
            $aPro->project_note     = $req->pro_note;
            $aPro->project_bank     = $req->bank;
            $aPro->margin_rate      = $req->margin_rate;
            $aPro->check_by         = isset($req->project_check) == 1? \Auth::user()->id: $req->olduser;
            $aPro->supplier_id      = $req->agent;
            $aPro->project_amount   = $req->project_amount;
            $aPro->save();
        // @end project update
            $usertags = $req->usertag;
            if ($req->usertag) {
                array_push($usertags, \Auth::user()->id);
            }else{
                $usertags = \Auth::user()->id;
            }
            $aPro->usertag()->sync($usertags, true);
            \DB::table('booking')->where('book_project', $req->project_number)->update(['supplier_id'=>$req->agent, 'book_fileno'=>$req->fileno]);
            if (isset($req->copy) && $req->copy == "copy" && $req->option == 0) {
                // return $req->copy;
                $pbooked = Booking::where(['book_project'=>$req->project_number, 'book_status'=>1, 'book_option'=>1])->get();
                if ($pbooked->count() > 0) {
                    // return $pbooked;
                    foreach ($pbooked as $key => $bpro) {
                        $bquot = New Booking;
                        $bquot->project_id      = $bpro->project_id;
                        $bquot->user_id         = $bpro->user_id;
                        $bquot->book_fileno     = $bpro->book_fileno;
                        $bquot->book_client     = $bpro->book_client;
                        $bquot->book_project    = $bpro->book_project;
                        $bquot->book_checkin    = $bpro->book_checkin;
                        $bquot->book_checkout   = $req->book_checkout;
                        $bquot->country_id      = $bpro->country_id;
                        $bquot->province_id     = $bpro->province_id;
                        $bquot->golf_id         = $bpro->golf_id;
                        $bquot->cruise_id       = $bpro->cruise_id;
                        $bquot->program_id      = $bpro->program_id;
                        $bquot->tour_id         = $bpro->tour_id;                        
                        $bquot->hotel_id        = $bpro->hotel_id;
                        $bquot->book_day        = $bpro->book_day;
                        $bquot->supplier_id     = $bpro->supplier_id;
                        $bquot->flight_id       = $bpro->flight_id;
                        $bquot->book_way        = $bpro->book_way;
                        $bquot->book_pax        = $bpro->book_pax;
                        $bquot->book_agent      = $bpro->book_agent;
                        $bquot->city_destination= $bpro->city_destination;
                        $bquot->book_price      = $bpro->book_price;
                        $bquot->book_nprice     = $bpro->book_nprice;
                        $bquot->book_kprice     = $bpro->book_kprice;
                        $bquot->book_nkprice    = $bpro->book_nkprice;
                        $bquot->book_amount     = $bpro->book_amount;
                        $bquot->book_namount    = $bpro->book_namount;
                        $bquot->book_kamount    = $bpro->book_kamount;
                        $bquot->book_nkamount   = $bpro->book_nkamount;
                        $bquot->book_option     = $req->option;
                        $bquot->book_date       = date('Y-m-d');
                        $bquot->book_time       = date('H:i:s');
                        $bquot->save();
                    }
                }

                $hbooked=HotelBooked::where(['project_number'=>$req->project_number,'status'=>1])->get();
                if ($hbooked->count() > 0 ) {
                    foreach ($hbooked as $key => $hbk) {
                        $bhotel = New HotelBooked;
                        $bhotel->project_number = $hbk->project_number;
                        $bhotel->book_id        = $hbk->book_id;
                        $bhotel->hotel_id       = $hbk->hotelId;
                        $bhotel->room_id        = $hbk->room_id;
                        $bhotel->category_id    = $hbk->category_id;
                        $bhotel->checkin        = $hbk->book_checkin;
                        $bhotel->checkout       = $hbk->book_checkout;
                        $bhotel->book_day       = $hbk->book_day;
                        $bhotel->no_of_room     = $hbk->no_of_room;
                        $bhotel->ssingle        = $hbk->ssingle;
                        $bhotel->stwin          = $hbk->stwin;
                        $bhotel->sdouble        = $hbk->sdouble;
                        $bhotel->sextra         = $hbk->sextra;
                        $bhotel->schextra       = $hbk->schextra;
                        $bhotel->nsingle        = $hbk->nsingle;
                        $bhotel->ntwin          = $hbk->ntwin;
                        $bhotel->ndouble        = $hbk->ndouble;
                        $bhotel->nextra         = $hbk->nextra;
                        $bhotel->nchextra       = $hbk->nchextra;
                        $bhotel->hotel_option   = $req->option;
                        $bhotel->sell_amount    = $hbk->sell_amount;
                        $bhotel->net_amount     = $hbk->net_amount;
                        $bhotel->remark         = $hbk->remark;
                        $bhotel->save();
                    }
                }

                $Crbooked =CruiseBooked::where(['project_number'=>$req->project_number, 'status'=>1])->get();
                if ($Crbooked->count() > 0) {
                    foreach ($Crbooked as $key => $crb) {
                        $crbook = New CruiseBooked;
                        $crbook->project_number = $crb->project_number;
                        $crbook->book_id        = $crb->book_id;
                        $crbook->cruise_id      = $crb->cruise_id;
                        $crbook->room_id        = $crb->room_id;
                        $crbook->category_id    = $crb->category_id;
                        $crbook->program_id     = $crb->program_id;
                        $crbook->checkin        = $crb->checkin;
                        $crbook->checkout       = $crb->checkout;
                        $crbook->book_day       = $crb->book_day;
                        $crbook->cabin_pax      = $crb->cabin_pax;
                        $crbook->ssingle        = $crb->ssingle;
                        $crbook->stwin          = $crb->stwin;
                        $crbook->sdouble        = $crb->sdouble;
                        $crbook->sextra         = $crb->sextra;
                        $crbook->schextra       = $crb->schextra;
                        $crbook->nsingle        = $cr->nsingle;
                        $crbook->ntwin          = $crb->ntwin;
                        $crbook->ndouble        = $crb->ndouble;
                        $crbook->nextra         = $nextra->nextra;
                        $crbook->nchextra       = $crb->nchextra;
                        $crbook->sell_amount    = $crb->sell_amount;
                        $crbook->net_amount     = $crb->sell_amount;
                        $crbook->remark         = $crb->remark;
                        $crbook->option         = $req->option;
                        $crbook->save();
                    }
                }
            }
            return back()->with(['message'=> 'Project has been successfully updated',  'status'=> 'success', 'status_icon'=> 'fa-check-circle']);
        }catch (Exception $e) {
            return back()->with(['message'=> 'Something went wrong please try again', 'status'=> 'warning', 'status_icon'=> 'fa-exclamation-circle']);
        }
    }

    public function UpdateTourDesc(Request $req){
        try{
            $booked = Booking::find($req->tour_id);
            $booked->book_tour_details = $req->tourBook_desc;
            $booked->save();
           return back()->with(['message'=> 'Tour Program Details has been Updated',  'status'=> 'success', 'status_icon'=> 'fa-check-circle']);
        }catch (Exception $e) {
            return back()->with(['message'=> 'Something went wrong please try again', 'status'=> 'warning', 'status_icon'=> 'fa-exclamation-circle']);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function preProject($prjectNo)
    {
        $projectBooked=Booking::where(['book_project'=>$prjectNo, 'book_status'=>1])->orderBy('book_checkin','ASC')->get();
        return view('admin.project.preview_project', compact('projectBooked'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
