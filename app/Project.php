<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = "project";

    public function supplier()
    {
    	return $this->belongsTo(Supplier::class);
    }

    public function supplier_agent(){
        return $this->belongsTo(Supplier::class, 'supplier_agent');
    }

    public function user()
    {
        return $this->belongsTo(user::class);
    }

    public function acc_transaction(){
        return $this->hasMany(AccountTransaction::class);
    }

    public function usertag()
    {
    	return $this->belongsToMany(User::class);
    }

    public function service()
    {
    	return $this->belongsToMany(Service::class);
    }

    public function flightDep(){
        return $this->belongsTo(FlightSchedule::class, 'project_dep_time');
    }

    public function flightArr(){
        return $this->belongsTo(FlightSchedule::class, 'project_arr_time');
    }

    public static function projectExit($projectNumber){
        return self::select('project_number')->where('project_number', $projectNumber)->first();
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function hotelbooked () {
        return $this->hasMany(HotelBooked::class, 'project_number');
    }

    public function booking(){
        return $this->hasMany(Booking::class);
    } 
 
    public function paymentLink(){
        // return $this->Project(PaymentLink::class);
    }

    public function accountjournal () {
        return $this->hasMany(AccountJournal::class, "project_number");
    }
    
    public static function getProjectQuotation($currentDate, $nextMonth){
        return \DB::table('project')
            ->join('booking', 'booking.book_project','=','project.project_number')
            ->Join('project_user', 'project_user.project_id','=','project.id')
            ->Join('users', 'users.id','=','project.user_id')
            ->select("project.*", "project_user.id as id", "project_user.*", "project.user_id as UserID", "project.id as project_id")
            ->whereBetween('project.project_start', [$currentDate, $nextMonth])
            ->where(["project.project_status"=>1, "booking.book_status"=>1,"booking.book_option"=>1])
            ->groupBy("project.id")
            ->orderBy('project.project_number', 'ASC');
    } 

       public static function getProjectTags ($currentDate, $nextMonth, $active = 1, $option = 0 ){
     
        if (\Auth::user()->role_id == 2 || \Auth::user()->role_id == 4 ) {
            $projects = \DB::table('project')
                ->join('project_user', 'project_user.project_id','=','project.id')
                ->join('users', 'users.id','=','project.user_id')
                ->select("project.*", "project_user.id as id", "project_user.*", "project.user_id as UserID", "project.id as project_id")
                ->orWhereBetween('project.project_end', [$currentDate, $nextMonth])
                ->where("project.active",$active)
                ->whereNotIn("project.project_status",[0])
                ->groupBy("project.id")
                ->orderBy('project.project_start', 'ASC');
          
    
        }else{
            $projects = \DB::table('project')
                ->join('project_user', 'project_user.project_id','=','project.id')
                ->join('users', 'users.id','=','project.user_id')
                ->select("project.*", "project_user.id as id", "project_user.*", "project.user_id as UserID", "project.id as project_id")
                ->orWhereBetween('project.project_end', [$currentDate, $nextMonth])
                ->where("project.active",$active)
                ->whereNotIn("project.project_status",[0])
                ->groupBy("project.id")
                ->orderBy('project.project_start', 'ASC');
        }
        return $projects;             
    }


    public static function getProjectSearch ($projectNum , $active =1, $option = 0){
        // if (\Auth::user()->role_id == 2 ) {
            $projects = \DB::table('project')
                ->Join('project_user', 'project_user.project_id','=','project.id')
                ->Join('users', 'users.id','=','project.user_id')
                ->select("project.*", "project_user.id as id", "project_user.*", "project.user_id as UserID", "project.id as project_id")
                ->where(['project.project_number'=>$projectNum])
                ->orWhere('project.project_fileno', $projectNum)
                ->whereNotIn('project.project_status',[0])
                ->orwhere('project.project_client', 'like', $projectNum. '%')
                ->groupBy("project.id")
                ->orderBy('project.project_number', 'DESC');
      
        // }
        return $projects;
    }
    //for disable project
    
    public static function getProjectSearchforDisable ($currentDate, $nextMonth, $active = 1, $option = 0 ){
     
          
            $projects = \DB::table('project')
            ->join('project_user', 'project_user.project_id','=','project.id')
            ->join('users', 'users.id','=','project.user_id')
            ->select("project.*", "project_user.id as id", "project_user.*", "project.user_id as UserID", "project.id as project_id")
            ->orWhereBetween('project.project_end', [$currentDate, $nextMonth])
            ->where(["project.project_status"=>0])
            ->groupBy("project.id")
            ->orderBy('project.project_start', 'ASC');
     
        return $projects;
    }

    // for acccounting 
    public static function AccountProjectTags ($currentDate, $nextMonth, $active=1 , $option = 0){
        // if (\Auth::user()->role_id == 2 || \Auth::user()->role_id == 4) {
        //     $projects = \DB::table('project')
        //         ->leftJoin('project_user', 'project_user.project_id','=','project.id')
        //         ->join('users', 'users.id','=','project.user_id')
        //         ->select("project.*", "project_user.id as id", "project_user.*", "project.user_id as UserID", "project.id as project_id")
        //         ->whereBetween('project.project_start', [$currentDate, $nextMonth])
        //         ->where(["project.project_status"=>1, "project.active"=>$active])
        //         ->whereNotNull("project.project_fileno")
        //         ->groupBy("project.id")
        //         ->orderBy('project.project_start', 'ASC');
        // }else{
        //     $projects = \DB::table('project')
        //         ->leftJoin('project_user', 'project_user.project_id','=','project.id')
        //         ->join('users', 'users.id','=','project.user_id')
        //         ->select("project.*", "project_user.id as id", "project_user.*", "project.user_id as UserID", "project.id as project_id")
        //         ->whereBetween('project.project_start', [$currentDate, $nextMonth])
        //         ->where(["project.project_status"=>1, "project_user.user_id"=>\Auth::user()->id, "project.active"=>$active])
        //         ->whereNotIn("project.project_fileno", ["", 0, "Null" ])
        //         ->groupBy("project.id")
        //         ->orderBy('project.project_start', 'ASC');
        // }
        $projects = \DB::table('project')
        ->leftJoin('project_user', 'project_user.project_id','=','project.id')
        ->join('users', 'users.id','=','project.user_id')
        ->select("project.*", "project_user.id as id", "project_user.*", "project.user_id as UserID", "project.id as project_id")
        ->whereBetween('project.project_start', [$currentDate, $nextMonth])
        ->where(["project.project_status"=>1, "project.active"=>$active])
        ->whereNotNull("project.project_fileno")
        ->groupBy("project.id")
        ->orderBy('project.project_start', 'ASC');
        return $projects;             
    }

    public static function AccountProjectSearch ($projectNum, $active =1 ){
        if (\Auth::user()->role_id == 2 || \Auth::user()->role_id == 4) {
            $projects = \DB::table('project')
                ->leftJoin('project_user', 'project_user.project_id','=','project.id')
                ->join('users', 'users.id','=','project.user_id')
                ->where(['project.project_number'=>$projectNum, 'project.project_status'=>1, "project.active"=>$active])
                ->whereNotNull("project.project_fileno")
                ->select("project.*", "project_user.id as id", "project_user.*", "project.user_id as UserID", "project.id as project_id")
                ->orWhere('project.project_fileno', $projectNum)
                ->orwhere('project.project_client', 'like', $projectNum. '%')
                ->groupBy("project.id")
                ->orderBy('project.project_start', 'DESC');
        }else{
            $projects = \DB::table('project')
                ->leftJoin('project_user', 'project_user.project_id','=','project.id')
                ->join('users', 'users.id','=','project.user_id')
                ->select("project.*", "project_user.id as id", "project_user.*", "project.user_id as UserID", "project.id as project_id")
                ->whereNotNull("project.project_fileno")
                ->where(['project.project_number'=>$projectNum, 'project.project_status'=>1, "project_user.user_id"=>\Auth::user()->id, "project.active"=>$active])
                ->orWhere('project.project_fileno', $projectNum)
                ->orwhere('project.project_client', 'like', $projectNum. '%')
                ->groupBy("project.id")
                ->orderBy('project.project_start', 'DESC');
        }
        return $projects;
    }

    // end accounting 
    public static function ProjectByAccount($active =1){
        return \DB::table('account_journal')
            ->join('project', 'project.project_number','=','account_journal.project_number')
            ->where(["project.active"=>$active, "account_journal.status"=>1])
            ->groupBy('project.project_number')
            ->select('account_journal.id','account_journal.*', 'account_journal.credit', 'account_journal.debit', 'account_journal.kdebit', 'account_journal.kcredit', 'account_journal.book_amount', 'account_journal.book_kamount')
            ->orderBy('project.project_number', 'ASC')
            ->get();
    }
}
