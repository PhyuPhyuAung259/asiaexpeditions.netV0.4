<?php
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\component\Content;
use Intervention\Image\ImageManagerStatic as Image;
use App\Business;
use App\Room;
use App\RoomRate;
use App\RoomCategory;
use App\Supplier;
use App\HotelFacitily;
use App\HotelCategory;
use App\HotelRate;
use App\HotelBooked;
class HotelController extends Controller 
{
    //
    public function hotelList(){
    	$hotels = Business::where('name', 'hotels')->first();
    	return view('admin.hotel.hotel', compact('hotels'));
    } 

    public function getRoom(){
    	$rooms = Room::orderBy('name', 'ASC')->get();
    	return view('admin.hotel.roomList', compact('rooms'));
    }
    
    public function getHotelinfo(Request $req){
        if (isset($req->hotel_checked) && !empty($req->hotel_checked)) {
            $hotelChecked = isset($req->hotel_checked) ? $req->hotel_checked : [0];
            $suppliers = Supplier::where('supplier_status', 1)->whereIn('id', $hotelChecked)->orderBy('supplier_name')->get();
            return view('admin.hotel.hotel_Report', compact('suppliers'));
        }else{
            $hotelinfo = HotelCategory::orderBy('name', 'ASC')->get();
            return view('admin.hotel.hotel_info', compact('hotelinfo'));
        }
    }

    public function getHotelFacility(){
        $hfacility = HotelFacitily::orderBy('name', 'ASC')->get();
        return view('admin.hotel.hotel_facility', compact('hfacility'));
    }

    public function getRoomCategory(){
    	$roomCat = RoomCategory::orderBy('name', 'ASC')->get();
    	return view('admin.hotel.category', compact('roomCat'));
    }
 
    public function getRoomApplied(Request $req){
        $locationid = isset($req->location) ? $req->location: \Auth::user()->country_id;
        $roomHotel = Room::getHotelRoomRate($locationid);
        return view('admin.hotel.roomAppliedHotel', compact('roomHotel', 'locationid'));
    }

    public function getHotelRate($hotelId, $roomId){
        $hotel = Supplier::find($hotelId);
        $room = Room::find($roomId);
        return view('admin.hotel.roomRate', compact('hotel', 'room'));
    }

    public function addRoomRate(Request $req){
        foreach ($req->ssingle as $key => $value) {
            $addRate = new RoomRate;
            $addRate->supplier_id = $req->hotelId;
            $addRate->room_id   = $req->roomId;
            $addRate->ssingle   = $req->ssingle[$key] ?? 0;
            $addRate->sdbl_price= $req->sdouble[$key] ?? 0;
            $addRate->stwin     = $req->stwin[$key] ?? 0;
            $addRate->sextra    = $req->sextra[$key] ?? 0;
            $addRate->schexbed  = $req->schextra[$key] ?? 0;
            $addRate->nsingle   = $req->nsingle[$key] ?? 0;
            $addRate->ntwin     = $req->ntwin[$key] ?? 0;
            $addRate->ndbl_price= $req->ndouble[$key] ?? 0;
            $addRate->nextra    = $req->nextra[$key] ?? 0;
            $addRate->user_id   = \Auth::user()->id;
            $addRate->nchexbed  = $req->nchextra[$key] ?? 0;
            $addRate->start_date= $req->fromdate[$key];
            $addRate->end_date  = $req->todate[$key];
            $addRate->save();
        }
        return redirect()->route('getRoomApplied');
    }

    public function getEdiRoomRate($hotelId, $roomId){
        $edithotelRate =RoomRate::where(['supplier_id'=>$hotelId, 'room_id' =>$roomId])->orderBy('start_date', 'ASC')->get();
        $hotel = Supplier::find($hotelId);
        $room = Room::find($roomId);
        return view('admin.hotel.roomRateEdit', compact('edithotelRate', 'hotel', 'room'));
    }

    public function updateRoomRate(Request $req){
        $addRate = RoomRate::find($req->eid);
        $addRate->supplier_id = $req->hotelId;
        $addRate->room_id   = $req->roomId;
        $addRate->ssingle   = $req->ssingle ?? 0;
        $addRate->stwin     = $req->stwin ?? 0;
        $addRate->sdbl_price= $req->sdouble ?? 0;
        $addRate->sextra    = $req->sextra ?? 0;
        $addRate->schexbed  = $req->schextra ?? 0;
        $addRate->nsingle   = $req->nsingle ?? 0;
        $addRate->ntwin     = $req->ntwin ?? 0;
        $addRate->ndbl_price= $req->ndouble ?? 0;
        $addRate->nextra    = $req->nextra ?? 0;
        $addRate->auth_id   = \Auth::user()->id;
        $addRate->nchexbed  = $req->nchextra ?? 0;
        $addRate->start_date= $req->from_date;
        $addRate->end_date  = $req->to_date;
        if ($addRate->save()) {
            return response()->json(["message"=>"Updated", "messagetype"=>'success', "status_icon"=> "fa-check-circle"]);
        }else{
            return response()->json(["message"=>"no update", "messagetype"=>'warning', "status_icon"=> "fa-exclamation-circle"]);
        }
    }
    // add row for hotel rate price 
    public function getRatePrice(Request $req){
        if($req->type == "hotelrate"){
            echo '<tr>
                    <td colspan="2">                   
                       <div class="input-group input-daterange">
                          <input type="text" name="fromdate[]" id="from_date_'.$req->i.'" class="form-control input-sm" required readonly placeholder="2018-04-25"><div class="input-group-addon">to</div>
                          <input type="text" name="todate[]" id="to_date_'.$req->i.'" class="form-control input-sm" required readonly placeholder="2018-06-25">
                        </div>
                    </td>';              
                    foreach(\App\RoomCategory::where('status',1)->orderBy('id', 'ASC')->get() as $cat) {
                       echo '<td><input type="text" class="number_only form-control input-sm text-center" name="'.$cat->key_name.'[]" placeholder="0.00"></td>';
                    }
                    echo '<td title="Make sure if you wish to remove this"><span class="btn btn-danger btn-xs RemoveHotelRate"><i class="fa fa-minus-circle"></i> Remove</span></td>
                </tr>';
            echo '<script type="text/javascript">
                    $(function(){
                        var nowTemp = new Date();
                        var formatdate = "yyyy-mm-dd";
                        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
                        var checkin = $("#from_date_'.$req->i.'").datepicker({
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
                          $("#to_date_'.$req->i.'")[0].focus();
                        }).data("datepicker");
                        var checkout = $("#to_date_'.$req->i.'").datepicker({
                            format: formatdate,
                            onRender: function(date) {
                                return date.valueOf() < checkin.date.valueOf() ? "disabled" : "";
                            }
                        }).on("changeDate", function(ev) {
                          checkout.hide();
                        }).data("datepicker");
                    });
                </script>';
        }else if ($req->type = 'tourrate') {
            echo '<tr>
                <td><input name="pax_no[]" value="'.($req->i + 1).'" placeholder="Pax '.($req->i + 1).'" type="text" class="form-control input-sm" required></td>
                <td><input name="sprice[]" placeholder="0.00 '.Content::currency().'" type="text" class="form-control input-sm number_only"></td>
                <td><input name="nprice[]" placeholder="0.00 '.Content::currency().'" type="text" class="form-control input-sm number_only"></td>
                <td title="Make sure if you wish to remove this">
                <span class="btn btn-danger btn-xs RemoveHotelRate"><i class="fa fa-minus-circle" disabled="disabled"></i> Remove</span></td>
              </tr>';
        }    
    }

    // apply room for hotel 
    public function getRoomApply(Request $req, $subhotelId){
        $hotelId = isset($req->hotel) ? $req->hotel : $subhotelId;
        $hotels = Supplier::where('business_id', 1)->orderBy('supplier_name', 'ASC')->get();
        $subhotel =  Supplier::find($subhotelId);
        $roomId ='';
        foreach ($subhotel->room as $key => $value){
            $roomId .= $value->pivot->room_id.',';    
        }        
        $roomApply = Room::where('room_status', 1)->orderBy("name", "ASC")->get();
        return view('admin.hotel.applyRoom', compact('roomApply', 'hotels', 'hotelId', 'roomId', 'subhotel'));
    }

    public function getRoomApplyNow(Request $req){
        $editsuplier = Supplier::find($req->hotel);
        $editsuplier->room()->sync($req->roomId, true);
        $editsuplier->save();    
        return back(); 
    }

    public function getHotelRoomRate(Request $req){
        $Enddate = date("Y-m-d", strtotime("+6 month"));
        $d =  Content::diffDate(date('Y-m-d'), $Enddate); 
        $nextMonth = date('Y-m-').$d;
        $currentDate = date('Y-m-d');
        $hotelrates = HotelRate::whereBetween('start_date', [$currentDate, $nextMonth])->orderBy("start_date", "ASC")->get();
        return view('admin.hotel.categoryApplyPrice', compact('hotelrates'));
    }

    public function serachHotelRate(Request $req){
        $htoelName = $req->textSearch;
        $startDate = $req->start_date;
        $endDate   = $req->end_date;
        if (!empty($htoelName)) {
            $supp = Supplier::Where('supplier_name', 'LIKE', $htoelName.'%')->first();
            if (!empty($supp)) {
                $hotelrates = \App\HotelRate::where('supplier_id', $supp->id)->orderBy("start_date", "ASC")->get();
            }else{
                $hotelrates = \App\HotelRate::where('supplier_id', 0)->get();
            }                      
        }else{
            $hotelrates = \App\HotelRate::whereBetween('start_date', [$startDate, $endDate])->orderBy("start_date", "ASC")->get();
        }      
        return view('admin.hotel.categoryApplyPrice', compact('hotelrates',  'startDate', 'endDate', 'htoelName'));    
    } 

    public function EditRoomType( Request $req){
        $aroom = Room::find($req->roomid);
        if ($aroom) {
            $aroom->name = $req->room_name;
            $aroom->room_status = $req->status;
            $aroom->save();
            $message = "Room update Successfully";
        }else{
            if (!Room::roomExit($req->room_name)) {
                $eroom = new Room;
                $eroom->name = $req->room_name;
                $eroom->room_status = $req->status;
                $eroom->save();
                $message = "Room added Successfully";
            }else{
                $message = "Room Name Already Exiting";
            }           
        }
        return back()->with(['message'=> $message,  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }    

    public function eddHotelFacility( Request $req){
        $edithf = HotelFacitily::find($req->eid);
        if ($edithf){
            $edithf->name = $req->name;
            $edithf->status = $req->status;
            $edithf->save();
            $message = "Hotel Facility Successfully Updated";
        }else{
            $adithf = new HotelFacitily;
            $adithf->name = $req->name;
            $adithf->status = $req->status;
            $adithf->save();
            $message = "Hotel Facility Successfully created";
        }
        return back()->with(['message'=> $message,  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }    

    public function addHotelinfo(Request $req){
        $edithf = HotelCategory::find($req->eid);
        if ($edithf){
            $edithf->name = $req->name;
            $edithf->status = $req->status;
            $edithf->save();
            $message = "Hotel Info Successfully Updated";
        }else{
            $adithf = new HotelCategory;
            $adithf->name = $req->name;
            $adithf->status = $req->status;
            $adithf->save();
            $message = "Hotel Info Successfully created";
        }
        return back()->with(['message'=> $message,  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    } 

    public function getEditHotelInfo( $supplierId){
        $supplier = Supplier::find($supplierId);
        $hotelCategory = '';
        $hotelFacility = '';
        if ($supplier->hotel_category) {
            foreach ($supplier->hotel_category as $key => $hc) {
                $hotelCategory .= $hc->pivot->hotel_category_id.',';
            }
        }
        if ($supplier->hotel_facility) {
            foreach ($supplier->hotel_facility as $key => $hf) {
                $hotelFacility .= $hf->pivot->hotel_facitily_id.',';
            }
        }
        return view('admin.hotel.editHotel_info', compact('supplier', 'hotelFacility', 'hotelCategory'));
    }
 
    public function updateHotelInfo(Request $req){
        $esub = Supplier::find($req->eid);
        $esub->supplier_term_condition = $req->term_condition;
        $esub->supplier_pgroup = $req->pgroup;
        $esub->supplier_pchild = $req->pchild;
        $esub->supplier_pcancelation= $req->pcancelation;
        $esub->supplier_ppayment = $req->ppayment;
        $esub->supplier_bank_info= $req->supplier_bank_info;
        $esub->save();
        $esub->hotel_facility()->sync($req->hotel_facility);
        $esub->hotel_category()->sync($req->hotel_category);
        return back()->with(['message'=> 'Hotel contract is/are successfully updated',  'status'=> 'success', 'status_icon'=> 'fa-cehck-circle']); 
    }

    public function AddHotelDiscount (Request $req) {
        // $totalAmount = strval($req->no_of_room * $req->current_amount);
        // $currentAmount = strval($req->no_of_room * (strval($req->book_day * $req->current_amount)));
        // if (isset($req->discount) && !empty($req->discount)) {
        //     $Netamount = strval(strval(strval($totalAmount / 100 ) * $req->discount) + $currentAmount );
        // }else{
        //     $Netamount = $currentAmount;
        // }
        // $ahotel = HotelBooked::find($req->eid);
        // $ahotel->discount = $req->discount;
        // $ahotel->net_amount = $Netamount;
        // $ahotel->save();
        // $messagetype = "warning";
        // $message = "Added successfully";
        // $status_icon = "fa-exclamation-circle";
        // return response()->json(["message"=> $message, "messagetype"=>$messagetype, "status_icon"=>$status_icon,]);



          // $totalAmount = strval($req->no_of_room * $req->current_amount);
        $currentAmount = strval($req->no_of_room * (strval($req->book_day * $req->current_amount)));
        $ahotel = HotelBooked::find($req->eid);
        $currentNet = ($ahotel->nsingle + $ahotel->ntwin + $ahotel->ndouble + $ahotel->nextra + $ahotel->nchextra);
        $currentSell = ($ahotel->ssingle + $ahotel->stwin + $ahotel->sdouble + $ahotel->sextra + $ahotel->schextra);
        if (isset($req->discount) && !empty($req->discount)) {
            // $Netamount = strval(strval(strval($totalAmount / 100 ) * $req->discount) + $currentAmount );
            
            $Netamount = strval(strval(strval($ahotel->net_amount / 100 ) * $req->discount) + $currentNet );
            $Selling_amount = strval(strval(strval($ahotel->sell_amount / 100 ) * $req->discount) + $currentSell );
        }else{
            $Netamount = $currentNet;
            $Selling_amount = $currentSell;
        }
        // $ahotel = HotelBooked::find($req->eid);
        $ahotel->discount = $req->discount;
        $ahotel->net_amount = $Netamount;
        $ahotel->sell_amount = $Selling_amount;
        $ahotel->save();
        $messagetype = "warning";
        $message = "Added successfully";
        $status_icon = "fa-exclamation-circle";
        return response()->json(["message"=> $message, "messagetype"=>$messagetype, "status_icon"=>$status_icon,]);
    }

}
