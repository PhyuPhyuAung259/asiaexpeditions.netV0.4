<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $table = 'tbl_role';

    public function user(){
    	return $this->hasMany(User::class);
    }

    public function departmentmenu()
    { 
    	return $this->belongsToMany(DepartmentMenu::class);
    }

    public function department()
    {
    	return $this->belongsToMany(Department::class);
    }
}
