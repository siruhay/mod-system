<?php

namespace Module\System\Http\Resources;

use Module\System\Models\SystemUserLog;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return SystemUserLog::mapResource($request, $this);
    }
}
