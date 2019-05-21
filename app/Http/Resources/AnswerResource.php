<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'id' => $this->id,
            'content' => $this->content,
            'likes' => $this->likes->count(),
            'liked_by_me' => $this->likes->contains('user_id', Auth::user()->id),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_best_answer' => isset($this->post->bestAnswer) ? $this->post->bestAnswer->id == $this->id : false,
            'can_approbe' => $this->post->user == Auth::user(),
            'post_id' => $this->post->id,
            'user' => [
                'id' => $this->user->id,
                'is_it_me' => $this->user->id == Auth::user()->id,
                'name' => $this->user->name,
                'firstname' => $this->user->firstname,
                'lastname' => $this->user->lastname,
                'avatar_url' => asset('storage/avatars/' . $this->user->avatar),
                'avatar_small_url' => asset('storage/avatars_small/' . $this->user->avatar)
            ]
        ];
    }
}
