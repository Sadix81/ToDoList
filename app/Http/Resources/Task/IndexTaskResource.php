<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            // 'user_id' => $this->user_id,
            'group_id' => $this->group_id,
            'title' => $this->title,
            'description' => $this->description,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
            'priority' => $this->priority,
            'status' => $this->status,
            'category' => $this->categories->pluck('title'),
            'note' => $this->notes->pluck('id'),
            'subtask' => $this->subtasks->pluck('id'),
        ];
    }
}
