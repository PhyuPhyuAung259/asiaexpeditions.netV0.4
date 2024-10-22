<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FlightSchedule;
use App\Supplier;
use App\FsPrice;
class FlightController extends Controller
{
    // 
    public function getFlightSchedule(Request $req){
    	$locationid = isset($req->location) ? $req->location: \Auth::user()->country_id;
    	$schedules = FlightSchedule::where('country_id', $locationid)->orderBY('flightno', 'ASC')->get();
    	return view('admin.flight.schedule', compact('schedules', 'locationid'));
    }

    public function createFlightSchedule(){
    	return view('admin.flight.scheduleForm');
    }

    public function createSchedule(Request $req){
    	$addflight = new FlightSchedule;
    	$addflight->flightno = $req->flightNo; 
    	$addflight->country_id = $req->country;
    	$addflight->province_id = $req->city;
        $addflight->supplier_id = $req->airline;
        $addflight->dep_time = $req->dep_time;     
        $addflight->arr_time = $req->arr_time;               
        $addflight->flight_from = $req->flight_from;               
        $addflight->flight_to = $req->flight_to;               
        $addflight->flight_note = $req->flight_intro;
        $addflight->flight_status = $req->status;
        $addflight->save();
        $addflight->weekday()->sync($req->weekday, false);
        $addflight->flightagent()->sync($req->flightAgent, false);
        return back()->with(['message'=> 'Flight schedule has been added successfully',  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }


    public function getEditSchedule($flightid){
        $flight = FlightSchedule::find($flightid);
        $dataid = '';
        $weekday = '';
        foreach ($flight->flightagent as $key => $value) {
            $dataid .= $value->pivot->supplier_id.',';
        }
        foreach ($flight->weekday as $key => $value) {
            $weekday .= $value->pivot->week_day_id.',';
        }
        return view('admin.flight.scheduleFormEdit', compact('flight', 'dataid', 'weekday'));
    }

    public function updateSchedule(Request $req){
        $addflight = FlightSchedule::find($req->flgihtId);
        $addflight->flightno = $req->flightNo;
        $addflight->country_id = $req->country;
        $addflight->province_id = $req->city;
        $addflight->supplier_id = $req->airline;
        $addflight->dep_time = $req->dep_time;     
        $addflight->arr_time = $req->arr_time;               
        $addflight->flight_from = $req->flight_from;               
        $addflight->flight_to = $req->flight_to;               
        $addflight->flight_note = $req->flight_intro;
        $addflight->flight_status = $req->status;
        $addflight->save();
        $addflight->weekday()->sync($req->weekday, true);
        $addflight->flightagent()->sync($req->flightAgent, true);
        return back()->with(['message'=> 'Flight schedule updated successfully', 'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 

    }

    public function getSchedulePrice( $flightId){
        $agent = Supplier::find($flightId);
        $schedulesNo = FlightSchedule::getFlightPrice($flightId);
        return view('admin.flight.schedulePrice', compact('schedulesNo', 'agent'));
    }


    public function upscheduleprice(Request $req){
        $addsprice = FsPrice::find($req->eid);
        $addsprice->oneway_price = $req->oneway_price;
        $addsprice->return_price = $req->return_price;
        $addsprice->oneway_nprice = $req->oneway_nprice;
        $addsprice->return_nprice = $req->return_nprice;
        $addsprice->oneway_kprice = $req->oneway_kprice;
        $addsprice->return_kprice = $req->return_kprice;
        $addsprice->save();      
        return response()->json(["message"=> "Flight Schedule successfully updated", "messagetype"=>'warning', "status_icon"=> "fa-exclamation-circle"]);
        
    }
}
