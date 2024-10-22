<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tour;
use App\Country;
use App\Category;
use App\Business;
use App\CountView;
use App\component\Content;
use Illuminate\Support\Facades\Response;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $per = isset($req->per) ? $req->per : 4;
        $tourID = [];
        if ($req->cat_type && !empty($req->cat_type)) {
            $caTour = \DB::table("category_tours")->select('tour_id')->where('category_id', $req->cat_type)->get();
            if ($caTour->count() > 0) {
                foreach ($caTour as $key => $value) {
                    $tourID[] = $value->tour_id;
                }
            }
            $tours = Tour::where(['tour_status'=>1, 'web'=>1, 'post_type'=>$req->type])->whereIn('id', $tourID)->orderBy('created_at', 'DESC')->paginate($per);
        }else{
            $tours = Tour::where(['tour_status'=>1, 'web'=>1, 'post_type'=>$req->type])->orderBy('created_at', 'DESC')->paginate($per);
        }
        $tourData = [];
        if($tours){
            foreach ($tours as $key => $value) {
                $countView = CountView::where('tour_id', $value->id);
                $country = Country::find($value->country_id);
                $province = Country::find($value->province_id);
                $tourData[] = [
                    'Id'    => $value->id, 
                    'view'  => $countView->count(),
                    'duration' => $value->tour_daynight,
                    'slug'  => $value->slug,
                    'index' => $key,
                    'country'=> $country['country_name'],
                    'province'=> $country['province_name'],
                    'tour_desc' => $value->tour_desc,
                    'publish_date' => date('F m Y', strtotime($value->updated_at)),
                    'title' => $value->tour_name, 
                    'price' => Content::money($value->tour_price)." ".Content::currency(), 
                    'photo' => Content::urlthumbnail($value->tour_photo), 
                    'gallery'=> trim($value->tour_picture, '|')
                ];
            }
        }else{
            $tourData = ['message'=> "Not Found"];
        }
        return response()->json(['per' => $tours->perPage(),
                                'page' => $tours->currentPage(),
                                'total_page'=>$tours->total(),
                                'tourData'  =>$tourData])->header("Access-Control-Allow-Origin",  "*");
    }

    public function siteMape(Request $req){
        $tours = Tour::where(['tour_status'=>1, 'web'=>1, 'post_type'=>$req->type])->orderBy('created_at', 'DESC')->paginate($per);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $tour = Tour::where('slug', $slug)->first();
        $category_data = [];
        $tourId = [];
        $per = 6;
        if ($tour->categories->count() > 0 ) {
        	foreach ($tour->categories as $key => $value) {
	            $tourId[] = $value->pivot->category_id;
	        }
        }      
        
        if(!empty($tourId)){
        	$catgories = Business::whereIn("id", $tourId)->first();
        	$tour_category = $catgories->tours()
                ->whereNotIn('tour_id', [$tour['id']])
                ->where(['tour_status'=>1,'web'=>1,'post_type'=>0])
                ->orderBy('tour_name')->get();
            foreach ($tour_category as $key => $value) {              
                $category_data[] = [
                    'Id'        => $value->id, 
                    'index'     => $key,
                    'duration'  => $value->tour_daynight,
                    'slug'      => $value->slug,
                    'title'     => $value->tour_name, 
                    'tour_desc' => $value->tour_desc,
                    'country'   => $value->country['country_name'],
                    'province'  => $value->province['province_name'],
                    'price'     => Content::money($value->tour_price)." ".Content::currency(), 
                    'photo'     => Content::urlthumbnail($value->tour_photo), 
                    'gallery'   => trim($value->tour_picture, '|'), 
                ];
            }
        }
        $gallery = [];
        $gallery[] = ['photo'=>Content::urlImage($tour->tour_photo), 'thumbnail'=>Content::urlthumbnail($tour->tour_photo)]; 
        $tour_gallery = explode("|", trim($tour->tour_picture, '|'));
        if (!empty($tour->tour_picture)) {
            foreach ($tour_gallery as $key => $gl) {
                $gallery[] = ['photo'=>Content::urlImage($gl), 'thumbnail'=>Content::urlthumbnail($gl)];
            }            
        }
        $jsonData = [
            'Id'    => $tour->id, 
            'slug'  => $tour->slug,
            'title' => $tour->tour_name, 
            'publish_date' => date('F m Y', strtotime($tour->updated_at)),
            'price' => Content::money($tour->tour_price)." ".Content::currency(), 
            'photo' => Content::urlImage($tour->tour_photo),
            'thumbnail' => Content::urlthumbnail($tour->tour_photo), 
            'images'=> $gallery, 
            'tour_desc' => $tour->tour_desc,
            'tour_hightlight' => $tour->tour_desc,
            'tour_service' => $tour->tour_remark,
            'tour_category'=>$category_data
        ];
        return response()->json($jsonData)->header("Access-Control-Allow-Origin",  "*");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
