<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = "booking";

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }
    
    public function province(){
        return $this->belongsTo(Province::class);
    }
    
    public function bookmisc(){
        return $this->hasMany(BookMisc::class, 'book_id');
    }

    public function bookrest(){
        return $this->hasMany(BookRestaurant::class);
    }

    public function hotelbooked (){
        return $this->hasMany(HotelBooked::class, 'book_id');
    }

    public function cruisebooked (){ 
        return $this->hasMany(CruiseBooked::class);
    }

    public function transportbooked (){
        return $this->hasMany(BookTransport::class);
    }

    public function guidebooked (){
        return $this->hasMany(BookGuide::class, 'book_id');
    }

    public function tour(){
        return $this->belongsTo(Tour::class);
    }

    public function hotel(){
        return $this->belongsTo(Supplier::class, 'hotel_id');
    }

    public function flight(){
        return $this->belongsTo(FlightSchedule::class, "flight_id");
    }

    public function cruise(){
        return $this->belongsTo(Supplier::class, 'cruise_id');
    }

    public function cprogram(){
        return $this->belongsTo(CrProgram::class, 'program_id');
    }

    public function golf(){
        return $this->belongsTo(Supplier::class, 'golf_id');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function fagent(){
        return $this->belongsTo(Supplier::class, 'book_agent');
    }

    public function golf_service(){
        return $this->belongsTo(GolfMenu::class, 'program_id');
    }

    public static function tourMaxPrice ($projectNo, $tourPax = 0){
        if ($tourPax) {
            $DataPax = \DB::table('tours')
                ->join('booking', 'booking.tour_id','=','tours.id')
                ->join('tour_price', 'tour_price.tour_id','=','tours.id')
                ->select("tour_price.pax_no")
                ->where(['tours.tour_status'=>1, 'booking.book_project'=>$projectNo, 'booking.book_status'=>1])
                ->whereIn('tour_price.pax_no', $tourPax)->skip(0)->take(15)
                ->groupBy('tour_price.pax_no')->get();
        }else{
            $DataPax = \DB::table('tours')
                ->join('booking', 'booking.tour_id','=','tours.id')
                ->join('tour_price', 'tour_price.tour_id','=','tours.id')
                ->select("tour_price.pax_no")
                ->where(['tours.tour_status'=>1, 'booking.book_project'=>$projectNo, 'booking.book_status'=>1])
                ->whereNotIn('tour_price.sprice', ["", 0, "null"])->skip(0)->take(15)
                ->groupBy('tour_price.pax_no')->get();
        }
        $pax = '';
        foreach ($DataPax as $key => $value) {
            $pax[] = $value->pax_no;
        }
        return $pax;
    }


    public static function bookingTour($currentDate, $nextMonth, $option=0){
        return \DB::table('booking as book')
            ->join('tours', 'tours.id','=','book.tour_id')
            ->join('project', 'project.project_number', '=', 'book.book_project')
            ->where(['book.book_status'=>1, 'project.active'=>1, 'book.book_option'=> $option])
            ->whereBetween('book.book_checkin', [$currentDate, $nextMonth])
            ->whereNotIn('project.project_fileno', ["", "Null",0])
            ->select('book.id as book_id', 'book.*', 'tours.tour_name', "project.*")
            ->orderBy('book.book_checkin', 'ASC');
    }

    public static function tourBook($project, $option= 0){
        return \DB::table('booking as book')
            ->join('tours', 'tours.id','=','book.tour_id')
            ->where(['book.book_project'=> $project, 'book.book_status'=>1, 'book.book_option'=>$option])
            // ->whereHas('pricetour', function($query) {$query->whereNotIn('sprice', ['',0, 'Null'])})
          //  ->whereNotIn('book.book_pax', ["", "Null",0])
            ->select('book.id as id', 'book.*', 'tours.tour_name')

            ->orderBy('book.book_checkin', 'ASC');
    }
    public static function tourDetailsBook($project, $option= 0){
        return \DB::table('booking as book')
            ->join('tours', 'tours.id','=','book.tour_id')
            ->where(['book.book_project'=> $project, 'book.book_status'=>1 ]) //remove , 'book.book_option'=>$option
            // ->whereHas('pricetour', function($query) {$query->whereNotIn('sprice', ['',0, 'Null'])})
            ->whereNotIn('book.book_pax', ["", "Null",0])
            ->select('book.id as id', 'book.*', 'tours.tour_name')

            ->orderBy('book.book_checkin', 'ASC');
    }

    public static function flightBook($project, $option= 0){
        return \DB::table('booking as book')
            ->join('flights', 'flights.id','=','book.flight_id')
            ->join("project", "project.project_number","=", "book.book_project")
            ->where(['book.book_project'=>$project, 'book.book_status'=>1, "project.active"=>1]) //remove , 'book.book_option'=>$option
            ->select('book.id as id', 'book.*', 'flights.flightno', 'flights.flight_from', 'flights.flight_to', 'flights.dep_time', 'flights.arr_time')
            ->orderBy('book.book_checkin', 'ASC');
    }

    public static function golfBook($project, $option = 0){
        return \DB::table('booking as book')
            ->join('suppliers', 'suppliers.id','=','book.golf_id')
            ->join("project", "project.project_number","=", "book.book_project")
            ->where(['book.book_project'=> $project, 'book.book_status'=>1 ]) //remove ,'book.book_option'=>$option
            ->select('book.id as id', 'book.*', 'suppliers.id as supplier_id', 'suppliers.supplier_name')
            ->orderBy('book.book_checkin', 'ASC');
    }

    public static function countryByBooking(){
        return \DB::table('guide_service as gservice')
            ->join('country', 'country.id','=','gservice.country_id')
            ->where(['country.country_status'=>1])
            ->select('gservice.*', 'country.*')
            ->groupBy("gservice.country_id")
            ->orderBy('country.country_name', 'ASC')->get();
    }




    public static function getBookedProjectByProjectNum ( $projectNum){
        if (\Auth::user()->role_id == 2 || \Auth::user()->role_id == 4) {
            $projects = \DB::table('project')
                ->LeftJoin('booking', 'booking.book_project','=','project.project_number')
                ->join('project_user', 'project_user.project_id','=','project.id' )
                ->join('users', 'users.id','=','project.user_id')
                ->select("project.*", "users.*", "booking.*", "booking.user_id as book_userId", "booking.id as book_id", "project_user.*", "project.user_id as UserID")
                ->where(['booking.book_project'=>$projectNum, 'booking.book_status'=>1, "project.active"=>1])
                ->groupBy("booking.id")
                ->orderBy('booking.book_checkin', 'ASC');
        }else{
            $projects = \DB::table('project')
                ->join('booking', 'booking.book_project','=','project.project_number')
                ->join('project_user', 'project_user.project_id','=','project.id' )
                ->join('users', 'users.id','=','project.user_id')
                ->select("project.*","users.*", "booking.*", "booking.user_id as book_userId", "booking.id as book_id", "project_user.*", "project.user_id as UserID")
                ->where(["project.active"=>1, 'booking.book_project'=>$projectNum, 'booking.book_status'=> 1, 'project_user.user_id' =>\Auth::user()->id])
                ->groupBy("booking.id")
                ->orderBy('booking.book_checkin', 'ASC');
        }
        return $projects;            
    }

    public static function getBookedProjectByDate ( $checkIn, $checkOut, $option = 0 ){
        if (\Auth::user()->role_id == 2 || \Auth::user()->role_id == 4) {
            $projects = \DB::table('project')
                ->LeftJoin('booking', 'booking.book_project','=','project.project_number')
                ->join('project_user', 'project_user.project_id','=','project.id' )
                ->join('users', 'users.id','=','project.user_id')
                ->select("project.*",  "users.*", "booking.*", "booking.id as book_id", "booking.user_id as book_userId", "project_user.*", "project.user_id as UserID")
                ->where([ 'booking.book_status'=>1, "project.active"=>1, 'booking.book_option'=> $option])
                ->whereBetween('booking.book_checkin', [$checkIn, $checkOut])
                ->groupBy("booking.id")
                ->orderBy('booking.book_checkin', 'ASC');
        }else{
           $projects = \DB::table('project')
                ->LeftJoin('booking', 'booking.book_project','=','project.project_number')
                ->join('project_user', 'project_user.project_id','=','project.id' )
                ->join('users', 'users.id','=','project.user_id')
                ->select("project.*","users.*", "booking.*", "booking.id as book_id", "booking.user_id as book_userId", "project_user.*", "project.user_id as UserID")
                ->where(["project.active"=>1,'booking.book_status'=> 1, 'project_user.user_id' => \Auth::user()->id, 'booking.book_option'=> $option])
                ->whereBetween('booking.book_checkin', [$checkIn, $checkOut])
                ->groupBy("booking.id")
                ->orderBy('booking.book_checkin', 'ASC');
        }
        return $projects;            
    }

    public static function getBookProjectNoAndDate($projectNum, $checkIn, $checkOut, $option = 0){
         if (\Auth::user()->role_id == 2 || \Auth::user()->role_id == 4) {
            $projects = \DB::table('project')
                ->join('project_user', 'project_user.project_id','=','project.id' )
                ->join('booking', 'booking.book_project','=','project.project_number')
                ->select("project.*", "booking.*", "booking.id as book_id", "booking.user_id as book_userId", "project_user.*", "project.user_id as UserID")
                ->where(["project.active"=>1,'booking.book_status'=> 1, 'project.project_number'=> $projectNum, 'booking.book_option'=>$option])
                ->whereBetween('booking.book_checkin', [$checkIn, $checkOut])
                ->groupBy("booking.id")
                ->orderBy('booking.book_checkin', 'ASC');
        }else{
           $projects = \DB::table('project')
                ->join('project_user', 'project_user.project_id','=','project.id' )
                ->join('booking', 'booking.book_project','=','project.project_number')
                ->select("project.*", "booking.*", "booking.id as book_id", "booking.user_id as book_userId", "project_user.*", "project.user_id as UserID")
                ->where([ "project.active"=>1, 'booking.book_status'=> 1, 'project_user.user_id' => \Auth::user()->id, 'project.project_number'=> $projectNum, 'booking.book_option'=> $option])
                ->whereBetween('booking.book_checkin', [$checkIn, $checkOut])
                ->groupBy("booking.id")
                ->orderBy('booking.book_checkin', 'ASC');
        }
        return $projects; 
    }
}
