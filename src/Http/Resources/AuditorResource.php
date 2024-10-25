<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemAuditor;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return SystemAuditor::mapResource($request, $this);
    }
}
