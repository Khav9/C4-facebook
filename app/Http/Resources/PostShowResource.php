<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'tags' => $this->tags,
            'images' => $this->images, 
            'videos' => $this->videos, 
            "created_at" => $this->created_at->format('Y-m-d'),
            "comments" => CommentPostResource::collection($this->comments),
            "reactions"=>$this->likes
        ];
    }
}
