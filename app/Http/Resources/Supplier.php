<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\component\Content;

class Supplier extends Resource
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
        $gallery = [];
        $galleries = explode('|',trim($this->supplier_picture, '|'));
        if ( $this->supplier_picture ) {
            foreach ($galleries as $key => $va) {
                $gallery[] = Content::urlImage($va);
            }
        }

        return [
            'id'=> $this->id,
            'supplier' => $this->supplier_name,
            'slug'      => $this->slug,
            'contact_name' => $this->supplier_contact_name,
            'phone'     => $this->supplier_phone,
            'phone2'    => $this->supplier_phone2,
            'pax'       => $this->supplier_fax,
            'email'     => $this->supplier_email,
            'website'   => $this->supplier_website,
            'address'   => $this->supplier_address,
            'remark'    => $this->supplier_remark,
            'intro'     => $this->supplier_intro,
            'photo'     => Content::urlImage($this->supplier_photo),
            'gallery'   => $gallery,
            'policy_cancellation'   => $this->supplier_pcancellation,
            'policy_payment'        => $this->supplier_ppayment,
            'term_condition'        => $this->supplier_term_condition,
            'business' => $this->business->name,
            'hotel_categories'  => $this->hotel_category()->select('name')->orderBy('name')->get(),
            'hotel_facility' => $this->hotel_facility,
        ];
    }
}
