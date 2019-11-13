<?php

namespace App\Http\Resources\Spare;

use Illuminate\Http\Resources\Json\Resource;

class SpareResource extends Resource
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
            'uid' => $this->uid,
            'name' => $this->name,
            'stock' => $this->stock,
            'manufacturer' => $this->manufacturer,
            'vendor' => $this->vendor,
            'unit_id' => $this->unit_id,
            'cost' => [
                'amount' => $this->amount,
                'currency_id' => $this->currency_id
            ],
            'status' => $this->status
        ];
    }
}
