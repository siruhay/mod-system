<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemAbilityPage;
use Illuminate\Http\Resources\Json\JsonResource;

class AbilityPageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return SystemAbilityPage::mapResource($request, $this);
    }
}
