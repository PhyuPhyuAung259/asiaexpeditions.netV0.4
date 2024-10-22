<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
Use App\Business;
use App\DocsModel;
use App\DepartmentMenu;
use App\Department;
class DocsController extends Controller
{
    //
    public function getDocs(){
    	return view("docs.index");
    }

    public function getDocsList(){
    	$docs = DocsModel::where("status", 1)->orderBy('title', "ASC")->get();
    	return view("docs.docs_list", compact('docs'));
    }

    public function getDocsDetail ( Request $req){
    	$sub_dep = DepartmentMenu::where('slug', $req->view)->first();
    	$depart = Department::find($sub_dep->department_id);
    	$docs = DocsModel::where("category_id", $sub_dep->id)->get();
    	return view("docs.single_view", compact('docs', 'depart', 'sub_dep'));
    }

    public function createDocs(Request $req){
    	$edoc = DocsModel::find($req->eId);
    	return view("docs.create_form", compact('edoc'));
    }

    public function createNewDocs(Request $req){
    	if (isset($req->eid) && !empty($req->eid)) {
    		$addDoc = DocsModel::find($req->eid);
	    	$addDoc->title = $req->title;
	    	$addDoc->user_id = \Auth::user()->id;
	    	$addDoc->auth_id = \Auth::user()->id;
	    	$addDoc->business_id = $req->business_id;
	    	$addDoc->category_id = $req->category_id;
	    	$addDoc->desc  = $req->desc;
	    	$addDoc->save();
	    	$message = "Documentation has been successfully updated";
    	}else{
	    	$addDoc = New DocsModel;
	    	$addDoc->title = $req->title;
	    	$addDoc->user_id = \Auth::user()->id;
	    	$addDoc->auth_id = \Auth::user()->id;
	    	$addDoc->business_id = $req->business_id;
	    	$addDoc->category_id = $req->category_id;
	    	$addDoc->desc  = $req->desc;
	    	$addDoc->save();
	    	$message = "Documentation has been successfully created";
	    }
    	return back()->with(['message'=> $message, 'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }
}
