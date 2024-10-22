<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartmentMenu extends Model
{
    //
    protected $table = 'tbl_department_menu';

   	public function role()
    {
    	return $this->belongsToMany(Role::class, 'department_menu_role', 'role_id', 'department_menu_id');
    }

    public static function getDepartment_menu($depart_id){
        return \DB::table('tbl_department_menu as dep_menu')
            ->join('department_menu_role as role', 'role.department_menu_id', '=', 'dep_menu.id')
            ->select("dep_menu.*")
            ->where(['dep_menu.department_id'=> $depart_id, 'role.role_id'=> \Auth::user()->role_id, 'status'=> 1])
            ->orderBy("dep_menu.name", "ASC")
            ->get();
    }
}
