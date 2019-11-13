<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;

class AssetResource extends Resource
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
            'uid' => $this->uid,
            'area_id' => $this->area_id,
            'category_id' => $this->category_id,
            'condition_id' => $this->condition_id,
            'avatar_file_id' => $this->avatar_file_id,
            'avatar' => $this->getAvatarLink(),
            'meter_readings' => $this->meter_readings,
            'location_description' => $this->location_description,
            'manufacturer' => $this->manufacturer,
            'model' => $this->model,
            'serial' => $this->serial,
            'installed' => $this->installed,
            'status' => $this->status,
            'spares' => $this->spares->pluck('id'),
            'assets' => $this->subAssets->pluck('id')
        ];
    }
}
