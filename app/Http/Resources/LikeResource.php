<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class LikeResource extends JsonResource
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
            'user' => [
                'id' => $this->user->id,
                'is_it_me' => $this->user->id == Auth::user()->id,
                'name' => $this->user->name,
                'firstname' => $this->user->firstname,
                'lastname' => $this->user->lastname,
            ],
            'answer' => [
                'id' => $this->answer->id,
                'likes' => $this->answer->likes->count(),
                'liked_by_me' => $this->answer->likes->contains('user_id', Auth::user()->id),
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
