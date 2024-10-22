<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Subscribe;
use Illuminate\Support\Facades\Mail;
use App\Mail\TourRequested;

class TourItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'fdsafdsaf';
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
        $add = new Subscribe;
        $add->name      = $req->fullname;
        $add->email     = $req->email;
        $add->phone     = $req->phone;
        $add->pax_number= $req->pax;
        $add->start_date = $req->start_date;
        $add->end_date   = $req->end_date;
        $add->country_id= $req->country;
        $add->message   = $req->message;
        $add->tour_id   = $req->tour_id;
        $add->type = 1;
        if ($add->save()) {
            $ReqTour = Subscribe::findOrFail($add->id);
            Mail::to($req->email)
                // ->cc(\Auth::user()->email)
                ->bcc(config('app.email'), 'Tour Request')
                ->send( new TourRequested($ReqTour));
            $data = ['status'=>'success'];
            return response()->json($data)->header("Access-Control-Allow-Origin",  "*");
        }

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
