<?php

namespace App\Http\Resources\Subtask;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexSubtaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'owner_id' => $this->owner_id,
        ];
    }
}
