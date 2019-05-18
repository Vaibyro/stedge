<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'firstname' => $this->user->firstname,
                'lastname' => $this->user->lastname
            ]
        ];
    }
}
