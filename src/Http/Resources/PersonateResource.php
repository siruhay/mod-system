<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemPersonate;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return SystemPersonate::mapResource($request, $this);
    }
}
