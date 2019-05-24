<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCirclesResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'avatar_url' => asset('storage/avatars/' . $this->avatar),
            'avatar_small_url' => asset('storage/avatars_small/' . $this->avatar),
            'link' => route('user', $this->id),
            'circles' => new UserCirclesRelationshipResource($this->circles)
        ];
    }
}
