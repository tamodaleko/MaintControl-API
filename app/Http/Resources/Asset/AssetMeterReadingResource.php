<?php

namespace App\Http\Resources\Asset;

use Illuminate\Http\Resources\Json\Resource;

class AssetMeterReadingResource extends Resource
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
            'reporter_id' => $this->reporter_id,
            'value' => $this->value,
            'modified' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null
        ];
    }
}
