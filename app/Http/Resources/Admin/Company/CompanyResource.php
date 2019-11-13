<?php

namespace App\Http\Resources\Admin\Company;

use App\Http\Resources\Admin\Location\LocationResource;
use Illuminate\Http\Resources\Json\Resource;

class CompanyResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'name' => $this->name,
            'logo_file_id' => $this->logo_file_id,
            'logo' => $this->getLogoLink(),
            'slogan' => $this->slogan,
            'locations' => LocationResource::collection($this->whenLoaded('locations'))
        ];
    }
}
