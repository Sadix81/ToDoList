<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'owner_id' => $this->owner_id,
            'users' => $this->userRoles->map(function ($userRole) {
                return [
                    'user_id' => $userRole->user_id,
                    'username' => $userRole->user->username, //using user relation in "userRole" model
                ];
            }),
        ];
    }
}
