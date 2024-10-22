<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrProgram extends Model
{
    protected $table = 'rc_program';
    public function crCabin(){
        // $cruiseRate = ['ssingle_price', 'stwin_price', 'sdbl_price', 'sextra_price', 'schexbed_price', 'nsingle_price','ntwin_price','ndbl_price','nextra_price','nchexbed_price'];
    	return $this->belongsToMany(CrCabin::class);
    }

    public function supplier(){
    	return $this->belongsTo(Supplier::class);
    }

    public function cruisebooked(){
        return $this->hasMany(CruiseBooked::class);
    }
    

    public function province(){
    	return $this->belongsTo(Province::class);
    }

    public function book(){
        return $this->hasMany(Booking::class);
    }

    public static function getCabinProgram(){
        return \DB::table('cr_cabin_cr_program as cabin')
            ->join('rc_program', 'rc_program.id', '=', 'cabin.cr_program_id')
            ->join('rc_cabin', 'rc_cabin.id', '=', 'cabin.cr_cabin_id')
            ->select('rc_program.*','rc_cabin.name', 'cabin.*')
            ->where(['rc_program.status'=>1])
            ->orderBy('rc_cabin.name', 'ASC')
            ->get(); 
    }
}
