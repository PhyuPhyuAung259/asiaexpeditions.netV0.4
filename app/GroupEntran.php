<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupEntran extends Model
{
    protected $table = "group_supplier_service";


    public function supplier(){
        return $this->hasMany(AccountTransaction::class);
    }
}
