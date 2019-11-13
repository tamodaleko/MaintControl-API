<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;

class AreaResource extends Resource
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
            'location_id' => $this->location_id,
            'status' => $this->status,
            'users' => $this->users->pluck('id'),
            'workgroups' => $this->workgroups->pluck('id'),
            'assets' => $this->assets->pluck('id')
        ];
    }
}
