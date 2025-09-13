<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'building'  => new BuildingResource($this->whenLoaded('building')),
            'phones'    => PhoneResource::collection($this->whenLoaded('phones')),
            'activities'=> ActivityResource::collection($this->whenLoaded('activities')),
        ];
    }
}
