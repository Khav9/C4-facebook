<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class usershowresource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'profile_image' => $this->profile_image,
            'total_friend' => $this->friends->count(),
            'friends' => FriendUseResource::collection($this->friends),
            'total_following' => $this->following->count(),
            'following' => $this->following,
        ];
    }
}
