<?php

namespace App\Http\Resources\Workgroup;

use Illuminate\Http\Resources\Json\Resource;

class WorkgroupResource extends Resource
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
            'supervisor_id' => $this->supervisor_id,
            'status' => $this->status,
            'areas' => $this->areas->pluck('id'),
            'users' => $this->users->pluck('id')
        ];
    }
}
