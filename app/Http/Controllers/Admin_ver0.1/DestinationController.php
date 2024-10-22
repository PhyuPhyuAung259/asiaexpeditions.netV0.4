<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Country;
use App\Province;
class DestinationController extends Controller
{
    //
	public function CountryList(){
		$countries = Country::orderBy('country_name', 'ASC')->get();
		return view('admin.country.country', compact('countries'));
	}

	public function provinceList(Request $req){
		$locationid = isset($req->location) ? $req->location: \Auth::user()->country_id;
		$provinces = Province::where(['country_id'=> $locationid, 'province_status'=>1])->orderBy('province_name', 'ASC')->get();
		return view('admin.province.province', compact('provinces', 'locationid'));
	}

	public function getCountry(){
		return view('admin.country.countryForm');
	}

	public function createCountry(Request $req){
		$gallery = '';
        if (isset($req->gallery)) {
            foreach ($req->gallery as $key => $g) {
                $gallery .= $g."|";
            } 
        } 
		$addNew = New Country;
		$addNew->country_name 	= $req->name;
		$addNew->country_title 	= $req->country_title;
		$addNew->country_slug 	= str_slug($req->name, '-');
		$addNew->nationality  	= $req->nationality;
		$addNew->country_intro 	= $req->country_intro;
		$addNew->country_status = $req->status;
		$addNew->web = $req->web;		
		$addNew->country_photo   = $req->image;
        $addNew->country_picture = $gallery;
		$addNew->save();
		return back()->with(['message'=> 'Added New country successfully', 'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
	}

	public function getCountryEdit($countId){
		$country = Country::find($countId);
		return view('admin.country.countryFormEdit', compact('country'));
	}

	public function updateCountry(Request $req){
		$gallery = '';
        if (isset($req->gallery)) {
            foreach ($req->gallery as $key => $g) {
                $gallery .= $g."|";
            } 
        } 
		$addNew = Country::find($req->eid);
		$addNew->country_name = $req->name;
		$addNew->country_title 	= $req->country_title;
		$addNew->country_slug = str_slug($req->name, '-');
		$addNew->nationality  = $req->nationality;
		$addNew->country_intro = $req->country_intro;
		$addNew->country_status = $req->status;
		$addNew->country_photo   = $req->image;
        $addNew->country_picture = $gallery;
		$addNew->web = $req->web;		
		$addNew->save();
		return back()->with(['message'=> 'Cuntry updated successfully',  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
	}

	public function getProvince(){
		return view('admin.province.provinceForm');
	}

	public function createProvince (Request $req){
		$gallery = '';
        if (isset($req->gallery)) {
            foreach ($req->gallery as $key => $g) {
                $gallery .= $g."|";
            } 
        } 
		$addpro = New Province;
		$addpro->province_name = $req->province_name;
		$addpro->slug = str_slug($req->province_name,'-');
		$addpro->country_id = $req->country;
		$addpro->province_status = $req->status;
		$addpro->web = $req->web;
		$addpro->province_photo   = $req->image;
        $addpro->province_picture = $gallery;
		$addpro->province_intro = $req->province_intro;
		$addpro->save();
		return back()->with(['message'=> 'New Province Has Been Add successfully', 'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
	}

	public function getProvinceEdit($proId){
		$province = Province::find($proId);
		return view('admin.province.provinceFormEdit', compact('province'));
	}

	public function updateProvince (Request $req){
		$gallery = '';
        if (isset($req->gallery)) {
            foreach ($req->gallery as $key => $g) {
                $gallery .= $g."|";
            } 
        } 
		$addpro = Province::find($req->eid);
		$addpro->province_name 	= $req->province_name;
		$addpro->slug 			= str_slug($req->province_name,'-');
		$addpro->country_id 	= $req->country_id;
		$addpro->province_status = $req->status;
		$addpro->web 			= $req->web;
		$addpro->province_photo = $req->image;
        $addpro->province_picture = $gallery;
		$addpro->province_intro = $req->province_intro;
		$addpro->save();
		return back()->with(['message'=> 'province has been updated successfull', 'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
	}
}
