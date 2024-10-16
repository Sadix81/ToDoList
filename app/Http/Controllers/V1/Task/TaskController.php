<?php

namespace App\Http\Controllers\V1\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\Task\IndexTaskResource;
use App\Http\Resources\Task\ShowTaskResource;
use App\Models\Group\Group;
use App\Models\Task\Task;
use App\Models\User;
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
        $auth = Auth::id();
        $group = null;
        
        if($request->group_id !== null){
            $group = Group::find($request->group_id);
        }

        if($group){
            // Check the users of the group
            $groupUsers = $group->users()->get()->pluck('id');
            $allUsers = $groupUsers; //containe all users from the group which we find it
            $allUsers[] = $auth;
        }

        if($group && $request->user_id === null){
            return 'تسک باید به شخصی واگذار شود';
        }

        if($group && (! $allUsers->contains($request->user_id))){
            return 'کاربر مورد نظر یافت نشد';
        }
        
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
        $auth = Auth::id();
        $group = Group::find($request->group_id);


        if (! $group) {
            return 'گروهی یافت نشد';
        }

        if($group && $request->user_id === null){
            return 'تسک باید به شخصی واگذار شود';
        }

        // Check the users of the group
        $groupUsers = $group->users()->get()->pluck('id');
        $allUsers = $groupUsers; //containe all users from the group which we find it
        $allUsers[] = $auth;

        if($group && (! $allUsers->contains($request->user_id))){
            return 'کاربر مورد نظر یافت نشد';
        }

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
