<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlightSchedule extends Model
{
    //
    protected $table= 'flights';

    public function weekday(){
    	return $this->belongsToMany(WeekDay::class);
    }

    public function supplier(){
    	return $this->belongsTo(Supplier::class);
    }

    public function project(){
        return $this->hasMany(Project::class);
    }

    public function country(){
    	return $this->belongsTo(Country::class);
    }

    public function book(){
        return $this->belongsTo(Booking::class);
    }

    public function province(){
    	return $this->belongsTo(Province::class);
    } 

    public function flightagent(){
        $price = ['oneway_price', 'return_price', 'oneway_nprice', 'return_nprice', 'oneway_kprice', 'return_kprice'];
        return $this->belongsToMany(Supplier::class)->withPivot($price);
    }

    public static function getFlightPrice($flightId){
        return \DB::table('flight_schedule_supplier as agent')
            ->join('suppliers', 'suppliers.id','=','agent.supplier_id')
            ->join('flights', 'flights.id', '=', 'agent.flight_schedule_id')
            ->select('suppliers.supplier_name', 'agent.*', 'flights.flightno', 'flights.dep_time', 'flights.arr_time', 'flights.flight_from', 'flights.flight_to', 'flights.supplier_id')
            ->where(['suppliers.id'=> $flightId])
            ->groupBy("agent.id")
            ->orderBy('flights.flightno', 'ASC')
            ->get(); 
    }
}
