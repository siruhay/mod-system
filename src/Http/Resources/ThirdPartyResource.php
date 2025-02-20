<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemThirdParty;
use Illuminate\Http\Resources\Json\JsonResource;

class ThirdPartyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return SystemThirdParty::mapResource($request, $this);
    }
}
