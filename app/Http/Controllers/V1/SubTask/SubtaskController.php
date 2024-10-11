<?php

namespace App\Http\Controllers\V1\SubTask;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subtask\CreateSubtaskRequest;
use App\Http\Requests\Subtask\UpdateSubtaskRequest;
use App\Http\Resources\Subtask\ShowSubtaskResource;
use App\Models\Subtask\Subtask;
use App\Repositories\Subtask\SubtaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubtaskController extends Controller
{
    private $subTask;

    public function __construct(SubtaskRepository $subtaskRepository)
    {
        $this->subTask = $subtaskRepository;
    }

    public function store(CreateSubtaskRequest $request)
    {
        $error = $this->subTask->store($request);

        if ($error === null) {
            return response()->json(['message' => __('messages.subtask.store.success', ['title' => $request->title])], 201);
        }

        return response()->json(['message' => __('messages.subtask.store.failed', ['title' => $request->title])], 500);
    }

    public function show(Subtask $subtask)
    {
        $user = Auth::user();

        if (! $user) {
            return 'عدم دسترسی';
        }

        return new ShowSubtaskResource($subtask);
    }

    public function update(Subtask $subtask, UpdateSubtaskRequest $request)
    {
        $error = $this->subTask->update($subtask, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.subtask.update.success', ['title' => $subtask->title])], 200);
        }

        return response()->json(['message' => __('messages.subtask.update.failed', ['title' => $subtask->title])], 500);
    }

    public function destroy(Subtask $subtask)
    {
        $error = $this->subTask->delete($subtask);
        if ($error === null) {
            return response()->json(['message' => __('messages.subtask.delete.success')], 200);
        }

        return response()->json(['message' => __('messages.subtask.delete.failed')], 500);
    }
}

