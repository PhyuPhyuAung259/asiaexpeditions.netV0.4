<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\ImageManagerStatic as Image;
// use App\
use App\Company;

class UploadController extends Controller
{
    protected $site_settings;
    protected $dir_path;

    public function fileUploaded(Request $req){        
        $this->dir_path = 'storage/';
        $this->dir_thumb ='storage/thumbnail/';
        $datafile = "No File Exiting";
        $status   = false;
        $dirFile  = glob($this->dir_thumb."*", GLOB_BRACE);
        $result   = [];
        $monthall = [];
        if (!empty($dirFile)) {
            $datafile = [];
            $index    = 2;
            $mydate   = "";         
            $storarr  = array();
         
            foreach( $dirFile as $key => $files){

                // return $files;
                $mydate = date ("m-Y", filemtime($files));
                array_push($storarr,['dt' => $mydate ]);
                $file = explode('/', $files);
                // return end($file);
                if ($req->month == 0) {
                    $datafile[] = [ 'file' => end($file),
                                    'name' => basename(end($file)),
                                    'url'  => '/storage/thumbnail/'.end($file)
                                  ];
                }
                elseif ($req->month === $mydate) {
                    $datafile[] = [ 'file' => end($file),
                                    'name' => basename(end($file)),
                                    'url'  => '/storage/thumbnail/'.end($file)
                                  ];
                }
            }
            foreach ($storarr as $element) {
                $result[$element['dt']][] = $element;                
            }
            foreach ($result as $key => $value) {
                 array_push($monthall, $key);
            }
            $status = true;
        }        
        return response()->json(['files'=> $datafile, 'status' => $status, 'dates' => $monthall, 'check' => $req->month]);    
    } //json_encode();

    public function removeFile(Request $req){
        if($req->pathImg){
            unlink("storage/thumbnail/".$req->pathImg);
            unlink("storage/".$req->pathImg);
            $message = "Yes";
        }else{
            $message = "not";
        }   
        return response()->json(['message'=> $message]);
    }

    public function uploadFile(Request $req){
        $this->dir_path = "storage/";
        $this->dir_thumb = "storage/thumbnail";
        $message = 'Folder Exiting';        
        if ( $req->hasFile("uploadfile") ) {
            foreach ($req->file("uploadfile") as $key=>$image) {
                $filename = str_slug(time()."_".pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME), "_").'.'.$image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath())->fit(360, 260);
                $img->save($this->dir_thumb."/".$filename);
                $image->move($this->dir_path, $filename); 
                $message = "Uploaded Successfully";
            }
        }
         
        return response()->json(['message'=>$message]);
    }

    public function uploadOnlyFile(Request $req){
        $this->dir_path = 'storage/avata';
        if ( $req->hasFile("onlyFile")) {
            $image = $req->file("onlyFile");
            $filename = str_slug(time()."_".$image->getClientOriginalName(), "_").'.'.$image->getClientOriginalExtension();
            $image->move($this->dir_path, $filename); 
            $cp = Company::find($req->cp_id);
            $cp->logo = $filename;
            $cp->save();
            $message = "Uploaded Successfully";           
        }
        return response()->json(['message'=>$message, 'onlyFile'=>$filename, 'collect'=> $req->all()]);
    }

    public function RemoveLogo(Request $req){
        $cp = Company::find($req->cp_id);
        $cp->logo = '';
        if ($cp->save()) {
            unlink('storage/avata'."/".$req->filename);
        }
        return response()->json(['message'=>"Image Remove Successfully"]);
    }
}
