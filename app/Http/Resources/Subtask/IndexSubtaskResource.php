<?php

namespace App\Http\Resources\Subtask;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexSubtaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'owner_id' => $this->owner_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'description' => $this->description,
        ];
    }
}
