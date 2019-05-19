<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostResource extends JsonResource
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
            'emergency' => $this->emergency,
            'state'  => new StateResource($this->state),
            'tags' => new PostTagsRelationshipResource($this->tags),
            'answers' => new PostAnswersRelationshipResource($this->answers),
            'is_public' => $this->circle_id == env('PUBLIC_CIRCLE_ID', 1),
            'is_it_me' => $this->user_id == Auth::user()->id,
            'circle' => new CircleResource($this->circle),
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
