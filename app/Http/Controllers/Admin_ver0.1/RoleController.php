<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;
use App\DepartmentMenu;
use App\Role;

class RoleController extends Controller
{
    public function roleApply(Request $req){
    	$reqAll = $req->all();
    	$role = Role::find($req->role_id);
        $departmenu_id = [];
        if ( !empty($role->departmentmenu) ) {
            foreach ($role->departmentmenu as $key => $value) {
                $departmenu_id[] = $value->pivot->department_menu_id;
            }
        }
        
        $depart_id  = [];
        $roleDepart =  Role::find($req->role_id);
        if (!empty($roleDepart->department )) {
            foreach ($roleDepart->department as $key => $dep) {
                $depart_id[] = $dep->pivot->department_id;
            }
        }
    	return view("admin.user.role_apply", compact('roleApply', "role", "departmenu_id", "depart_id"));
    }

    public function menuApplied(Request $req){    	    
        try {
            $role = Role::find($req->rid);
            $role->department()->sync($req->depart, true);
            $role->departmentmenu()->sync($req->dep_menu, true);
            return redirect()->route('rolList')->with(['message'=> "menu permissions Applied",  'status'=>'success', 'status_icon'=>'fa-check-circle']);
        } catch (DecryptException $e) {
            $message = "Invalid URL...!";
            return view('errors.error', compact('message', 'action'));
        }
    }

}
