<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\ImageManagerStatic as Image;
use App\Admin\Photos;
use App\Customers;


class APIUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // protected $loc_org  = 'D:\wamp64\www\AE-Web\ae-contents/photos';
    // protected $loc_thum = 'D:\wamp64\www\AE-Web\ae-contents/thumbnails';

    protected $loc_org = 'home3/asiagolf/public_html/ea-content/photos/share/';
    protected $loc_thum = 'home3/asiagolf/public_html/ea-content/photos/share/thumbs/'; 

    public function index(Request $req)
    {
        if($req->user_id){
            $data=Photos::select('name','id')->where('user_id',$req->user_id)->get();
        }
        if($req->photo_id){
            $datas=Photos::where('id',$req->photo_id)->first();
            $data = array('id' => $datas['id'],
                          'province_name' => $datas->province['province_name'],
                          'province_id' => $datas['province_id'],
                          'country_name' => $datas->country['country_name'],
                          'country_id' => $datas['country_id'],
                          'photo_intro' => $datas['photo_intro'],
                          'name' => $datas['name']
                           );

        }    
        return response()->json(['data'=>$data])->header('Access-Control-Allow-Origin', '*');
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
        // return $req->message;
        $getId=Photos::latest('id')->first();
        if (isset($getId->id)) {
           $setId=$getId->id+1;
        }else{
           $setId=1;
        }
        $message = 'Folder Exiting';
        // if (!file_exists($this->loc_org) && !is_dir($this->loc_org)) {
        //     mkdir($this->loc_org);
        // }
        // if (!file_exists($this->loc_thum) && !is_dir($this->loc_thum)) {
        //     mkdir($this->loc_thum);
        // }
        
         // return $req->file();

        if ( $req->hasFile("myfile") ) {
            foreach ($req->file("myfile") as $key=>$image) {
                $filename = str_slug('ID_'.$setId."_".pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME), "_").'.'.$image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath())->fit(400, 270);
                $img->save($this->loc_thum."/".$filename);
                $image->move($this->loc_org, $filename);

                $data = New Photos;
                $data->name  = $filename;
                $data->original_name = $image->getClientOriginalName();
                $data->user_id     =$req->user_id;
                $data->status      =1;
                $data->photo_intro =$req->message;
                $data->province_id ='';
                $data->save();
              
                if(isset($req->u_file) > 0){
                    $data = Customers::find($req->user_id);
                    if ($req->cover > 0 ) {
                      $data->cover_photo = $filename;
                    }else{
                      $data->picture = $filename;
                    }
                    $data->save();
                }
                $message = "Uploaded Successfully";
            }
            
        }
        if($req->file_name){
                    $data = Customers::find($req->user_id);
                    if ($req->cover > 0 ) {
                      $data->cover_photo = $req->file_name;
                    }else{
                      $data->picture = $req->file_name;
                    }                   
                    $data->save();
        }
        if ($req->delid) {
            // return $req->all();
            Photos::find($req->delid)->delete();
        }
        if ($req->updateid) {
            // return $req->all();
            $data = Photos::find($req->updateid);
            $data->photo_intro =$req->message;
            $data->province_id =$req->proid;
            $data->country_id =$req->cntid;
            $data->save();
        }
        
        // return $req->message;
        return response()->json(['message'=>$message])->header('Access-Control-Allow-Origin', '*');
        
        
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
