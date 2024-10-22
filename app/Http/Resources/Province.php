<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\component\Content;

class Province extends Resource
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
            'name' => $this->province_name,
            'id'=> $this->id,            
            'slug'  => $this->slug,
            'country'=> $this->country->country_name,
            'photo'  => Content::urlImage($this->province_photo),
        ];
    }
}
