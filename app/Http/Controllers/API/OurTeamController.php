<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Country;
use App\Category;
use App\CountView;
use App\component\Content;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\OurTeamResources;

class OurTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $per = isset($req->per) ? $req->per : 8;
        $teams = User::where(['web'=>1])->orderBy('created_at', 'DESC')->paginate($per);
        $userData = OurTeamResources::collection($teams);
        // if($teams){
        //     foreach ($teams as $key => $value) {                
        //         $userData[] = [ $value->id => [ 
        //             'Id'        => $value->id, 
        //             'fullname'  => $value->fullname, 
        //             'position'  => $value->position,
        //             'slug'      => $value->name,
        //             'joined_date' => date('F m Y', strtotime($value->created_at)),
        //             'desc'      => $value->descs,
        //             // 'country'   => $value->country['country_name'],
        //             'city'      => $value->province['province_name'],
        //             'photo'     => Content::urlImage($value->picture),
        //             'thumbnail' => Content::urlthumbnail($value->picture)]
        //         ];
        //     }
        // }else{
        //     $tourData = ['message'=> "Not Found"];
        // }

        return response()->json(['userData' => $userData ])->header("Access-Control-Allow-Origin",  "*");
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
        
        $teams = User::where(['slug'=>$slug])->orderBy('created_at', 'DESC')->paginate($per);
        if($teams){
            foreach ($teams as $key => $value) {                
                $userData[] = [ $value->id => [ 
                    'Id'        => $value->id, 
                    'fullname'  => $value->fullname, 
                    'position'  => $value->position,
                    'slug'      => $value->slug,
                    'joined_date' => date('F m Y', strtotime($value->created_at)),
                    'desc'      => $value->descs,
                    'country'   => $value->country['country_name'],
                    'city'      => $value->province['province_name'],
                    'photo'     => Content::urlImage($value->picture),
                    'thumbnail' => Content::urlthumbnail($value->picture)]
                ];
            }
        }else{
            $tourData = ['message'=> "Not Found"];
        }

        return response()->json(['userData' => $userData ])->header("Access-Control-Allow-Origin",  "*");
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
