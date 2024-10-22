<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrPrice extends Model
{
    protected $table = 'rc_cabprice';

    public static function getCabinPrice($locationId){
        return \DB::table('rc_cabprice as cabin')
            ->join('suppliers', 'suppliers.id','=','cabin.supplier_id')
            ->join('rc_cabin', 'rc_cabin.id', '=', 'cabin.cabin_id')
            ->join('rc_program', 'rc_program.id', '=', 'cabin.program_id')
            ->select('cabin.*', 'rc_program.program_name', 'rc_cabin.name')
            ->where(['suppliers.country_id'=> $locationId]) 
            ->orderBy('cabin.start_date', 'ASC')
            ->get(); 
    }
    
}
