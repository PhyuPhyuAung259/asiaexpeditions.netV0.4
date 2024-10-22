<?php

namespace App; 

use Illuminate\Database\Eloquent\Model;

class TransportService extends Model
{
 	protected $table = "transport_service";

 	public function country (){
 		return $this->belongsTo(Country::class);
 	}

 	public function province (){
 		return $this->belongsTo(Province::class);
 	}
 
 	public function tranmenu(){
 		return $this->belongsTo(TransportMenu::class);
 	}

 	public function supplier (){
 		return $this->belongsTo(Supplier::class, 'tour_id');
 	}

 	public function booktransport(){
    	return $this->hasMany(BookTransport::class);
    }

    public function supplier_transport (){
    	return $this->belongsToMany(Supplier::class, "supplier_transport_service");
    }

 //  	public static function getCountry(){
	//     return \DB::table($this->table as 'transport')
	//         ->join('country', 'country.id','=','transport.country_id')
	//         ->select('country.*')
	//         ->groupBy("country_id")
	//         ->orderBy('country.country_name', 'ASC')
	//         ->get();
 //  	}

	// public static function getProvice($country = 30 ){
 //        return \DB::table($this->table as 'transport')
 //            ->join('province', 'province.id','=','tbl_driver.province_id')
 //            ->select('province.*')
 //            ->where("province.country_id", $country)
 //            ->groupBy("province_id")
 //            ->orderBy('province.province_name', 'ASC')
 //            ->get();
	// }

}


