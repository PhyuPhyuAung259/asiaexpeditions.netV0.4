<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookEntrance extends Model
{
    protected $table = "entrancefee_book";

    public function entrance(){
    	return $this->belongsTo(Entrance::class, 'service_id');
    }

    public function accountJournal () {
        return $this->hasMany(AccountJournal::class, 'book_id');
    }
}
 