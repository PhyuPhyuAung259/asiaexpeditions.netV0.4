<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tour;
use App\CountView;
use App\Country;
use App\Province;
use App\component\Content;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        
        $popularData = [];
        $PopularTours = Tour::where(['tour_status'=>1, 'web'=>1, 'post_type'=>$req->type])
                ->where('tour_name', 'like', '%' . $req->key . '%')
                ->whereHas('viewtour', function($query){
                })->get();

        if($PopularTours){
            foreach ($PopularTours as $key => $value) {
                $countView = CountView::where('tour_id', $value->id);
                $country = Country::find($value->country_id);
                $province = Province::find($value->province_id);
                $popularData[] = [
                            'view'  => $countView->count(),
                            'duration' => $value->tour_daynight,
                            'slug'  => $value->slug,                            
                            'title' => $value->tour_name, 
                            'price' => Content::money($value->tour_price)." ".Content::currency(), 
                            'photo' => Content::urlthumbnail($value->tour_photo),
                            'country' => $country->country_name,
                            'province' => $province->province_name,
                            // 'gallery'=> trim($value->tour_picture, '|'),
                            // 'index' => $key,
                            // 'tour_desc' => $value->tour_desc,
                            // 'publish_date' => date('F m Y', strtotime($value->updated_at)),
                        ];
            }
        }else{
            $popularData = ['message'=> "Not Found"];
        }
        return response()->json(['Tours'  =>$popularData
                            ])->header("Access-Control-Allow-Origin",  "*");
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
