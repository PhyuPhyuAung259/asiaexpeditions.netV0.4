<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'province';

    public function country(){
    	return $this->belongsTo(Country::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tour(){
    	return $this->hasMany(Tour::class);
    }

    public function guideservice(){
        return $this->hasMany(GuideService::class);
    }

    public function misc(){
        return $this->hasMany(MISCService::class);
    }

    public function supplier(){
        return $this->hasMany(Supplier::class);
    }


    public function entrance(){
        return $this->hasMany(Entrance::class);
    }

    public function bookguide(){
        return $this->hasMany(BookGuide::class);
    }

    public function book(){
        return $this->hasMany(Booking::class);
    }

    public function driver(){
        return $this->hasMany(Driver::class);
    }

   	public function schedule(){
    	return $this->hasMany(FlightSchedule::class);
    }

    public function crprogram(){
        return $this->hasMany(CrProgram::class); 
    }

    public function customer(){
        return $this->hasMany(Customers::class);
    }
 
    public function transervice(){
        return $this->hasMany(TransportService::class)->orderBy('province_name');
    }

    public static function CrProvince($locationId){
        return \DB::table('province')
            ->crossJoin('rc_program', 'province.id', '=', 'rc_program.province_id')
            ->select( 'province.*')
            ->groupBy('province.id')
            ->where(['province.country_id'=> $locationId, 'province.province_status'=>1]) 
            ->orderBy('province_name', 'ASC')
            ->get();
    }

    public static function getProvinceTour($con_id = 0){
        return \DB::table("province as pro")
            ->join('tours', 'tours.province_id','=','pro.id')
            ->where(['pro.province_status'=>1,'pro.country_id'=>$con_id])
            ->whereNotNull("pro.province_name")
            ->select("pro.*")
            ->groupBy('tours.province_id')
            ->orderBy('pro.province_name', 'ASC')
            ->get();
    }

    public static function getProvinceSub( $con_id = 0, $bus_type = 1){
        return \DB::table("province as pro")
            ->join('suppliers', 'suppliers.province_id','=','pro.id')
            ->where(['pro.province_status'=> 1, 'suppliers.business_id' => $bus_type, 'pro.country_id' => $con_id ])
            ->select("pro.*")
            ->groupBy('suppliers.province_id')
            ->orderBy('pro.province_name', 'ASC')
            ->get();
    }

    public static function getProvince( $con_id = 0){
        return \DB::table("province as pro")
            ->join('suppliers', 'suppliers.province_id','=','pro.id')
            ->where(['pro.province_status'=> 1, 'pro.country_id' => $con_id ])
            ->select("pro.*")
            ->groupBy('suppliers.province_id')
            ->orderBy('pro.province_name', 'ASC')
            ->get();
    }

    public static function getEntranPro( $counId = 0){
        return \DB::table('entrance_service')
            ->join('province', 'province.id','=','entrance_service.province_id')
            ->where(['province.province_status'=>1, 'entrance_service.status'=>1, "province.country_id"=>$counId])
            ->select("province.*")
            ->groupBy('entrance_service.province_id')
            ->orderBy('province.province_name', 'ASC')
            ->get();
    }

    public static function getRestPro( $counId = 0){
        return \DB::table('restaurant_menu')
            ->join('province', 'province.id','=','restaurant_menu.province_id')
            ->where(['province.province_status'=>1, "province.country_id"=> $counId])
            ->groupBy('restaurant_menu.province_id')
            ->orderBy('province.province_name', 'ASC')
            ->get();
    }

    public static function getMiscPro( $counId = 0){
        return \DB::table('misc_service')
            ->join('province', 'province.id','=','misc_service.province_id')
            ->where(['province.province_status'=>1, "province.country_id"=> $counId])
            ->groupBy('misc_service.province_id')
            ->orderBy('province.province_name', 'ASC')
            ->get();
    }
}
