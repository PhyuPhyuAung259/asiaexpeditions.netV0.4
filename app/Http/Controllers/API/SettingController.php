<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\CountryFacts;
class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $dataJson = [];
        $dataValue = [1,2,3];
        foreach ($dataValue as $key => $value) {
            $getData = \DB::table('tbl_setting')->select('id', 'title', 'slug', 'type')->orderBy('title')->where(['status'=>1, 'type'=> $value])->get();
            $dataJson[] = $getData;
        }
        return response()->json(['setting'=>$dataJson])
                ->header("Access-Control-Allow-Origin",  "*");

       
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
    public function show($slug)
    {
        $getData = \DB::table('tbl_setting')->where('slug',$slug)->first();
        
        // if($getData){
        //     $dataJson = ['id'=>$getData->id, 'name'=> $getData->facts_name, 'slug'=> $getData->slug, 'desc'=> $getData->facts_details];
        // }else{
        //     $dataJson = [];
        // }
        return response()->json(['info'=>$getData ])
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
