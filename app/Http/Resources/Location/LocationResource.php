<?php

namespace App\Http\Resources\Location;

use Illuminate\Http\Resources\Json\Resource;

class LocationResource extends Resource
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
            'name' => $this->name,
            'country_id' => $this->country_id,
            'city' => $this->city,
            'address' => $this->address,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'email' => $this->email,
            'time_format_24' => $this->time_format_24,
            'workdays' => $this->workdays,
            'manager_id' => $this->manager_id,
            'timezone_id' => $this->timezone_id,
            'currency_id' => $this->currency_id,
            'shifts' => LocationShiftResource::collection($this->whenLoaded('shifts'))
        ];
    }
}
