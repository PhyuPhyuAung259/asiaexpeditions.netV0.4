<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tour;
use App\TourPrice;
use App\Business;
class TourController extends Controller
{
    //
    public function tourList(Request $req)
    {   
        $locationid = isset($req->location) ? $req->location: \Auth::user()->country_id;
        $tours = Tour::where(['tour_status'=>1, 'country_id'=>$locationid])->whereNotIn('post_type', [1])->orderBy('id', 'DESC')->get();
        return view('admin.tour.tour', compact('tours', 'locationid'));
    }

    public function tourForm(){
        return view('admin.tour.tourForm');
    }

    public function tourCreate(Request $req){   
        $gallery = '';
        if (isset($req->gallery)) {
            foreach ($req->gallery as $key => $g) {
                $gallery .= $g."|";
            } 
        } 
        $addTour = New Tour;
        $addTour->tour_name    = $req->title;
        $addTour->user_id      = \Auth::user()->id;
        $addTour->author_id    = \Auth::user()->id;
        $addTour->slug         = str_slug($req->title, '-');
        $addTour->country_id   = $req->country;
        $addTour->province_id  = $req->city;
        $addTour->tour_price   = $req->tour_price;
        $addTour->tour_dest    = $req->destination;
        $addTour->tour_daynight = $req->daynight;
        $addTour->tour_remark  = $req->tour_remark;
        $addTour->tour_intro   = $req->tour_hight;
        $addTour->tour_desc    = $req->tour_desc;
        $addTour->tour_photo   = $req->image;
        $addTour->tour_picture = $gallery;
        $addTour->web          = $req->web;
        $addTour->tour_status  = $req->status;
        $addTour->save();
        $addTour->categories()->sync($req->type, false);
        $addTour->tour_feasility()->sync($req->tour_feasiltiy, false);
        $addTour->supplier()->sync($req->tour_supplier, false);
        
        return back()->with(['message'=> "Tour Successfully Created",  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }

    public function getTourUpdate(Request $req, $tourId){
        $tour = Tour::find($tourId);
        $dataId = 0;
        if ($tour->categories){            
            foreach ($tour->categories as $key => $cat){
                $dataId .= $cat->pivot->category_id.',';
            }
        }
        // return $dataId;
        $datatour = 0;
        if ($tour->tour_feasility) {
            foreach ($tour->tour_feasility as $key => $ts) {
                $datatour .= $ts->pivot->service_id.',';
            }
        }

        $tourAccommodation = 0;
        if ($tour->supplier) {
            foreach ($tour->supplier as $key => $sup) {
                $tourAccommodation .= $sup->pivot->supplier_id.',';
            }
        }
        return view('admin.tour.tourFormEdit', compact('tour', 'dataId', 'datatour', 'tourAccommodation'));
    }

    public function updateTour(Request $req){  

        $gallery = '';
        $addTour = Tour::find($req->eid);
        if (isset($req->gallery)) {
            foreach ($req->gallery as $key => $g) {
                $gallery .= $g."|";
            } 
        } 
        $photo = isset($req->image) ? $req->image : $req->image_old;
        $addTour->tour_name    = $req->title;
        $addTour->slug         = str_slug($req->title, '-');
        $addTour->country_id   = $req->country;
        $addTour->province_id  = $req->city;
        $addTour->tour_price   = $req->tour_price;
        $addTour->tour_dest    = $req->destination;
        $addTour->tour_daynight = $req->daynight;
        $addTour->tour_remark  = $req->tour_remark;
        $addTour->tour_intro   = $req->tour_hight;
        $addTour->tour_desc    = $req->tour_desc;
        $addTour->tour_photo   = $req->image;
        $addTour->tour_picture = $gallery;
        $addTour->web          = $req->web;
        $addTour->tour_status  = $req->status;
        $addTour->save();
        $addTour->categories()->sync($req->type, true);
        $addTour->tour_feasility()->sync($req->tour_feasilitiy, true);
        $addTour->supplier()->sync($req->tour_supplier, true);
        return back()->with(['message'=> "Tour Successfully Updated", 'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }

    public function getTourtype(){
        $tourtype = Business::where(['category_id'=> 1, 'status'=> 1])->orderBy('name', 'ASC')->get();
        return view('admin.tour.tourtype', compact('tourtype'));
    }

    public function getTourTypeedit(Request $req){
        $tourtype = Business::find($req->dataId);
        return response()->json($tourtype);
    }

    public function createTourType(Request $req){
        if($req->eid == ""){
            $addbus = New Business;
            $addbus->name = $req->title;
            $addbus->slug = str_slug($req->title, '-');
            $addbus->business_iso = $req->bus_ios;
            $addbus->meta_keyword = $req->meta_keyword;
            $addbus->meta_description = $req->meta_desc;
            $addbus->category_id = 1;
            $addbus->web = $req->web;   
            $addbus->status = $req->status;
            $addbus->save();
            $message = "Tour Successfully Created";
        }else{
            $addbus = Business::find($req->eid);
            $addbus->name = $req->title;
            $addbus->slug = str_slug($req->title, '-');
            $addbus->business_iso = $req->bus_ios;
            $addbus->meta_keyword = $req->meta_keyword;
            $addbus->meta_description = $req->meta_desc;
            $addbus->category_id = 1;
            $addbus->web = $req->web;   
            $addbus->status = $req->status;
            $addbus->save();
            $message = "Tour Successfully Updated";
        }
        return back()->with(['message'=> $message, 'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }

    public function getTourPrice ($tourId){
        $tour = Tour::find($tourId);
        $PaxNo = isset($tour->pricetour->last()->pax_no) ? ($tour->pricetour->last()->pax_no +1): 1;
        return view('admin.tour.addTourPrice', compact('tour', 'PaxNo'));
    }

    public function addTourPrice( Request $req){
       if ($req->pax_no) {
            foreach ($req->pax_no as $key => $pax) {
                $addprice = New TourPrice;
                $addprice->tour_id = $req->tour_id;
                $addprice->pax_no = $req->pax_no[$key];
                $addprice->sprice = $req->sprice[$key];
                $addprice->nprice = $req->nprice[$key];
                $addprice->save();
            }
        }
        return back()->with(['message'=> "Pax & Price Assinged", 'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
    }

    public function getTourPriceEdit($tourId){
        $tour = Tour::find($tourId);
        return view('admin.tour.tourPriceEdit', compact('tour'));
    }

    public function updateTourPrice( Request $req){
        if ($req->pax_no) {
            foreach ($req->pax_no as $key => $pax) {
                $addprice = TourPrice::find($req->eid[$key]);
                $addprice->tour_id = $req->tour_id;
                $addprice->pax_no = $req->pax_no[$key];
                $addprice->sprice = $req->sprice[$key];
                $addprice->nprice = $req->nprice[$key];
                $addprice->save();
            }
        }
        return back()->with(['message'=> "Pax & Price has been udpated", 'status'=> 'success', 'status_icon'=> 'fa-check-circle']);
    }

    public function getTourReport(Request $req, $tourId, $type){
        $tour = Tour::find($tourId);
        return view('admin.tour.tourReport', compact('tour', 'type'));
    }
}
