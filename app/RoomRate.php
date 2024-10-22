<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomRate extends Model
{
    protected $table = 'hotel_rate';

    public function room(){
    	return $this->hasMany(Room::class);
    } 

    public static function getRoomRate($hotelid, $roomId){
        return \DB::table('room_supplier')
            ->join('suppliers', 'suppliers.id', '=', 'room_supplier.supplier_id')
            ->join('room', 'room.id', '=', 'room_supplier.room_id')
            ->where(['room_supplier.supplier_id'=>$hotelid, 'room_supplier.room_id' => $roomId])
            ->get();
    }
}
 