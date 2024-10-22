<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\component\Content;
class OurTeamResources extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'        => $this->id, 
            'fullname'  => $this->fullname, 
            'position'  => $this->position,
            'slug'      => $this->name,
            'joined_date' => date('F m Y', strtotime($this->created_at)),
            'desc'      => $this->descs,
            'country'   => $this->country['country_name'],
            'city'      => $this->province['province_name'],
            'file'     => $this->picture,
            // 'thumbnail' => $this->picture),
        ];
    }
}
