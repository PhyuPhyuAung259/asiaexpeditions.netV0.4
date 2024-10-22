<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocsModel extends Model
{
    //
    protected $table = "tbl_docs";

    public function user(){
    	return $this->belongsTo(User::class);
    }

}
