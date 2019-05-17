<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
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
            'name' => $this->name,
            'hash_name' => '#' . preg_replace('/\s+/', '', $this->name),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_official' => boolval($this->is_official)
        ];
    }
}
