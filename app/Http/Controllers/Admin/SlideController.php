<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SlideShow;
class SlideController extends Controller
{
    public function index(){
    	$slides = SlideShow::where(['status'=>1])->orderBy('updated_at', 'ASC')->get();
    	return view('admin.slides.index', compact('slides'));
    }


    public function createSlide(){
    	return view('admin.slides.add_form');
    }

    public function slideStore(Request $req){
        $addSlide = New SlideShow;
        $addSlide->title 	= $req->title;
        $addSlide->photo 	= $req->image;
        $addSlide->country_id = $req->country;
        $addSlide->user_id 	= \Auth::user()->id;
        $addSlide->province_id = $req->city;
        $addSlide->intro 	= $req->desc;
        $addSlide->status 	= $req->status;
        if($addSlide->save()){
            return redirect()->route("slides")->with(['message'=>'Slide Successfully Added']);
        }
    }

    public function getSlide($id){
    	$slide = SlideShow::find($id);
    	return view('admin.slides.update_form', compact('slide'));
    }

    public function updateStore(Request $req){
        $addSlide = SlideShow::find($req->eid);
        $addSlide->title 	= $req->title;
        $addSlide->photo 	= $req->image;
        $addSlide->country_id = $req->country;
        $addSlide->province_id = $req->city;
        $addSlide->intro 	= $req->desc;
        $addSlide->status 	= $req->status;
        if($addSlide->save()){
            return redirect()->route("slides")->with(['message'=>'Slide Successfully Updated']);
        }
    }
}
