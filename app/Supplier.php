<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    //
    protected $table = 'suppliers';

    public function acc_transaction(){
        return $this->hasMany(AccountTransaction::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function entran(){ 
        return $this->belongsToMany(BookEntrance::class, "group_supplier_service", "supplier_id", "service_id");
    }

    public function transport_service (){
        return $this->belongsToMany(TransportService::class, "supplier_transport_service");
    }

    public function project(){
    	return $this->hasMany(Project::class);
    }

     public function acc_name()
    {
        return $this->hasMany(Supplier::class);
    } 

    public function driver(){
        return $this->haMany(Driver::class);
    }

    public function restaurant(){
        return $this->hasMany(BookRestaurant::class);
    }

    public function guide(){
        return $this->hasMany(BookGuide::class);
    }

    public function transportservice (){
        return $this->hasMany(TransportService::class); 
    }

    public function account_journal(){
        return $this->hasMany(AccountJournal::class);
    }
 
    public function business(){
    	return $this->belongsTo(Business::class);
    }

    public function province(){
    	return $this->belongsTo(Province::class);
    }
    public function book(){
        return $this->hasMany(Booking::class);
    }
    public function hotelbooked(){
        return $this->hasMany(HotelBooked::class);
    }

    public function cruisebooked(){
        return $this->hasMany(CruiseBooked::class);
    }

    public function country(){
    	return $this->belongsTo(Country::class);
    }

    public function schedule(){
        return $this->haMany(FlightSchedule::class);
    }

    public function room (){
        // $hotelRate = ['ssingle', 'stwin', 'sdbl_price', 'sextra', 'schexbed', 'nsingle','ntwin','ndbl_price','nextra','nchexbed'];
        return $this->belongsToMany(Room::class);//->withPivot($hotelRate);
    } 

    public function hotel_category (){
        return $this->belongsToMany(HotelCategory::class);//->withPivot($hotelRate);
    }

    public function hotel_facility (){
        return $this->belongsToMany(HotelFacitily::class);//->withPivot($hotelRate);
    }

    public function tour(){
        return $this->belongsToMany(Tour::class,  'tour_suppliers', 'tour_id', 'supplier_id')->wherePivot('tour_id', 1);
    }

    public function crprogram(){
        return $this->hasMany(CrProgram::class);
    }

    // for flight agency
    public function flighschedule(){
        $price = ['oneway_price', 'return_price', 'oneway_nprice', 'return_nprice', 'oneway_kprice', 'return_kprice'];
        return $this->belongsToMany(FlightSchedule::class)->withPivot($price);
        // return $this->belongsToMany(FlightSchedule::class, 'flight_schedule_supplier','supplier_id', 'flight_schedule_id');
    }

    public function guideLanguage(){
        return $this->belongsToMany(GuideLanguage::class);
    }

    public function res_menu () {
        return $this->hasMany(RestaurantMenu::class);
    }

    public function guid_service ( ){
        return $this->hasMany(Supplier::class);
    }

    public function transport(){
        return $this->hasMany(BookTransport::class);
    } 

    public function golf_menu(){
        return $this->hasMany(GolfMenu::class);
    }

    public static function getSupplier( $bus_id = 9){
        return \DB::table("project as pro")
            ->join('suppliers', 'suppliers.id','=','pro.supplier_id')
            ->where(["pro.active"=>1, 'suppliers.supplier_status'=> 1, 'pro.project_status' => 1, 'suppliers.business_id'=> $bus_id])
            ->groupBy('pro.supplier_id')
            ->orderBy('suppliers.supplier_name', 'ASC');
    }

    public static function restMenu( $con_id = 0){
        return \DB::table("restaurant_menu as menu")
            ->join('suppliers', 'suppliers.id','=','menu.supplier_id')
            ->where(['suppliers.supplier_status'=>1, 'suppliers.supplier_status'=>1, 'suppliers.business_id'=>2, 'suppliers.province_id'=> $con_id])
            ->groupBy('menu.supplier_id')
            ->orderBy('suppliers.supplier_name', 'ASC')->get();
    }

    public static function SupByAccount($bus_id = 0){
        return \DB::table('account_journal as journal')
            ->join('suppliers as sup', 'sup.id','=','journal.supplier_id')
            ->select("sup.*")
            ->groupBy('supplier_id')
            // ->where(["journal.business_id"=>$bus_id])
            ->orderBy('sup.supplier_name', 'ASC')
            ->get();
    }

    public static function getSupplierBooked($bus_id= 0, $con_id = 0){
        $book_type = 0;
        if ($bus_id == 1 ) {
            $book_type = "hotel_id";
        }elseif ($bus_id == 3) {
            $book_type = "cruise_id";
        }elseif ($bus_id == 9) {
            // $book_type = "book_agent";
            $book_type = "supplier_id";
        }elseif ($bus_id == 4) {
            $book_type = "flight_id";
        }elseif ($bus_id == 37) {
            $book_type = "book_agent";
        }elseif ($bus_id == 29) {
            $book_type = "golf_id";
        }

        $bookSupplier = \DB::table('booking as book')
            ->join('suppliers', 'suppliers.id','=',"book.$book_type")
            ->join("project", "project.project_number", "=", "book.book_project")
            ->where(["project.active"=>1, 'book.book_status'=>1, 'suppliers.supplier_status'=>1, 'suppliers.business_id'=>$bus_id, 'book.country_id'=> $con_id]) 
            ->whereIn("book.book_fileno", ['', 'null', 0])
            ->select('book.id as id', 'suppliers.*', 'suppliers.id as supplier_id');            
        if ($bus_id == 1 ) {
            $bookSupplier->groupBy('book.hotel_id');
        }elseif ($bus_id == 3) {
            // $book_type = "cruise_id";
            $bookSupplier->groupBy('book.cruise_id');
        }elseif ($bus_id == 4) {
            $bookSupplier->groupBy('book.flight_id');
            // $book_type = "flight_id";
        }elseif ($bus_id == 37) {
            $bookSupplier->groupBy('book.book_agent');
        }elseif ($bus_id == 29) {
            $bookSupplier->groupBy('book.golf_id');
            // $book_type = "golf_id";
        }else{
            $bookSupplier->groupBy(['suppliers.id']);
        }
        $bookSupplier->orderBy('suppliers.supplier_name', 'ASC');
        
        // bus_id = 51 office supply , 5 = banking & finance ,  6 = tour guide, 2 = restaurant;
        if ($bus_id == 51 || $bus_id == 5 ) {
            $bookSupplier = self::where(['supplier_status'=>1, 'business_id'=> $bus_id, 'suppliers.country_id' => $con_id ])->orderBy('supplier_name');


        }elseif ($bus_id == 2) {
            $bookSupplier = \DB::table('restaurant_book as rest')
                ->join('suppliers', 'suppliers.id','=', "rest.supplier_id")
                ->join("project", "project.project_number", "=", "rest.project_number")
                ->where(["project.active"=>1,'rest.status'=>1, 'suppliers.supplier_status'=>1, 'suppliers.business_id'=> $bus_id, 'suppliers.country_id' => $con_id ]) 
                // ->whereIn("book.book_fileno", ['', 'null', 0])
                ->select('rest.id as rest_id', 'suppliers.*', 'suppliers.id as supplier_id')
                ->groupBy('rest.supplier_id')
                ->orderBy('suppliers.supplier_name', 'ASC');
        }else if ($bus_id == 6) {
            $bookSupplier = \DB::table('guide_book as guide')
                ->join('suppliers', 'suppliers.id','=', "guide.supplier_id")
                ->join("project", "project.project_number", "=", "guide.project_number")
                ->where(["project.active"=>1,'guide.status'=>1, 'suppliers.supplier_status'=>1, 'suppliers.business_id'=> $bus_id, 'suppliers.country_id' => $con_id ]) 
                // ->whereIn("book.book_fileno", ['', 'null', 0])
                ->select('guide.id as guide_id', 'suppliers.*', 'suppliers.id as supplier_id')
                ->groupBy('guide.supplier_id')
                ->orderBy('suppliers.supplier_name', 'ASC');
        }else if ($bus_id ==  7) {

            $bookSupplier = \DB::table('transport_book as transport')
                ->join('suppliers', 'suppliers.id','=', "transport.transport_id")
                ->join("project", "project.project_number", "=", "transport.project_number")
                ->where(["project.active"=>1, 'transport.status'=>1, 'suppliers.supplier_status'=>1, 'suppliers.business_id'=> $bus_id, 'suppliers.country_id' => $con_id ]) 
                // ->whereIn("book.book_fileno", ['', 'null', 0])
                ->select('transport.id as transport_id', 'suppliers.*', 'suppliers.id as supplier_id')
                ->groupBy('transport.transport_id')
                ->orderBy('suppliers.supplier_name', 'ASC');
        }
        return $bookSupplier;
    }

}
