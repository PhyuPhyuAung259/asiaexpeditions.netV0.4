<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Film;
use App\Country;
class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
      
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
        $coun = Country::where("country_slug", $slug)->first();
        $per    = isset($req->per) ? $req->per : 6;
        $getFilm = Film::where(["status"=>1, 'country_id'=>$coun['id'], 'type'=>0])->orderBy("id", "ASC")->paginate($per);
        $dataJson = [];
        foreach ($getFilm as $key => $value) {
            $dataJson[] = [
                        'id'    => $value->id, 'title'=> $value->title, 'slug'  => $value->slug,
                        'user'  => $value->user['fullname'], 'country'=>$value->country['country_name'],
                        'province' => $value->province['province_name'],
                        'video' => $value->video, 
                        'photo' => $value->photo,
                        'gallery'=>$value->gallery,
                        'desc'  => $value->desc,
                    ];
        }
        return response()->json([
                    'total_page'=>$getFilm->total(), 
                    'pageActive'=>$getFilm->currentPage(),
                    'films'=>$dataJson
                ])->header("Access-Control-Allow-Origin",  "*");
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
