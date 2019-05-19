<?php

namespace App\Http\Resources;

use App\Circle;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCirclesRelationshipResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data'  => CircleResource::collection($this->collection)
        ];
    }
}
