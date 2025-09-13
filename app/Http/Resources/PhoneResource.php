<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PhoneResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'number' => $this->number,
        ];
    }
}
