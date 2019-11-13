<?php

namespace App\Http\Resources\Admin\Company;

use Illuminate\Http\Resources\Json\Resource;

class CompanyPreDefinedResource extends Resource
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
            'priorities' => CompanyPreDefinedItemResource::collection($this->whenLoaded('priorities')),
            'skills' => CompanyPreDefinedItemResource::collection($this->whenLoaded('skills')),
            'permits' => CompanyPreDefinedItemResource::collection($this->whenLoaded('permits')),
            'criticals' => CompanyPreDefinedItemResource::collection($this->whenLoaded('criticals')),

            'activityTypesCO' => CompanyPreDefinedItemResource::collection($this->whenLoaded('activityTypesCO')),
            'activityTypesPO' => CompanyPreDefinedItemResource::collection($this->whenLoaded('activityTypesPO')),

            'additionalFieldsCO' => CompanyPreDefinedItemResource::collection($this->whenLoaded('additionalFieldsCO')),
            'additionalFieldsPO' => CompanyPreDefinedItemResource::collection($this->whenLoaded('additionalFieldsPO')),

            'assetCategories' => CompanyPreDefinedItemResource::collection($this->whenLoaded('assetCategories')),
            'conditions' => CompanyPreDefinedItemResource::collection($this->whenLoaded('conditions')),
            'units' => CompanyPreDefinedItemResource::collection($this->whenLoaded('units'))
        ];
    }
}
