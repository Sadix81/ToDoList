<?php

namespace App\Http\Resources\Subtask;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowSubtaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'status' => $this->status,
            'task_id' => $this->task_id,
            'user_id' => $this->user_id,
        ];
    }
}
