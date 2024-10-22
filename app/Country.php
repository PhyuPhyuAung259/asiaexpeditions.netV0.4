<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    // 
    protected $table = 'country';

    public function province(){
    	return $this->hasMany(Province::class);
    }

    public function projectClient(){
        return $this->hasMany(ProjectClientName::class);
    }

    public function supplier () {
        return $this->hasMany(Supplier::class);
    }
    
    public function driver(){
        return $this->hasMany(Driver::class);
    }

    public function subscribe(){
        return $this->hasMany(Subscribe::class);
    }
    
    public function misc(){
        return $this->hasMany(MISCService::class);
    }

    public function journal(){
        return $this->hasMany(AccountJournal::class);
    }

    public function entrance(){
        return $this->hasMany(Entrance::class);
    }
    
    public function accountName () {
        return $this->hasMany(AccountName::class);
    }

    public function guideservice(){ 
        return $this->hasMany(GuideService::class);
    }

    public function project(){
        return $this->hasMany(Project::class);
    }

    public function tour(){
    	return $this->hasMany(Tour::class);
    }
    public function book(){
        return $this->hasMany(Booking::class);
    }

    public function schedule(){
    	return $this->hasMany(FlightSchedule::class);
    }

    public function transportservice(){ 
        return $this->hasMany(TransportService::class);
    }

    public static function LocalPayment(){
        return self::where('country_status',1)->whereHas('project')->orderBy('country_name')->get();
    }

    public static function getEntranCon(){
        return \DB::table('entrance_service')
            ->join('country', 'country.id','=','entrance_service.country_id')
            ->where('country.country_status', 1)
            ->groupBy('entrance_service.country_id')
            ->orderBy('country.country_name', 'ASC')
            ->get();
    }

    public static function getCountry($bus_type = 1){
        return \DB::table("country as con")
            ->join('suppliers', 'suppliers.country_id','=','con.id')
            ->where(['con.country_status'=>1, 'suppliers.business_id' => $bus_type ])
            ->select("con.*")
            ->groupBy('suppliers.country_id')
            ->orderBy('con.country_name', 'ASC')
            ->get();
    }

    public static function getCountryTour(){
        return \DB::table("country as con")
            ->join('tours', 'tours.country_id','=','con.id')
            ->where(['con.country_status'=> 1])
            ->select("con.*")
            ->groupBy('tours.country_id')
            ->orderBy('con.country_name', 'ASC')
            ->get();
    }


    public static function getRestCon(){
        return \DB::table('country')
            ->join('suppliers', 'suppliers.country_id','=','country.id')
            ->where(['country.country_status'=>1, 'suppliers.supplier_status' => 1, 'suppliers.business_id' => 2])
            ->select('country.*')
            ->groupBy('suppliers.country_id')
            ->orderBy('country.country_name', 'ASC')
            ->get();
    }

    public static function getConBySupplier(){
        return \DB::table('country')
            ->join('suppliers', 'suppliers.country_id','=','country.id')
            ->where(['country.country_status'=>1, 'suppliers.supplier_status' => 1])
            ->select('country.*')
            ->groupBy('suppliers.country_id')
            ->orderBy('country.country_name', 'ASC')
            ->get();
    }

    public static function getTranCon(){
        return \DB::table('transport_service')
            ->join('country', 'country.id','=','transport_service.country_id')
            ->where('country.country_status', 1)
            ->groupBy('transport_service.country_id')
            ->orderBy('country.country_name', 'ASC')
            ->get();
    }

    public static function getMIscCon(){
        return \DB::table('misc_service')
            ->join('country', 'country.id','=','misc_service.country_id')
            ->where('country.country_status', 1)
            ->groupBy('misc_service.country_id')
            ->orderBy('country.country_name', 'ASC')
            ->get();
    }

    public static function getGuideCon(){
        return \DB::table('guide_service')
            ->join('country', 'country.id','=','guide_service.country_id')
            ->where('country.country_status', 1)
            ->groupBy('guide_service.country_id')
            ->orderBy('country.country_name', 'ASC')
            ->get();
    }


    public static function conByGolfMenu(){
        return \DB::table('golfmenu')
            ->join('country', 'country.id','=','golfmenu.country_id')
            ->where('country.country_status', 1)
            ->groupBy('golfmenu.country_id')
            ->orderBy('country.country_name', 'ASC')
            ->get();
    }

    public static function countryBySupplier($bus_id = 0){
        return \DB::table('country')
            ->join('suppliers', 'suppliers.country_id','=','country.id')
            ->where(['country.country_status'=>1, 'suppliers.supplier_status'=>1, 'suppliers.business_id'=> $bus_id])
            ->select("country.*")
            ->groupBy('suppliers.country_id')
            ->orderBy('suppliers.supplier_name', 'ASC')
            ->get();
    }

    public static function countryByProject (){
        return $countries = \DB::table('country')
            ->join('project', 'project.country_id','=','country.id')
            ->where(['country.country_status'=>1, 'project.project_status' => 1])
            ->select("country.*")
            ->groupBy('country.id')
            ->orderBy('country.country_name', 'ASC')
            ->get();
    }
}

