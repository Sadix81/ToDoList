<?php

namespace App\Http\Controllers\V1\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\Task\IndexTaskResource;
use App\Http\Resources\Task\ShowTaskResource;
use App\Models\Task\Task;
use App\Repositories\Task\TaskRepository;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    private $taskrepo;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskrepo = $taskRepository;
    }

    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return 'عدم دسترسی';
        }

        return IndexTaskResource::collection($this->taskrepo->index());
    }

    public function indexCloseStatus()
    {
        return $this->taskrepo->closeStatusindex();
    }

    public function store(CreateTaskRequest $request)
    {
        $error = $this->taskrepo->store($request);

        if ($error === null) {
            return response()->json(['message' => __('messages.task.store.success', ['title' => $request->title])], 201);
        }

        return response()->json(['message' => __('messages.task.store.failed', ['title' => $request->title])], 500);
    }

    public function show(Task $task)
    {
        $user = Auth::user();

        if (! $user) {
            return 'عدم دسترسی';
        }

        return new ShowTaskResource($task);
    }

    public function update(Task $task, UpdateTaskRequest $request)
    {
        $error = $this->taskrepo->update($task, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.task.update.success', ['title' => $task->title])], 200);
        }

        return response()->json(['message' => __('messages.task.update.failed', ['title' => $task->title])], 500);
    }

    public function closeStatus(Task $task)
    {
        $error = $this->taskrepo->closeStatus($task);
        if ($error === null) {
            return response()->json(['message' => __('messages.task.status.change.success')], 200);
        }

        return response()->json(['message' => __('messages.task.status.change.failed')], 500);
    }

    public function destroy(Task $task)
    {
        $error = $this->taskrepo->delete($task);
        if ($error === null) {
            return response()->json(['message' => __('messages.task.delete.success')], 200);
        }

        return response()->json(['message' => __('messages.task.delete.failed')], 500);
    }
}
