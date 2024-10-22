<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    public function project()
    {
    	return $this->belongsToMany(Project::class);
    }
    public function tour()
    {
    	return $this->belongsToMany(Tour::class)->withPivot(['tour_id', 'service_id']);
    }
}
 