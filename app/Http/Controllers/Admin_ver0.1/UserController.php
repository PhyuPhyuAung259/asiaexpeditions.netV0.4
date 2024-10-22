<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;
use App\User;
use App\Role;
use Validator;
class UserController extends Controller
{
    public function userList(){
    	$users = User::whereNotIn('role_id', [7])->orderBy('fullname', 'ASC')->get();
    	return view('admin.user.userList', compact('users'));
    }

    public function rolList(){
        $roles = Role::where("status", 1)->orderBy('name', 'ASC')->get();
        return view('admin.user.role_list',  compact('roles'));
    }

    public function createRole(Request $req){
        if (isset($req->eid) && !empty($req->eid)) {
            $addRole = Role::find($req->eid);
            $message = '"'.$req->title.'" Successfully updated';
        }else{
            $addRole = new Role;    
            $message = '"'.$req->title.'" Successfully Added';
        }
        $addRole->name  = $req->title;
        $addRole->desc  = $req->desc;
        $addRole->save();
        return redirect()->route('rolList')->with(['message'=>$message, 'status'=> 'success', 'status_icon'=> 'fa-check-circle']);
    }

    public function userForm(){
    	return view('admin.user.user_form');
    }

    public function registerNew(Request $req){
    	$message = "email exiting in the system";
    	$validate = Validator::make($req->all(), [
	            'username' => 'required',
	            'fullname' => 'required',
	            'email'	   => 'required|email',
	            'password' => 'required|min:6',
	            'con-password' => 'required|min:6|same:password'
	        ]);
    	if (!User::Exitemail($req->email)) {
	       	if (!$validate->fails()) {
	       		$aUser = New User;
		    	$aUser->fullname = $req->fullname;
		    	$aUser->name 	 = str_slug($req->username, "_");
		    	$aUser->email    = $req->email;
		    	$aUser->phone    = $req->phone;
		    	$aUser->password = bcrypt($req->password);
    			$aUser->password_text = $req->password;
		    	$aUser->banned   =1;
		    	$aUser->save();
		    	$message = "User added successffully";
		    	return redirect()->route('userList');
	       	}else{
	       		return back()->withInput($validate);
	       	}
    	}
    	return back()->withErrors($validate)->withInput()->with("message", $message);
    }

    public function userStore($userid){
    	$user = User::find($userid);
        if (\Auth::user()->id == $user->id || \Auth::user()->role_id == 2) {
            return view('admin.user.user_formEdit', compact('user'));
        }else{
            return back()->with(['message'=> "Permission denied",  'status'=> 'warning', 'status_icon'=> 'fa-check-circle']);
        }
    }

    public function updateUser(Request $req){
        if ($req->hasFile("image")) {
            $image = $req->file('image');
            $filename = time().'-'.$image->getClientOriginalName();
            $img = Image::make($image->getRealPath())->fit(200, 210);
            $img->save(('storage/avata/thumbnail/'.$filename));
            $image->move(('storage/avata/'), $filename);     
        }else{
            $filename = $req->oldFile;
        }
    	$aUser = User::find($req->eid);
    	$aUser->fullname = $req->fullname;
    	$aUser->position = $req->position;
    	$aUser->postal   = $req->zipcode;
        $aUser->email = $req->email;
        $aUser->company_id = $req->company;
    	$aUser->phone    = $req->phone;
    	$aUser->country_id = $req->country;
    	$aUser->province_id  = $req->city;
        $aUser->picture  = $filename;
    	$aUser->address  = $req->address;
    	$aUser->descs    = $req->desc;
    	$aUser->web 	 = $req->web;    	
    	$aUser->save();
    	$message = 'User have been successfully updated';   
        return back()->with(['message'=>$message, 'status'=>'success', 'status_icon'=>'fa-check-circle']);
    }

    public function editpermission($userid){
    	$user = User::find($userid); 
    	return view("admin.user.user_permission", compact('user'));
    }

    public function changePermission(Request $req){
    	if (isset($req->password)) {
    		$validate = Validator::make($req->all(), [
	            'password' => 'required|min:6',
	            'con-password' => 'required|min:6|same:password'
	        ]);
	        if($validate->fails()){
				return back()->withErrors($validate)->withInput();
	        } 
    	}
    	$aUser = User::find($req->eid);
    	$aUser->fullname = $req->fullname;
    	$aUser->password = isset($req->password) ? bcrypt($req->password):bcrypt($req->old_password);
		$aUser->password_text = isset($req->password) ? $req->password: $req->old_password;
		$aUser->role_id  = $req->role;
    	$aUser->banned   = $req->banned;
    	$aUser->save();
    	$message = 'User have been successfully updated';
        return back()->with(['message'=> $message,  'status'=> 'success', 'status_icon'=> 'fa-check-circle']);
    }
}
