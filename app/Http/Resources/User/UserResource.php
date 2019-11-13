<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'position' => $this->position,
            'avatar_file_id' => $this->avatar_file_id,
            'avatar' => $this->getAvatarLink(),
            'email' => $this->profile ? $this->profile->email : null,
            'phone' => $this->profile ? $this->profile->phone : null,
            'fax' => $this->profile ? $this->profile->fax : null,
            'office' => $this->profile ? $this->profile->office : null,
            'skype' => $this->profile ? $this->profile->skype : null
        ];
    }
}
