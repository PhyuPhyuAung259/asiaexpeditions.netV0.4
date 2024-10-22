<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'room';

    public function supplier(){
        // $hotelRate = ['ssingle', 'stwin', 'sdbl_price', 'sextra', 'schexbed', 'nsingle','ntwin','ndbl_price','nextra','nchexbed'];
    	return $this->belongsToMany(Supplier::class);//->withPivot($hotelRate);
    }

    public static function roomExit($roomName){
        return self::where('name', $roomName)->first();
    }

    public function rate(){
        return $this->belongsTo(RoomRate::class);
    }

    public function hotelbooked (){
        return $this->belongsTo(hotelbooked::class);
    }

    public static function getHotelRoomRate($locationId){
    	return \DB::table('room_supplier')
            ->join('suppliers', 'suppliers.id', '=', 'room_supplier.supplier_id')
            ->join('room', 'room.id', '=', 'room_supplier.room_id')
            ->select('room_supplier.*','suppliers.supplier_name', 'room.name')
            ->where(['suppliers.country_id'=> $locationId]) 
            ->orderBy('room_supplier.id', 'ASC')
            ->get();
    }

    public static function getHotelRate($locationId){
        return \DB::table('hotel_rate')
            ->join('suppliers', 'suppliers.id','=','hotel_rate.supplier_id')
            ->join('room', 'room.id', '=', 'hotel_rate.room_id')
            ->select('hotel_rate.*','suppliers.supplier_name', 'room.name')
            ->where(['suppliers.country_id'=> $locationId]) 
            ->orderBy('hotel_rate.id', 'ASC')
            ->get();
    }
 
}
