<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Province as ProvinceJson;
use App\Http\Resources\ProvinceCollection;
use App\Province;
use App\Country;
use App\Http\Resources\CountryCollection;

class DestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $req)
    {
        $name = isset($req->key) ? $req->key : 'null';
        $destJson = ['there is nothing to show...'];
        if ($req->type == 'province') {
            $dest = Province::where(['province_status'=>1])
                    ->orderBy('province_name')->get();
            $destJson= new ProvinceCollection($dest);
        }else if ($req->type == 'country') {
            $dest = Country::where(['country_status'=>1])->orderBy('province_id')->get();
            $destJson= new CountryCollection($dest);
        }
        return response()->json(['data'=>$destJson]);
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
