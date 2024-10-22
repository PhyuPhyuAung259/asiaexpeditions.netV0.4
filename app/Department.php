<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    protected $table = 'tbl_department';

    public function role()
    {
    	return $this->belongsToMany(Role::class, 'department_role',  'department_id', 'role_id');
    }


}
