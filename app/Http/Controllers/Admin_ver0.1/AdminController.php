<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\RoomRate;
use App\Country;
use App\Province;
use App\Business;   
use App\TourPrice;
use App\CrProgram;  
use App\CrPrice;
use App\Supplier;
use App\Tour; 
use App\FlightSchedule;
use App\FsPrice; 
use App\GolfMenu;
use DB;
use App\Driver;
use App\Booking;
use App\Project;
use App\component\Content;
use App\HotelBooked;
use App\TransportService;
use App\CruiseBooked;
use App\RestaurantMenu;
use App\AccountName;
use App\SlideShow;
use Illuminate\Support\Carbon;
class AdminController extends Controller
{
    public function index()
    {
      return view('admin.index');
    }

    public function delPriceRate ($hotelid, $bookid, $type) {
      if ($type == "hotel") {
        if (\App\HotelBooked::where(['hotel_id'=>$hotelid, 'book_id'=> $bookid])->delete()) {
          return back()->with(['message'=> 'Hotel Rate Delete Successfully', 'status'=> "success", 'status_icon'=> 'fa-check-circle']);
        }else{
          return back()->with(['message'=> 'Delete Hotel Rate not Successfully', 'status'=> "warning", 'status_icon'=> ' fa-exclamation-triangle']);
        }
        
      }elseif ($type == "cruise") {
        $cruiseDel = \App\CruiseBooked::where(['cruise_id'=>$hotelid, 'book_id'=> $bookid]);
        if ($cruiseDel){
          $cruiseDel->delete();
          return back()->with(['message'=> 'Cruise Rate Delete Successfully', 'status'=> "success", 'status_icon'=> 'fa-check-circle']);
        }else{
          return back()->with(['message'=> 'Delete Cruise Rate not Successfully', 'status'=> "warning", 'status_icon'=> ' fa-exclamation-triangle']);
        }
        
      }
    }

    // delete program option
    public function getOptionDelete(Request $req){
      $id = $req->dataId;
      $message = "Permission denied";
      if($req->type == "roomRate"){
        if(RoomRate::find($id)->delete()){ 
          $message = "Delete Successfully"; 
        }
      }elseif ($req->type == "driver") {
        if(Driver::find($id)->delete()){
          $message = "Delete Successfully";
        }
      }elseif ($req->type == "slide-show") {
          $slide = SlideShow::find($id);
          $slide->status = 0;
          $slide->save();
          $message = "Delete Successfully";
      }elseif ($req->type == "project_pdf") {
        $photo = \App\Admin\Photo::find($id);
        if($photo){        
          $photo->delete();
          if (file_exists(public_path('storage/contract/projects/').$photo->name)) {
            unlink("storage/contract/projects/".$photo->name);
          }
        }
        $message = "Delete Successfully";
      }elseif ($req->type == "clear-guide") {
        $delBookMISC = \App\BookGuide::find($id);
        if ($delBookMISC) {
          $delBookMISC->delete();
          $message = "Guide service cleared";
        }

      }elseif ($req->type == "clear-transport") {
        $delBookTransport = \App\BookTransport::find($id);
        if ($delBookTransport) {
          $delBookTransport->delete();
          $message = "Transport service cleared";
        }
      }elseif ($req->type == "clear-misc") {
        $delBookGuide = \App\BookMisc::find($id);
        if ($delBookGuide) {
          $delBookGuide->delete();
          $message = "Miscellaneouse service cleared";
        }
      }elseif ($req->type == "company") {
        $company = \App\Company::find($id);
        $company->status = 0;
        $company->save();
        $message = "Company has been disabled";
      }elseif ($req->type == "bank") {
        $bank = \App\Bank::find($id);
        $bank->status = 0;
        $bank->save();
        $message = "Bank has been disabled";
      }elseif ($req->type == "hotel_facility") {
        if(\App\HotelFacitily::find($id)->delete()){
          $message = "Delete Hotel Facility Successfully";
        }  
      }elseif ($req->type == "hotel_info") {
        if(\App\HotelCategory::find($id)->delete()){
          $message = "Delete Hotel Facility Successfully";
        }         
      }elseif ($req->type == "tour_type") {
          $tourtype = \App\Business::find($id);
          $tourtype->status = 0;
          $tourtype->save();
          $message = "Tour Type Facility Successfully Deleted";
      }elseif ($req->type == "tour") {
          $tour = \App\Tour::find($id);
          $tour->tour_status =0;
          $tour->save();
          $message = "Delete Tour Successfully";
      }elseif($req->type == "room_type"){
        if(\App\Room::find($id)->delete()){
          $message = "Delete Room Successfully";
        }
      }elseif($req->type == "golf_service"){
        if(\App\GolfMenu::find($id)->delete()){
          $message = "Delete Golf Menu Successfully";
        }
      }elseif ($req->type == "service_include") {
        if(\App\Service::find($id)->delete()){
          $message = "Delete Service Successfully";
        }
      }elseif ($req->type == "book_project") {
        $project = \App\Project::find($id);
        $project->project_status = 0;
        $project->save();
        $projectBooked = \App\Booking::where("book_project", $project->project_number)
        ->update(['book_status' => 0]);
        $projectBooked = \App\HotelBooked::where("project_number", $project->project_number)
        ->update(['status' => 0]);
        $projectBooked = \App\BookGuide::where("project_number", $project->project_number)
        ->update(['status' => 0]);
        $projectBooked = \App\BookTransport::where("project_number", $project->project_number)
        ->update(['status' => 0]);
        $projectBooked = \App\BookRestaurant::where("project_number", $project->project_number)
        ->update(['status' => 0]);
        $message = "Delete Booking Project Successfully";
      }elseif ($req->type == "booked_project") {
        $projectBooked = \App\Booking::find($id);
        $projectBooked->book_status = 0;
        $projectBooked->save();
        $message = "Delete Booking Project Successfully";
      }elseif ($req->type == 'projectForClient') {
        $projecClient = \App\Admin\ProjectClientName::find($id);
        $projecClient->delete();
        $message = $req->title. "Successfully Deleted" ;
      }elseif($req->type == "user"){
        $user = \App\User::find($id);
        $user->banned = 1;
        $user->save();
        $message = "User Remove Successfully";
      }elseif ($req->type == "delMISC") {
        \App\MISCService::find($id)->delete();
        $message = "MISC Delete Successfully";
      }elseif ($req->type == "delEntrance") {
        \App\Entrance::find($id)->delete();
        $message = "Entrance Delete Successfully";
      }elseif ($req->type == "restMenu") {
        RestaurantMenu::find($id)->delete();
        $message = "Delete Successfully";
      }elseif ($req->type == "apply_rest") {
        \App\BookRestaurant::find($id)->delete();
        $message = "Delete BookRestaurant Successfully";

      }elseif ($req->type == 'apply_entrance') {
        \App\BookEntrance::find($id)->delete();
        $message = "Delete BookEntrance Successfully";
      }elseif ($req->type == "vehicle") {
        \App\TransportMenu::find($id)->delete();
        $message = "Vehicle Delete Successfully";
      }elseif ($req->type == "service_language"){
        $dGuide = \App\GuideLanguage::find($id);
        $dGuide->supplier()->detach();
        $dGuide->delete();
        $message = "Language Delete Successfully";
      }elseif ($req->type == "guide_service") {
        \App\GuideService::find($id)->delete();
        $message = "Guide Service Delete Successfully";
      }elseif ($req->type == "transport_service") {
        \App\TransportService::find($id)->delete();
        $message = "Delete Transport Service Successfully";
      }elseif ($req->type =="tourPaxPrice") {
          if(TourPrice::find($id)->delete()){
            $message = "Delete Successfully";
          }
      }elseif ($req->type == "cruise-program") {
          $crpro = CrProgram::find($id);
          if($crpro){
            $crpro->crCabin()->detach();
            $crpro->delete();
            $message = "Delete Successfully";
          }
      }elseif ($req->type == "cruise-cabin-price") {
          $crPrince = CrPrice::find($id);
          if ($crPrince) {
            $crPrince->delete();
            $message = "Delete Successfully";
          }
      }elseif ($req->type == "scheduleprice") {
          \DB::delete("delete from flight_schedule_supplier where id =?", [$id]);            
          $message = "Delete Successfully";
      }elseif ($req->type ==  "country") {
          $ecount = Country::find($id);
          $ecount->country_status = 0;
          $ecount->save();
          $message = "Delete Successfully";
      }elseif ($req->type == 'province') {
        $province = Province::find($id);
        $province->province_status = 0;
        $province->save();
        $message = "Delete Successfully";
      }elseif ($req->type == 'flightNo') {
        $delFlight = FlightSchedule::find($id);
        $delFlight->flightagent()->detach();
        $delFlight->delete();
        $message = "Delete Successfully";
      }elseif ($req->type == 'supplier') {
        $supplier = Supplier::find($id);
        $supplier->supplier_status = 0;
        $supplier->save();
        $message = "Delete Successfully";
      }elseif ($req->type == 'book_tour' || $req->type == 'book_hotel' || $req->type == 'book_flight' || $req->type == 'book_cruise' || $req->type == 'book_golf') {
        Booking::find($id)->delete();
        $message = "Delete $req->type Successfully";
      }elseif ($req->type == 'book_hotelrate') {
        $hotelRatedel = HotelBooked::find($id);
        if ($hotelRatedel) {
          $hotelRatedel->delete();
        }
        $message = "Delete Hotel Rate Successfully";
      }elseif ($req->type == "book_cruiserate") {
        $cruiseRatedel = CruiseBooked::find($id);
        if ($cruiseRatedel) {
          $cruiseRatedel->delete();
        }
        $message = "Delete Cruise Rate Successfully";
      }else{
        return back()->with(['message'=> $message ,  'status'=> 'warning', 'status_icon'=> 'fa-check-circle']);
      }
      
      return response()->json(["message"=> $message, "messagetype"=>"warning", "status_icon"=> "fa-exclamation-circle"]);
    }

    // sorting programs
    public function getFilter(Request $req){
        $title = $req->dataName;
        $message = '';
        if ($req->type == 'tourtype') {
            $tourtype = Business::where('name', 'LIKE', '%'.$title.'%')->where(['category_id'=>1, 'status'=>1])->get();
            if ($tourtype->count() > 0) {
                foreach ($tourtype as $key => $tour) {
                  $message.= "<li><div class='checkbox'>
                        <input id='checkid".$key."' type='checkbox' name='FlightAgent[]' value=".$tour->id."> 
                        <label for='checkid".$key."'>".$tour->name."</label>
                      </div>
                    </li>";
                }
            }else {
                $message ='<li readonly class="form-control">No Result Matched "'.$title.'"</li>';
            }        
        }elseif ($req->type == "transport_service") {
          if ($req->filterType == "transport_service_by_province") {

            $gettranService = Supplier::where([
              ["supplier_status", "=", 1], 
              ["business_id", "=", 7], 
              ["province_id", "=", $title]])->orderBy('supplier_name')->get();
            // return $gettranService;
          }else{
            $gettranService = Supplier::where([
              ["supplier_name", "LIKE", "%".$title."%"], 
              ["supplier_status", "=", 1], 
              ["business_id", "=", 7], 
              ["province_id", "=", $req->filterType]])->orderBy('supplier_name')->get();
          }
          
          if ($gettranService->count() > 0) {
            foreach ($gettranService as $key => $tran) {
              $message .= "<li><div class='checkbox'><label for='checkid".$key."'><input id='checkid".$key."' style='position: relative;' type='checkbox' name='multi_sup[]' value=".$tran->id."> <span style='position: relative;top: -5px;left: 5px;'>".$tran->supplier_name."</span></label></div></li>";
            }
          }else{
            $message = "<li>No Transport</li>";
          }
         }elseif ($req->type == "guide_by_country") {
          $getGuide = Supplier::where([
                  ["supplier_name", "LIKE", "%".$title."%"], 
                  ["supplier_status", "=", 1], 
                  ["business_id", "=", 6], 
                  ["country_id", "=", $req->filterType]])->orderBy('supplier_name', 'ASC')->get();
          if ($getGuide->count() > 0) {
            foreach ($getGuide as $key => $gud) {
              $message .= "<li style='padding:4px 0px;'><div class='checkbox' style='margin:6px 0px;'><label style='padding-left:0px !important;' for='checkid".$key."'><input id='checkid".$key."' style='position:relative; width:14px; height:14px;' type='checkbox' name='multi_sup[]' value=".$gud->id."> <span style='position: relative;top: -5px;left: 5px;'>".$gud->supplier_name."</span></label></div></li>";
            }
          }else{
            $message = "<li>No Guide</li>";
          }

        }else if ($req->type == 'flightAgent') {
          $getFlight = Supplier::where('supplier_name', 'LIKE', '%'.$title.'%')->where(['business_id'=>37, 'supplier_status'=>1])->whereNotNull('supplier_name')->get();
          if ($getFlight->count() > 0) {
              foreach ($getFlight as $key => $fligt) {
                $message.= "<li><div class='checkbox'>
                      <input id='checkid".$key."' type='checkbox' name='FlightAgent[]' value=".$fligt->id."> 
                      <label for='checkid".$key."'>".$fligt->supplier_name."</label>
                    </div>
                  </li>";
              }
          }else {
              $message ='<li readonly class="form-control">No Result Matched "'.$title.'"</li>';
          }
        }
        echo $message;
    }

    public function getOptionfind(Request $req){
        $dataId = $req->id ? $req->id : 0;
        $message ='';
        if ($req->datatype == "country"){
          if ($req->selectedid == "apply_transport") {
              $getTransportBooked = App\Supplier::where(['business_id'=>7, 'supplier_status'=>1, 'country_id'=> $dataId ])->orderBy('supplier_name', 'ASC')->get();
              if ($getTransportBooked->count() > 0 ) {
                foreach($getTransportBooked as $sup){
                  $message .= "<option ".($sup->id == $req->selectedid?'selected':'')." value='".$sup->id."' data-phone='".$sup->supplier_phone."' data-phone2='".$sup->supplier_phone2."'>".$sup->supplier_name."</option>";
                }
              }else{
                $message = "<option value=''>No Transport</option>";
              }
          }else{
              if ($req->title == "tour_bus" ) {
                $getProvince= Province::where(['province_status'=>1,'country_id'=>$dataId])->select('id', 'province_name')
                      ->whereHas('tour', function($query) {
                        $query->where(['tour_status'=>1]);
                      })->orderBy('province_name')->get();
                if($getProvince->count() > 0){
                  $message .= "<option value=''>--choose--</option>"; 
                  foreach ($getProvince as $key => $pro) {
                    $message .= "<option value=".$pro->id." ".($pro->id == $req->selectedid?'selected':'').">".$pro->province_name."</option>";
                  }
                }else{
                  $message = "<option value=''>No city</option>";
                } 
                // return $message;
              }elseif ($req->bus_type == "misc_type") {
                $getProvince = Province::getEntranPro($dataId);
                if($getProvince->count() > 0){
                    $message .= "<option value=''>--choose--</option>";
                    foreach ($getProvince as $key => $pro) {
                      $message .= "<option value=".$pro->id." ".($pro->id == $req->selectedid?'selected':'').">".$pro->province_name."</option>";
                    }
                }else{
                  $message = "<option>No city</option>";
                } 
              }else{
                if (isset($req->selectedid) && !empty($rq->selectedid) ||  isset($req->bus_type) && !empty($req->bus_type) ) {
                    $bus_type = isset($req->selectedid) ? $req->selectedid : $req->bus_type;
                    $getProvince = Province::where(['country_id'=>$dataId, 'province_status'=>1])
                      ->whereNotNull('province_name')
                      ->whereHas('supplier', function($query) use ($bus_type) {
                      $query->where(["supplier_status"=>1, 'business_id'=>$bus_type]);
                    })->orderBy("province_name", 'ASC')->get();
                }else{
                  $getProvince = Province::where(['country_id'=>$dataId, 'province_status'=>1])
                      ->whereNotNull('province_name')->orderBy("province_name", 'ASC')->get();
                }
                  

                if($getProvince->count() > 0){
                    $message .= "<option value=''>--choose--</option>";
                    foreach ($getProvince as $key => $pro) {
                      $message .= "<option value=".$pro->id." ".($pro->id == $req->selectedid?'selected':'').">".$pro->province_name."</option>";
                    }
                }else{
                    $message = "<option value=''>No city</option>";
                } 
              }
              // return $message;
            }
        }elseif ($req->datatype == "entrance" || $req->datatype == 'country_guide') {
          $getProvince = Province::where(['province_status'=>1, 'country_id'=>$dataId])
                      ->select('id', 'province_name')
                      ->whereHas('tour', 
                        function ($query) {
                          $query->where('tour_status', 1);
                        })
                      ->orderBy('province_name')
                      ->get();
          if($getProvince->count() > 0){
            $message .= "<option value=''>--choose--</option>";
            foreach ($getProvince as $key => $pro) {
              $message .= "<option value=".$pro->id." ".($pro->id == $req->selectedid?'selected':'').">".$pro->province_name."</option>";
            }
          }else{
            $message = "<option value=''>No city</option>";
          }     
        }elseif ($req->datatype == "tour_accommodation") {
          $getHotel = Supplier::where(['country_id'=> $dataId, 'business_id'=>1, 'supplier_status'=>1])->orderBy('supplier_name', 'ASC')->get();
          if ($getHotel->count() > 0) {
            foreach ($getHotel as $key => $sup) {
              $message .= '<li>
                              <div class="checkMebox">
                                <label>
                                  <span style="position: relative;top: 4px;"> 
                                    <i class="fa fa-square-o "></i>
                                    <input type="checkbox" name="tour_supplier[]" value="'.$sup->id.'">&nbsp;
                                  </span>
                                  <span>'.$sup->supplier_name.'</span>
                                </label>
                              </div>
                            </li>';
            }
          }else{
             $message = '<li>
                              <div class="checkMebox">
                                <label><span>Hotel not found...!</span></label>
                              </div>
                            </li>';
          }
        }elseif ($req->datatype == "addVehicle") {
          $tranName = TransportService::find($dataId);
          if ($tranName->supplier_transport->count() > 0) {
            $message .= "<option value=''>--Choose--</option>";
            foreach ($tranName->supplier_transport as $key=> $tran) {
              $message .= "<option value=".$tran->pivot->supplier_id." data-transport='".$dataId."'>".$tran->supplier_name."</option>";;
            }
          }else{
            $message = "<option>No Transport</option>";
          }
        }elseif ($req->datatype == "driver") {
            $getProvince = Province::where(['province_status'=>1, 'country_id'=>$dataId])
                        ->select("id", "province_name")
                        ->whereHas('driver')->orderBy('province_name')->get();
            // $getProvince = TransportService::where("country_id", $dataId)->groupBy("province_id")->get();
            if($getProvince->count() > 0){
              $message .= "<option value=''>Select city</option>"; 
              foreach ($getProvince as $key => $pro) {
                $message .= "<option value=".$pro->id." ".($pro->id == $req->selectedid?'selected':'').">".$pro->province_name."</option>";
              }
            }else{
                $message = "<option value=''>No city</option>";    
            } 
        }elseif ($req->datatype == "country_flight") {
          $cityFlight = FlightSchedule::where(['flight_status'=>1, 'country_id'=> $dataId])->groupBy('province_id')->orderBy('flight_from', 'DESC')->get();
            if($cityFlight->count() > 0){
              $message = "<option value=''>Choose City</option>"; 
                foreach ($cityFlight as $key => $pro) {
                  $fl = Province::find($pro->province_id);
                  if ($fl) {
                    $message .= "<option value=".$fl->id.">".$fl->province_name."</option>";
                  }                  
                }
            }else{
                $message .= "<option value=''>No city</option>";    
            } 
        }elseif ($req->datatype == "city_destination") {
          $cityFlight = FlightSchedule::where([['flight_status','=',1], ['province_id','=',$req->title], ['flight_to', 'LIKE', '%'.$dataId.'%']])->orderBy('flight_to', 'DESC')->get();
            if($cityFlight->count() > 0){
              $message = "<option value=''>Flight No</option>"; 
              foreach ($cityFlight as $key => $fl) {
                $message .= "<option value=".$fl->id.">$fl->flightno, D: $fl->dep_time -> A: $fl->arr_time</option>";
              }
            }else{
              $message .= "<option value=''>No city</option>";     
            }         
        }elseif ($req->datatype == "country_cruise") {
          $crProgram = Province::where(['province_status'=>1, 'country_id'=>$dataId])
            ->whereHas('supplier')
            ->orderBy('province_name', 'DESC')->get();
            if($crProgram->count() > 0){
              foreach ($crProgram as $key => $pro) {
                $Crp = CrProgram::where('province_id', $pro->id)->first();
                if ($Crp) {
                  $message .= "<option value=".$Crp->province->id.">".$Crp->province->province_name."</option>";
                }                  
              }
            }else{
              $message .= "<option value=''>No city</option>";    
            } 
        }elseif ($req->datatype == "restaurant") {
          $restaurant = Supplier::where(['supplier_status'=>1, 'business_id'=>2, 'province_id'=>$dataId])
              ->select("id", 'supplier_name')->whereHas('res_menu')->orderBy('supplier_name')
              ->get();
          if($restaurant->count() > 0){
            $message .= "<option value=''>--Choose--</option>"; 
            foreach ($restaurant as $key => $rest) {
              $message .= "<option  value=".$rest->id." ".($rest->id == $req->selectedid?'selected':'').">".$rest->supplier_name."</option>";
            }
          }else{
            $message .= "<option value=''>No Restaurant</option>";    
          }
        }elseif ($req->datatype == "restaurant_apply_menu") {
          $restaurant = Supplier::where(['supplier_status'=>1, 'business_id'=>2, 'country_id'=>$dataId])
              ->select("id", 'supplier_name')->whereHas('res_menu')->orderBy('supplier_name')
              ->get();
          if($restaurant->count() > 0){
            $message .= "<option value=''>--Choose--</option>"; 
            foreach ($restaurant as $key => $rest) {
              $message .= "<option  value=".$rest->id." ".($rest->id == $req->selectedid?'selected':'').">".$rest->supplier_name."</option>";
            }
          }else{
            $message .= "<option value=''>No Restaurant</option>";    
          }

        }elseif ($req->datatype == "pro_tour"){
          $getTour = Tour::where(['tour_status'=>1, 'province_id'=> $dataId])
            ->whereHas('pricetour')
            ->orderBy('tour_name', 'ASC')->get();
          if($getTour->count() > 0){
             $message .= "<option value=''>--Choose--</option>"; 
              foreach ($getTour as $key => $tour) {
                $message .= "<option  value=".$tour->id.">".$tour->tour_name."</option>";
              }
          }else{
            $message .= "<option value=''>No Tour</option>"; 
          }         
        }elseif ($req->datatype == "pax_no"){
          $getTourPrice= TourPrice::where(['status'=>1, 'tour_id'=> $dataId])->whereNotIn('sprice', ['', 0, "null"])->orderBy('pax_no', 'ASC')->get();
          if($getTourPrice->count() > 0){
            $message .= "<option value=''>---select---</option>";
              foreach ($getTourPrice as $key => $pax) {
                if ($pax->pax_no > 0) {
                  $message .= '<option value="'.($pax->pax_no).'" data-price="'.($pax->sprice).'"   data-nprice='.$pax->nprice.'>'.$pax->pax_no.'</option>';
                }
              }
          }else{
              $message = "<option value=''>No Pax</option>"; 
          }       
        }elseif ($req->datatype == "pro_hotel"){
          $getHotel = Supplier::where(['business_id'=>1, 'supplier_status'=>1, 'province_id'=>$dataId])
              ->whereHas('room') 
              ->orderBy('supplier_name', 'ASC')->get();
          if($getHotel->count() > 0){
            $message .= "<option value=''>Select Hotel</option>";
            foreach ($getHotel as $key => $hotel) {
              $message .= "<option value=".$hotel->id.">".$hotel->supplier_name."</option>";
            }
          }else{
              $message = "<option value=''>No Hotel</option>";
          }       
        }elseif ($req->datatype == "pro_flight"){
          $getSchedule = FlightSchedule::where(['flight_status'=>1, 'province_id'=>$dataId])->whereNotIn("flight_to", [""])->groupBy("flight_to")->orderBy('flight_to', 'ASC')->get();
          if($getSchedule->count() > 0){
            $message .= "<option value=''>Destination</option>";
            foreach ($getSchedule as $key => $sch) {
              $message .= "<option value='".$sch->flight_to."'>".$sch->flight_to."</option>";
            }
          }else{
            $message = "<option value=''>No Destination</option>";    
          }  
        }elseif ($req->datatype == "flightno"){
          $getfAgent = FlightSchedule::find($dataId);
          if( isset($getfAgent->flightagent) && $getfAgent->flightagent->count() > 0){
            $message = "<option value='' class='no_agent'>Flight Agent</option>";
            foreach ($getfAgent->flightagent as $key => $sch) {
              $message .= "<option value=".$sch->id." data-oneway='".$sch->pivot->oneway_price."' data-return='".$sch->pivot->return_price."'
              data-noneway='".$sch->pivot->oneway_nprice."' data-nreturn='".$sch->pivot->return_nprice."' data-koneway='".$sch->pivot->oneway_kprice."' data-kreturn='".$sch->pivot->return_kprice."'>".$sch->supplier_name."</option>";
            }
          }else{
            $message = "<option value=''>No Agent</option>";    
          }  
        }elseif ($req->datatype == "cruise"){
          $getCruise = Supplier::where(['business_id'=>3, 'supplier_status'=>1, 'province_id'=> $dataId])->orderBy('supplier_name', 'ASC')->get();
          if($getCruise->count() > 0){
            $message .= "<option id='no_program'>Select Cruise</option>";
            foreach ($getCruise as $key => $cr) {
              $message .= "<option value=".$cr->id.">".$cr->supplier_name."</option>";
            }
          }else{
            $message .= "<option value=''>No Golf Course</option>";
          }    
        }elseif ($req->datatype == "river-cruise"){
          $getCProgram = CrProgram::where(['status'=>1,'province_id'=> $dataId])->orderBy('program_name', 'ASC')->get();
          if($getCProgram->count() > 0){
              $message .= "<option id='no_program'>Select Cruise Program</option>";
            foreach ($getCProgram as $key => $cpro) {
              $message .= "<option value=".$cpro->id.">".$cpro->program_name."</option>";
            }
          }else{
            $message .= "<option value=''>No Program</option>";    
          }  
        }elseif ($req->datatype == "pro_golf"){
          $getGolf = Supplier::where(['business_id'=>29, 'supplier_status'=>1, 'province_id'=> $dataId])->orderBy('supplier_name', 'ASC')->get();
          if($getGolf->count() > 0){
            $message .= "<option value=''>Select Golf</option>";
            foreach ($getGolf as $key => $golf) {
              $message .= "<option value=".$golf->id.">".$golf->supplier_name."</option>";
            }
          }else{
            $message .= "<option value=''>No Golf</option>"; 
          }       
        }elseif ($req->datatype == "golf_supplier"){
          $getGolf = Supplier::where(['business_id'=>29, 'supplier_status'=>1, 'country_id'=> $dataId])->orderBy('supplier_name', 'ASC')->get();
                    // Supplier::where(['business_id'=>29, 'supplier_status'=>1])->orderBy('supplier_name', 'ASC')->get() 
          if($getGolf->count() > 0){
            $message .= "<option value=''>Select Golf</option>";
            foreach ($getGolf as $key => $golf) {
              $message .= "<option value=".$golf->id." ".($req->selectedid == $golf->id ? 'selected' : '').">".$golf->supplier_name."</option>";
            } 
          }else{
            $message .= "<option value=''>No Golf</option>"; 
          }       
        
        }elseif ($req->datatype == "golf_service"){
          $getGolfMenu = GolfMenu::where(['status'=>1, 'supplier_id'=>$dataId])->orderBy('name', 'ASC')->get();
          if($getGolfMenu->count() > 0){
            $message .= "<option value='' id='no_golf_service'>Select Golf</option>";
            foreach ($getGolfMenu as $key => $golf) {
              $message .= "<option value=".$golf->id." data-price='".$golf->price."' data-nprice='".$golf->nprice."' data-kprice='".$golf->kprice."' data-nkprice='".$golf->nkprice."'>".$golf->name."</option>";
            }
          }else{
            $message .= "<option value=''>No Service</option>";
          }     

        }elseif ($req->datatype == "book_tour"){
            $getTPrice = TourPrice::where('tour_id', $dataId)->orderBy('pax_no', 'ASC')->get();
            if ($getTPrice->count() > 0) {
              $message .= "<option id='no_tourPax'>Select Pax</option>";
              foreach ($getTPrice as $key => $tprice) {
                if($tprice->sprice > 0 || $tprice->nprice > 0){
                  $message .="<option value=".$tprice->pax_no." data-price='".$tprice->sprice."' data-nprice='".$tprice->nprice."'>".$tprice->pax_no." = ".$tprice->sprice." <small>".Content::currency()."</small></option>";
                }
              }
            }
        }elseif ($req->datatype == "book_golf") {
          $getGolmen = GolfMenu::where('supplier_id', $dataId)->orderBy('name', 'ASC')->get();
          if ($getGolmen->count() > 0) {
            $message .= "<option  id='no_data'>--Choose--</option>";
              foreach ($getGolmen as $key => $gms) {
                $message .="<option value=".$gms->id." data-price='".$gms->price."' data-nprice='".$gms->nprice."'>".$gms->name." <small>".Content::currency()."</small></option>";
              }
          }else{
            $message = "<option>No Service</option>";
          }
        }elseif ($req->datatype == "transport_service"){
          $gettranService = Supplier::where(['supplier_status'=> 1, 'business_id'=>7, 'province_id'=> $dataId])->orderBy('supplier_name')->get();
          if ($gettranService->count() > 0 && $req->selectedid != "transportation") {
            foreach ($gettranService as $key => $tran) {
              $checked = in_array($tran->id, explode(',', $req->selectedid)) ? 'checked':'';
              $message .= "<li><div class='checkbox'><label for='checkid".$key."'><input id='checkid".$key."' style='position: relative;' type='checkbox' name='multi_sup[]' value=".$tran->id."  ".$checked."> <span style='position: relative;top: -5px;left: 5px;'>".$tran->supplier_name."</span></label></div></li>";
            }
          }else{
              $message = "<option value='0'>--Choose--</option>";
              if ($gettranService->count() > 0){
                foreach ($gettranService as $key => $tran) {
                  $message .="<option value=".$tran->id.">".$tran->supplier_name."</option>";
                }
              }else{
                $message = "<option>No Transport</option>";
              }
          }

        }elseif ($req->datatype == "guide") {
          $GuideService = Supplier::where(['supplier_status'=>1, 'business_id'=>6, 'province_id'=> $dataId])->orderBy('supplier_name', 'ASC')->get();
          if ($GuideService->count() > 0) {
            $message .= "<option id='no_data'>--choose--</option>";
            foreach ($GuideService as $key => $gservice) {
              $message .="<option ".($req->selectedid == $gservice->id ? "selected" : "")." value=".$gservice->id.">".$gservice->supplier_name."</option>";
            }
          }else{
            $message = "<option>No Guide</option>";
          }
      
        }elseif ($req->datatype == "vehicle") {
          $vehicleService = \App\TransportMenu::where(['status'=>1, 'supplier_id'=> $req->selectedid, 'transport_id'=>$dataId])->orderBy('name', 'ASC')->get();
          if ($vehicleService->count() > 0) {
            $message .= "<option id='no_data'>Choose Vehicle</option>";
            foreach ($vehicleService as $key => $tran) {
              $message .="<option ".($req->selectedid == $tran->id ?"selected":'')."  value=".$tran->id." data-price='".$tran->price."' data-kprice='".$tran->kprice."' >".$tran->name."</option>";
            }
          }else{
            $message = "<option>No Vehicle</option>";
          }
        }elseif ($req->datatype == "booking_restaurant") {
          $restName = Supplier::where(['supplier_status'=>1,'province_id'=>$dataId])
              ->whereHas('res_menu')->orderBy('supplier_name')->get();
          if ($restName->count() > 0) {
            $message .= "<option id='no_data'>--Choose--</option>";
            foreach ($restName as $key => $tran) {
              $message .="<option ".($req->selectedid == $tran->id ? "selected" : "")." value=".$tran->id.">".$tran->supplier_name."</option>";
            }
          }else{
            $message = "<option>No Restaurant</option>";
          }
        }elseif ($req->datatype == "booking_restaurant_menu") {
          $restMenu = \App\RestaurantMenu::where(['status'=>1, 'supplier_id'=>$dataId])->orderBy('title', 'ASC')->get();
          if ($restMenu->count() > 0) {
            $message .= "<option id='no_data'>--Choose--</option>";
            foreach ($restMenu as $key => $rm) {
              $message .="<option ".($req->selectedid == $rm->id ?"selected":'')." value=".$rm->id." data-price='".$rm->price."' data-kprice='".$rm->kprice."'>".$rm->title."</option>";
            }
          }else{
            $message = "<option>No Menu</option>";
          }
        }elseif ($req->datatype == "booking_transport") {
          $gettranService = Supplier::where(['supplier_status'=>1, 'business_id'=>7, 'country_id'=>$dataId])
            ->whereHas('transport_service', function($query) {
                $query->where('status', 1);
            })
            ->orderBy('supplier_name', 'ASC')->get();
          if ($gettranService->count() > 0) {
              $message .= "<option id='no_data' data-phone='' data-phone2=''>--Choose--</option>";
            foreach ($gettranService as $key => $sup) {
              $message .="<option value=".$sup->id." data-phone='".$sup->supplier_phone."'  data-phone2='".$sup->supplier_phone2."'>".$sup->supplier_name."</option>";
            }
          }else{
            $message = "<option>No Service</option>";
          }
        }elseif ($req->datatype == "booking_driver") {
          $getDriver = \App\Driver::where(['supplier_id'=>$dataId, "status"=>1])->orderBy('driver_name', 'ASC')->get();
          if ($getDriver->count() > 0) {
            $message .= "<option id='no_data'>--Choose--</option>";
            foreach ($getDriver as $key => $dr) {
              $message .="<option value=".$dr->id." data-phone='".$dr->phone."' data-phone2='".$dr->phone2."'>".$dr->driver_name."</option>";
            }
          }else{
            $message = "<option>No Driver</option>";
          }
        
        }elseif ($req->datatype == "tran_service") {
          $tranService = Supplier::find($dataId);
          if ($tranService->transport_service->count() > 0) {
            $message .= "<option id='no_data'>--Choose--</option>";
            foreach ($tranService->transport_service as $key => $tran) {
              $message .="<option value=".$tran->id.">".$tran->title."</option>";
            }
          }else{
            $message = "<option>No Service</option>";
          }
        }elseif ($req->datatype == "transport_service") {
          $gettranService = Supplier::where(['supplier_status'=> 1, 'business_id'=>6, 'country_id'=> $dataId])
              ->orderBy('supplier_name')->get();
          if ($gettranService->count() > 0) {
            // $message .= "<option id='no_data'>Choose Driver</option>";
            foreach ($gettranService as $key => $sup) {
              $message .="<option value=".$sup->id." data-phone='".$sup->supplier_phone."'  data-phone2='".$sup->supplier_phone2."'>".$sup->supplier_name."</option>";
            }
          }else{
            $message = "<option>No Guide</option>";
          }
        }elseif ($req->datatype == "entrance_fee") {
          $restMenu = \App\Entrance::where(['status'=>1, 'province_id'=> $dataId])->orderBy('name', 'ASC')->get();
          if ($restMenu->count() > 0) {
            $message .= "<option id='no_data'>--Choose--</option>";
            foreach ($restMenu as $key => $rm) {
              $message .="<option ".($rm->id == $req->selectedid ? "selected" : "")." value=".$rm->id." data-price='".$rm->price."'  data-kprice='".$rm->kprice."'>".$rm->name."</option>";
            }
          }else{
            $message = "<option>No Service</option>";
          }
        }elseif ($req->datatype == "apply_guide") {
          $gServices = \App\GuideService::where(['status'=>1, 'province_id'=> $dataId])->orderBy('title', 'ASC')->get();
          if ($gServices->count() > 0) {
            $message .= "<option id='no_data'>--Choose--</option>";
            foreach ($gServices as $key => $rm) {
              $message .="<option ".($rm->id == $req->selectedid ? "selected" : "")." value=".$rm->id." data-price='".$rm->price."' data-kprice='".$rm->kprice."' >".$rm->title."</option>";
            }
          }else{
            $message = "<option>No Service</option>";
          }
        }elseif ($req->datatype == "apply_misc") {
            $miscServices = \App\MISCService::where(['status'=>1, 'province_id'=>$dataId])
                      ->orderBy('name', 'ASC')->get();
            if ($miscServices->count() > 0) {
              $message .= "<option id='no_data'>--choose--</option>";
              foreach ($miscServices as $key => $rm) {
                $message .="<option ".($rm->id == $req->selectedid ? "selected" : "")." value=".$rm->id." data-price='".$rm->price."' data-kprice='".$rm->kprice."' >".$rm->name."</option>";
              }
            }else{
              $message = "<option>No Service</option>";
            }
        }elseif ($req->datatype == "country_misc") {
            $crProgram = Province::where(['province_status'=>1, 'country_id'=>$dataId])
            ->whereHas('tour')
            ->orderBy('province_name', 'DESC')->get();
            if($crProgram->count() > 0){
              $message .= "<option id='no_data'>--choose--</option>";
              foreach ($crProgram as $key => $pro) {
                $message .= "<option value=".$pro->id.">".$pro->province_name."</option>";
              }
            }else{
              $message .= "<option value=''>No city</option>";    
            } 
        }elseif ($req->datatype == "apply_language") {
          $gLanguage = \App\GuideLanguage::where(['status'=>1, 'guide_service_id'=>$dataId])->orderBy('name', 'ASC')->get();
          if ($gLanguage->count() > 0) {
            $message .= "<option id='no_data'>--Choose--</option>";
            foreach ($gLanguage as $key => $rm) {
              $message .="<option ".($req->selectedid == $rm->id ? 'selected' : '')."  value=".$rm->id."   data-sup=".$rm->supplier_id.">".$rm->name."</option>";
            }
          }else{
            $message = "<option>No Language</option>";
          }
        }elseif ($req->datatype == "language-supplier") {
          $getSupBylanguage = \App\GuideLanguage::find($dataId);
          if (isset($getSupBylanguage)) {
            $message .= "<option id='no_data'>--Choose--</option>";
            foreach ($getSupBylanguage->supplier as $key => $g) {
              $message .="<option ".($req->selectedid == $g->id ?"selected":'')."  data-phone='".$g->supplier_phone."'  data-phone2='".$g->supplier_phone2."' value=".$g->id." data-price='".$getSupBylanguage->price."' data-kprice='".$getSupBylanguage->kprice."'>".$g->supplier_name."</option>";
            }

          }else{
            $message = "<option>No Guide</option>";
          }
        }elseif ($req->datatype == "guide_by_country") {
          $getSupLanguage = \App\Supplier::where(['country_id'=> $dataId, 'business_id'=> 6, 'supplier_status'=>1])->orderBy('supplier_name', 'ASC')->get();
          if ($getSupLanguage->count() > 0) {
            foreach ($getSupLanguage as $key => $gud) {
              $checked = in_array($gud->id, explode(',', rtrim($req->selectedid, ","))) ? 'checked':'';
              $message .= "<li style='padding-left:0px; padding:4px;'><div class='checkbox' style='margin:0px;'><label style='padding-left:0px !important;' for='checkid".$key."'>
                <input id='checkid".$key."' style='position:relative; width:14px; height:14px;' type='checkbox' name='multi_sup[]' value=".$gud->id." class='supName' ".$checked."> <span style='position: relative;top: -5px;left: 5px;'>".$gud->supplier_name."</span></label></div></li>";
            }
          }else{
            $message = "<li>No Guide </li>";
          }
        }elseif ($req->datatype == 'business_docs') {
          $getSubDepartment = \App\DepartmentMenu::where('department_id', $dataId)->orderBy('name', 'ASC')->get();
          if ($getSubDepartment->count() > 0) {
            foreach ($getSubDepartment as $key => $dep) {
              $message .= "<option value='".$dep->id."'>".$dep->name."</option>";
            }
          }else{
            $message = "<option>No Category</option>";
          }
        }elseif ($req->datatype == 'supplier_by_country') {
          $getSuppliers = \App\Supplier::where(['country_id'=>$dataId,'business_id'=>$req->selectedid, 'supplier_status'=>1])->orderBy('supplier_name')->get();
          if ($getSuppliers->count() > 0) {
            $message ="<option>--Choose--</option>";
            foreach ($getSuppliers as $key => $sup) {
             $message .="<option value='$sup->id'>$sup->supplier_name</option>";
            }
          }else{
            $message = "<option>No Supplier</option>";
          }
        }
        echo $message;
    }

    public function addBookinOption(Request $req){
      $i = $req->i;
      if ($req->type == "tour") {
          echo '<tr>
                  <td>
                    <div class="row">
                      <div class="col-md-8">
                        <input type="hidden" name="group_tour[]" value="1">
                        <input type="text" id="book_date_'.$i.'" name="tour_start[]" class="form-control input-sm" placeholder="TourDate: 2018-04-11" required>
                      </div>
                    </div>
                  </td>
                  <td style="width:16%;">
                    <select class="form-control input-sm country" name="country_tour[]" data-type="country" data-pro_of_bus="tour_bus" data-pro_of_bus_id="6" data-book="booking" required><option class="no_country">Select Country</option>';
                      foreach(Country::where('country_status', 1)->whereHas('tour')->orderBy('country_name', 'ASC')->get() as $con){
                        echo '<option value="'.$con->id.'">'.$con->country_name.'</option>';
                      }
                    echo '</select>
                  </td>
                  <td class="province_container" style="width:16%;">
                    <select class="form-control input-sm province" name="city_tour[]" data-type="pro_tour" required>
                      <option>City</option>                  
                    </select>
                  </td>
                  <td class="tour_container" style="width:24%;">
                    <select class="form-control tour input-sm" name="tour_name[]" data-type="pax_no" required>
                      <option>Tour</option>
                    </select>
                  </td>
                  <td class="pax_container">   
                   <select class="form-control input-sm pax_no" name="tour_pax[]">
                      <option>Pax</option>
                    </select>                         
                  </td>
                  <td class="text-right pax_price" style="vertical-align: middle;">
                    <input type="hidden" name="tour_price[]" id="pax_price">
                    <input type="hidden" name="tour_nprice[]" id="tour_nprice">
                    <span>00.0</span>
                  </td>
                  <td class="text-right pax_total" style="vertical-align: middle;">
                    <input type="hidden" name="tour_amount[]" id="tour_amount">
                    <span>00.0</span>  
                  </td>
                  <td class="text-right" style="vertical-align: middle;">
                    <span class="btn btn-xs btn-danger RemoveBook"><i class="fa fa-close (alias)"></i></span>
                  </td>';
                  $this->tourDate($i);
          echo '</tr>';          
              
      } else if($req->type == "hotel"){
          echo '<tr>
                  <td>
                    <div class="input-group">
                      <input type="hidden" name="group_hotel[]" value="2">
                      <input type="text" name="fromdate[]" id="from_date_'.$i.'" class="form-control input-sm" placeholder="From Date" required><div class="input-group-addon">to</div>
                      <input type="text" name="todate[]" id="to_date_'.$i.'" class="form-control input-sm" placeholder="To Date" required>
                    </div>
                  </td>
                  <td style="width:16%;">
                    <select class="form-control input-sm country" name="country_hotel[]" data-type="country" data-pro_of_bus="sub_bus" data-pro_of_bus_id="1" data-book="booking" required><option class="no_country">Select Country</option>';
                      foreach(Country::getCountry() as $con){
                        echo '<option value="'.$con->id.'">'.$con->country_name.'</option>';
                      }
                    echo '</select>
                  </td>
                  <td class="province_container" style="width:16%;">
                    <select class="form-control input-sm province" name="city_hotel[]" data-type="pro_hotel" required>
                      <option>City</option>                  
                    </select>
                  </td>
                  <td class="hotel_container" style="width: 24%;">
                    <select class="form-control input-sm hotel" name="hotel_name[]" required>
                      <option>Hotel</option>
                    </select>
                  </td>
                  <td>';
                  if ($req->option && $req->option == "quotation") {
                      echo'<select class="form-control input-sm" name="hotel_option[]" required>';
                        for ($qu=1; $qu < 8; $qu++) { 
                          echo '<option value="'.$qu.'">Option '.$qu.'</option>';
                        }
                      echo '</select>';
                      }
                    
                  echo '</td>
                  <td class="text-right" style="vertical-align: middle;">00.0</td>
                  <td class="text-right" style="vertical-align: middle;">00.0</td>
                  <td class="text-right" style="vertical-align: middle;">
                    <span class="btn btn-xs btn-danger RemoveBook"><i class="fa fa-close (alias)"></i></span>
                  </td>';
                  $this->checkInCheckOut($i);
          echo '</tr>';
        
      } else if($req->type == "flight"){
          echo '<tr>
                <td class="bookway_container">
                  <div class="row">
                    <div class="col-md-6">
                      <input type="hidden" name="group_flight[]" value="3">
                      <input type="text" name="ftodate[]" class="form-control input-sm" required="" id="book_date_'.$i.'" placeholder="Flight Date" required>
                    </div>
                    <div class="col-md-6 ">
                      <select class="form-control input-sm bookway" name="fway[]" required>
                        <option value="Oneway">Oneway</option>
                        <option value="Return">Return</option>
                      </select>
                    </div><div class="clearfix"></div>
                  </div>
                </td>
                <td style="width:16%;">
                  <select class="form-control input-sm country" name="country_flight[]" data-type="country_flight" data-pro_of_bus="sub_bus" data-pro_of_bus_id="4" data-book="booking" required><option class="no_country">Select Country</option>';
                    foreach(Country::getCountry(4) as $con){
                      echo '<option value="'.$con->id.'">'.$con->country_name.'</option>';
                    }
                  echo '</select>
                </td>
                <td class="province_container" style="width:16%;">
                  <div class="row">
                    <div class="col-md-6" style="padding-right:0px;">
                      <select class="form-control input-sm province" name="city_flight[]" data-type="pro_flight" required>
                        <option>City</option>                  
                      </select>
                    </div>
                    <div class="col-md-6" style="padding-left:0px;">
                      <select class="form-control input-sm city_destination" name="city_destination[]" data-type="city_destination" required>
                        <option>Destination</option>                  
                      </select>
                    </div>
                  </div>
                </td>
                <td class="flight_container">
                  <div class="row">
                    <div class="col-md-6">
                      <select class="form-control input-sm flightno" name="flightno[]" data-type="flightno" required>
                        <option>Flight No.</option>
                      </select> 
                    </div>
                    <div class="col-md-6">                      
                      <select class="form-control input-sm ticketing" name="ticketing[]" required>
                        <option>Ticketing Agent</option>
                      </select> 
                    </div>
                  </div>
                </td>
                <td class="fagent_container">  
                  <select class="form-control input-sm flightPax" name="flightPax[]">';
                    for ($fp=1; $fp <= 10; $fp++) { 
                      echo '<option value='.$fp.'>'.$fp.'</option>';
                    }
                  echo '</select>
                </td>
                <td class="text-right pax_price" style="vertical-align: middle;">
                  <input type="hidden" name="flight_price[]" id="pax_price">
                  <input type="hidden" name="flight_nprice[]" id="pax_nprice">
                  <input type="hidden" name="flight_kprice[]" id="pax_kprice">
                  <span>00.0</span>
                </td>
                <td class="text-right pax_total" style="vertical-align: middle;">
                  <input type="hidden" name="flight_amount[]" id="flight_amount">
                  <span>00.0</span></td>
                <td class="text-right" style="vertical-align: middle;">
                  <span class="btn btn-xs btn-danger RemoveBook"><i class="fa fa-close (alias)"></i></span>
                </td>';
            $this->tourDate($i);
        echo '</tr>';
      
      } else if($req->type == "cruise"){
        echo '<tr>
                <td>
                  <div class="input-group">
                    <input type="hidden" name="group_cruise[]" value="4">
                    <input type="text" name="cfromdate[]" id="from_date_'.$i.'" class="form-control input-sm" placeholder="Start Date" required><div class="input-group-addon">to</div>
                    <input type="text" name="ctodate[]" id="to_date_'.$i.'" class="form-control input-sm" placeholder="End Date" required>
                  </div>
                </td>
                <td style="width:16%;">
                  <select class="form-control input-sm country" name="country_cruise[]" data-type="country_cruise" data-pro_of_bus="sub_bus" data-pro_of_bus_id="3" data-book="booking" required><option class="no_country">Select Country</option>';
                    foreach(Country::getCountry(3) as $con){
                      echo '<option value="'.$con->id.'">'.$con->country_name.'</option>';
                    }
                echo '</select>
                </td>
                <td class="province_container" style="width:16%;">
                  <select class="form-control input-sm province" name="city_cruise[]" data-type="river-cruise" required>
                    <option>City</option>                  
                  </select>
                </td>
                <td class="cruise_container" style="width:24%;">
                  <div class="row">
                    <div class="col-md-12">
                      <select class="form-control input-sm cruise-program" name="cruise_program[]" required>
                        <option>Cruise Program</option>
                      </select>
                    </div>
                  </div>
                </td>
                <td>                           
                </td>
                <td class="text-right" style="vertical-align: middle;">00.0</td>
                <td class="text-right" style="vertical-align: middle;">00.0</td>
                <td class="text-right" style="vertical-align: middle;">
                  <span class="btn btn-xs btn-danger RemoveBook"><i class="fa fa-close (alias)"></i></span>
                </td>';
                $this->checkInCheckOut($i);
        echo '</tr>';
      
      } else if($req->type == "golf"){
        echo '<tr>
                <td>
                  <div class="row">
                    <div class="col-md-8">
                      <input type="hidden" name="group_golf[]" value="5">
                      <input type="text" name="gdate[]" class="form-control input-sm" required id="book_date_'.$i.'" placeholder="GolfDate: 2018-04-26">
                    </div>                   
                  </div>
                </td>
                <td style="width:16%;">
                  <select class="form-control input-sm country" name="country_golf[]" data-type="country" data-pro_of_bus="sub_bus" data-pro_of_bus_id="29" data-book="booking" required><option class="no_country">Select Country</option>';
                    foreach(Country::getCountry(29) as $con){
                      echo '<option value="'.$con->id.'">'.$con->country_name.'</option>';
                    }
                  echo '</select>
                </td>
                <td class="province_container" style="width:16%;">
                  <select class="form-control input-sm province" name="city_golf[]" data-type="pro_golf" required>
                    <option>City</option>                  
                  </select>
                </td>
                <td class="golf_container">
                  <div class="row">
                    <div class="col-md-6">
                      <select class="form-control input-sm golf" name="golf_name[]" data-type="golf_service" required>
                        <option>Golf Course</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <select class="form-control input-sm golf_service" name="golf_service[]" data-type="golf_price" required>
                        <option>Golf Services</option>
                      </select> 
                    </div>
                  </div>
                </td>
                <td class="golf_pax_container" style="width:6%;">';
                echo '<select class="form-control input-sm golfPax" name="pax[]">';
                for($n=1; $n<=30; $n++){
                  echo '<option value="'.$n.'">'.$n.'</option>';
                }
                echo '</select>';
                echo '</td>
                <td class="text-right pax_price" style="vertical-align: middle;">
                <input type="hidden" name="golfprice[]" id="golfprice">
                <input type="hidden" name="golfnprice[]" id="golfnprice">
                <input type="hidden" name="golfkprice[]" id="golfkprice">
                <input type="hidden" name="golfnkprice[]" id="golfnkprice">

                <span>00.0</span></td>
                <td class="text-right pax_total" style="vertical-align: middle;">
                <input type="hidden" name="golfamount[]" id="golfamount">
                <input type="hidden" name="golfnamount[]" id="golfnamount">
                <input type="hidden" name="golfkamount[]" id="golfkamount">
                <input type="hidden" name="golfnkamount[]" id="golfnkamount">

                <span >00.0</span></td>
                <td class="text-right" style="vertical-align: middle;">
                  <span class="btn btn-xs btn-danger RemoveBook"><i class="fa fa-close (alias)"></i></span>
                </td>';
                $this->tourDate($i);
        echo '</tr>';
      }
    }

    public function tourDate($i){
      echo '<script type="text/javascript">
              $(function(){
                var nowTemp = new Date();
                var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
                var book_date = jQuery("#book_date_'.$i.'").datepicker({
                  format: "yyyy-mm-dd",
                  onRender: function(date) {
                    // return date.valueOf() < now.valueOf() ? "disabled" : "";
                  }
                }).on("changeDate", function(ev){
                    book_date.hide();
                  }).data("datepicker");
              });
            </script>';
    }

    // get datepicker to show check and check out/
    public function checkInCheckOut($i){
        echo'<script type="text/javascript">
                $(function(){
                    var nowTemp = new Date();
                    var formatdate = "yyyy-mm-dd";
                    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
                    var checkin = $("#from_date_'.$i.'").datepicker({
                        format: formatdate,
                        onRender: function(date) {
                            return date.valueOf() < now.valueOf() ? "" : "";
                        }
                    }).on("changeDate", function(ev) {
                      if (ev.date.valueOf() > checkout.date.valueOf()) {
                        var newDate = new Date(ev.date)
                        newDate.setDate(newDate.getDate() + 1);
                        checkout.setValue(newDate);
                      }
                      checkin.hide();
                      $("#to_date_'.$i.'")[0].focus();
                    }).data("datepicker");
                    var checkout = $("#to_date_'.$i.'").datepicker({
                        format: formatdate,
                        onRender: function(date) {
                          return date.valueOf() < checkin.date.valueOf() ? "disabled" : "";
                        } 
                    }).on("changeDate", function(ev) {
                      checkout.hide();
                    }).data("datepicker");
                });
            </script>';
    } 

    public function searchProject(Request $req, $project){
        $status = isset($req->status) && $req->status == "Inactive" ? 0 : 1;
        $quotation = isset($req->type) && $req->type == "quotation" ? 1 : 0;
        $startDate = $req->start_date;
        $endDate = $req->end_date;
        $projectNum = $req->textSearch;
        if ($project == "project") {
          if ( !empty($projectNum) ) {
            if (isset($req->type) && $req->type == "quotation") {
              $projects = Project::getProjectSearch($projectNum, $status, $quotation)->get();
              return view('admin.project.projectQuotation', compact('projects', 'startDate','endDate', 'projectNum'));
            }
            $projects = Project::getProjectSearch($projectNum)->get();
          }else if( !empty($startDate) && !empty($endDate)){
              if (isset($req->type) && $req->type == "quotation") {
                $projects = Project::getProjectTags($startDate, $endDate, $status, $quotation)->get();
                return view('admin.project.projectQuotation', compact('projects', 'startDate','endDate', 'projectNum'));
              }            
            $projects = Project::getProjectTags($startDate, $endDate, $status, $quotation)->get();
          }else {
            $projects = project::where(['project_number'=>$projectNum, 'project_status'=>1, "active"=>$status])
                        ->orWhere('project_fileno', $projectNum)
                        ->Where('project_client', 'like', $projectNum. '%')
                        ->whereBetween('project_start', [$startDate, $endDate])
                        ->orderBy('project_number', 'DESC')->get();
          }
        }elseif ($project == "hotelrate") { 
          if ( !empty($projectNum) ) {
              $projects = HotelBooked::where(['project_number'=>$projectNum, 'status'=> 1])->orderBy('id', 'DESC')->get();
            }else if( !empty($startDate) && !empty($endDate)){
              $projects = HotelBooked::where('status', 1)->whereBetween('checkin', [$startDate, $endDate])->orderBy('id', 'DESC')->get();
            }else {
              $projects = HotelBooked::where(['project_number'=>$projectNum, 'status'=> 1])
                        ->whereBetween('checkin', [$startDate, $endDate])->orderBy('id', 'DESC')
                        ->get();
          }
        }elseif ($project == "cruiserate") {
          if ( !empty($projectNum) ) {
              $projects = CruiseBooked::where(['project_number'=> $projectNum, 'status'=>1])->orderBy('id', 'DESC')->get();
            }else if( !empty($startDate) && !empty($endDate)){
              $projects = CruiseBooked::whereBetween('checkin', [$startDate, $endDate])->orderBy('id', 'DESC')->get();
            }else {
              $projects = CruiseBooked::where(['project_number'=> $projectNum, 'status'=>1])
                        ->whereBetween('checkin', [$startDate, $endDate])->orderBy('id', 'DESC')->get();
          }
        }else{ //if not search project will doing on this
          $bookId = $project.'_id'; 
          if ( !empty($projectNum) )  {
              $projects = Booking::getBookedProjectByProjectNum($projectNum)->whereNotIn($bookId, ['NULL','0'])->get();
          }else if( !empty($startDate) && !empty($endDate) ){          
              $projects = Booking::getBookedProjectByDate($startDate, $endDate)->whereNotIn($bookId, ['NULL','0'])->get();
          }else{
              $projects = Booking::getBookProjectNoAndDate($projectNum, $startDate, $endDate)->whereNotIn($bookId, ['NULL','0'])->get();
          }
        }      
      return view('admin.project.booked'.ucwords($project), compact('projects', 'startDate','endDate', 'projectNum'));
    }

    public function bookingHotelRate(Request $req){
      $rate = RoomRate::where(['supplier_id'=> $req->hotelid, 'room_id'=>$req->roomid])
                      ->whereBetween('end_date', [$req->checkin, $req->checkout])->first();
      return response()->json($rate);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDepart()
    {
        //
        // return view('admin.department.department'); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
