<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $table = 'business';

    public function tours(){         
    	return $this->belongsToMany(Tour::class,  'category_tours', 'category_id', 'tour_id');
    }

    public function supplier(){
    	return $this->hasMany(Supplier::class);
    } 

    public function journal(){
        return $this->hasMany(AccountJournal::class);
    }

    public function accountTransaction() {
        return $this->hasMany(AccountTransaction::class);
    }

    public static function PaymentBusiness(){
    	return \DB::table('account_journal')
            ->join('business', 'business.id','=','account_journal.business_id')
            ->groupBy('business_id')
            ->orderBy('business.name', 'ASC')
            ->get();
    }

    public static function getSupplierPermission($depart_id){

        return \DB::table('business')
            ->join('department_menu_role as role', 'role.department_menu_id', '=', 'business.id')
            ->join('tbl_department_menu as role_menu', 'role_menu.id', '=', 'role.department_menu_id')
            ->select("business.*")
            ->where(['role_menu.department_id'=> $depart_id, 'role.role_id'=> \Auth::user()->role_id])
            ->get();
    }
}
