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
            'description' => $this->description,
            'image' => $this->image,
            'status' => $this->status,
            'task_id' => $this->task_id,
            'owner_id' => $this->owner_id,
        ];
    }
}
