<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\component\Content;
class Country extends Resource
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
        $provinces = [];
        foreach ($this->province as $key => $value) {
            $provinces[] = ['id'=> $value->id,
                'name' => $value->province_name,
                'slug' => $value->slug,
                'country'=> $value->country->country_name,
                'photo'  => Content::urlImage($value->province_photo)];
        }
        return [
            'id'   => $this->id,
            'name' => $this->country_name,
            'slug' => $this->country_slug,
            'photo'=> Content::urlImage($this->country_photo),
            'country_desc' => $this->country_intro,
            'provinces'=> $provinces,

        ];
    }
}
