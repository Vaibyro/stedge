<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'firstname' => $this->user->firstname,
                'lastname' => $this->user->lastname,
                'avatar_url' => asset('storage/avatars/' . $this->user->avatar),
                'avatar_small_url' => asset('storage/avatars_small/' . $this->user->avatar)
            ]
        ];
    }
}
