<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'user_id' => $this->user_id,
            'group_id' => $this->group_id,
            'title' => $this->title,
            'description' => $this->description,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
            'priority' => $this->priority,
            'status' => $this->status,
            'image' => $this->image,
            'category' => $this->categories->pluck('title'),
        ];
    }
}
