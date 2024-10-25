<?php

namespace Module\System\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\System\Models\SystemUser;

class AuthenticatedShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return SystemUser::authenticatedShowResource($request, $this);
    }
}
