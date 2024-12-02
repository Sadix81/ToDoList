<?php

namespace App\Http\Controllers\V1\SubTask;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subtask\CreateSubtaskRequest;
use App\Http\Requests\Subtask\UpdateSubtaskRequest;
use App\Http\Resources\Subtask\IndexSubtaskResource;
use App\Http\Resources\Subtask\ShowSubtaskResource;
use App\Models\Group\Group;
use App\Models\Subtask\Subtask;
use App\Models\Task\Task;
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

    public function index(Task $task){

        return IndexSubtaskResource::collection($this->subTask->index($task));
    }

    public function store(Task $task , CreateSubtaskRequest $request)
    {
        $auth = Auth::id();
        $group = $task->group_id;

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }

        if(! $task){
            return response()->json(['message' => 'تسکی یافت نشد']);
        }

        if($group === null && $request->user_id !== null){
            return response()->json(['message' => 'تسک به گروهی وابسته نیست']);
        }

        if($group){
            $group = Group::find($task->group_id);
        }

        if($group){
            // Check the users of the group
            $groupUsers = $group->users()->get()->pluck('id');
            $allUsers = $groupUsers; //containe all users from the group which we find it
            $allUsers[] = $auth;
        }

        if($group && $request->user_id === null){
            return response()->json(['message' => 'ساب تسک باید به شخصی واگذار شود']);
        }

        if($group && (! $allUsers->contains($request->user_id))){
            return response()->json(['message' => 'کاربر مورد نظر یافت نشد']);
        }

        $error = $this->subTask->store($task , $request);

        if ($error === null) {
            return response()->json(['message' => __('messages.subtask.store.success', ['title' => $request->title])], 201);
        }

        return response()->json(['message' => __('messages.subtask.store.failed', ['title' => $request->title])], 500);
    }

    public function show(Task $task , Subtask $subtask)
    {
        $auth = Auth::id();

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }

        if(! $subtask){
            return response()->json(['message' => 'موردی یافت نشد']);
        }

        if($task->id && ($subtask->task_id !== $task->id)){
            return response()->json(['message' => 'تسک مورد نظر یافت نشد']);
        }

        return new ShowSubtaskResource($subtask);
    }

    public function update(Task $task ,Subtask $subtask, UpdateSubtaskRequest $request)
    {
        $auth = Auth::id();
        $group = $task->group_id;

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }

        if(! $task){
            return response()->json(['message' => 'تسکی یافت نشد']);
        }

        if(! $subtask){
            return response()->json(['message' => 'موردی یافت نشد']);
        }

        if($task->id && ($subtask->task_id !== $task->id)){
            return response()->json(['message' => 'تسک مورد نظر یافت نشد']);
        }

        if($group === null && $request->user_id !== null){
            return response()->json(['message' => 'تسک به گروهی وابسته نیست']);
        }

        if($group){
            $group = Group::find($task->group_id);
        }

        if($group){
            // Check the users of the group
            $groupUsers = $group->users()->get()->pluck('id');
            $allUsers = $groupUsers; //containe all users from the group which we find it
            $allUsers[] = $auth;
        }

        if($group && $request->user_id === null){
            return response()->json(['message' => 'تسک باید به شخصی واگذار شود']);
        }

        if($group && (! $allUsers->contains($request->user_id))){
            return response()->json(['message' => 'کاربر مورد نظر یافت نشد']);
        }

        
        $error = $this->subTask->update($task , $subtask, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.subtask.update.success', ['title' => $subtask->title])], 200);
        }

        return response()->json(['message' => __('messages.subtask.update.failed', ['title' => $subtask->title])], 500);
    }

    public function closeStatus(Task $task , Subtask $subtask)
    {
        $auth = Auth::id();

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }

        if(! $task){
            return response()->json(['message' => 'تسکی یافت نشد']);
        }

        if(! $subtask){
            return response()->json(['message' => 'موردی یافت نشد']);
        }

        if($task->id && ($subtask->task_id !== $task->id)){
            return response()->json(['message' => 'تسک مورد نظر یافت نشد']);
        }

        $error = $this->subTask->closeStatus($task , $subtask);
        if ($error === null) {
            return response()->json(['message' => __('messages.task.status.change.success')], 200);
        }

        return response()->json(['message' => __('messages.task.status.change.failed')], 500);
    }

    public function destroy(Task $task , Subtask $subtask)
    {
        $auth = Auth::id();

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }
        
        if(! $task){
            return response()->json(['message' => 'تسکی یافت نشد']);
        }

        if(! $subtask){
            return response()->json(['message' => 'موردی یافت نشد']);
        }
        
        if($task->id && ($subtask->task_id !== $task->id)){
            return response()->json(['message' => 'تسک مورد نظر یافت نشد']);
        }

        $error = $this->subTask->delete($task , $subtask);
        if ($error === null) {
            return response()->json(['message' => __('messages.subtask.delete.success')], 200);
        }

        return response()->json(['message' => __('messages.subtask.delete.failed')], 500);
    }
}

