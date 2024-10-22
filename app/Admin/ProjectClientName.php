<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class ProjectClientName extends Model
{
    protected $table = "project_client_name";

    public function country (){
    	return $this->belongsTo(\App\Country::class);
    }
}
