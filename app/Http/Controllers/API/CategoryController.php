<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Business;
use App\Tour;
use App\component\Content;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoryData = Business::where(['web'=>1, 'status'=>1])->orderBy('name')->get();
        $categories = [];
        if($categoryData){
            foreach ($categoryData as $key => $value) {
                $categories[] = [
                            'id'=>$value->id, 
                            'name'=>$value->name, 
                            'slug'=>$value->slug, 
                            'desc'=>$value->description, 
                            'meta_key'=>$value->meta_key, 
                            'meta_desc'=>$value->meta_description
                        ];
            }
        }

		return response()->json(['categories'=>$categories ])->header("Access-Control-Allow-Origin",  "*");
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
    public function show(Request $req, $slug)
    {
        $categories = Business::where('slug', $slug)->first();
		$per = isset($req->per) ? $req->per : 4;
        $tour_category = $categories->tours()->where(['tour_status'=>1, 'web'=>1, 'post_type'=>0])->orderBy('tour_name')->paginate($per);
       	$toursbyCategory = [];
        if($tour_category->count() > 0){
            foreach ($tour_category as $key => $value) {
                $toursbyCategory[] = [
                    'Id'    	=> $value->id, 
                    'view'  	=> $value->viewtour->count(),
                    'duration' 	=> $value->tour_daynight,
                    'slug'  	=> $value->slug,
                    'index' 	=> $key,
                    'country'	=> $value->country['country_name'],
                    'province'	=> $value->country['province_name'],
                    'tour_desc' => $value->tour_desc,
                    'publish_date' => date('F m Y', strtotime($value->updated_at)),
                    'title' 	=> $value->tour_name, 
                    'price' 	=> Content::money($value->tour_price)." ".Content::currency(), 
                    'photo' 	=> Content::urlthumbnail($value->tour_photo), 
                    'gallery'	=> trim($value->tour_picture, '|'), 
                    
                ];
            }
        }
		return response()->json([
					'cat' 	=> $categories,
					'per'   	=> $tour_category->perPage(),
                	'page'  	=> $tour_category->currentPage(),
                	'total_page'=> $tour_category->total(),
                	'toursbyCategory'=> $toursbyCategory])
				->header("Access-Control-Allow-Origin",  "*");
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
