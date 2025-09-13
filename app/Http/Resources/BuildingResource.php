<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
class BuildingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'address'   => $this->address,
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
