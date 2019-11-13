<?php

namespace App\Http\Resources\Workgroup;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkgroupResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
