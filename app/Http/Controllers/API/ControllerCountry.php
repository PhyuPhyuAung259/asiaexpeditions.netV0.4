<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Country;
use App\component\Content;
use App\Tour;
class ControllerCountry extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
    	// $web =isset($req->web) ? $req->web : 1;
    	if (isset($req->web)) {
    		$countries = Country::where(['country_status'=>1, 'web'=>1])->orderByRaw('RAND()')->orderBy('updated_at', 'ASC')->get();	
    	}else{
    		$countries = Country::where(['country_status'=>1, 'country_status'=>1])->whereNotNull('nationality')->orderByRaw('RAND()')->orderBy('updated_at', 'ASC')->get();	
    	}
        
        $conData = [];
        if ($countries->count() > 0) {
            foreach ($countries as $key => $value) {
                $conData[] = [
                	'id'		=> 	$value->id, 
                    'country'	=>	$value->country_title, 
                    'country_name'=> $value->country_name,
                    'nationality'	=> $value->nationality,
                    'slug'		=>	$value->country_slug, 
                    'city'		=>	$value->city_capital,
                    'photo'		=>	Content::urlImage($value->country_photo), 
                    'thumbnail'	=>	Content::urlthumbnail($value->country_photo)
                ];
            }    
        }        
        return response()->json(['countries'=>$conData])->header("Access-Control-Allow-Origin",  "*");
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
        $country = Country::where('country_slug', $slug)->first();     
        $gallery = [];
        $thumbnail = '';
        $picID = explode( ".", $country->country_photo);
        $index = 0;
        $gallery[] = [
					'index'		=> 	(int)$index,
        			'picId'		=> 	$picID[0], 
        			'photo'		=>	Content::urlImage($country->country_photo), 
        			'thumbnail' =>	Content::urlthumbnail($country->country_photo)]; 
        $tourData = [];
        $per = isset($req->per) ? $req->per : 9;        
        $tours = Tour::where(['country_id'=>$country['id'], 'tour_status'=>1, 'web'=>1, 'post_type'=>0])->orderBy('created_at')->get();
        foreach ($tours as $key => $value) {        	
            $piTID = explode(".", $value->tour_photo);
            if ( !empty($value->tour_photo) ) {	
            	$index++;
    			$gallery[] = [
                    	'index'		=> (int)$index,
						'picId'		=> $piTID[0], 
                    	'photo'		=> Content::urlImage($value->tour_photo), 
						'thumbnail'	=> Content::urlthumbnail($value->tour_photo)
					]; 
            }
            if ($value->tour_picture) {
                $tour_gallery = explode("|", trim($value->tour_picture, '|'));
                foreach ($tour_gallery as $key => $gl) {
                    $picGID = explode(".", $gl);
                    if (Content::urlImage($gl) != '/img/no_image.png') { 
                    	$index++;
                    	$gallery[] = 
                        	['index'	=> 	(int)$index,
    						'picId'		=> 	$picGID[0], 
    						'photo'		=> Content::urlImage($gl), 
    						'thumbnail'	=> Content::urlthumbnail($gl)
    					];  
            		}
                }
            }            

            if(!isset($req->picId)){
	            $tourData[] = [
	                'Id'        => $value->id, 
	                'view'      => $value->viewtour->count(),
	                'duration'  => $value->tour_daynight,
	                'title'     => $value->tour_name, 
	                'slug'      => $value->slug,
	                'index'     => $key,
	                'country'   => $value->country['country_title'],
	                'province'  => $value->country['province_name'],
	                'tour_desc' => $value->tour_desc,
	                'publish_date' => date('F m Y', strtotime($value->updated_at)),
	                'price'     => Content::money($value->tour_price)." ".Content::currency(), 
	                'photo'     => Content::urlthumbnail($value->tour_photo), 
	                'thumbnail'   => Content::urlthumbnail($value->tour_photo)
	            ];
	        }
        }

      	$featureImg = '';
        if (isset($req->picId)) {
	        foreach ($gallery as $key => $value) {
	        	if (in_array($req->picId, $value)) {
	        	   $featureImg = $value;
	        	}
	        }
        }

        $conData = ['id'    => $country->id, 
                'country'   => $country->country_title, 
                'slug'      => $country->country_slug, 
                'con_desc'  => $country->country_intro, 
                'city'      => $country->city_capital,            
                'galleries' => $gallery,
                'tourData'  => $tourData,
                // 'totalTour' => $tours->total(),                
                ];
        return response()->json(['featureImg'=> $featureImg,'countries'=>$conData])->header("Access-Control-Allow-Origin",  "*");
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
