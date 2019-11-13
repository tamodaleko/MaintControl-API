<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Area\AreaResource;
use Illuminate\Http\Resources\Json\Resource;

class UserInitResource extends Resource
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
            'avatar' => $this->getAvatarLink(),
            'default_area_id' => $this->preference ? $this->preference->default_area_id : null,
            'roles' => $this->roles->pluck('name'),
            'areas' => AreaResource::collection($this->getAttachedAreas()),
            'preventiveOrdersCounter' => 0,
            'correctiveOrdersCounter' => 0
        ];
    }
}
