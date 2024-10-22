<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tour;
class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $locationid = isset($req->location) ? $req->location: \Auth::user()->country_id;
        $tours = Tour::where(['tour_status'=>1, 'country_id'=>$locationid, 'web'=>1,  'post_type'=>1])
                ->orderBy('id', 'DESC')->get();
        return view('admin.blog.index', compact('tours', 'locationid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.blog.blogForm');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $gallery = '';
        if (isset($req->gallery)) {
            foreach ($req->gallery as $key => $g) {
                $gallery .= $g."|";
            } 
        } 
        $addBlog = New Tour;
        $addBlog->tour_name    = $req->title;
        $addBlog->user_id      = \Auth::user()->id;
        $addBlog->author_id    = \Auth::user()->id;
        $addBlog->slug         = str_slug($req->title, '-');
        $addBlog->country_id   = $req->country;
        $addBlog->province_id  = $req->city;     
        $addBlog->tour_desc    = $req->tour_desc;
        $addBlog->tour_photo   = $req->image;
        $addBlog->tour_picture = $gallery;
        $addBlog->web          = 1;
        $addBlog->post_type    = 1;
        $addBlog->tour_status  = $req->status;
        $addBlog->save();        
        return redirect()->route('blogindex',['message'=>"Tour Successfully Created",  'status'=> 'success', 'status_icon'=> 'fa-check-circle']); 
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
        $tour = Tour::find($id);
        return view('admin.blog.blogFormEdit', compact('tour'));
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
        $gallery = '';
        $addTour = Tour::find($id);
        if (isset($req->gallery)) {
            foreach ($req->gallery as $key => $g) {
                $gallery .= $g."|";
            } 
        } 
        $photo = isset($req->image) ? $req->image : $req->image_old;
        $addTour->tour_name    = $req->title;
        $addTour->slug         = str_slug($req->title, '-');
        $addTour->country_id   = $req->country;
        $addTour->province_id  = $req->city;
        
        $addTour->tour_desc    = $req->tour_desc;
        $addTour->tour_photo   = $req->image;
        $addTour->tour_picture = $gallery;
        $addTour->tour_status  = $req->status;
        $addTour->save();
        return redirect()->route('blogindex',['message'=>"Blog Successfully Updated", 'status'=>'success', 'status_icon'=>'fa-check-circle']); 
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
