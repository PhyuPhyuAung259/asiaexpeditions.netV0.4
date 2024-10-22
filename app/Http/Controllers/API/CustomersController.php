<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Customers;
use App\Admin\Photos;

class CustomersController extends Controller
{   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {   
        
        if ($req->u_info) {
            $get = Customers::where(['id'=>$req->u_info,])->first();          
            $data = array('id' => $get['id'] ,
                      'name' => $get['name'],
                      'fullname' => $get['fullname'],
                      'province_name' => $get->province['province_name'],
                      'country_name' => $get->country['country_name'],
                      'province_id' => $get['province_id'],
                      'country_id'  => $get['country_id'],
                      'descs' => $get['descs'],
                      'picture' => $get['picture'],
                      'cover_photo' => $get['cover_photo'],
                      'date'  => $get['created_at'],
                    );
            return response()->json($data)->header('Access-Control-Allow-Origin', '*');   
        }elseif ($req->dataid) {      
            $data = Photos::where(['user_id'=> $req->dataid,'status'=>1])->whereNotIn('photo_intro',[''])->get();
        }
        #return response()->json(['datas'=> $data])->header('Access-Control-Allow-Origin', '*');        
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
    public function store(Request $req)
    {
        $mes= false;
        if ($req->id) {        
            $data = Customers::find($req->id);
            $data->fullname = $req->username;
            $data->name = $req->name;            
            $data->descs = $req->message;
            $data->province_id= $req->proid;
            $data->country_id= $req->cntid;
            $data->save();
            $mes=true;
            $getid = array( 'fullname' =>  $req->username,
                            'name'      =>  $req->name,
                            'email'     =>  $req->email,
                            'id'        =>  $req->id );
        }
        else if (!Customers::exitEmail($req->email) ) {   
            $data = new Customers();
            $data->fullname = $req->username;
            $data->name = $req->name;
            $data->email = $req->email;
            $data->picture   = 'user.png';
            $data->cover_photo = '1572487302_bagan_ballooms_over_u_bein_bridge.jpg';
            $data->save();
            $getid = array( 'fullname'  =>  $req->username,
                            'name'      =>  $req->name,
                            'email'     =>  $req->email,
                            'id'        =>  $req->id );
            $mes=true;        
        }
        else{   
            $getid= Customers::where('email',$req->email)->first();
            if ($getid) {
            	$mes=true;
            }            
        }
        return response()->json(['ok'=>$mes,'getid'=>$getid])->header('Access-Control-Allow-Origin', '*');
        
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
    public function update(Request $req, $id)
    {

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

