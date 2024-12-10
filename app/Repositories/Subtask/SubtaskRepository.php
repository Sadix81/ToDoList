<?php

namespace App\Repositories\Subtask;

use App\Models\Subtask\Subtask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubtaskRepository implements SubtaskRepositoryInterface
{
    public function index($task)
    {
        try {
            $subtasks = Subtask::where('task_id', $task->id)->get();

            return $subtasks;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store($task, $request)
    {
        $user = Auth::id();

        DB::beginTransaction();

        if (request()->hasFile('image')) {
            $image_name = time().'-'.$request->title.'-'.$request->image->getClientOriginalName();
            $request->image->move(public_path('images'), $image_name);
        }

        try {
            Subtask::create([
                'owner_id' => $user,
                'user_id' => $request->user_id ? $request->user_id : $user,
                'task_id' => $task->id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status ?: 0,
                'image' => $request->image ? $image_name : null,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update($task, $subtask, $request)
    {
        $user = Auth::id();

        $subtask = Subtask::where('task_id', $task->id)
            ->where('id', $subtask->id)->firstOrFail();

        DB::beginTransaction();

        try {
            $image_name = $subtask->image;

            // Check if an image has been uploaded
            if ($request->hasFile('image')) {
                $image_name = time().'-'.$request->title.'-'.$request->image->getClientOriginalName();
                $request->image->move(public_path('images'), $image_name);
            } elseif ($request->image === null) {
                $image_name = null;
                if ($subtask->image && file_exists(public_path('images/'.$subtask->image))) {
                    unlink(public_path('images/'.$subtask->image));
                }
            }

            $subtask->update([
                'owner_id' => $user,
                'user_id' => $request->user_id ? $request->user_id : $request->user_id,
                'task_id' => $task->id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status !== null ? $request->status : $subtask->status,
                'image' => $image_name,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function closeStatus($task, $subtask)
    {
        try {
            // XOR
            $subtask->status = $subtask->status ^ 1;
            $subtask->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($task, $subtask)
    {
        $subtask = Subtask::where('task_id', $task->id)
            ->where('id', $subtask->id)->firstOrFail();
        try {
            $subtask->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
